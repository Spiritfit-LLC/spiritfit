<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

//КОДЫ
//1-остаемся на теущем шаге
//2-возвращаемся на первый шаг

class FormRequestNew extends CBitrixComponent implements Controllerable {
    public function ConfigureActions(){
        return [
            'reg' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'code' => [
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

    private function componentParams(){
        if(empty($arParams["WEB_FORM_ID"])){
            $this->arResult["WEB_FORM_ID"]=Context::getCurrent()->getRequest()->getPost('WEB_FORM_ID');
        }
        $this->arResult['COMPONENT_NAME']=$this->GetName();
        $this->arResult["SALT"]=$this->GetName();
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams["WEB_FORM_ID"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        return $arParams;
    }

    function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            $this->arResult["ERROR"]="Не удалось загрузить модуль iblock";
        }

        if (!empty($this->arResult["ERROR"])) {
            echo $this->arResult["ERROR"];
            return;
        }

        $this->arResult['FORM_TYPE']=$this->arParams['FORM_TYPE'];

        $this->componentParams();
        $this->arResult["WEB_FORM_ID"]=$this->arParams['WEB_FORM_ID'];
        $this->arResult["WEB_FORM_FIELDS"]=$this->arParams["WEB_FORM_FIELDS"];

        $this->arResult["SIGNED"]=[
            "WEB_FORM_FIELDS"=>$this->arParams["WEB_FORM_FIELDS"],
        ];
        $this->arResult["SIGNED"]=\Bitrix\Main\Component\ParameterSigner::signParameters($this->arResult["SALT"], $this->arResult["SIGNED"]);

        $this->arResult["FORM_FIELDS"]=$this->GetFormFields();

        if (empty($this->arParams["TEXT_FORM"])){
            $res = CIBlockElement::GetList(
                Array("SORT"=>"ASC"),
                Array('IBLOCK_ID'=>Utils::GetIBlockIDBySID('FORM_TYPES'), 'PROPERTY_FORM_TYPE'=>$this->arResult["FORM_TYPE"]),
                false,
                false,
                Array("PROPERTY_FORM_TITLE")
            );

            if ($ar_res=$res->Fetch()) {
                $this->arResult["FORM_TITLE"] = $ar_res["PROPERTY_FORM_TITLE_VALUE"];
            }
        }
        else{
            $this->arResult["FORM_TITLE"] = $this->arParams["TEXT_FORM"];
        }
        if ($this->arParams["CLUB_ID"]){
            $this->arResult["CLUB_ID"]=$this->arParams["CLUB_ID"];
        }
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

        $status = CForm::GetDataByID($this->arResult['WEB_FORM_ID'], $this->arResult['FORM']["arForm"], $this->arResult['FORM']["arQuestions"], $this->arResult['FORM']["arAnswers"], $this->arResult['FORM']["arDropDown"], $this->arResult['FORM']["arMultiSelect"]);
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


//    AJAX
    public function regAction(){
        Loader::includeModule('iblock');
        $this->componentParams();
        $this->GetClient();

        $this->arResult["SIGNED"]=\Bitrix\Main\Component\ParameterSigner::unsignParameters($this->arResult["SALT"], Context::getCurrent()->getRequest()->getPost("signed_params"));
        $this->arResult["WEB_FORM_FIELDS"]=$this->arResult["SIGNED"]["WEB_FORM_FIELDS"];

        $FORM_FIELDS=$this->GetFormFields(true, false);

//        return $FORM_FIELDS;

        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Не заполнены обязательные поля');
        }

        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];

        if (!empty(Context::getCurrent()->getRequest()->getPost("CLUB_ID"))){
            $this->arParams['CLUB_ID']=Context::getCurrent()->getRequest()->getPost("CLUB_ID");
        }

        if (!$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран');
        }

        $this->arResult['FORM_TYPE']=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $arParams=[
            'type'=>$this->arResult['FORM_TYPE'],

            'source'=>$this->arResult['CLIENT']['src'],
            'channel'=>$this->arResult['CLIENT']['mdm'],
            'campania'=>$this->arResult['CLIENT']['cnt'],
            'message'=>$this->arResult['CLIENT']['cmp'],
            'kword'=>$this->arResult['CLIENT']['trm'],
            'cid'=>$this->arResult['CLIENT']['google'],
            'yaClientID'=>$this->arResult['CLIENT']['yandex'],

            'clubid'=>$this->arResult["CLUB_NUMBER"],

            "phone"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
            'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
            'surname'=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
            'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
        ];

        if (!empty($FORM_FIELDS['FIELDS']["company"]["VALUE"])){
            $arParams["company"]=$FORM_FIELDS["FIELDS"]["company"]["VALUE"];
        }
        if (!empty($FORM_FIELDS['FIELDS']["address"]["VALUE"])){
            $arParams["address"]=$FORM_FIELDS["FIELDS"]["address"]["VALUE"];
        }

        $res = CIBlockElement::GetList(
            Array("SORT"=>"ASC"),
            Array('IBLOCK_ID'=>Utils::GetIBlockIDBySID('FORM_TYPES'), 'PROPERTY_FORM_TYPE'=>$this->arResult["FORM_TYPE"]),
            false,
            false,
            Array("PROPERTY_GA_EACTION", "PROPERTY_GA_ELLABEL", "PROPERTY_GA_ECATEGORY", "PROPERTY_FORM_TITLE", "PROPERTY_UPMETRIC_CLIENT_TYPE")
        );

        if ($ar_res=$res->Fetch()){
            $DATALAYER=[
                "eAction"=>$ar_res['PROPERTY_GA_EACTION_VALUE'],
                "eCategory"=>$ar_res["PROPERTY_GA_ECATEGORY_VALUE"],
                "eLabel"=>str_replace('<br>', ' ', $this->arResult['CLUB_NAME']).'/'.$ar_res["PROPERTY_GA_ELLABEL_VALUE"]
            ];

            if (!empty($ar_res["PROPERTY_UPMETRIC_CLIENT_TYPE_VALUE"])){
                $UPMETRIC=[
                    "CLIENT_TYPE"=>$ar_res["PROPERTY_UPMETRIC_CLIENT_TYPE_VALUE"],
                    "PHONE"=>"7".$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    "EMAIL"=>$FORM_FIELDS['FIELDS']['email']['VALUE']
                ];
            }
        }




        global $USER;
        if ($USER->IsAuthorized() && $USER->GetLogin()==$FORM_FIELDS['FIELDS']['phone']['VALUE']){
            //СМС не нужна, пользователь авторизован и ввел свои данные, отправялем contact
            $api=new Api([
                "action"=>"contact",
                "params"=>$arParams
            ]);

            $response=$api->result();
            if (!$response["success"]){
                if (!empty($response["data"]["result"]["userMessage"])) {
                    throw new Exception($response["data"]["result"]["userMessage"]);
                } else {
                    throw new Exception("Непредвиденная ошибка");
                }
            }

            $result=[
                'next-action'=>'reg',
                'btn'=>'Отправить',
                'elements'=>[
                    '.subscription__sent-tel'=>'<div class="subscription__sent-tel"></div>',
                    '.form-request-new__code'=>"hide",
                    '.form-request-new__fields-list'=>"show",
                    '.form-request-new__agreements'=>"show"
                ],
                'message'=>"Спасибо! Ваша заявка успешно отправлена!",
                "enable-inputs"=>true,
                "clear-inputs"=>true,
            ];

            if (!empty($DATALAYER)){
                $result["dataLayer"]=$DATALAYER;
            }
            if (!empty($UPMETRIC)){
                $result["upmetric"]=$UPMETRIC;
            }

            return $result;
        }
        else{
            //СМС НУЖНА, отправляем reg
            $api=new Api([
                "action"=>"reg",
                "params"=>$arParams
            ]);

            $response=$api->result();
            if (!$response["success"]) {
                if (!empty($response["data"]["result"]["userMessage"])) {
                    throw new Exception($response["data"]["result"]["userMessage"]);
                } else {
                    throw new Exception("Непредвиденная ошибка");
                }
            }


            $result=[
                'next-action'=>'code',
                'btn'=>'Подтвердить',
                'elements'=>[
                    '.subscription__sent-tel'=>'<div class="subscription__sent-tel">+7 ('
                        .substr($FORM_FIELDS['FIELDS']['phone']['VALUE'], 0, 3).') '
                        .substr($FORM_FIELDS['FIELDS']['phone']['VALUE'], 3, 3).'-'
                        .substr($FORM_FIELDS['FIELDS']['phone']['VALUE'], 6, 2).'-'
                        .substr($FORM_FIELDS['FIELDS']['phone']['VALUE'], 8, 2).'</div>',
                    '.form-request-new__fields-list'=>"hide",
                    '.form-request-new__agreements'=>"hide",
                    '.form-request-new__code'=>"show"
                ],
            ];

            if (!empty($DATALAYER)){
                $result["dataLayer"]=$DATALAYER;
            }
            if (!empty($UPMETRIC)){
                $result["upmetric"]=$UPMETRIC;
            }

            return $result;
        }
    }

    public function codeAction(){
        Loader::includeModule('iblock');
        $this->componentParams();
        $this->GetClient();

        $this->arResult["SIGNED"]=\Bitrix\Main\Component\ParameterSigner::unsignParameters($this->arResult["SALT"], Context::getCurrent()->getRequest()->getPost("signed_params"));
        $this->arResult["WEB_FORM_FIELDS"]=$this->arResult["SIGNED"]["WEB_FORM_FIELDS"];

        $FORM_FIELDS=$this->GetFormFields(true, false);

        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Не заполнены обязательные поля');
        }

        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!empty(Context::getCurrent()->getRequest()->getPost("CLUB_ID"))){
            $this->arParams['CLUB_ID']=Context::getCurrent()->getRequest()->getPost("CLUB_ID");
        }

