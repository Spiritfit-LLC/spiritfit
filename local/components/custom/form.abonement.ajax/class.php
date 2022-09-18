<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class FormAbonementAjaxComponent extends CBitrixComponent implements Controllerable{

    function onPrepareComponentParams($arParams){
        if(!$arParams["WEB_FORM_ID"]){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        return $arParams;
    }


    private function GetClubsArr(){
        foreach($this->arResult['ELEMENT']['PROPERTIES']['BASE_PRICE']['VALUE'] as $key=>$arPrice){
            $result[]=$arPrice['LIST'];
        }
        return $result;
    }

    private function GetFormFields($request=false, $check=true){
        $FORM_FIELDS=[];
        if ($request && $check){
            $error = CForm::Check($this->arResult['WEB_FORM_ID'], Context::getCurrent()->getRequest()->toArray());
            if (strlen($error)>0){
                return $error;
            }
            $FORM_FIELDS['ISSET']=true;
        }

        $status = CForm::GetDataByID($this->arResult['WEB_FORM_ID'], $this->arResult['FORM']["arForm"], $this->arResult['FORM']["arQuestions"], $this->arResult['FORM']["arAnswers"], $this->arResult['FORM']["arDropDown"], $this->arResult['FORM']["arMultiSelect"]);
        $FORM=$this->arResult['FORM'];
        if(!$status) {
            return ['result'=>false, 'error'=>'Не удалось выполнить запрос'];
        }
        foreach($FORM["arAnswers"] as $key=>$value){
            $by= "s_sort";
            $order = "asc";
            $FORM_FIELDS['FIELDS'][$key]=[
                'PLACEHOLDER'=>$FORM["arQuestions"][$key]["TITLE"],
                'TYPE'=>$key=="phone" ? "tel" : $value['0']["FIELD_TYPE"],
                'REQUIRED'=>$FORM["arQuestions"][$key]["REQUIRED"]=="Y" ? true:false,
                "COMMENT"=>$FORM["arQuestions"][$key]["COMMENTS"]
            ];
            if ($value['0']["FIELD_TYPE"]=='radio' || $value['0']["FIELD_TYPE"]=='checkbox'){
                $FORM_FIELDS['FIELDS'][$key]['NAME']="form_".$value['0']["FIELD_TYPE"]."_".$FORM['arQuestions'][$key]['SID'];
            }
            else{
                $FORM_FIELDS['FIELDS'][$key]['NAME']="form_".$value['0']["FIELD_TYPE"]."_".$value['0']["ID"];
            }

            if (!$request){
                $validator=CFormValidator::GetList($FORM["arQuestions"][$key]['ID'], array(), $by,$order)->Fetch();
                if ($key=="club"){
                    if (boolval($this->arResult["ELEMENT"]["PROPERTIES"]["ONLINE"]["VALUE"])){
                        $FORM_FIELDS['FIELDS'][$key]["TYPE"]="hidden";
                        $FORM_FIELDS['FIELDS'][$key]["VALUE"]=Utils::GetIBlockElementIDBySID("setevoy-abonement-");
                    }
                    else{
                        $arFilter = array(
                            "IBLOCK_ID" => Utils::GetIBlockIDBySID('clubs'),
                            "PROPERTY_SOON" => false,
                            "ACTIVE" => "Y",
                            "PROPERTY_HIDE_LINK_VALUE"=>false,
                            'ID'=>$this->GetClubsArr()
                        );
                        $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "CODE", "NAME", "PROPERTY_NUMBER"));
                        while ($res = $dbElements->fetch()) {
                            $club=array(
                                'NUMBER'=>$res["PROPERTY_NUMBER_VALUE"],
                                'STRING'=>$res["NAME"],
                                'VALUE'=>$res['ID'],
                            );
                            if ($this->arResult['CLUB_ID']==$res['ID']){
                                $club['SELECTED']=true;
                                $this->arResult['SELECTED_CLUB']=$res['NAME'];
                            }
                            $CLUBS[]=$club;
                        }
                        $FORM_FIELDS['FIELDS'][$key]['TYPE']='SELECT';
                        $FORM_FIELDS['FIELDS'][$key]['ITEMS']=$CLUBS;
                        $FORM_FIELDS['FIELDS'][$key]['PARAMS']=$value['0']["FIELD_PARAM"];
                    }
                }
                elseif ($FORM_FIELDS['FIELDS'][$key]['TYPE']=='checkbox'){
                    $FORM_FIELDS['FIELDS'][$key]['VALUE']=$value['0']["ID"];
                }
                else{
                    $val=null;
                    global $USER;
                    if ($USER->IsAuthorized()){
                        $rsUser=CUser::GetByID($USER->GetID());
                        $arUser=$rsUser->Fetch();
                        $val=$arUser[$value['0']['VALUE']];
                    }
                    $FORM_FIELDS['FIELDS'][$key]['VALUE']=$val;
                }
                if (!empty($validator)) {
                    $validate_text='';
                    foreach ($validator['PARAMS'] as $KEY=>$PARAM){
                        if ($KEY=="LENGTH_FROM"){
                            $validate_text.=' min-length='.$PARAM;
                        }
                        elseif($KEY=="LENGTH_TO"){
                            $validate_text.=' max-length='.$PARAM;
                        }
                    }
                    $FORM_FIELDS['FIELDS'][$key]['VALIDATOR']=$validate_text;
                }
            }
            else{
                if ($key=="phone"){
                    if (!empty(Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME']))){
                        $valbuff=substr(preg_replace('![^0-9]+!', '', Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME'])), 1);
                        if ($valbuff[0]!='9' || strlen($valbuff)!=10){
                            throw new Exception('Формат телефона неверный', 7);
                        }
                    }
                    else{
                        throw new Exception('Телефон не заполнен', 7);
                    }
                }
                elseif($FORM_FIELDS['FIELDS'][$key]['TYPE']=='checkbox'){
                    if (Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME'])[0]==$value['0']["ID"]){
                        $valbuff=true;
                    }
                    else{
                        $valbuff=false;
                    }
                }
                else{
                    $valbuff=Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME']);
                }
                $FORM_FIELDS['FIELDS'][$key]['VALUE']=$valbuff;
                $FORM_FIELDS['FIELDS'][$key]['TYPE']='hidden';

            }
            if ($FORM_FIELDS['FIELDS'][$key]["REQUIRED"] && empty($FORM_FIELDS['FIELDS'][$key]['VALUE'])){
                $FORM_FIELDS['ISSET']=false;
            }
        }
        $FORM_FIELDS["WEB_FORM_ID"]=$this->arResult['WEB_FORM_ID'];

        return $FORM_FIELDS;
    }

    private function GetClient(){
        $client_id=Context::getCurrent()->getRequest()->getCookieRaw('_ga');
        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        $this->arResult['CLIENT']=[
            'google'=>$client_id,
            'yandex'=>Context::getCurrent()->getRequest()->getCookieRaw('_ym_uid'),
        ];

        foreach ($GLOBALS['arTraficAnswer'][$this->arResult['WEB_FORM_ID']] as $key=>$value){
            $this->arResult['CLIENT'][$key]=Context::getCurrent()->getRequest()->get($value);
        }
    }

    private function GetClubNumber(){
        $res = CIBlockElement::GetByID($this->arParams['CLUB_ID']);
        if( $ob = $res->GetNextElement() ) {
            $currentClub = $ob->GetFields();
            $currentClub["PROPERTIES"] = $ob->GetProperties();
            $this->arResult['CLUB_NUMBER']=$currentClub["PROPERTIES"]["NUMBER"]["VALUE"];
            $this->arResult['CLUB_NAME']=$currentClub['NAME'];
            return true;
        }
        else{
            return false;
        }
    }

    public function ConfigureActions(){
        return [
            'getTrial'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'checkCodeTrial'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    function executeComponent(){
        global $APPLICATION;
        Loader::includeModule('iblock');

        if (empty($this->arResult["ERROR"])){
            $this->arResult['COMPONENT_NAME']=$this->GetName();

            $this->arResult['WEB_FORM_ID']=$this->arParams["WEB_FORM_ID"];
            $this->arResult["FORM_FIELDS"]=$this->GetFormFields();

            $this->arResult["AJAX_ACTION"]=$this->arParams["AJAX_ACTION"];
            $this->arResult["ABONEMENT_CODE"]=$this->arParams["ABONEMENT_CODE"];
            $this->arResult["FORM_TYPE"]=$this->arParams["FORM_TYPE"];

            $this->arResult["SALT"]=$this->GetName();

            $this->includeComponentTemplate();

        }
        else{
            echo $this->arResult["ERROR"];
        }
    }

//    AJAX
    public function getTrialAction(){
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('ELEMENT_CODE');
        $this->arResult["WEB_FORM_ID"] =Context::getCurrent()->getRequest()->getPost('WEB_FORM_ID');

        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);


        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        $this->arResult['ABONEMENT_IBLOCK_ID']=Utils::GetIBlockIDBySID('subscription');
        $this->arResult['CLUBS_IBLOCK_ID']=Utils::GetIBlockIDBySID('clubs');

        if (!$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        $arParam= [
            'type'=>$FORM_TYPE,

            'source'=>$this->arResult['CLIENT']['src'],
            'channel'=>$this->arResult['CLIENT']['mdm'],
            'campania'=>$this->arResult['CLIENT']['cnt'],
            'message'=>$this->arResult['CLIENT']['cmp'],
            'kword'=>$this->arResult['CLIENT']['trm'],
            'cid'=>$this->arResult['CLIENT']['google'],
            'yaClientID'=>$this->arResult['CLIENT']['yandex'],

            "phone"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
            'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
            'surname'=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
            'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
            'clubid'=>$this->arResult["CLUB_NUMBER"],

            'subscriptionId'=>$this->arResult["ELEMENT_CODE"]
        ];

        $api = new Api(array(
            "action" => "request_sendcode_new",
            "params" => $arParam
        ));

        $responce=$api->result();

        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"], 7);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
            }
        }

        return [
            'next-action'=>'checkCodeTrial',
            'btn'=>'Подтвердить',
            'sms_code'=>true,
            'result'=>$api->result(),
            'elements'=>[
                '.b-checkbox'=>'hide',
                '.form-field-item input'=>'disable'
            ],
        ];
    }

    public function checkCodeTrialAction(){
        $code=Context::getCurrent()->getRequest()->getPost('smscode');
        $code = preg_replace('![^0-9]+!', '', $code);
        if (strlen($code) != 5) {
            throw new Exception('Формат значения кода не верный', 10);
        }

        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('ELEMENT_CODE');
        $this->arResult["WEB_FORM_ID"] =Context::getCurrent()->getRequest()->getPost('WEB_FORM_ID');

        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);


        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        $this->arResult['ABONEMENT_IBLOCK_ID']=Utils::GetIBlockIDBySID('subscription');
        $this->arResult['CLUBS_IBLOCK_ID']=Utils::GetIBlockIDBySID('clubs');

        if (!$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        $arParam= [
            'type'=>$FORM_TYPE,

            'source'=>$this->arResult['CLIENT']['src'],
            'channel'=>$this->arResult['CLIENT']['mdm'],
            'campania'=>$this->arResult['CLIENT']['cnt'],
            'message'=>$this->arResult['CLIENT']['cmp'],
            'kword'=>$this->arResult['CLIENT']['trm'],
            'cid'=>$this->arResult['CLIENT']['google'],
            'yaClientID'=>$this->arResult['CLIENT']['yandex'],

            "phone"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
            'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
            'surname'=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
            'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
            'clubid'=>$this->arResult["CLUB_NUMBER"],

            'subscriptionId'=>$this->arResult["ELEMENT_CODE"],

            "code"=>$code
        ];
        $api = new Api(array(
            "action" => "request2_new",
            "params" => $arParam
        ));

        $responce=$api->result();

        if(empty($responce["success"]) ) {
            if ($responce['data']['http_code'] == 400) {
                throw new Exception('Не удалось подтвердить код. Попробуйте еще раз', 7);
            }
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"], 8);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
            }
        }

        $this->arResult["SALT"]=$this->GetName();
        $templatePath=\Bitrix\Main\Component\ParameterSigner::unsignParameters(
            $this->arResult["SALT"],
            Context::getCurrent()->getRequest()->getPost("path")
        );
        ob_start();
        $this->includeComponentTemplate('done', $templatePath);
        $result=ob_get_clean();

        return ['elements'=>[
                '.form-abonement__container'=>$result
            ],
        ];
    }



}
?>