<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	
	use \Bitrix\Main\Loader;
	
	class FormResumeComponent extends CBitrixComponent {
	
		public function __construct(CBitrixComponent $component = null) {
            parent::__construct($component);
            
            Loader::includeModule('form');
        }
		
		function onPrepareComponentParams($arParams){
        	if(!$arParams["WEB_FORM_ID"]){
            	$this->arResult["ERROR"] = "Не выбранна веб форма";
        	}
        	return $arParams;
    	}

    	private function getFields() {
        	
			$status = CForm::GetDataByID($this->arParams["WEB_FORM_ID"], $this->arResult["arForm"], $this->arResult["arQuestions"], $this->arResult["arAnswers"], $this->arResult["arDropDown"], $this->arResult["arMultiSelect"]);
			
        	if($status) {
            	$this->arResult["WEB_FORM_ID"] = $this->arResult["arForm"]["VARNAME"];
        	}
    	}

    	private function checkStep() {
        	$step = $this->request->get('step');
        	$type = $this->request->get('type');
		
        	if (empty($step)) {
            	return 1;
        	}
			
			foreach($this->request as $key => $value){
            	if(strpos($key, "form_") !== false){
                	$this->arResult["HIDDEN_FILEDS"][$key] = $value;
            	}
        	}
			
        	// Обработка формы без SMS-подтверждения номера
        	if ($this->arParams["SKIP_CHECKS"] == "Y") {
			
				$this->arResult["ERROR"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
            	if ( !empty($this->arResult["ERROR"]) ) {
					return 1;
				}
				
				$arParam = $this->getFormatFields();
            	$arParam['type'] = (int)$type;
            	
            	$api = new Api(array(
					"action" => "web_site_resume",
					"params" => $arParam
            	));
				
				if( !empty($_FILES) ) {
					foreach($_FILES as $key => $file) {
						$_REQUEST["form_file_109"] = CFile::MakeFileArray(CFile::SaveFile($file, "emails"));
						break;
					}
				} else {
					unset($_REQUEST["form_file_109"]);
				}
				
				if( !empty($_REQUEST["form_text_104"]) ) {
					$_REQUEST["form_text_104"] = preg_replace("/[^0-9]/", '', $_REQUEST["form_text_104"]);
				}
					
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
                } else {
					global $strError;
					$this->arResult["ERROR"] = "Внутренняя ошибка. " . $strError;
					return 2;
				}
				
            	return 3;
        	}

        	if ($step == 1 && $this->request->get('ajax_send')) {
            	$this->arResult["ERROR"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
            	if ( empty($this->arResult["ERROR"]) ) {
					if( !empty($_FILES) ) {
						foreach($_FILES as $key => $file) {
							$this->arResult["HIDDEN_FILEDS"][$key] = CFile::SaveFile($file, "emails");
							break;
						}
					}
                	return 2;
            	} else {
                	return 1;
            	}
        	}
			
        	if ($step == 2 && $this->request->get('ajax_send')) {
			
				$response = $this->checkSms();
				
            	if( $response["success"] ) {
				
					$api = new Api(array(
						"action" => "web_site_resume",
						"params" => $arParam
            		));
					
					if( !empty($_REQUEST["form_file_109"]) ) {
						$_REQUEST["form_file_109"] = CFile::MakeFileArray(intval($_REQUEST["form_file_109"]));
					} else {
						unset($_REQUEST["form_file_109"]);
					}
					if( !empty($_REQUEST["form_text_104"]) ) {
						$_REQUEST["form_text_104"] = preg_replace("/[^0-9]/", '', $_REQUEST["form_text_104"]);
					}
					
                	if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    	CFormResult::SetEvent($RESULT_ID);
                    	CFormResult::Mail($RESULT_ID);
                	} else {
						//global $strError;
						//$this->arResult["ERROR"] = "Внутренняя ошибка. " . $strError;
						return 2;
					}
					
                	return 3;
            	} else {
                	$this->arResult["ERROR"] = "Не правильно введен код";
                	return 2;
            	}
        	}
			
        	if ($step == 3 && $this->request->get('ajax_send')) {
            	$this->resetForm();
            	return 1;
        	}
			
			$this->arResult["ERROR"] = "Внутренняя ошибка.";
			
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
        	$arParam['client_id'] = $this->request->getCookieRaw('_ga');
        	return $arParam;
    	}
		
    	private function checkSms() {
        	global $APPLICATION;
			
        	$arParam = $this->getFormatFields();
        	$arParam["code"] = $this->request->get('code');
        	
        	$api = new Api(array(
            	"action" => "check_code",
            	"params" => $arParam
        	));

        	$result = $api->result();
        	
        	return $result;
    	}
		
    	function executeComponent() {
			
        	
        	$this->getFields();
			
        	if($this->request->get("mode")){
            	switch($this->request->get("mode")){
                	case "try_sms":
                    	if($this->request->get("phone")){
                        	$this->sendSms($this->request->get("phone"));
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
                	if (empty($this->arResult["ERROR"])) {
                    	$this->sendSms();
                	}
					if( empty($this->arResult["ERROR"]) ) {
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
        	}
    	}
	}
?>