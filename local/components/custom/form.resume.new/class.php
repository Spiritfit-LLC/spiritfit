<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;


class FormResumeNew extends CBitrixComponent implements Controllerable {
    public function ConfigureActions(){
        return [
            "send"=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    protected function listKeysSignedParameters()
    {
        return [  //массива параметров которые надо брать из параметров компонента
            "WEB_FORM_ID",
            "EMAIL"
        ];
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams["WEB_FORM_ID"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        if( empty($arParams["EMAIL"]) ){
            $this->arResult["ERROR"] = "Не задан email адрес";
        }
        return $arParams;
    }

    function executeComponent()
    {
        if (!empty($this->arResult["ERROR"])) {
            echo $this->arResult["ERROR"];
            return;
        }
        $this->arResult["FORM_FIELDS"]=$this->GetFormFields(false, false);
        $this->arResult["COMPONENT_NAME"]=$this->GetName();

        $this->IncludeComponentTemplate();
    }

    private function GetFormFields($request=false, $check=true){
        $FORM_FIELDS=[];
        $FORM_FIELDS['ISSET']=true;
        if ($request && $check){
            $error = CForm::Check($this->arResult['WEB_FORM_ID'], Context::getCurrent()->getRequest()->toArray());
            if (strlen($error)>0){
                return false;
            }
        }

        $status = CForm::GetDataByID($this->arParams['WEB_FORM_ID'], $this->arResult['FORM']["arForm"], $this->arResult['FORM']["arQuestions"], $this->arResult['FORM']["arAnswers"], $this->arResult['FORM']["arDropDown"], $this->arResult['FORM']["arMultiSelect"]);
        $FORM=$this->arResult['FORM'];
        if(!$status) {
            return ['result'=>false, 'error'=>'Не удалось выполнить запрос'];
        }

        if (!empty($this->arResult["WEB_FORM_FIELDS"])){
            $WEB_FORM_FIELDS=$this->arResult["WEB_FORM_FIELDS"];
        }

        foreach($FORM["arAnswers"] as $key=>$value) {
            if (!empty($WEB_FORM_FIELDS) && count($WEB_FORM_FIELDS)>0 && !in_array($key, $WEB_FORM_FIELDS)){
                continue;
            }

            $FORM_FIELDS['FIELDS'][$key]=[
                'PLACEHOLDER'=>$FORM["arQuestions"][$key]["TITLE"],
                'TYPE'=>$key=="phone" ? "tel" : $value['0']["FIELD_TYPE"],
                'REQUIRED'=>$FORM["arQuestions"][$key]["REQUIRED"]=="Y" ? true:false,
                "COMMENT"=>$FORM["arQuestions"][$key]["COMMENTS"],
                "PARAMS"=>$value['0']["FIELD_PARAM"],
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
                    $arFilter = array(
                        "IBLOCK_ID" => Utils::GetIBlockIDBySID('clubs'),
                        "PROPERTY_SOON" => false,
                        "ACTIVE" => "Y",
                        "PROPERTY_HIDE_LINK_VALUE"=>false
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
                elseif($FORM_FIELDS["FIELDS"][$key]["TYPE"]=="file"){
                    $photo_file=Context::getCurrent()->getRequest()->getFile($FORM_FIELDS['FIELDS'][$key]['NAME']);
                    $fileId = CFile::SaveFile($photo_file, 'career/resume');
                    $valbuff=$fileId;
                }
                else{
                    $valbuff=Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME']);
                }
                $FORM_FIELDS['FIELDS'][$key]['VALUE']=$valbuff;
                $FORM_FIELDS['FIELDS'][$key]['TYPE']='hidden';

                if ($FORM_FIELDS['FIELDS'][$key]["REQUIRED"] && empty($FORM_FIELDS['FIELDS'][$key]['VALUE'])){
                    $FORM_FIELDS['ISSET']=false;
                }
            }
        }
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

        foreach (getGaFormInputs(Context::getCurrent()->getRequest()->toArray()) as $key=>$value){
            $this->arResult['CLIENT'][$key]=$value;
        }
    }


    public function sendAction(){
        $this->GetClient();
        $FORM_FIELDS=$this->GetFormFields(true, false);
        if (!$FORM_FIELDS["ISSET"]){
            throw new Exception('Не заполнены обязательные поля', 7);
        }

        $postText="Новое резюме.\n\n".
        "Имя: ".$FORM_FIELDS["FIELDS"]["name"]["VALUE"]."\n".
        "Фамилия: ". $FORM_FIELDS["FIELDS"]["surname"]["VALUE"]."\n".
        "Телефон: ". $FORM_FIELDS["FIELDS"]["phone"]["VALUE"]."\n".
        "E-mail: ". $FORM_FIELDS["FIELDS"]["email"]["VALUE"]."\n";

        if (!empty($FORM_FIELDS["FIELDS"]["position"]["VALUE"])){
            $postText.="Должность: ".$FORM_FIELDS["FIELDS"]["position"]["VALUE"]."\n";
        }
        if (!empty($FORM_FIELDS["FIELDS"]["salary"]["VALUE"])){
            $postText.="Ожидаемая зп: ".$FORM_FIELDS["FIELDS"]["salary"]["VALUE"]."\n";
        }
        if (!empty($FORM_FIELDS["FIELDS"]["metro"]["VALUE"])){
            $postText.="Метро: ".$FORM_FIELDS["FIELDS"]["metro"]["VALUE"]."\n";
        }
        if (!empty($FORM_FIELDS["FIELDS"]["description"]["VALUE"])){
            $postText.="Дополнительная информация: ".$FORM_FIELDS["FIELDS"]["description"]["VALUE"]."\n\n";
        }
        if (!empty($FORM_FIELDS["FIELDS"]["file_resume"]["VALUE"])){
            $postText.="Ссылка на резюме: ".MAIN_SITE_URL.CFile::GetPath($FORM_FIELDS["FIELDS"]["file_resume"]["VALUE"]);
        }

        $subject="Резюме на сайте career.spiritfit.ru";
        $emails=[$this->arParams["EMAIL"]];

        $api=new Api([
            "action"=>"sendEmailFromSMTP",
            "params"=>[
                "subject"=>$subject,
                "message"=>$postText,
                "address"=>$emails
            ]
        ]);

        $response=$api->result();
        if ($response["success"]){
            return ["result"=>true, "message"=>"Ваша заявка успешно отправлена"];
        }
        else{
            throw new Exception('Произошла ошибка при отправке заявки.');
        }
    }
}
?>