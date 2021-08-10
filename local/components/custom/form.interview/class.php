<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	
	use \Bitrix\Main\Loader;
	use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Style\Border;
	use PhpOffice\PhpSpreadsheet\Style\Color;
	
	class FormResumeComponent extends CBitrixComponent {
	
		public function __construct(CBitrixComponent $component = null) {
            parent::__construct($component);
            
            Loader::includeModule('form');
        }
		
		function onPrepareComponentParams($arParams){
        	if(!$arParams["WEB_FORM_ID"]){
            	$this->arResult["ERROR"] = "Не выбранна веб форма";
        	}
			if(!$arParams["EXCEL_FILE"]){
            	$this->arResult["ERROR"] = "Не выбран файл EXCEL";
        	}
			if(!$arParams["IBLOCK_CODE"]){
            	$this->arResult["ERROR"] = "Не выбран инфоблок с клубами";
        	}
        	return $arParams;
    	}

    	private function getFields() {
        	
			$status = CForm::GetDataByID($this->arParams["WEB_FORM_ID"], $this->arResult["arForm"], $this->arResult["arQuestions"], $this->arResult["arAnswers"], $this->arResult["arDropDown"], $this->arResult["arMultiSelect"]);
        	if($status) {
            	$this->arResult["WEB_FORM_ID"] = $this->arResult["arForm"]["VARNAME"];
        	}
			$this->arResult["arAnswersOrigin"] = $this->arResult["arAnswers"];
			$this->getRequestFields();
			
			if( !empty($this->arResult["arQuestions"]["club"]["ID"]) && !empty($this->arParams["IBLOCK_CODE"]) ) {
				$this->getClubs($this->arResult["arAnswers"]);
			}
    	}
		
		private function getClubs( &$answers ) {
			$clubSelection = [];
			$clubSelection["BASE"] = $answers["club"][0];
			
			$res = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"], "ACTIVE" => "Y"), false);
			$cSort = 100;
			while($arRes = $res->GetNext()) {
				$clubSelection[] = array(
					"ID" => $arRes["ID"],
                    "FIELD_ID" => $clubSelection["BASE"]["FIELD_ID"],
                    "QUESTION_ID" => $clubSelection["BASE"]["QUESTION_ID"],
                    "TIMESTAMP_X" => $clubSelection["BASE"]["TIMESTAMP_X"],
                    "MESSAGE" => $clubSelection["BASE"]["MESSAGE"],
                    "VALUE" => $arRes["NAME"],
                    "FIELD_TYPE" => "select",
                    "FIELD_WIDTH" => $clubSelection["BASE"]["FIELD_WIDTH"],
                    "FIELD_HEIGHT" => $clubSelection["BASE"]["FIELD_HEIGHT"],
                    "FIELD_PARAM" => $clubSelection["BASE"]["FIELD_PARAM"],
                    "C_SORT" => $cSort,
                    "ACTIVE" => $clubSelection["BASE"]["ACTIVE"],
				);
				$cSort += 100;
			}
				
			$answers["club"] = $clubSelection;
		}
		
		private function saveToExcel() {
			
			$arFormParam = $this->getNamedFields();
			$arExcelFields = array(
				"club_768" => "A",
				"sex_116" => "B",
				"sex_117" => "B",
				"age_118" => "C",
				"age_119" => "C",
				"age_120" => "C",
				"age_121" => "C",
				"age_122" => "C",
				"knowledge_123" => "D",
				"knowledge_124" => "D",
				"knowledge_125" => "D",
				"knowledge_126" => "D",
				"visit_127" => "E",
				"visit_128" => "E",
				"visit_129" => "E",
				"goals_130" => "F",
				"goals_131" => "G",
				"goals_132" => "H",
				"goals_133" => "I",
				"goals_134" => "J",
				"goals_135" => "K",
				"goals_207" => "L",
				"payment_136" => "M",
				"payment_137" => "M",
				"payment_138" => "M",
				"payment_208" => "M",
				"recommendation_139" => "N",
				"reccomendation_ask_140" => "O",
				"fullness_141" => "P",
				"fullness_161" => "Q",
				"fullness_162" => "R",
				"fullness_ask_144" => "S",
				"administrator_145" => "T",
				"administrator_163" => "U",
				"administrator_164" => "V",
				"administrator_ask_148" => "W",
				"trainer_149" => "X",
				"trainer_165" => "Y",
				"trainer_166" => "Z",
				"trainer_ask_152" => "AA",
				"dressing_153" => "AB",
				"dressing_167" => "AC",
				"dressing_168" => "AD",
				"dressing_ask_156" => "AE",
				"hall_157" => "AF",
				"hall_169" => "AG",
				"hall_170" => "AH",
				"hall_ask_160" => "AI",
				"programs_171" => "AJ",
				"programs_172" => "AK",
				"programs_173" => "AL",
				"programs_174" => "AM",
				"programs_175" => "AN",
				"programs_176" => "AO",
				"programs_ask_177" => "AP",
				"ambience_178" => "AQ",
				"ambience_179" => "AR",
				"ambience_180" => "AS",
				"ambience_181" => "AT",
				"ambience_ask_182" => "AU",
				"comfort_183" => "AU",
				"comfort_184" => "AV",
				"comfort_185" => "AW",
				"comfort_186" => "AX",
				"comfort_ask_187" => "AY",
				"application_188" => "BA",
				"application_189" => "BB",
				"application_190" => "BC",
				"application_191" => "BD",
				"application_192" => "BE",
				"application_193" => "BF",
				"application_isgood_194" => "BG",
				"application_isgood_195" => "BH",
				"application_isgood_196" => "BI",
				"application_isgood_ask_197" => "BJ",
				"statements_198" => "BK",
				"statements_199" => "BL",
				"statements_200" => "BM",
				"statements_201" => "BN",
				"statements_202" => "BO",
				"statements_203" => "BP",
				"statements_206" => "BQ",
				"comment_205" => "BR",
				"phone_212" => "BS"
			);
			
			$arExcelCels = [];
			foreach( $arFormParam as $code => $value ) {
				foreach($value["VALUES"] as $item) {
					$key = $code . "_" . $item["ID"];
					$value = "";
					if( $item["FIELD_TYPE"] === "text" || $item["FIELD_TYPE"] === "textarea" ) {
						$value = $item["VALUE"];
					} else if($item["FIELD_TYPE"] === "radio" && !empty($item["MESSAGE"])) {
						$value = $item["MESSAGE"];
					} else {
						$value = "Да";
					}
					if( !empty($arExcelFields[$key]) ) {
						if( empty($arExcelCels[$arExcelFields[$key]]) ) {
							$arExcelCels[$arExcelFields[$key]] = $value;
						} else {
							$arExcelCels[$arExcelFields[$key]] = $arExcelCels[$arExcelFields[$key]] . ", " . $value;
						}
					}
				}
			}
			
			$sFile = $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $this->arParams["EXCEL_FILE"];
			if( \Bitrix\Main\Loader::includeModule('phpoffice') && file_exists($sFile) ) {
				
				$oReader = new Xlsx();
		
				$oSpreadsheet = $oReader->load($sFile);
				$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
				
				$rowNum = $oCells->getHighestRow();
				$rowNum += 1;
				
				foreach($arExcelFields as $key) {
					$num = $key.$rowNum;
					$val = (!empty($arExcelCels[$key])) ? $arExcelCels[$key] : "";
					$oSpreadsheet->setActiveSheetIndex(0)->setCellValue($num, $val);
					
					$oSpreadsheet->getActiveSheet()->getStyle($num)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->SetVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
					$oSpreadsheet->getActiveSheet()->getStyle($num)->getAlignment()->setWrapText(true);
					$oSpreadsheet->getActiveSheet()->getStyle($num)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('CECECE'));
				}
				
				$oWriter = IOFactory::createWriter($oSpreadsheet, 'Xlsx');
				$oWriter->save($sFile);
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
			
			$arParam = $this->getFormatFields();
			$arParam['type'] = (int)$type;
			
        	/* Обработка формы без SMS-подтверждения номера */
			if ($this->arParams["SKIP_CHECKS"] == "Y") {
            	
				$this->arResult["ERROR"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
				if( !empty($this->arResult["ERROR"]) ) {
					return 1;
				}
				
				$api = new Api(array(
					"action" => "web_site_inteview",
					"params" => $arParam
            	));
					
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    
					CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
					
					$this->saveToExcel();
					
                } else {
					global $strError;
					$this->arResult["ERROR"] = "Внутренняя ошибка. " . $strError;
					return 1;
				}
				
            	return 3;
        	}
			/* Обработка формы без SMS-подтверждения номера */

        	if ($step == 1 && $this->request->get('ajax_send')) {
            	$this->arResult["ERROR"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
            	if ( empty($this->arResult["ERROR"]) ) {
                	return 2;
            	} else {
                	return 1;
            	}
        	}
			
        	if ($step == 2 && $this->request->get('ajax_send')) {
			
				$response = $this->checkSms();
				
            	if( $response["success"] ) {
				
					$api = new Api(array(
						"action" => "web_site_inteview",
						"params" => $arParam
            		));
					
                	if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    	
						CFormResult::SetEvent($RESULT_ID);
                    	CFormResult::Mail($RESULT_ID);
						
						$this->saveToExcel();
						
                	} else {
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
		
		private function getRequestFields() {
			foreach ($this->arResult["arAnswers"] as $code => $answers) {
				foreach( $answers as $key => $value ) {
					if( !empty($this->request["form_" . $value["FIELD_TYPE"] . "_" . $value["ID"]]) ) {
						$this->arResult["arAnswers"][$code][$key]["VALUE"] = $this->request["form_" . $value["FIELD_TYPE"] . "_" . $value["ID"]];
					}
					if( !empty($this->request["form_" . $value["FIELD_TYPE"] . "_" . $this->arResult["arQuestions"][$code]["VARNAME"]]) && $this->request["form_" . $value["FIELD_TYPE"] . "_" . $this->arResult["arQuestions"][$code]["VARNAME"]] == $value["ID"] ) {
						$this->arResult["arAnswers"][$code][$key]["VALUE"] = "Y";
					}
				}
			}
		}
		
		private function getNamedFields() {
			$resArr = [];
			foreach ($this->arResult["arAnswersOrigin"] as $code => $answers) {
				$resArr[$code] = ["NAME" => $this->arResult["arQuestions"][$code]["TITLE"], "VALUES" => []];
				foreach( $answers as $key => $value ) {
					if( !empty($this->request["form_" . $value["FIELD_TYPE"] . "_" . $value["ID"]]) ) {
						$resArr[$code]["VALUES"][] = $this->arResult["arAnswers"][$code][$key];
					}
					if( !empty($this->request["form_" . $value["FIELD_TYPE"] . "_" . $this->arResult["arQuestions"][$code]["VARNAME"]]) && $this->request["form_" . $value["FIELD_TYPE"] . "_" . $this->arResult["arQuestions"][$code]["VARNAME"]] == $value["ID"] ) {
						$resArr[$code]["VALUES"][] = $this->arResult["arAnswers"][$code][$key];
					}
				}
			}
			return $resArr;
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
			
        	/*if($this->request->get("mode")){
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
        	}*/
			
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