<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;

use \Bitrix\Main\Loader;

class FormAbonimentComponent extends CBitrixComponent{

    function onPrepareComponentParams($arParams){
        if(!$arParams["WEB_FORM_ID"]){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
        return $arParams;
    }

    private function getFields(){
        $status = CForm::GetDataByID($this->arParams["WEB_FORM_ID"], $this->arResult["arForm"], $this->arResult["arQuestions"], $this->arResult["arAnswers"], $this->arResult["arDropDown"], $this->arResult["arMultiSelect"]);
        if($status){
            if($this->arResult["arAnswers"]["club"]){
                $clubField = $this->request["form_text_5"] ? $this->request["form_text_5"] : $this->request["form_text_15"];

                if(!empty($this->request["club"])){
                    $club = $this->request["club"];
                }elseif(!empty($_SESSION['CLUB_NUMBER'])){
                    $club = $_SESSION['CLUB_NUMBER'];
                }else{
                    $club = $clubField;
                }
                
                if(empty($club)){
                    $club = '08'; // по умолчанию клуб Одинцово
                }
                $this->arResult["arAnswers"]["club"][0]['ITEMS'] = Utils::getClubsForm($club);
            }
            $this->arResult["WEB_FORM_ID"] = $this->arResult["arForm"]["VARNAME"];

        }
    }

    private function checkStep() {
        $step = $this->request->get('step');
		$this->arParams["PREV_STEP"] = $step;
		
        if (empty($step)) {
            return 1;
        }

        foreach($this->request as $key => $value){
            if(strpos($key, "form_") !== false){
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;
            }
            if(strpos($key, "promo") !== false){
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;   
            }
        }

        // Обработка формы без SMS-подтверждения номера
        if ($this->arParams["SKIP_CHECKS"] == "Y") {
            $arParam = $this->getFormatFields();
            $arParam['type'] = 6;
            
            $api = new Api(array(
                "action" => "web_site_contact",
                "params" => $arParam
            ));
            
            if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                CFormResult::SetEvent($RESULT_ID);
                CFormResult::Mail($RESULT_ID);
            }
            return 3;
        }

        if ($step == 1 && $this->request->get('ajax_send')) {
            $this->arResult["ERROR"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
            if (empty($this->arResult["ERROR"])) {
                return 2;
            } else {
                return 1;
            }
        }

        if ($step == 2 && $this->request->get('ajax_send')) {
            if($this->arResult["RESPONSE"]["data"]["result"]["errorCode"] === 0){
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
                }
    
                return 3;
            }else{
                $this->arResult["ERROR"] = "Не правильно введен код";
                return 2;
            }
        }

        if ($step == 3 && $this->request->get('ajax_send')) {
            $this->resetForm();
            return 1;
        }

        return 1;
    }

    private function sendSms($phone = null) {
        if($phone == null){
            $phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
            $phone = $this->request->get($phoneName);
        }
        
        $this->arResult["SMS_PHONE"] = $phone;
        $phone = preg_replace('![^0-9]+!', '', $phone);
        $api = new Api(array(
            "action" => "request_sendcode",
            "params" => array(
                "phone" => $phone,
            )
        ));

        $result = $api->result();
        if(!$result["success"]) {
            $this->arResult["ERROR"] = $result['data']['result']['userMessage'];
        }
    }

    private function checkCoupon($coupon) {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
                
        $api = new Api(array(
            "action" => "coupon",
            "params" => $coupon
        ));
        
        $result = $api->result();
        echo json_encode($result['data']['result']);   
        die;
    }

    private function resetForm() {
        $arParam = array();
        foreach ($this->arResult["arAnswers"] as $name => $answer) {
            $_REQUEST["form_" . $answer['0']["FIELD_TYPE"] . "_" . $answer['0']["ID"]] = "";
        }
    }
	
    private function getFormatFields() {
        $arParam = array();
        foreach ($this->arResult["arAnswers"] as $name => $answer) {
            $fieldId = $this->request["form_" . $answer['0']["FIELD_TYPE"] . "_" . $answer['0']["ID"]];
            if ($fieldId) {
                if ($name == "phone") {
                    $fieldId = preg_replace('![^0-9]+!', '', $fieldId);
                }
                $arParam[$name] = $fieldId;
            }
        }
        $arParam['promo'] = $this->request["promo"];
        $arParam['client_id'] = $this->request->getCookieRaw('_ga');
        return $arParam;
    }

