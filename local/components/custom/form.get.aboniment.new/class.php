<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class FormGetAbonimentComponentNew extends CBitrixComponent implements Controllerable {

    public function __construct($component = null)
    {
        CModule::IncludeModule("iblock");
        parent::__construct($component);
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams["WEB_FORM_ID"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        else{
            $arParams["WEB_FORM_ID"]=Utils::GetFormIDBySID($arParams['WEB_FORM_ID']);
        }

        $CLUB_ID=Context::getCurrent()->getRequest()->getPost('club_id');
        if (!empty($CLUB_ID)){
            $arParams["CLUB_ID"]=$CLUB_ID;
        }

        return $arParams;
    }

    protected function listKeysSignedParameters()
    {
        return [  //массива параметров которые надо брать из параметров компонента
            "WEB_FORM_ID",
            "ELEMENT_CODE",
            "CLUB_ID",
            "FORM_TYPE",
            "INVOICE_ID"
        ];
    }

    public function ConfigureActions(){
        return [
            'getClub'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'applyPromocode'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'setBonus'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'checkSms'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'done'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getOrder'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getTrial'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'checkSmsTrial'=>[
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

    private function GetElement(){
        $elArray = [];
        $clubRes = CIBlockElement::GetList([], ["IBLOCK_ID" => Utils::GetIBlockIDBySID('subscription'), "CODE" => $this->arParams["ELEMENT_CODE"], "ACTIVE" => "Y"], false);
        if( $ob = $clubRes->GetNextElement() ) {
            $elArray = $ob->GetFields();
            $elArray["PROPERTIES"] = $ob->GetProperties();
        }
        else{
            return false;
        }
        $this->arResult['ELEMENT']=$elArray;
        return true;
    }

    private function CheckClub(){
        foreach($this->arResult['ELEMENT']['PROPERTIES']['PRICE']['VALUE'] as $key=>$arPrice){
            if ($arPrice['LIST']==$this->arParams['CLUB_ID']){
                $this->arResult['CLUB_ID']=$this->arParams['CLUB_ID'];
                return true;
            }
        }
        return false;
    }

    private function GetClub(){
        $defaultSign=[
            1=>'Первый месяц',
            2=>'Второй месяц',
            3=>'Третий месяц',
            4=>'Четвертый месяц',
            5=>'Пятый месяц',
            6=>'Шестой месяц',
            7=>'Седьмой месяц',
            8=>'Восьмой месяц',
            9=>'Девятый месяц',
            10=>'Десятый месяц',
            11=>'Одиннацатый месяц',
            12=>'Год'
        ];


        $CLUB_ID=$this->arResult['CLUB_ID'];
        $ELEMENT=$this->arResult['ELEMENT'];
        if (empty($CLUB_ID) || empty($ELEMENT)){
            return false;
        }

        $result=['PRICE'=>[], 'SERVICES'=>[]];

        //Добываем прайс для данного клуба
        foreach($ELEMENT['PROPERTIES']['BASE_PRICE']['VALUE'] as $key=>$arPrice){
            if ($arPrice['LIST']==$CLUB_ID){
                $BASE_PRICE=$arPrice['PRICE'];
                $NUMBER=$arPrice['NUMBER'];
                break;
            }
        }
        foreach($ELEMENT['PROPERTIES']['PRICE']['VALUE'] as $key=>$arPrice){
            if ($arPrice['LIST']==$CLUB_ID){
                $PRICE[]=$arPrice;
                if (empty($NUMBER)){
                    $NUMBER=$arPrice['NUMBER'];
                }
            }
        }
        foreach($ELEMENT['PROPERTIES']['PRICE_SIGN_DETAIL']['VALUE'] as $key=>$arPrice){
            if ($arPrice['LIST']==$CLUB_ID){
                $SIGN[]=$arPrice;
            }
        }


        if (!empty($PRICE)){
            foreach ($PRICE as $price){
                $result['PRICE'][$price['NUMBER']]['VALUE']=$price['PRICE'];
                if ($price['NUMBER']==$NUMBER || $price["NUMBER"]==0){
                    $result['CURRENT_PRICE']=$price['PRICE'];
                }
            }
        }
        else{
            $result['PRICE'][$NUMBER]['PRICE']=$BASE_PRICE;
            $result['CURRENT_PRICE']=$BASE_PRICE;
            unset($BASE_PRICE);
        }
        $result['BASE_PRICE']=$BASE_PRICE;

        //Дефолт SIGN
        foreach ($result['PRICE'] as $key=>&$value){
            $DBRes=CIBlockElement::GetList(array("SORT" => "ASC"), ["IBLOCK_ID"=>Utils::GetIBlockIDBySID("price_sign"), "ACTIVE"=>"Y", "NAME"=>$key], false, false, array("PROPERTY_MONTH"));
            if ($signRes=$DBRes->Fetch()){
                $value['SIGN']=$signRes["PROPERTY_MONTH_VALUE"];
            }
            else{
                $value['SIGN']=$defaultSign[(int)$key];
            }
        }

        //Если есть такая подпись
        foreach ($SIGN as $sign){
            $result['PRICE'][$sign['NUMBER']]['SIGN']=$sign['PRICE'];
        }

        //Услуги:
        foreach($ELEMENT['PROPERTIES']['FOR_PRESENT']['VALUE'] as $key=>$arPrice){
            if ($arPrice['LIST']==$CLUB_ID){
                $SERVICES[]=$arPrice['PRICE'];
            }
        }
        $result['SERVICES']=$SERVICES;

        $_SESSION['CURRENT_PRICE']=$result['CURRENT_PRICE'];
        $res = CIBlockElement::GetByID($this->arParams['CLUB_ID']);
        if( $ob = $res->GetNextElement() ) {
            $currentClub = $ob->GetFields();
            $result['CLUB_NAME']=$currentClub['NAME'];
        }

        $this->arResult['CLUB']=$result;
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

    private function GetClubsArr(){
        foreach($this->arResult['ELEMENT']['PROPERTIES']['BASE_PRICE']['VALUE'] as $key=>$arPrice){
            $result[]=$arPrice['LIST'];
        }
        return $result;
    }

    private function GetFormFields($request=false, $check=true){
        $FORM_FIELDS=[];
        if ($request && $check){
            $error = CForm::Check($this->arParams['WEB_FORM_ID'], Context::getCurrent()->getRequest()->toArray());
            if (strlen($error)>0){
                return false;
            }
            $FORM_FIELDS['ISSET']=true;
        }

        $status = CForm::GetDataByID($this->arParams['WEB_FORM_ID'], $this->arResult['FORM']["arForm"], $this->arResult['FORM']["arQuestions"], $this->arResult['FORM']["arAnswers"], $this->arResult['FORM']["arDropDown"], $this->arResult['FORM']["arMultiSelect"]);
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
                "COMMENT"=>$FORM["arQuestions"][$key]["COMMENTS"],
                "PARAMS"=>$value['0']["FIELD_PARAM"],
                "ID"=>$key.'-field'
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
                    }
                    $FORM_FIELDS['FIELDS'][$key]["CLASSNAME"]="long-row";
                }
                elseif ($FORM_FIELDS['FIELDS'][$key]['TYPE']=='checkbox'){
                    $FORM_FIELDS['FIELDS'][$key]['VALUE']=$value['0']["ID"];
                    $FORM_FIELDS['FIELDS'][$key]["CLASSNAME"]="long-row margin-20";
                }
                elseif ($key=="promocode"){
                    $FORM_FIELDS['FIELDS'][$key]["CLASSNAME"]="long-row";
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

    private function setSeo(){
        global $APPLICATION;
        /* Получаем значения SEO */
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arResult['ABONEMENT_IBLOCK_ID'], $this->arResult["ELEMENT"]["ID"]);
        $seoValues = $ipropValues->getValues();
        if ($seoValues['ELEMENT_META_TITLE']) {
            $ELEMENT_META_TITLE = $seoValues['ELEMENT_META_TITLE'];
        } else {
            $ELEMENT_META_TITLE = strip_tags($this->arResult["ELEMENT"]["~NAME"]).' - '.'Абонементы сети фитнес-залов Spirit.Fitness';
        }

        if ($seoValues['ELEMENT_META_DESCRIPTION']) {
            $ELEMENT_META_DESCRIPTION = $seoValues['ELEMENT_META_DESCRIPTION'];
        }
        else{
            $ELEMENT_META_DESCRIPTION=$this->arResult["ELEMENT"]["~NAME"].'. 💸 Удобная ежемесячная оплата 💥 Полный безлимит по времени и услугам 💯';
        }

        if ($seoValues['SECTION_META_KEYWORDS']) {
            $ELEMENT_META_KEYWORDS = $seoValues['ELEMENT_META_KEYWORDS'];
            $APPLICATION->SetPageProperty('keywords', $ELEMENT_META_KEYWORDS);
        }

        $APPLICATION->SetPageProperty('title', $ELEMENT_META_TITLE);
        $APPLICATION->SetPageProperty('description', $ELEMENT_META_DESCRIPTION);


        $arInfoProps = Utils::getInfo()['PROPERTIES'];
        if($this->arResult["ELEMENT"]["PREVIEW_PICTURE"]) {
            $ogImage = CFile::GetPath($this->arResult["ELEMENT"]["PREVIEW_PICTURE"]);
        } else {
            $ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
        }
        $APPLICATION->AddViewContent('inhead', $ogImage);
        $APPLICATION->AddChainItem(strip_tags($this->arResult["ELEMENT"]["~NAME"]));

    }

    private function set404() {
        \Bitrix\Iblock\Component\Tools::process404(
            '',
            true,
            true,
            true,
            false
        );
    }

    function executeComponent()
    {
        if (!empty($this->arResult["ERROR"])){
            echo $this->arResult["ERROR"];
            return;
        }

        if (!$this->GetElement()){
            $this->set404();
        }

        $this->setSeo();

        if (!empty($this->arParams['CLUB_ID'])){
            if ($this->CheckClub()){
                $this->GetClub();
            }
            else{
                $this->set404();
            }
        }

        $this->arResult['FORM_FIELDS']=$this->GetFormFields();
        $siteSettings = Utils::getInfo();
        if(!empty($siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'])) {
            $this->arResult["OFERTA_TEXT"] = $siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'];
        }

        if (!empty($this->arResult["ELEMENT"]["PROPERTIES"]["HAS_LEADER"]["VALUE"])){
            $this->arResult["LEADERS"]=[
                "NAME"=>"leader_id",
                "REQUIRED"=>true,
            ];
            $filter=["IBLOCK_ID"=>Utils::GetIBlockIDBySID("leaders"), "ACTIVE"=>"Y"];

            $LEADERS_ID=$this->arResult["ELEMENT"]["PROPERTIES"]["LEADERS_ID"]["VALUE"];
            if ($LEADERS_ID){
                $filter["ID"]=$LEADERS_ID;
            }
            $dbRes=CIBlockElement::GetList(Array("SORT"=>"ASC"), $filter, false, false, array('ID', 'NAME'));
            while($leader=$dbRes->Fetch()){
                $this->arResult["LEADERS"]["ITEMS"][]=[
                    "VALUE"=>$leader["ID"],
                    "STRING"=>$leader["NAME"],
                    "SELECTED"=>$_GET["leader_id"]==$leader["ID"]?true:false,
                ];
            }
        }

        $this->IncludeComponentTemplate();
    }

    private function sendSMS($phone){
        $api=new Api([
            'action'=>'ordercode',
            'params'=>[
                'phone'=>$phone
            ]
        ]);

        $responce=$api->result();
        if ($responce['error']==true){
            throw new Exception('Ошибка выполнения запроса');
        }
        if ($responce['success']==true){
            $_SESSION['code']=$responce["data"]["result"]["userMessage"];
            return true;
        }
        else{
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"], 1);
            } else {
                throw new Exception("Непредвиденная ошибка", 1);
            }
        }
    }

    public function orderCreate(){
        $this->GetClient();
        $FORM_FIELDS=$this->GetFormFields(true);


        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 1);
        }

        $this->GetElement();
        if (!empty($this->arResult["ELEMENT"]["PROPERTIES"]["HAS_LEADER"]["VALUE"])){
            $LEADER_ID=Context::getCurrent()->getRequest()->getPost("leader_id");

            $this->arResult["LEADERS"]=[
                "NAME"=>"leader_id",
                "REQUIRED"=>true,
            ];
            $LEADERS_ID=$this->arResult["ELEMENT"]["PROPERTIES"]["LEADERS_ID"]["VALUE"];
            if ($LEADERS_ID && !in_array($LEADER_ID, $LEADERS_ID)){
                throw new Exception('Тренер не может быть выбран', 1);
            }

            $dbRes=CIBlockElement::GetByID($LEADER_ID);
            if (!$rsLeader=$dbRes->GetNextElement()){
                throw new Exception("Не удалось выбрать тренера", 1);
            }
            $leader=$rsLeader->GetProperties();
        }

        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 1);
        }

        $arParams=[
            'type'=>$this->arParams["FORM_TYPE"],

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
            'promocode'=>$FORM_FIELDS['FIELDS']['promocode']['VALUE'],

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"],

            'action'=>'widget',
            'price'=>(int)($_SESSION['CURRENT_PRICE']),
            "leaderId"=>!empty($leader)?$leader["CODE_1C"]["VALUE"]:null
        ];

        unset($_SESSION['CURRENT_PRICE']);


        $api=new Api([
            'action'=>'ordercreate',
            'params'=>$arParams
        ]);

        $responce=$api->result();

//        //ИМИТАЦИЯ ДАННЫХ
//        $responce=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"amount":3490,"publicID":"pk_1e0b24ff055b1c6b0e3bb4e0e2774","JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":"3490","quantity":"1","amount":"3490","vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"description":"Оплата контракта № N10011587 от 22 декабря 2022 г. При оплате, лицо ознакомлено с условиями Оферты, принимает их.","bonusessum":349,"invoiceId":"0S20003522","fullprice":3490},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];

        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Непредвиденная ошибка");
            }
        }

        $this->arParams["INVOICE_ID"]=$responce["data"]["result"]["result"]["invoiceId"];

        $result=[
            "action"=>"bonuses",
            "ajax"=>"setBonus",
            "bonuses"=>!empty($responce["data"]["result"]["result"]["bonusessum"])?$responce["data"]["result"]["result"]["bonusessum"]:null,
            "model"=>[
                "publicId"=>$responce["data"]["result"]["result"]["publicID"],
                "description"=>$responce["data"]["result"]["result"]["description"],
                "amount"=>(float)$responce["data"]["result"]["result"]["amount"],
                "currency"=>'RUB',
                "accountId"=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                "invoiceId"=>$responce["data"]["result"]["result"]["invoiceID"],
                'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                "requireEmail"=>true,
                "skin"=>"mini",
                "data"=>$responce["data"]["result"]["result"]["JsonData"],
            ],
            "fullprice"=>$responce["data"]["result"]["result"]["fullprice"],
        ];

        return $result;
    }

    //AJAX
    public function getClubAction($club_id){
        $this->GetElement();

        if (empty($this->arResult['ELEMENT'])){
            throw new Exception('Ошибка::Абонемент не выбран');
        }

        if ($this->CheckClub()){
            $this->GetClub();

            if(!empty($this->arResult['CLUB']['SERVICES'])){
                $SERVICES='<div class="subscription__subheading">Услуги в подарок:</div>';
                $SERVICES.='<ul class="subscription__gift">';
                foreach ($this->arResult['CLUB']['SERVICES'] as $service){
                    $SERVICES.='<li class="subscription__gift-item">'.$service.'</li>';
                }
                $SERVICES.='</ul>';
            }
            $this->arResult['CLUB']['SERVICES']=$SERVICES;

            if(!empty($this->arResult['CLUB']['PRICE'])){
                $PRICES='<div class="subscription__label">';
                foreach($this->arResult['CLUB']['PRICE'] as $key=>$price){
                    if(intval($key) == 99 )
                        continue;

                    if (empty($price['VALUE']))
                        continue;
                    $PRICES.='<div class="subscription__label-item" data-month="'.$key.'">';
                    $PRICES.=$price['SIGN'].' - <span class="price-value">'.$price['VALUE'].'</span> руб.';
                    $PRICES.='</div>';
                }
                $PRICES.='</div>';
            }
            $this->arResult['CLUB']['PRICE']=$PRICES;

            return $this->arResult['CLUB'];
        }
        else{
            throw new Exception('Клуб не может быть выбран', 1);
        }
    }

    public function applyPromocodeAction(){
        $this->GetElement();
        if (empty($this->arResult["ELEMENT"])){
            throw new Exception('Не выбран абонемент');
        }

        $FORM_FIELDS=$this->GetFormFields(true, false);

        if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
            throw new Exception('Выберите клуб', 1);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 1);
        }

        if (empty($FORM_FIELDS['FIELDS']['promocode']['VALUE'])){
            throw new Exception('Введите промокод', 1);
        }

        if (empty($FORM_FIELDS['FIELDS']['phone']['VALUE'])){
            throw new Exception('Введите номер телефона', 1);
        }

        $api=new Api([
            'action'=>'orderpromocode',
            'params'=>[
                'phone'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'clubid'=>$this->arResult["CLUB_NUMBER"],
                'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"],
                'promocode'=>$FORM_FIELDS['FIELDS']['promocode']['VALUE']
            ]
        ]);

        $responce=$api->result();
        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Промокод не применен");
            }
        }
        else{
            $prices=$responce["data"]["result"]["result"]['object']['prices'];
            foreach($prices as $item){
                $result["DISCOUNTS"][]=$item;
                if (!empty($item['baseprice']) && empty($result["BASEPRICE"])){
                    $result["BASEPRICE"]=$item['baseprice'];
                }
                if ($item['number']==1){
                    $result['CURRENT_PRICE']=$item['price'];

                    if (!empty($item['baseprice'])){
                        $result["BASEPRICE"]=$item['baseprice'];
                    }
                }
            }
            if(!empty($responce["data"]["result"]["result"]['object']["free"]) ) {
                $result["MESSAGE"] = "Бесплатный абонемент. Для верификации, мы спишем с карты и вернем небольшую сумму, чтобы убедиться, что Вы человек, а не робот.";
            } else {
                $result["MESSAGE"] = "Ваш промокод применен";
            }
            $result["promocode"] = $FORM_FIELDS['FIELDS']['promocode']['VALUE'];
        }

        $_SESSION['CURRENT_PRICE']=$result;
        return $result;
    }

    public function getOrderAction($legalinfo){
        global $USER;
        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 1);
        }

        if (!$USER->IsAuthorized() || ($USER->GetLogin()!=$FORM_FIELDS['FIELDS']['phone']['VALUE'])){
            $this->sendSMS($FORM_FIELDS['FIELDS']['phone']['VALUE']);

            return ["action"=>"sms", "ajax"=>"checkSms"];
        }

        return $this->orderCreate();
    }

    public function setBonusAction($bonussessum, $invoiceid){

        $api=new Api([
            "action"=>"orderedit",
            "params"=>[
                "invoiceID"=>$invoiceid,
                "bonusessum"=>(int)$bonussessum
            ]
        ]);

        $response=$api->result();

//        ИМИТАЦИЯ ДАННЫХ1
//        $response=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":"3490","quantity":"1","amount":"3490","vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"amount":1000},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];

//        $response=[
//            "success"=>false,
//            "data"=>[
//                "result"=>json_decode('{"result":false,"userMessage":"Превышен лимит бонусов","errorCode":3,"success":false}', true)
//            ]
//        ];

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $ERROR=$response["data"]["result"]["userMessage"];
            } else {
                $ERROR="Непредвиденная ошибка";
            }
            throw new Exception($ERROR);
        }

        return [
            "amount"=>$response["data"]["result"]["result"]["amount"],
            "action"=>"recalc",
            "ajax"=>"setBonus",
            "jsonData"=>$response["data"]["result"]["result"]["JsonData"]
        ];
    }

    public function checkSmsAction($sms_code_field){
        $FORM_FIELDS=$this->GetFormFields(true);
        $sms_code_field=preg_replace('![^0-9]+!', '', $sms_code_field);

        if (strlen($sms_code_field) != 5) {
            throw new Exception('Формат значения кода не верный', 10);
        }

        $api=new Api([
            'action'=>'ordercodecheck',
            'params'=>[
                'phone'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'code'=>$sms_code_field
            ]
        ]);

        $response=$api->result();
        if ($response['success'] == true) {
            return $this->orderCreate();
        } else {
            switch ($response['data']['http_code']) {
                case 200:
                    throw new Exception('Не верный код из СМС', 2);
                case 400:
                    throw new Exception('Не удалось подтвердить код. Попробуйте еще раз', 1);
                default:
                    throw new Exception('Непредвиденная ошибка');
            }
        }
    }

    public function doneAction(){
        ob_start();
        $this->IncludeComponenttemplate('done');
        return ob_get_clean();
    }

    public function getTrialAction(){
        $this->GetClient();
        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 1);
        }

        $this->GetElement();
        if (!empty($this->arResult["ELEMENT"]["PROPERTIES"]["HAS_LEADER"]["VALUE"])){
            $LEADER_ID=Context::getCurrent()->getRequest()->getPost("leader_id");
            if (empty($LEADER_ID)){
                throw new Exception('Выберите тренера', 7);
            }

            $this->arResult["LEADERS"]=[
                "NAME"=>"leader_id",
                "REQUIRED"=>true,
            ];
            $LEADERS_ID=$this->arResult["ELEMENT"]["PROPERTIES"]["LEADERS_ID"]["VALUE"];
            if ($LEADERS_ID && !in_array($LEADER_ID, $LEADERS_ID)){
                throw new Exception('Тренер не может быть выбран', 1);
            }

            $dbRes=CIBlockElement::GetByID($LEADER_ID);
            if (!$rsLeader=$dbRes->GetNextElement()){
                throw new Exception("Не удалось выбрать тренера", 1);
            }
            $leader=$rsLeader->GetProperties();
        }

        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 1);
        }

        global $USER;
        if ($USER->IsAuthorized() && $USER->GetLogin()==$FORM_FIELDS['FIELDS']['phone']['VALUE']){
            $currUser = PersonalUtils::get_lk_info($USER->GetID());
            $workout = unserialize($currUser["UF_WORKOUT"]);
            if (!empty($workout["usage"])){
                return ["action"=>"href", 'href'=>'/personal/?v=2&pds=workout&club='.$this->arResult["CLUB_NUMBER"]];
            }
            else{
                throw new Exception("К сожалению, вам недоступна пробная тренировка. <br>Мы свяжемся с Вами для уточнения дополнительной информации.", 20);
            }
        }

        $currUser=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);

        $arParam= [
            'type'=>$this->arParams["FORM_TYPE"],

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

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"],
            "leaderId"=>!empty($leader)?$leader["CODE_1C"]["VALUE"]:null
        ];

        if ($arUser=$currUser->Fetch()){
            $currUser = PersonalUtils::get_lk_info($USER->GetID());
            $workout = unserialize($currUser["UF_WORKOUT"]);
            if (empty($workout["usage"])){
                $api = new Api(array(
                    "action" => "contact",
                    "params" => $arParam
                ));

                throw new Exception("К сожалению, вам недоступна пробная тренировка. <br>Мы свяжемся с Вами для уточнения дополнительной информации.", 20);
            }
        }

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
            "action"=>"sms",
            "ajax"=>"checkSmsTrial",
        ];
    }

    public function checkSmsTrialAction($sms_code_field){
        $sms_code_field=preg_replace('![^0-9]+!', '', $sms_code_field);

        if (strlen($sms_code_field) != 5) {
            throw new Exception('Формат значения кода не верный', 10);
        }

        $FORM_FIELDS=$this->GetFormFields(true);
        $this->GetClient();


        if (empty($FORM_FIELDS) || !$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 1);
        }

        $this->GetElement();
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 1);
        }

        if (!empty($this->arResult["ELEMENT"]["PROPERTIES"]["HAS_LEADER"]["VALUE"])) {
            $LEADER_ID = Context::getCurrent()->getRequest()->getPost("leader_id");
            if (empty($LEADER_ID)) {
                throw new Exception('Выберите тренера', 7);
            }

            $this->arResult["LEADERS"] = [
                "NAME" => "leader_id",
                "REQUIRED" => true,
            ];
            $LEADERS_ID = $this->arResult["ELEMENT"]["PROPERTIES"]["LEADERS_ID"]["VALUE"];
            if ($LEADERS_ID && !in_array($LEADER_ID, $LEADERS_ID)) {
                throw new Exception('Тренер не может быть выбран', 1);
            }

            $dbRes = CIBlockElement::GetByID($LEADER_ID);
            if (!$rsLeader = $dbRes->GetNextElement()) {
                throw new Exception("Не удалось выбрать тренера", 1);
            }
            $leader=$rsLeader->GetProperties();
        }


        $arParam= [
            'type'=>$this->arParams["FORM_TYPE"],

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

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"],

            "code"=>$sms_code_field,
            "event"=>"registration",
            "leaderId"=>!empty($leader)?$leader["CODE_1C"]["VALUE"]:null
        ];

        $api = new Api(array(
            "action" => "request2_new",
            "params" => $arParam
        ));

        $response=$api->result();

        if(empty($response["success"]) ) {
            switch ($response['data']['http_code']) {
                case 200:
                    throw new Exception('Не верный код из СМС', 2);
                case 400:
                    throw new Exception('Не удалось подтвердить код. Попробуйте еще раз', 1);
                default:
                    throw new Exception('Непредвиденная ошибка');
            }
        }

        global $USER;
        $currUser=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
        if ($arUser=$currUser->Fetch()){
            $USER_ID=$arUser["ID"];
        }
        else{
            //Заранее добавялем пользователя с имеющимися полями и авторизовываем его
            $user=new CUser;
            $user1Carr=$response['data']['result']['result'];

            function generateRandomString($length = 10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }

            $passwd=generateRandomString();
            $arFields=array(
                'UF_IS_CORRECT'=>false,
                'NAME'=>$user1Carr['name'],
                'LAST_NAME'=>$user1Carr['surname'],
                'EMAIL'=>$user1Carr['email'],
                'LOGIN'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'ACTIVE'=>'Y',
                "GROUP_ID"=>array(Utils::GetUGroupIDBySID('CLIENTS')),
                'UF_1CID'=>$user1Carr['id1c'],
                'PERSONAL_BIRTHDAY'=>$user1Carr['birthday'],
                'PERSONAL_PHONE'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'PERSONAL_GENDER'=>$user1Carr['gender'],
                "PASSWORD"=>$passwd,
                "CONFIRM_PASSWORD"=>$passwd,
                "UF_ADDRESS"=>$user1Carr['address'],
            );
            if (empty($user1Carr['imageurl'])) {
                $settings = Utils::getInfo();
                $imgPath = $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE'];
            }
            else{
                $imgPath = $user1Carr['imageurl'];
            }
            $arImage=CFile::MakeFileArray($imgPath);
            $arImage["MODULE_ID"] = "main";
            $arFields['PERSONAL_PHOTO']=$arImage;
            $ID = $user->Add($arFields);
            if (intval($ID) > 0){
                $USER_ID=$ID;
            }
            else{
                throw new Exception($user->LAST_ERROR, 17);
            }
        }

//        PersonalUtils::UpdatePersonalInfoFrom1C($USER_ID);
        PersonalUtils::get_lk_info($USER_ID);
        $USER->Authorize($USER_ID);
        return ["action"=>"href", 'href'=>'/personal/?v=2&pds=workout&club='.$this->arResult["CLUB_NUMBER"]];

//        if (!empty($userArr["UF_USAGETW"])){
//
//        }
//        elseif (!empty($userArr["UF_TRIALWORKOUT"])){
//            $USER->Authorize($USER_ID);
//            return ["action"=>"href", 'href'=>'/personal/?SECTION='.Utils::GetIBlockSectionIDBySID('trialworkout_zapis').'&CLUB='.$this->arResult["CLUB_NUMBER"]];
//        }
//        else{
//            throw new Exception("К сожалению, вам недоступна пробная тренировка. <br>Мы свяжемся с Вами для уточнения дополнительной информации.", 20);
//        }
    }


}