        if (!$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран');
        }

        $this->arResult['FORM_TYPE']=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $code=Context::getCurrent()->getRequest()->getPost('request-code');
        if (empty($code)){
            throw new Exception("Не удалось выполнить проверку кода");
        }

        //Подтверждаем СМС Код
        $code=preg_replace('![^0-9]+!', '', $code);
        if (strlen($code)!=5){
            throw new Exception("Некорректный код");
        }

        $api=new Api([
            "action"=>"code",
            "params"=>[
                "phone"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                "code"=>$code,
            ]
        ]);

        $response=$api->result();
        if (!$response["success"]) {
            if ($response['data']['http_code'] == 400) {
                return [
                    'next-action'=>'reg',
                    'btn'=>'Отправить',
                    'elements'=>[
                        '.subscription__sent-tel'=>'<div class="subscription__sent-tel"></div>',
                        '.form-request-new__code'=>"hide",
                        '.form-request-new__fields-list'=>"show",
                        '.form-request-new__agreements'=>"show",
                        ".form-request-new__footer"=>["style"=>[
                            "justify-content"=>"space-between"
                        ]]
                    ],
                    'message'=>"Не удалось подтвердить код. Пожалуйста, попробуйте еще раз!",
                    "enable-inputs"=>true,
                    "clear-inputs"=>true,
                ];
            }
            if (!empty($response["data"]["result"]["userMessage"])) {
                throw new Exception($response["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Непредвиденная ошибка");
            }
        }

        $arParams=[
            'type'=>$this->arResult['FORM_TYPE'],

            'source'=>$this->arResult['CLIENT']['src'],
            'channel'=>$this->arResult['CLIENT']['mdm'],
            'campania'=>$this->arResult['CLIENT']['cnt'],
            'message'=>$this->arResult['CLIENT']['cmp'],
            'kword'=>$this->arResult['CLIENT']['trm'],
            'cid'=>$this->arResult['CLIENT']['google'],
            'yaClientID'=>$this->arResult['CLIENT']['yandex'],

            'clubid'=>$this->arResult["CLUB_NUMBER"],

            "phone"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
            'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
            'surname'=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
            'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
        ];

        if (!empty($FORM_FIELDS['FIELDS']["company"]["VALUE"])){
            $arParams["company"]=$FORM_FIELDS["FIELDS"]["company"]["VALUE"];
        }
        if (!empty($FORM_FIELDS['FIELDS']["address"]["VALUE"])){
            $arParams["address"]=$FORM_FIELDS["FIELDS"]["address"]["VALUE"];
        }

        $api=new Api([
            "action"=>"contact",
            "params"=>$arParams
        ]);

        $response=$api->result();
        if (!$response["success"]){
            if (!empty($response["data"]["result"]["userMessage"])) {
                throw new Exception($response["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Непредвиденная ошибка");
            }
        }

        $result=[
            'next-action'=>'reg',
            'btn'=>'Отправить',
            'elements'=>[
                '.subscription__sent-tel'=>'<div class="subscription__sent-tel"></div>',
                '.form-request-new__code'=>"hide",
                '.form-request-new__fields-list'=>"show",
                '.form-request-new__agreements'=>"show"
            ],
            'message'=>"Спасибо! Ваша заявка успешно отправлена!",
            "enable-inputs"=>true,
            "clear-inputs"=>true,
        ];

        return $result;
    }

}
?>