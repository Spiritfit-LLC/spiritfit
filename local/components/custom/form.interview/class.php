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
				"club_798" => "B",
				"sex_116" => "C",
				"sex_117" => "C",
				"age_118" => "D",
				"age_119" => "D",
				"age_120" => "D",
				"age_121" => "D",
				"age_122" => "D",
				"knowledge_123" => "E",
				"knowledge_124" => "E",
				"knowledge_125" => "E",
				"knowledge_126" => "E",
				"visit_127" => "F",
				"visit_128" => "F",
				"visit_129" => "F",
				"goals_130" => "G",
				"goals_131" => "H",
				"goals_132" => "I",
				"goals_133" => "J",
				"goals_134" => "K",
				"goals_135" => "L",
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
				"comfort_183" => "AV",
				"comfort_184" => "AW",
				"comfort_185" => "AX",
				"comfort_186" => "AY",
				"comfort_ask_187" => "AZ",
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
					} elseif($item["FIELD_TYPE"] === "select") {
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
			
			if( empty($arExcelCels["N"]) ) {
				$arExcelCels["N"] = "0";
			}
			
			if( !empty($arExcelCels["H"]) ) {
				$arExcelCels["H"] = "Да";
			}
			if( !empty($arExcelCels["I"]) ) {
				$arExcelCels["I"] = "Да";
			}
			if( !empty($arExcelCels["J"]) ) {
				$arExcelCels["J"] = "Да";
			}
			if( !empty($arExcelCels["K"]) ) {
				$arExcelCels["K"] = "Да";
			}
			if( !empty($arExcelCels["L"]) ) {
				$arExcelCels["L"] = "Да";
			}
			
			$sFile = $_SERVER["DOCUMENT_ROOT"] . "/upload/" . $this->arParams["EXCEL_FILE"];
			if( \Bitrix\Main\Loader::includeModule('phpoffice') && file_exists($sFile) ) {
				
				$oReader = new Xlsx();
		
				$oSpreadsheet = $oReader->load($sFile);
				$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
				
				$rowNum = $oCells->getHighestRow();
				$rowNum += 1;
				
				$currentDate = date("d.m.Y H:i:s");
				$oSpreadsheet->setActiveSheetIndex(0)->setCellValue("A".$rowNum, $currentDate);
				$oSpreadsheet->getActiveSheet()->getStyle("A".$rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->SetVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$oSpreadsheet->getActiveSheet()->getStyle("A".$rowNum)->getAlignment()->setWrapText(true);
				$oSpreadsheet->getActiveSheet()->getStyle("A".$rowNum)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('CECECE'));
				
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
        	
			$reqFields = $this->getNamedFields();
			$arParam = [];
			foreach( $arFormParam as $code => $value ) {
				foreach($value["VALUES"] as $item) {
					$value = "";
					if( $item["FIELD_TYPE"] === "text" || $item["FIELD_TYPE"] === "textarea" ) {
						$value = $item["VALUE"];
					} elseif($item["FIELD_TYPE"] === "select") {
						$value = $item["VALUE"];
					} else if($item["FIELD_TYPE"] === "radio" && !empty($item["MESSAGE"])) {
						$value = $item["MESSAGE"];
					} else {
						$value = "Да";
					}
					
					if( empty($arParam[$code]) ) {
						$arParam[$code] = $value;
					} else {
						$arParam[$code] = $arParam[$code] . ", " . $value;
					}
				}
			}
			
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
			$arParam["phone"] = $this->request->get('form_text_212');
        	
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
			
			$templateName = '';
        	switch ($this->checkStep()) {
            	case 2:
                	if (empty($this->arResult["ERROR"])) {
                    	$this->sendSms();
                	}
					if( empty($this->arResult["ERROR"]) || intval($this->arResult["ERROR"]) === 1 ) {
						$templateName = 'step-2';
					}
                	break;
            	case 3:
					$templateName = 'step-3';
                	break;
        	}
			
			$this->includeComponentTemplate($templateName);
    	}
	}
?>