<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;

use \Bitrix\Main\Loader;

class FormAbonimentComponent extends CBitrixComponent{

    function onPrepareComponentParams($arParams){
        if( empty($arParams["WEB_FORM_ID"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма";
        }
		if( empty($arParams["ABONEMENT_IBLOCK_ID"]) ){
            $this->arResult["ERROR"] = "Не задан ID инфоблока абонементов";
        }
		if( empty($arParams["CLUBS_IBLOCK_ID"]) ){
            $this->arResult["ERROR"] = "Не задан ID инфоблока клубов";
        }
        return $arParams;
    }

    /*
    private function checkStep() {
        $step = $this->request->get('step');
		$this->arParams["PREV_STEP"] = $step;
		
        if (empty($step)) {
            return 1;
        }

        foreach($this->request as $key => $value) {
            if(strpos($key, "form_") !== false){
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;
            }
            if(strpos($key, "promo") !== false) {
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
            if( !empty($this->arResult["RESPONSE"]) && empty($this->arResult["RESPONSE"]["data"]["result"]["errorCode"]) ) {
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
                }
                return 3;
            } else if( !empty($this->arResult["RESPONSE"]) && $this->arResult["RESPONSE"]["data"]["result"]["errorCode"] == 6 ) {
		        $this->arResult["ERROR"] = (!empty($this->arResult["ERROR"])) ? $this->arResult["ERROR"] : "Код введен неверно, повторите через 15 мин.";
				return 1;
			} else {
                $this->arResult["ERROR"] = (!empty($this->arResult["ERROR"])) ? $this->arResult["ERROR"] : "Не правильно введен код";
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
            $phone = (!empty($this->request->get($phoneName))) ? $this->request->get($phoneName) : $this->request->get("phone");
        }
		
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

    private function checkSms($num) {
        global $APPLICATION;

        $arParam = $this->getFormatFields();

        if ($this->request["num"]) {
            $arParam["code"] = $this->request["num"];
        }
		
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
        $phone = $this->request->get($phoneName);
        $arParam["phone"] = preg_replace('![^0-9]+!', '', $phone);
		
		if($arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]){
            $arParam["additional"] = $this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"];
        }
        
        if($this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]){
            $arParam["subscriptionId"] = $this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"];
        }
			
		// if ($this->request["form_hidden_10"] == 0 || $this->request["form_hidden_21"] == 0) {
		if ($this->request["form_hidden_10"] == 0) {
			
			$arParam["type"] = 1;
            if ($this->arResult["ELEMENT"]["CODE"] == "probnaya-trenirovka" || $this->arResult["ELEMENT"]["ID"] == "226") {
                $arParam["type"] = 3;
            }
			if( !empty($this->request["form_default_type"]) ) {
				$arParam["type"] = intval($this->request["form_default_type"]);
			}
            
            $api = new Api(array(
                "action" => "request2",
                "params" => $arParam
            ));
        } else {
            
			if( !empty($this->request["form_default_type"]) ) {
				$arParam["type"] = intval($this->request["form_default_type"]);
			}
			
			$api = new Api(array(
                "action" => "request",
               	"params" => $arParam
            ));
        }
		
		$result = $api->result();
		$this->arResult["RESPONSE"] = $result;
		
		$smsResultArray = $api->result();
		
		if( !empty($smsResultArray['data']['result']['errorCode']) ) {
			$this->arResult["ERROR"] = $smsResultArray['data']['result']['userMessage'];
			$this->arResult["RESPONSE"] = false;
		}
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
                $this->arResult["CLUB_ID"] = $club;
            }
            $this->arResult["WEB_FORM_ID"] = $this->arResult["arForm"]["VARNAME"];

        }
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
            //Выбираем клуб с минимальной ценой абонемента   

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

            $this->getPrice($list);
            $this->getService($list);
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
                
                //
                // цена за второй месяц после применения промокода
                //

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
	*/
	
	private function getPrice( int $currentClubId, array $element ) {
		$outArrPrice = [];
		$outArrBasePrice = [];
		$outSale = false;
		$outSaleTwoMonth = false;
		
		$props = [];
        $arFilter = ["IBLOCK_CODE" => "price_sign"];
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));
        while( $res = $dbElements->fetch() ) {
            $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
        }
		
		foreach( $element["PROPERTIES"]["PRICE"]["VALUE"] as $key => $arPrice ) {
            if( $arPrice["LIST"] == $currentClubId ) {
                $outArrPrice[] = $arPrice;
                if( $element["ID"] == ABONEMENTS_GOD_FITNESA_ID) {
                    break;
                }
            }
        }
		
		foreach( $element["PROPERTIES"]["BASE_PRICE"]["VALUE"] as $key => $arPrice ) {
            if ($arPrice["LIST"] == $currentClubId) {
                $outArrBasePrice = $arPrice;
                break;
            }
        }
		
		$priceSign = array();
        foreach( $element["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"] as $arItem ) {
            if ($arItem["LIST"] == $currentClubId) {
                $priceSign[] = $arItem;
            }
        }
		
		foreach( $outArrPrice as $key => $arPrice ) {
            if( !empty($outArrBasePrice) && $arPrice["PRICE"] != $outArrBasePrice["PRICE"] && $arPrice["NUMBER"] == $outArrBasePrice["NUMBER"] ) {
				
                $outSale = $arPrice["PRICE"];
                
				if( isset($_SESSION[$this->arResult["COMPONENT_ID"]][$currentClubId]["SALE"]) ) {
					$outSale = $_SESSION[$this->arResult["COMPONENT_ID"]][$currentClubId]["SALE"];
				}
				if( isset($_SESSION[$this->arResult["COMPONENT_ID"]][$currentClubId]["SALE_TWO_MONTH"]) ) {
					$outSaleTwoMonth = $_SESSION[$this->arResult["COMPONENT_ID"]][$currentClubId]["SALE_TWO_MONTH"];
				}

                $outArrPrice[$key]["PRICE"] = $outArrBasePrice["PRICE"];
            }
			
            if ($priceSign) {
                $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
            } else {
                $sign = false;
            }
            
            if( $sign !== false ) {
                $outArrPrice[$key]["SIGN"] = $priceSign[$sign]["PRICE"];
            }
			
            if( $props[$arPrice["NUMBER"]] && $sign === false ) {
                $outArrPrice[$key]["SIGN"] = $props[$arPrice["NUMBER"]];
            }
        }
		
		array_multisort(array_column($outArrPrice, "NUMBER"), SORT_ASC, $outArrPrice);
		
		return ["PRICE" => $outArrPrice, "BASE_PRICE" => $outArrBasePrice, "SALE" => $outSale, "SALE_TWO_MONTH" => $outSaleTwoMonth];
	}
	