    private function getElement() {
        if($this->request['abonement_code']){
            $code = $this->request['abonement_code'];
        }else{
            $code = $this->request["ELEMENT_CODE"];
        }

        $arFilter = array("IBLOCK_CODE" => "subscription", "CODE" => $code);
		if( empty($code) ) {
			unset($arFilter["CODE"]);
			$arFilter["ACTIVE"] = "Y";
		}
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array());

        if ($res = $dbElements->GetNextElement()) {
            $this->arResult["ELEMENT"] = $res->GetFields();
            $this->arResult["ELEMENT"]["PROPERTIES"] = $res->GetProperties();
            //$this->arResult["ELEMENT"]["PROPERTIES"] = $res->GetProperties();  
            /*Выбираем клуб с минимальной ценой абонемента*/     

            $min_price = $this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"][0]["PRICE"];
            $list = $this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"][0]["LIST"];
            foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $key => $value) {
                if ($value["NUMBER"] != 2) {
                    if ($value["PRICE"] < $min_price) {
                        $min_price = $value["PRICE"];
                        $list = $value["LIST"];
                    }
                }
            }
            /*!*/

            $this->getPrice($list);
            $this->getService($list);
        }
    }

    private function getService($list) {
        $clubField = $this->request["form_text_5"] ? $this->request["form_text_5"] : $this->request["form_text_15"];

        if(!empty($this->request["club"])){
            $club = $this->request["club"];
        }elseif(!empty($_SESSION['CLUB_NUMBER'])){
            $club = $_SESSION['CLUB_NUMBER'];
        }else{
            $club = $clubField;
        }

        if (!$club && $list){ 
            $selectedClub = Utils::getClubById($list);
        }else{
            $selectedClub = Utils::getClub($club);    
        }

        foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["VALUE"] as $service) {
            if ($service["LIST"] == $selectedClub["ID"]) {
                $this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"][] = $service["PRICE"];
            }
        }
    }

    private function getPrice($list) {
        $props = array();
        $arFilter = array("IBLOCK_CODE" => "price_sign");
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));

        while ($res = $dbElements->fetch()) {
            $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
        }
        $clubField = $this->request["form_text_5"] ? $this->request["form_text_5"] : $this->request["form_text_15"];
        
        if(!empty($this->request["club"])){
            $club = $this->request["club"];
        }elseif(!empty($_SESSION['CLUB_NUMBER'])){
            $club = $_SESSION['CLUB_NUMBER'];
        }else{
            $club = $clubField;
        }

        // по умолчанию клуб Одинково
        if(empty($club)){
            $clubField = '08';
            $club = '08';
        }

        if (!$club && $list){ 
           $selectedClub = Utils::getClubById($list);
        }else{
            $selectedClub = Utils::getClub($club);    
        }

        foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $key => $arPrice) {
            if ($arPrice["LIST"] == $selectedClub["ID"]) {
                $this->arResult["ELEMENT"]["PRICES"][] = $arPrice;
                if($this->arResult["ELEMENT"]['ID'] == ABONEMENTS_GOD_FITNESA_ID) {
                    break;
                }        
            }
        }

        foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"] as $key => $arPrice) {
            if ($arPrice["LIST"] == $selectedClub["ID"]) {
                $this->arResult["ELEMENT"]["BASE_PRICE"] = $arPrice;
                break;
            }
        }

        $priceSign = array();
        foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"] as $arItem) {
            if ($arItem["LIST"] == $selectedClub["ID"]) {
                $priceSign[] = $arItem;
            }
        }

        

        foreach ($this->arResult["ELEMENT"]["PRICES"] as $key => $arPrice) {
            if ($arPrice["PRICE"] != $this->arResult["ELEMENT"]["BASE_PRICE"]["PRICE"] && $arPrice["NUMBER"] == $this->arResult["ELEMENT"]["BASE_PRICE"]["NUMBER"]) {

                $this->arResult["ELEMENT"]["SALE"] = $arPrice["PRICE"];
                
                if ($this->request["form_hidden_10"] && !$this->request["no_save_price"]) {
                    $this->arResult["ELEMENT"]["SALE"] = $this->request["form_hidden_10"];
                }

                if ($this->request["form_hidden_21"] && !$this->request["no_save_price"]) {
                    $this->arResult["ELEMENT"]["SALE"] = $this->request["form_hidden_21"];
                }
                
                /**
                 * цена за второй месяц после применения промокода
                 */

                if ($this->request["two_month"] && !$this->request["no_save_price"]) {
                    $this->arResult["ELEMENT"]["SALE_TWO_MONTH"] = $this->request["two_month"];
                }

                $this->arResult["ELEMENT"]["PRICES"][$key]["PRICE"] = $this->arResult["ELEMENT"]["BASE_PRICE"]["PRICE"];
            }

            
            if ($priceSign) {
                $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
            } else {
                $sign = false;
            }
            
            if ($sign !== false) {
                $this->arResult["ELEMENT"]["PRICES"][$key]["SIGN"] = $priceSign[$sign]["PRICE"];
            }

            if ($props[$arPrice["NUMBER"]] && $sign === false) {
                $this->arResult["ELEMENT"]["PRICES"][$key]["SIGN"] = $props[$arPrice["NUMBER"]];
            }
        }

        array_multisort(array_column($this->arResult["ELEMENT"]["PRICES"], "NUMBER"), SORT_ASC, $this->arResult["ELEMENT"]["PRICES"]);
    }

    private function checkSms($num){
        global $APPLICATION;

        $arParam = $this->getFormatFields();
        
        if ($this->request["num"]) {
            $arParam["code"] = $this->request["num"];
        }

        if($this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]){
            $arParam["additional"] = $this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"];
        }
        
        if($this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]){
            $arParam["subscriptionId"] = $this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"];
        }
        
        if ($this->request["form_hidden_10"] == 0) {
        // if ($this->request["form_hidden_10"] == 0 || $this->request["form_hidden_21"] == 0) {
            $arParam["type"] = 1;
            if ($this->arResult["ELEMENT"]["CODE"] == "probnaya-trenirovka" || $this->arResult["ELEMENT"]["ID"] == "226") {
                $arParam["type"] = 3;
            }
            
            $api = new Api(array(
                "action" => "request2",
                "params" => $arParam
            ));
        } else {
            $api = new Api(array(
                "action" => "request",
                "params" => $arParam
            ));
        }
        
        $result = $api->result();
        
        
        $this->arResult["RESPONSE"] = $result;
    }

    function executeComponent(){
        Loader::IncludeModule("form");
        Loader::IncludeModule("iblock");

        
        $this->getFields();
        $this->getElement();

        
        if($this->arResult["ELEMENT"]["ACTIVE"] == 'N'){
            \Bitrix\Iblock\Component\Tools::process404(
                '', 
                true,
                true, 
                true, 
                false 
            );
        }
		
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arResult["ELEMENT"]["IBLOCK_ID"], $this->arResult["ELEMENT"]["ID"]);
        $seoValues = $ipropValues->getValues();
        if ($seoValues['ELEMENT_META_TITLE']) {
            $this->arResult['SEO']['ELEMENT_META_TITLE'] = $seoValues['ELEMENT_META_TITLE'];
        } else {
			$this->arResult['SEO']['ELEMENT_META_TITLE'] = strip_tags($this->arResult["ELEMENT"]["~NAME"]);
		}
        if ($seoValues['ELEMENT_META_DESCRIPTION']) {
            $this->arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
        }
        if ($seoValues['SECTION_META_DESCRIPTION']) {
            $this->arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
        }
        if ($seoValues['SECTION_META_KEYWORDS']) {
            $this->arResult['SEO']['ELEMENT_META_KEYWORDS'] = $seoValues['ELEMENT_META_KEYWORDS'];
        }
		
        if($this->request->get("mode")){
            switch($this->request->get("mode")){
                case "try_sms":
                    if($this->request->get("phone")){
                        $this->sendSms($this->request->get("phone"));
                    }
                    break;
                case "coupon":
                    if($this->request->get("coupon")){
                        $this->checkCoupon($this->request->get("coupon"));
                    }
                    break;
                case "check_sms":
                    if ($this->request->get("num")){
                        $this->checkSms($this->request->get("num"));
                    }
                    break;
            }
        }
        
        switch ($this->checkStep()) {
            case 2:
                // if (empty($this->arResult["ERROR"])) {
                    $this->sendSms();
                // }
                $this->includeComponentTemplate('step-2');
                break;
            case 3:
                $this->includeComponentTemplate('step-3');
                break;
            default:
                $this->includeComponentTemplate();
                break;
        }
    }
}
?>