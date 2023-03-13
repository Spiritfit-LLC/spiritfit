<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalSettingsComponent extends CBitrixComponent implements Controllerable
{
    public function ConfigureActions()
    {
        return [
            'save' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    private function getFormFields($request=false){
        global $USER;
        if (!$USER->IsAuthorized()){
            throw new Exception("Пользователь не авторизован");
        }
        if (!$request){
            $arUser=CUser::GetByID($USER->GetID())->Fetch();
        }

        CModule::IncludeModule("iblock");
        $objects=[];
        $filter = [
            'IBLOCK_CODE' => 'LK_PARSER',
            'ACTIVE'=>'Y',
            "PROPERTY_HIDE_ON_FORM"=>false,
            "SECTION_CODE" => "lk-settings"
        ];
        $order = ['SORT' => 'ASC'];

        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =& $row;
            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
            unset($row);
        }

        $SELECT=[
            "CODE"=>[
                "FIELD_TITLE",
                "REQUIRED",
                "USER_FIELD_CODE",
                "SERIALIZE"
            ]
        ];

        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $SELECT);
        $FIELDS=[];
        foreach ($objects as $id=>$element){
            $FIELD=[
                "NAME"=>$element["PROPERTIES"]["FIELD_TITLE"]["VALUE"],
                "FORM_NAME"=>"form_" . $element['CODE'] . "_" . $id,
                "USER_FIELD_CODE"=>$element["PROPERTIES"]["USER_FIELD_CODE"]["VALUE"],
            ];

            if (!$request){
                $FIELD["VALUE"]=$arUser[$element["PROPERTIES"]["USER_FIELD_CODE"]["VALUE"]];
                if ($element["PROPERTIES"]["SERIALIZE"]["VALUE_XML_ID"]=="Y"){
                    $FIELD["VALUE"]=unserialize($FIELD["VALUE"]);
                }
                $FIELDS[$element["CODE"]]=$FIELD;
            }
            else{
                $FIELD["VALUE"]=\Bitrix\Main\Context::getCurrent()->getRequest()->get($FIELD["FORM_NAME"]);
                if ($element["PROPERTIES"]["REQUIRED"]["VALUE_XML_ID"]=="Y" && empty($FIELD["VALUE"])){
                    $FIELDS["INCORRECT"]=true;
                    $FIELDS["ERROR_FIELD"][]=$FIELD["FORM_NAME"];
                }
                $FIELDS["FIELDS"][$element["CODE"]]=$FIELD;
            }

        }

        return $FIELDS;
    }

    function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()){
            $this->arResult["ERROR"]="Пользователь не авторизован";
        }
        else{
            $this->arResult["FIELDS"]=$this->getFormFields();
        }

        $this->IncludeComponentTemplate();
    }


    //ajax
    public function saveAction(){
        global $USER;

        //==ПРОВЕРКА ДАННЫХ==
        $FIELDS=$this->getFormFields(true);
        if ($FIELDS["INCORRECT"]){
            foreach ($FIELDS["ERROR_FIELD"] as $FIELD){
                $error_fields[]=[
                    "field"=>$FIELD,
                    "message"=>"Поле не заполнено или заполнено неправильно"
                ];
            }
            return [
                "result"=>false,
                "message"=>"Не удалось обновить данные",
                "error_fields"=>$error_fields
            ];
        }
        //==ПРОВЕРКА ДАННЫХ==


        //==Составляем массив для обновления данных==
        foreach ($FIELDS["FIELDS"] as $key=>$FIELD){
            if (empty($FIELD["VALUE"]))
                continue;

            $CHANGE_ARR[$FIELD["USER_FIELD_CODE"]]=$FIELD["VALUE"];
        }
        //==Составляем массив для обновления данных==


        //==Проверяем на согласие совершеннолетнего==
        $dbUser=CUser::GetByID($USER->GetID());
        $arUser=$dbUser->Fetch();

        $birthday = new DateTime($CHANGE_ARR["PERSONAL_BIRTHDAY"]);
        $now=new DateTime();
        $interval=$birthday->diff($now);
        $year_count=$interval->y;

        if ($year_count<18){
            $CONSENT_FILE=Context::getCurrent()->getRequest()->getFile($FIELDS["FIELDS"]["lk-settings-consent"]["FORM_NAME"]);
            if (!empty($arUser["UF_PARENTAL_CONSENT"]) && (empty($CONSENT_FILE) || !empty($CONSENT_FILE["error"]))){
                unset($CHANGE_ARR[$FIELDS["FIELDS"]["lk-settings-consent"]["USER_FIELD_CODE"]]);
            }
            elseif (empty($CONSENT_FILE) || !empty($CONSENT_FILE["error"])){
                $error_fields[]=[
                    "field"=>$FIELDS["FIELDS"]["lk-settings-consent"]["FORM_NAME"],
                    "message"=>"Необходимо прикрепить согласие"
                ];
                return [
                    "result"=>false,
                    "message"=>"Не удалось обновить данные",
                    "error_fields"=>$error_fields
                ];
            }
            else{
                $fileId = CFile::SaveFile($CONSENT_FILE, 'user/'+$USER->GetID()+'/parental_consent');
                $arFile = CFile::MakeFileArray($fileId);
                $CHANGE_ARR[$FIELDS["FIELDS"]["lk-settings-consent"]["USER_FIELD_CODE"]]=$arFile;

                $api=new Api([
                    "action"=>"lkdocs",
                    "params"=>[
                        "login"=>$arUser["LOGIN"],
                        "id1c"=>$arUser["UF_1CID"],
                        "url"=>sprintf(
                                "%s://%s",
                                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                                $_SERVER['SERVER_NAME']).CFile::GetPath($fileId),
                    ]
                ]);

                $response=$api->result();

                if (!$response['success']){
                    if(!empty($response["data"]["result"]["userMessage"]) ) {
                        $error_fields[]=[
                            'field'=>$FIELDS["FIELDS"]["lk-settings-consent"]["FORM_NAME"],
                            'message'=>$response["data"]["result"]["userMessage"]
                        ];
                    } else {
                        $error_fields[]=[
                            'field'=>$FIELDS["FIELDS"]["lk-settings-consent"]["FORM_NAME"],
                            'message'=>"При отправке файла произошла ошибка"
                        ];
                    }

                    return [
                        "result"=>false,
                        "message"=>"Не удалось обновить данные",
                        "error_fields"=>$error_fields
                    ];
                }
            }
        }
        //==Проверяем на согласие совершеннолетнего==


        $arUser=CUser::GetByID($USER->GetID())->Fetch();


        $arParams=array(
            'name'=>$CHANGE_ARR['NAME'],
            'surname'=>$CHANGE_ARR['LAST_NAME'],
            'email'=>$CHANGE_ARR['EMAIL'],
            'birthday'=>$CHANGE_ARR['PERSONAL_BIRTHDAY'],
            'gender'=>$CHANGE_ARR['PERSONAL_GENDER'],
            'id1c'=>$arUser["UF_1CID"],
            'login'=>$arUser["LOGIN"],
            'action'=>'edit',
            "address"=>$CHANGE_ARR["UF_ADDRESS"],
        );


        $geo_lat=Context::getCurrent()->getRequest()->get("geo_lat");
        $geo_lon=Context::getCurrent()->getRequest()->get("geo_lon");
        if ($geo_lat){
            $arParams["geo_lat"]=$geo_lat;
        }

        if ($geo_lon){
            $arParams["geo_lon"]=$geo_lon;
        }

        $api=new Api(array(
            'action'=>'lkedit',
            'params'=>$arParams,
        ));

        $response=$api->result();

        if ($response["success"]){
            global $USER;
            $result = $USER->Update($USER->GetID(), $CHANGE_ARR, false);
            if(!$result){
                throw new Exception($USER->LAST_ERROR, 1);
            }
        }
        else{
            throw new Exception("Не удалось обновить данные", 1);
        }

        return [
            "result"=>true,
            "message"=>"Ваши данные обновлены"
        ];
    }
}