	private function getElement(int $abonementsIBlockId, string $elementCode) {
		$elArray = [];
		
		$clubRes = CIBlockElement::GetList([], ["IBLOCK_ID" => $abonementsIBlockId, "CODE" => $elementCode, "ACTIVE" => "Y"], false);
		if( $ob = $clubRes->GetNextElement() ) {
			$elArray = $ob->GetFields();
			$elArray["PROPERTIES"] = $ob->GetProperties();
		}
		
		return $elArray;
	}
	
	private function getCurrentClub( int $currentClubId ) {
		$currentClub = [];
		$res = CIBlockElement::GetByID($currentClubId);
		if( $ob = $res->GetNextElement() ) {
			$currentClub = $ob->GetFields();
			$currentClub["PROPERTIES"] = $ob->GetProperties();
		}
		return $currentClub;
	}
	
	/* Выборка клубов, которые используются в абонементе */
	private function getClubsArr( int $clubsIBlockId, int $abonementsIBlockId, string $elementCode, int $currentClubId ) {
		$outArr = [];
		
		$clubRes = CIBlockElement::GetList([], ["IBLOCK_ID" => $abonementsIBlockId, "ACTIVE" => "Y", "CODE" => $elementCode], false, false, ["ID", "NAME", "PROPERTY_PRICE"]);
		$arClubs = [];
		while($resAbonement = $clubRes->fetch()) {	
			$arClubs[$resAbonement['PROPERTY_PRICE_VALUE']['LIST']] = $resAbonement['PROPERTY_PRICE_VALUE']['LIST'];
		}
		
		$arFilter = array(
			"IBLOCK_ID" => $clubsIBlockId, 
			"PROPERTY_SOON" => false, 
			"ACTIVE" => "Y",
			"ID" => array_values($arClubs),
		);
		unset($arClubs);
		
		$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PROPERTY_NUMBER", "PROPERTY_HIDE_LINK"));
		while ($res = $dbElements->fetch()) {
			if( !empty($res['PROPERTY_HIDE_LINK_VALUE']) && $res["ID"] != $currentClubId ) continue;
			$outArr[] = array(
				"ID" => $res["ID"],
				"MESSAGE" => $res["NAME"],
				"SELECTED" => $res["ID"] == $currentClubId ? "selected" : "",
				"NUMBER" => $res["PROPERTY_NUMBER_VALUE"]
			);
		}
		
		return $outArr;
	}
	
