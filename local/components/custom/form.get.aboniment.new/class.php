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

    private function componentParams(){
        if(empty($arParams["WEB_FORM_ID"])){
            $this->arResult["WEB_FORM_ID"] =Context::getCurrent()->getRequest()->getPost('WEB_FORM_ID');
        }
        $this->arResult['ABONEMENT_IBLOCK_ID']=Utils::GetIBlockIDBySID('subscription');
        $this->arResult['CLUBS_IBLOCK_ID']=Utils::GetIBlockIDBySID('clubs');
        $this->arResult['COMPONENT_NAME']=$this->GetName();
    }
    function onPrepareComponentParams($arParams){
        if( empty($arParams["WEB_FORM_ID"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        if( !empty($arParams["SELECTED_LEADER_ID"]) && $arParams["SELECTED_LEADER_ID"]=="-" ){
            unset($arParams["SELECTED_LEADER_ID"]);
	    }
        return $arParams;
    }

    public function ConfigureActions(){
        return [
            'getClub' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'setPromocode' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'checkCode'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getAbonement'=>[
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
            'sendSMS'=>[
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
            'checkCodeTrial'=>[
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



    function executeComponent() {
        if (!empty($this->arResult["ERROR"])){
            echo $this->arResult["ERROR"];
            return;
        }

        unset($_SESSION['BONUS_LIMIT']);
        unset($_SESSION['promocode']);
        unset($_SESSION['CURRENT_PRICE']);

        $this->componentParams();

        $this->arResult["WEB_FORM_ID"]=Utils::GetFormIDBySID($this->arParams['WEB_FORM_ID']);
        $this->arResult['FORM_TYPE']=$this->arParams['FORM_TYPE'];
        $this->arResult["ELEMENT_CODE"]=$this->arParams['ELEMENT_CODE'];

        if (!$this->GetElement()){
            $this->set404();
        }
        $this->GetSeo();
        $this->arResult['ACTION']=$this->arParams['FORM_ACTION'];

        if (!empty($this->arParams['CLUB_ID'])){
            if ($this->CheckClub()){
                $this->GetClub();
            }
            else{
                $this->set404();
            }
        }

        $this->arResult['FORM_FIELDS']=$this->GetFormFields();
        $this->arResult["OFERTA_TEXT"] = "";
        $siteSettings = Utils::getInfo();
        if(!empty($siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'])) {
            $this->arResult["OFERTA_TEXT"] = $siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'];
        }

        $this->IncludeComponentTemplate();
    }

    private function GetElement(){
        $elArray = [];
        $clubRes = CIBlockElement::GetList([], ["IBLOCK_ID" => $this->arResult['ABONEMENT_IBLOCK_ID'], "CODE" => $this->arResult["ELEMENT_CODE"], "ACTIVE" => "Y"], false);
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
                if ($price['NUMBER']==$NUMBER){
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
            $error = CForm::Check($this->arResult['WEB_FORM_ID'], Context::getCurrent()->getRequest()->toArray());
            if (strlen($error)>0){
                return false;
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
                }
                elseif ($key=='leaders' && isset($this->arParams['SELECTED_LEADER_ID'])){
					$FORM_FIELDS['FIELDS'][$key]['VALUE_STRING'] = '';
					$FORM_FIELDS['FIELDS'][$key]['VALUE']=$this->arParams['SELECTED_LEADER_ID'];
					
                    $FORM_FIELDS['FIELDS'][$key]['TYPE']='SELECT';
                    $FORM_FIELDS['FIELDS'][$key]['ITEMS']=[];
                    $arFilter = array(
                        'IBLOCK_ID' => Utils::GetIBlockIDBySID('leaders'),
                        'ACTIVE' => 'Y'
                    );
                    $dbElements = CIBlockElement::GetList(array('SORT' => 'ASC'), $arFilter, false, false, array("ID", "CODE", "NAME"));
                    while ($arFields = $dbElements->fetch()) {
						if($FORM_FIELDS['FIELDS'][$key]['VALUE']==$arFields['ID']) $FORM_FIELDS['FIELDS'][$key]['VALUE_STRING']=$arFields['NAME'];
						$FORM_FIELDS['FIELDS'][$key]['ITEMS'][] = ['VALUE' => $arFields['ID'], 'NAME' => $arFields['NAME'], 'SELECTED' => ($FORM_FIELDS['FIELDS'][$key]['VALUE']==$arFields['ID']) ? true:false];
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
//                    $valbuff=substr(preg_replace('![^0-9]+!', '', Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME'])), 1);

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
                elseif ($key=='leaders' && isset($this->arParams['SELECTED_LEADER_ID'])){
					$FORM_FIELDS['FIELDS'][$key]['VALUE_STRING'] = '';
                    $FORM_FIELDS['FIELDS'][$key]['VALUE']=intval(Context::getCurrent()->getRequest()->getPost($FORM_FIELDS['FIELDS'][$key]['NAME']));
                    
                    if( !empty($FORM_FIELDS['FIELDS'][$key]['VALUE']) ){
                        $arFilter = array(
                            'IBLOCK_ID' => Utils::GetIBlockIDBySID('leaders'),
                            'ACTIVE' => 'Y',
						    'ID' => $FORM_FIELDS['FIELDS'][$key]['VALUE']
                        );
                        $dbElements = CIBlockElement::GetList(array('SORT' => 'ASC'), $arFilter, false, false, array("ID", "CODE", "NAME"));
                        if ($arFields = $dbElements->fetch()) {
						    $FORM_FIELDS['FIELDS'][$key]['VALUE_STRING']=$arFields['NAME'];
                        }
                    }
                    else{
                        throw new Exception('Выберите тренера', 7);
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

    private function GetSeo(){
        /* Получаем значения SEO */
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arResult['ABONEMENT_IBLOCK_ID'], $this->arResult["ELEMENT"]["ID"]);
        $seoValues = $ipropValues->getValues();
        if ($seoValues['ELEMENT_META_TITLE']) {
            $this->arResult['SEO']['ELEMENT_META_TITLE'] = $seoValues['ELEMENT_META_TITLE'];
        } else {
            $this->arResult['SEO']['ELEMENT_META_TITLE'] = strip_tags($this->arResult["ELEMENT"]["~NAME"]).' - '.'Абонементы сети фитнес-залов Spirit.Fitness';

        }
        if ($seoValues['ELEMENT_META_DESCRIPTION']) {
            $this->arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
        }
        else{
            $this->arResult['SEO']['ELEMENT_META_DESCRIPTION']=$this->arResult["ELEMENT"]["~NAME"].'. 💸 Удобная ежемесячная оплата 💥 Полный безлимит по времени и услугам 💯';

        }
        if ($seoValues['SECTION_META_KEYWORDS']) {
            $this->arResult['SEO']['ELEMENT_META_KEYWORDS'] = $seoValues['ELEMENT_META_KEYWORDS'];
        }
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
                throw new Exception($responce["data"]["result"]["userMessage"], 7);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
            }
        }
    }

    private function checkCode($phone, $code){
        $api=new Api([
            'action'=>'ordercodecheck',
            'params'=>[
                'phone'=>$phone,
                'code'=>$code
            ]
        ]);

        $response=$api->result();
        if ($response['success'] == true) {
            return true;
        } else {
            switch ($response['data']['http_code']) {
                case 200:
                    throw new Exception('Не верный код из СМС', 6);
                case 400:
                    throw new Exception('Не удалось подтвердить код. Попробуйте еще раз', 7);
                default:
                    throw new Exception('Непредвиденная ошибка');
            }
        }

    }


    //AJAX ACTIONS
    public function getClubAction(){
        if (empty(Context::getCurrent()->getRequest()->getPost('CLUB_ID'))){
            return ['result'=>false];
        }
        $this->componentParams();
        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $this->arParams['CLUB_ID']=Context::getCurrent()->getRequest()->getPost('CLUB_ID');

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
            throw new Exception('Ошибка::Клуб не может быть выбран');
        }
    }

    public function setPromocodeAction(){
        $this->componentParams();
        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $this->GetElement();
        if (empty($this->arResult["ELEMENT"])){
            throw new Exception('Не выбран абонемент', 7);
        }

        $FORM_FIELDS=$this->GetFormFields(true, false);

        if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
            throw new Exception('Выберите клуб', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];
        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        if (empty($FORM_FIELDS['FIELDS']['promocode']['VALUE'])){
            throw new Exception('Промокод не введен', 7);
        }

        if (empty($FORM_FIELDS['FIELDS']['phone']['VALUE'])){
            throw new Exception('Введите номер телефона', 7);
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
                throw new Exception("Купон не применен", 7);
            }
        }
        else{
            $_SESSION["promocode"] = $FORM_FIELDS['FIELDS']['promocode']['VALUE'];
            $prices=$responce["data"]["result"]["result"]['object']['prices'];
            foreach($prices as $item){
                $result["DISCOUNTS"][]=$item;
                if (!empty($item['baseprice'])){
                    $result["BASEPRICE"]=$item['baseprice'];
                }
                if ($item['number']==1){
                    $result['CURRENT_PRICE']=$item['price'];
                }
            }
            if(!empty($responce["data"]["result"]["result"]['object']["free"]) ) {
                $result["MESSAGE"] = "Бесплатный абонемент. Для верификации, мы спишем с карты и вернем небольшую сумму, чтобы убедиться, что Вы человек, а не робот.";
            } else {
                $result["MESSAGE"] = "Ваш промокод применен";
            }

        }
        if( empty($_SESSION["promocode"]) ) {
            throw new Exception("Введен неверный промокод", 7);
        }
        $_SESSION['CURRENT_PRICE']=$result['CURRENT_PRICE'];
        return $result;
    }

    public function checkCodeAction(){
        $this->componentParams();
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');

        $FORM_FIELDS=$this->GetFormFields(true);
        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }

        $code=Context::getCurrent()->getRequest()->getPost('sms-code');
        $code = preg_replace('![^0-9]+!', '', $code);
        if (strlen($code) != 5) {
            throw new Exception('Формат значения кода не верный', 10);
        }

        if ($this->checkCode($FORM_FIELDS['FIELDS']['phone']['VALUE'], $code)){
            return $this->getOrderAction();
        }
    }

    public function getAbonementAction(){
//        return Context::getCurrent()->getRequest()->toArray();
        $this->componentParams();
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (empty($FORM_FIELDS['FIELDS']['legalinfo']['VALUE'])){
            throw new Exception('Необходимо ознакомиться с условиями Оферты', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];


        $this->GetElement();

        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        $promocode=!empty($_SESSION['promocode'])?$_SESSION['promocode']:null;

        $arParams=[
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
            'promocode'=>$promocode,

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]
        ];
        
        if(!empty($FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'])) $arParams['leader']=$FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'];
        
//        return $arParams;

        $api=new Api([
            'action'=>'orderreg',
            'params'=>$arParams
        ]);

        $responce=$api->result();
//        file_put_contents($_SERVER["DOCUMENT_ROOT"].'/logs/test.txt', print_r($responce, true), FILE_APPEND);

        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
            }
        }

        global $USER;
        if ($responce['data']['result']['result']['action']=='code'){
            if ($USER->IsAuthorized() && $USER->GetLogin()==$FORM_FIELDS['FIELDS']['phone']['VALUE']){
//                return [
//                    'next-action'=>'getOrder',
//                    'promocode'=>!empty($promocode),
//                    'btn'=>'Купить'
//                ];
                return $this->getOrderAction();
            }
            else{
                if ($this->sendSMS($FORM_FIELDS['FIELDS']['phone']['VALUE'])===true){
                    return [
                        'user-action'=>'code',
                        'next-action'=>'checkCode',
                        'promocode'=>!empty($promocode),
//                        'code'=>$_SESSION['code'],
                        'btn'=>'Подтвердить',
                        'step'=>2
                    ];
                }
            }
        }
        elseif ($responce['data']['result']['result']['action']=='authorization'){
            if (!$USER->IsAuthorized()){
                $user_action='authorization';
                $logout=false;
                $next_action='sendSMS';
            }
            elseif($USER->GetLogin()!=$FORM_FIELDS['FIELDS']['phone']['VALUE']){
                $user_action='authorization';
                $logout=true;
                $next_action='sendSMS';
            }
            elseif($USER->GetLogin()==$FORM_FIELDS['FIELDS']['phone']['VALUE']){
                $user_action='bonuses';
                $logout=false;
                $bonusessum=$responce['data']['result']['result']['bonusessum'];
                $next_action='getOrder';

                $_SESSION['BONUS_LIMIT']=(int)$bonusessum;
            }

            return [
                'user-action'=>$user_action,
                'next-action'=>$next_action,
                'promocode'=>!empty($promocode),
                'logout'=>$logout,
                'club'=>$this->arResult['CLUB_NUMBER'],
                'phone'=>Utils::phone_format($FORM_FIELDS['FIELDS']['phone']['VALUE']),
                'bonusessum'=>$bonusessum,
                'btn'=>'Продолжить',
                'step'=>2
            ];
//            if (!$USER->IsAuthorized() || $USER->GetLogin()!=$FORM_FIELDS['FIELDS']['phone']['VALUE']){
//                return ['user-action'=>'authorization', 'next-action'=>'auth', 'promocode'=>!empty($promocode)];
//            }
//            elseif ($USER->IsAuthorized() && $USER->GetLogin()!=$FORM_FIELDS['FIELDS']['phone']['VALUE']) {
//                return ['user-action'=>'authorization', 'next-action'=>'auth', 'promocode'=>!empty($promocode), 'logout'=>true];
//            }
        }
//        }
//        elseif($STEP==2){
//            $params=[
//                'phone'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
//                'code'=>Context::getCurrent()->getRequest()->getPost('sms-code')
//            ];
//
//            $api=new Api([
//                'action'=>'ordercodecheck',
//                'params'=>$params
//            ]);
//
//            return $api->result();
//        }



    }

    public function getOrderAction(){
        $this->componentParams();
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }

        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];


        $this->GetElement();

        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        $promocode=!empty($_SESSION['promocode'])?$_SESSION['promocode']:null;

        $arParams=[
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
            'promocode'=>$promocode,

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"],

            'action'=>'cloudpayments',
            'price'=>(int)($_SESSION['CURRENT_PRICE']),
        ];
        
        if(!empty($FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'])) $arParams['leader']=$FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'];
        
        $bonuses=(int)Context::getCurrent()->getRequest()->getPost('bonuses');
        if (!empty($bonuses)){
            $arParams['bonusessum']=$bonuses;
            if ($_SESSION['BONUS_LIMIT']<$bonuses){
                throw new Exception('Превышен лимит бонусов');
            }
        }
//        $logFile = "/logs/test.txt";
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] .$logFile, json_encode($arParams, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);

//        return $arParams;

        $api=new Api([
            'action'=>'ordercreate',
            'params'=>$arParams
        ]);

        $responce=$api->result();
//        $logFile = "/logs/test.txt";
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] .$logFile, "/ordercreate\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] .$logFile, json_encode($arParams, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] .$logFile, json_encode($responce, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);


        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"]);
            } else {
                throw new Exception("Непредвиденная ошибка");
            }
        }

        ob_start();

        $this->IncludeComponenttemplate('done');
        $content=ob_get_clean();


        $btn='<a href="'.$responce['data']['result']['result']['formUrl'].'" target="_blank" class="subscription__total-btn subscription__total-btn--pay btn btn--white get-abonement-pay">'.
                'Получить счет'.
                '</a>';

        $CURRENT_PRICE=$_SESSION['CURRENT_PRICE'];
        if (!empty($bonuses)){
            $CURRENT_PRICE-=$bonuses;
        }

        global $USER;
        if ($USER->IsAuthorized()){
            try{
                PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
            }
            catch (Exception $err){

            }
        }

        return [
            'elements'=>[
                '.subscription__main'=>$content,
                '.get-abonement-agree.subscription__total-btn'=>$btn
            ],
            'url'=>$responce['data']['result']['result']['formUrl'],
            'CURRENT_PRICE'=>$CURRENT_PRICE,
            'step'=>2
        ];
    }

    public function sendSMSAction(){
        $this->componentParams();
        $FORM_FIELDS=$this->GetFormFields(true);
        $promocode=!empty($_SESSION['promocode'])?$_SESSION['promocode']:null;
        if ($this->sendSMS($FORM_FIELDS['FIELDS']['phone']['VALUE'])===true){
            return [
                'user-action'=>'code',
                'next-action'=>'checkCode',
                'promocode'=>!empty($promocode),
//                'code'=>$_SESSION['code'],
                'btn'=>'Подтвердить'
            ];
        }
    }

    public function getTrialAction(){
        $this->componentParams();
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];


        $this->GetElement();

        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        global $USER;
        if ($USER->IsAuthorized() && $USER->GetLogin()==$FORM_FIELDS['FIELDS']['phone']['VALUE']){
            $currUser=PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
            if (!empty($currUser["UF_USAGETW"])){
                return ['href'=>'/personal/?SECTION='.Utils::GetIBlockSectionIDBySID('trialworkout_zapis').'&CLUB='.$this->arResult["CLUB_NUMBER"]];
            }
            elseif (!empty($currUser["UF_TRIALWORKOUT"])){
                return ['href'=>'/personal/?SECTION='.Utils::GetIBlockSectionIDBySID('trialworkout_zapis').'&CLUB='.$this->arResult["CLUB_NUMBER"]];
            }
            else{
                throw new Exception("К сожалению, вам недоступна пробная тренировка. <br>Мы свяжемся с Вами для уточнения дополнительной информации.", 20);
            }
        }

        $currUser=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);

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

            'subscriptionId'=>$this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]
        ];
        
        if(!empty($FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'])) $arParam['leader']=$FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'];
        

        if ($arUser=$currUser->Fetch()){
            $userArr=PersonalUtils::UpdatePersonalInfoFrom1C($arUser["ID"]);
            if (empty($userArr["UF_USAGETW"]) && empty($userArr["UF_TRIALWORKOUT"])){
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
            'next-action'=>'checkCodeTrial',
            'btn'=>'Подтвердить',
            'step'=>2,
//            'response'=>$responce
        ];
    }

    public function checkCodeTrialAction(){
        $code=Context::getCurrent()->getRequest()->getPost('sms-code');
        $code = preg_replace('![^0-9]+!', '', $code);
        if (strlen($code) != 5) {
            throw new Exception('Формат значения кода не верный', 10);
        }

        $this->componentParams();
        $this->GetClient();

        $this->arResult["ELEMENT_CODE"]=Context::getCurrent()->getRequest()->getPost('SUB_CODE');
        $FORM_TYPE=Context::getCurrent()->getRequest()->getPost('FORM_TYPE');

        $FORM_FIELDS=$this->GetFormFields(true);

        if (empty($FORM_FIELDS)){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception('Незаполнены обязательные поля', 7);
        }
        $this->arParams['CLUB_ID']=$FORM_FIELDS['FIELDS']['club']['VALUE'];


        $this->GetElement();

        if (!$this->CheckClub() || !$this->GetClubNumber()){
            throw new Exception('Клуб не может быть выбран', 7);
        }

        $arParam= [
            'type'=>(int)$FORM_TYPE,

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

            "code"=>$code,
            "event"=>"registration"
        ];
		
        if(!empty($FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'])) $arParam['leader']=$FORM_FIELDS['FIELDS']['leaders']['VALUE_STRING'];
        
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
                throw new Exception($responce["data"]["result"]["userMessage"], 7);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
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
            $user1Carr=$responce['data']['result']['result'];

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

        $userArr=PersonalUtils::UpdatePersonalInfoFrom1C($USER_ID);

        if (!empty($userArr["UF_USAGETW"])){
            $USER->Authorize($USER_ID);
            return ['href'=>'/personal/?SECTION='.Utils::GetIBlockSectionIDBySID('trialworkout_zapis').'&CLUB='.$this->arResult["CLUB_NUMBER"]];
        }
        elseif (!empty($userArr["UF_TRIALWORKOUT"])){
            $USER->Authorize($USER_ID);
            return ['href'=>'/personal/?SECTION='.Utils::GetIBlockSectionIDBySID('trialworkout_zapis').'&CLUB='.$this->arResult["CLUB_NUMBER"]];
        }
        else{
            throw new Exception("К сожалению, вам недоступна пробная тренировка. <br>Мы свяжемся с Вами для уточнения дополнительной информации.", 20);
        }

//        ob_start();
//        $this->IncludeComponentTemplate('trial-done');
//        $content=ob_get_clean();
//
//
//        return [
//            'elements'=>[
//                '.subscription__main'=>$content,
//                '.get-abonement-agree.subscription__total-btn'=>null,
//                '.subscription__code-new'=>null
//            ],
//            'step'=>3
//        ];
    }

}