	private function getFormFields( int $clubsIBlockId, int $abonementsIBlockId, string $elementCode, int $clubId ) {
		$status = CForm::GetDataByID($this->arParams["WEB_FORM_ID"], $this->arResult["arForm"], $this->arResult["arQuestions"], $this->arResult["arAnswers"], $this->arResult["arDropDown"], $this->arResult["arMultiSelect"]);
		if( $status ) {
			if( !empty($this->arResult["arAnswers"]["club"]) ) {
				reset($this->arResult["arAnswers"]["club"]);
				$fKey = key($this->arResult["arAnswers"]["club"]);
				$this->arResult["arAnswers"]["club"][$fKey]["ITEMS"] = $this->getClubsArr( $clubsIBlockId, $abonementsIBlockId, $elementCode, $clubId );
			}
			$this->arResult["WEB_FORM_ID"] = $this->arParams["WEB_FORM_ID"];
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
	
	private function sendSms($phone = null) {
        if($phone == null){
            $phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
            $phone = (!empty($this->request->get($phoneName))) ? $this->request->get($phoneName) : $this->request->get("phone");
        }
		
        $phone = preg_replace('![^0-9]+!', '', $phone);
        $api = new Api(array(
            "action" => "request_sendcode",
            "params" => array(
                "phone" => $phone,
            )
        ));

        return $api->result();
    }
	
	private function checkCoupon( $coupon ) {
        $api = new Api(array(
            "action" => "coupon",
            "params" => $coupon
        ));
        
        return $api->result();
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
	
    private function checkSms($num) {
        global $APPLICATION;

        $arParam = $this->getFormatFields();

        if ($this->request["num"]) {
            $arParam["code"] = $this->request["num"];
        }
		
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
        $phone = $this->request->get($phoneName);
        $arParam["phone"] = preg_replace('![^0-9]+!', '', $phone);
		
		if($arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]){
            $arParam["additional"] = $this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"];
        }
        
        if($this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]){
            $arParam["subscriptionId"] = $this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"];
        }
		
		if ($this->request["form_hidden_10"] == 0) {
			
			$arParam["type"] = 1;
            if ($this->arResult["ELEMENT"]["CODE"] == "probnaya-trenirovka" || $this->arResult["ELEMENT"]["ID"] == "226") {
                $arParam["type"] = 3;
            }
			if( !empty($this->request["form_default_type"]) ) {
				$arParam["type"] = intval($this->request["form_default_type"]);
			}
            
            $api = new Api(array(
                "action" => "request2",
                "params" => $arParam
            ));
			
        } else {
            
			if( !empty($this->request["form_default_type"]) ) {
				$arParam["type"] = intval($this->request["form_default_type"]);
			}
			
			$api = new Api(array(
                "action" => "request",
               	"params" => $arParam
            ));
        }
		
		return $api->result();
    }
	
	private function resetForm() {
        $arParam = array();
        foreach ($this->arResult["arAnswers"] as $name => $answer) {
            $_REQUEST["form_" . $answer['0']["FIELD_TYPE"] . "_" . $answer['0']["ID"]] = "";
        }
    }
	
	private function checkStep( $currentStep, &$responseResult ) {
        
		foreach( $this->request as $key => $value ) {
            if(strpos($key, "form_") !== false){
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;
            }
            if(strpos($key, "promo") !== false) {
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;   
            }
        }
		
		$actionName = $this->request->get("action");
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
		$phoneValue = "";
		if( !empty($this->request->get($phoneName)) ) {
			$phoneValue = $this->request->get($phoneName);
		}
		
		if( $currentStep == 1 ) {
			if( !empty($actionName) ) {
				switch( $actionName ) {
					case "SEND_SMS":
						$responseResult["RESPONSE"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
						if( empty($responseResult["RESPONSE"]) && !empty($phoneValue) ) {
                        	$responseResult["RESPONSE"] = $this->sendSms($this->request->get("phone"));
							if( empty($responseResult["RESPONSE"]["success"]) ) {
            					$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
								$responseResult["ERROR"] = true;
								return 1;
        					} else {
								return 2;
							}
                    	} else {
							$responseResult["MESSAGE"] = (is_array($responseResult["RESPONSE"])) ? implode(", ", $responseResult["RESPONSE"]) : $responseResult["RESPONSE"];
							$responseResult["ERROR"] = true;
						}
					break;
					case "COUPON":
						$coupon = $this->request->get("coupon");
						if( $coupon ) {
							$responseResult["RESPONSE"] = $this->checkCoupon( $coupon );
							print_r($responseResult["RESPONSE"]); exit; // TEST HERE
							if( empty($responseResult["RESPONSE"]["success"]) ) {
            					$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
								$responseResult["ERROR"] = true;
        					} else {
								if( !empty($responseResult["RESPONSE"]["data"]["result"]["Free"]) ) {
									$responseResult["JS_MESSAGE"] = (!empty($this->$arParams["FREE_MESSAGE"])) ? $this->$arParams["FREE_MESSAGE"] : "Бесплатный абонемент. Для верификации, мы спишем с карты небольшую сумму. Чтобы убедиться, что Вы человек, а не робот.";
								}
								$_SESSION[$this->arResult["COMPONENT_ID"]][$this->arResult["CLUB_ID"]]["SALE_TWO_MONTH"] = "";
								return 2;
							}
                    	}
						return 1;
                    break;
				}
			}
		} else {
			if( !empty($actionName) ) {
				switch( $actionName ) {
					case "CHECK_SMS":
						
						if ( $this->request->get("NUM") ) {
                        	$responseResult["RESPONSE"] = $this->checkSms( $this->request->get("NUM") );
                    	}
						if( !empty($responseResult["RESPONSE"]) && empty($responseResult["RESPONSE"]) ) {
							
							if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    			CFormResult::SetEvent($RESULT_ID);
                    			CFormResult::Mail($RESULT_ID);
                			}
							
							$responseResult["MESSAGE"] = (!empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"])) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "";
							
                			return 3;
							
            			} else if( !empty($responseResult["RESPONSE"]) && $responseResult["RESPONSE"]["data"]["result"]["errorCode"] == 6 ) {
							$responseResult["ERROR"] = true;
							$responseResult["MESSAGE"] = (!empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"])) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "Код введен неверно, повторите через 15 мин.";
						} else {
							$responseResult["ERROR"] = true;
							$responseResult["MESSAGE"] = (!empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"])) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "Не правильно введен код";
						}
						
						return 2;
						
					break;
				}
			}
		}
		
		/*
				if( $this->request["form_hidden_10"] && !$this->request["no_save_price"]) {
                    $outSale = $this->request["form_hidden_10"];
                }

                if ($this->request["form_hidden_21"] && !$this->request["no_save_price"]) {
                    $outSale = $this->request["form_hidden_21"];
                }
                
                //
                // цена за второй месяц после применения промокода
                //

                if ($this->request["two_month"] && !$this->request["no_save_price"]) {
                    $this->arResult["ELEMENT"]["SALE_TWO_MONTH"] = $this->request["two_month"];
                }*/
		
		/*$step = $this->request->get('step');
		$this->arParams["PREV_STEP"] = $step;
		
        if (empty($step)) {
            return 1;
        }

        foreach($this->request as $key => $value) {
            if(strpos($key, "form_") !== false){
                $this->arResult["HIDDEN_FILEDS"][$key] = $value;
            }
            if(strpos($key, "promo") !== false) {
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
            if( !empty($this->arResult["RESPONSE"]) && empty($this->arResult["RESPONSE"]["data"]["result"]["errorCode"]) ) {
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
                }
                return 3;
            } else if( !empty($this->arResult["RESPONSE"]) && $this->arResult["RESPONSE"]["data"]["result"]["errorCode"] == 6 ) {
		        $this->arResult["ERROR"] = (!empty($this->arResult["ERROR"])) ? $this->arResult["ERROR"] : "Код введен неверно, повторите через 15 мин.";
				return 1;
			} else {
                $this->arResult["ERROR"] = (!empty($this->arResult["ERROR"])) ? $this->arResult["ERROR"] : "Не правильно введен код";
                return 2;
            }
        }

        if ($step == 3 && $this->request->get('ajax_send')) {
            $this->resetForm();
            return 1;
        }*/

        return 1;
    }

    function executeComponent() {
		
		if( empty($this->arParams["ELEMENT_CODE"]) ) {
			$this->set404();
		}
        
		if( empty($this->arResult["ERROR"]) ) {
			
			Loader::IncludeModule("form");
        	Loader::IncludeModule("iblock");
			
			global $APPLICATION;
			
			$this->arResult["COMPONENT_ID"] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
			$this->arResult["STEP"] = 1;
			
			$clubId = false;
			if( !empty($this->request["CLUB_ID"]) ) {
				$clubId = $this->request["CLUB_ID"];
            } else if( !empty($this->arParams["CLUB_ID"]) ) {
				$clubId = $this->arParams["CLUB_ID"];
			} else if( !empty($this->arParams["DEFAULT_CLUB_ID"]) ) {
				$clubId = $this->arParams["DEFAULT_CLUB_ID"];
			}
			
			if( !empty($clubId) ) {
				$this->arResult["CLUB"] = $this->getCurrentClub( $clubId );
			}
			
			/* Получаем текущий абонемент */
			$this->arResult["ELEMENT"] = $this->getElement($this->arParams["ABONEMENT_IBLOCK_ID"], $this->arParams["ELEMENT_CODE"]);
			if( empty($this->arResult["ELEMENT"]) ) {
				$this->set404();
			}
			
			/* Если клуб не задан, то выбираем его из цен, по минимальной цене */
			if( empty($clubId) ) {
				$minPriceKey = -1;
				foreach( $this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $key => $value ) {
					if( $value["NUMBER"] == 2 ) continue;
					if( $minPriceKey < 0 || $value["PRICE"] < $this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"][$minPriceKey]["PRICE"] ) {
						$minPriceKey = $key;
					}
				}
				if( $minPriceKey >= 0 ) {
					$clubId = $this->arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"][$minPriceKey]["LIST"];
					$this->arResult["CLUB"] = $this->getCurrentClub( $clubId );
				}
			}
			if( !empty($clubId) ) {
				$this->arResult["CLUB_ID"] = $clubId;
			}
			if( !empty($this->arResult["CLUB"]["PROPERTIES"]["NUMBER"]["VALUE"]) ) {
				$this->arResult["CLUB_NUMBER"] = $this->arResult["CLUB"]["PROPERTIES"]["NUMBER"]["VALUE"];
				$this->arParams["CLUBS_IBLOCK_ID"] = $this->arResult["CLUB"]["IBLOCK_ID"];
			}
			
			/*Получаем поля формы*/
			$this->getFormFields( $this->arParams["CLUBS_IBLOCK_ID"], $this->arParams["ABONEMENT_IBLOCK_ID"], $this->arParams["ELEMENT_CODE"], $clubId );
			
			/* Получаем значения SEO */
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arParams["ABONEMENT_IBLOCK_ID"], $this->arResult["ELEMENT"]["ID"]);
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
			
			/*AJAX Actions*/
			if( check_bitrix_sessid() && !empty($this->request["COMPONENT_ID"]) && !empty($this->request["STEP"]) && $this->request["COMPONENT_ID"] === $this->request["COMPONENT_ID"] ) {
				
				$responseResult = ["ERROR" => false, "MESSAGE" => "", "RESPONSE" => ""];
				
				$this->arResult["STEP"] = $this->checkStep($this->arResult["STEP"], $responseResult);
				$this->arResult['RESPONSE'] = $responseResult;
				
				/*Получаем цены*/
				$this->arResult["ELEMENT"] = array_merge($this->arResult["ELEMENT"], $this->getPrice( $clubId, $this->arResult["ELEMENT"] ));
				foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["VALUE"] as $service) {
					if ($service["LIST"] == $clubId) {
						$this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"][] = $service["PRICE"];
					}
				}
				
				$templateName = ($this->arResult["STEP"] > 1) ? "step-".$this->arResult["STEP"] : "template";
				
				$this->includeComponentTemplate($templateName);
				
			} else {
				
				/*Получаем цены*/
				$this->arResult["ELEMENT"] = array_merge($this->arResult["ELEMENT"], $this->getPrice( $clubId, $this->arResult["ELEMENT"] ));
				foreach ($this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["VALUE"] as $service) {
					if ($service["LIST"] == $clubId) {
						$this->arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"][] = $service["PRICE"];
					}
				}
				
				$this->includeComponentTemplate();
			}
			
		} else {
			echo $this->arResult["ERROR"];
		}
		
        /*
        
        $this->getFields();
        $this->getElement();
        
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->arResult["ELEMENT"]["ABONEMENT_IBLOCK_ID"], $this->arResult["ELEMENT"]["ID"]);
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
		
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
		if( $this->request->get($phoneName) ) {
			$this->arResult["SMS_PHONE"] = $this->request->get($phoneName);
		}
		
        if($this->request->get("mode")){
            switch($this->request->get("mode")) {
                case "try_sms":
					if($this->request->get("phone")) {
                        $this->sendSms($this->request->get("phone"));
                    }
                    break;
                case "coupon":
                    if($this->request->get("coupon")) {
                        $this->checkCoupon($this->request->get("coupon"));
                    }
                    break;
                case "check_sms":
                    if ($this->request->get("num")) {
                        $this->checkSms($this->request->get("num"));
                    }
                    break;
            }
        }

        $prevStep = intval($this->request->get('step'));

        switch ($this->checkStep()) {
            case 2:
                if( $prevStep == 1 ) $this->sendSms();
                if( empty($this->arResult["ERROR"]) || intval($this->arResult["ERROR"]) === 1 ) {
                    $this->includeComponentTemplate('step-2');
                } else if( $prevStep > 1 ) {
                	$this->includeComponentTemplate('step-2');
                } else {
					$this->includeComponentTemplate();
				}
                break;
            case 3:
                $this->includeComponentTemplate('step-3');
                break;
            default:
                $this->includeComponentTemplate();
                break;
        }*/
    }
}
?>