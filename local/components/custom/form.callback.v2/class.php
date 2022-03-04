<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	
	use \Bitrix\Main\Loader;
	
	class FormCallbackComponent extends CBitrixComponent {
		
		function onPrepareComponentParams($arParams){
			if( empty($arParams["WEB_FORM_ID"]) ){
				$this->arResult["ERROR"] = "Не выбранна веб форма";
			}
			
			if( empty($arParams["CLUB_NUMBER"]) && !empty($this->request->get("club")) ) {
				$this->arParams["CLUB_NUMBER"] = $this->request->get("club");
			}
			
			if( !isset($arParams["FORM_TYPE"]) ) {
				$this->arResult["ERROR"] = "Не выбран тип формы";
			}
			
			return $arParams;
		}
		
		private function getFormFields() {
			$status = CForm::GetDataByID($this->arParams["WEB_FORM_ID"], $this->arResult["arForm"], $this->arResult["arQuestions"], $this->arResult["arAnswers"], $this->arResult["arDropDown"], $this->arResult["arMultiSelect"]);
			if( $status && !empty($this->arParams["CLUB_NUMBER"]) ) {
				if($this->arResult["arAnswers"]["club"]) {
					$this->arResult["arAnswers"]["club"][0]['ITEMS'] = Utils::getClubsForm( $this->arParams["CLUB_NUMBER"] );
				}
			}
			$this->arResult["SELECTED_THEME"] = "";
			if( $status && !empty($this->arResult["arAnswers"]["theme"]) ) {
				$fieldId = "form_" . $this->arResult["arAnswers"]["theme"][0]["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["theme"][0]["ID"];
				$this->arResult["SELECTED_THEME"] = (!empty($this->request[$fieldId])) ? $this->request[$fieldId] : "";
			}
		}
		
		private function getRequestFields() {
			$fromFields = [];
			if( !empty($this->arResult["arAnswers"]) && is_array($this->arResult["arAnswers"]) ) {
				foreach ($this->arResult["arAnswers"] as $name => $answer) {
					$fieldId = "form_" . $answer['0']["FIELD_TYPE"] . "_" . $answer['0']["ID"];
					$fieldValue = (!empty($this->request[$fieldId])) ? $this->request[$fieldId] : false;
					if( !empty($fieldValue) ) {
						if( $name == "phone" ) {
							$fieldValue = preg_replace('![^0-9]+!', '', $fieldValue);
						}
						$fromFields[$name] = $fieldValue;
					}
				}
			}
			$fromFields["client_id"] = $this->request->getCookieRaw("_ga");
			$fromFields["type"] = $this->arParams["FORM_TYPE"];
			$fromFields["WEB_FORM_ID"] = $this->arParams["WEB_FORM_ID"];
			return $fromFields;
		}
		
		private function resetForm() {
        	foreach ($this->arResult["arAnswers"] as $name => $answer) {
				$_REQUEST["form_" . $answer['0']["FIELD_TYPE"] . "_" . $answer['0']["ID"]] = "";
			}
		}
		
		private function sendSms( $phone = NULL ) {
			if( empty($phone) && !empty($this->arResult["arAnswers"]["phone"]["0"]) ){
				$phoneFieldName = "form_" . $this->arResult["arAnswers"]["phone"]["0"]["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]["0"]["ID"];
				$phone = $this->request->get($phoneFieldName);
			}
			
			$phone = preg_replace('![^0-9]+!', '', $phone);
			
			$api = new Api([
				"action" => "request_sendcode",
				"params" => [ "phone" => $phone ]
			]);
			
			return $api->result();
		}
		
		private function checkSms( $num ) {
			
			$fromFields = $this->getRequestFields();
			$fromFields["code"] = $num;
			
			$requestType = (!empty($this->arParams["ACTION_TYPE"])) ? $this->arParams["ACTION_TYPE"] : "request";
			$api = new Api([
				"action" => "check_code",
            	"params" => $fromFields
			]);
			
			return $api->result();
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
			
			$actionName = $this->request->get("ACTION");
			$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
			$phoneValue = "";
			
			if( !empty($this->request->get($phoneName)) ) {
				$phoneValue = $this->request->get($phoneName);
				$this->arResult["SMS_PHONE"] = $phoneValue;
			}
			if( $currentStep == 1 ) {
				switch( $actionName ) {
					case "REFRESH": //Обновление формы
						$responseResult["RESPONSE"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
						if( !empty($responseResult["RESPONSE"]) ) {
							if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
								$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
							} else {
								$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["error"];
							}
							$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
							$responseResult["ERROR"] = true;
						}
						return 1;
					break;
					case "SEND_SMS":
						$responseResult["RESPONSE"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
						if( empty($responseResult["RESPONSE"]) && !empty($phoneValue) ) {
                        	$responseResult["RESPONSE"] = $this->sendSms($phoneValue);
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
				}
			} else if( $currentStep == 2 ) {
				switch( $actionName ) {
					case "RESEND_SMS":
						$responseResult["RESPONSE"] = CForm::Check($this->arParams["WEB_FORM_ID"], $_REQUEST, false, "Y", "N");
						if( empty($responseResult["RESPONSE"]) && !empty($phoneValue) ) {
                        	$responseResult["RESPONSE"] = $this->sendSms($phoneValue);
							if( empty($responseResult["RESPONSE"]["success"]) ) {
            					$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
								$responseResult["ERROR"] = true;
        					} else {
								$responseResult["MESSAGE"] = "Код успешно отправлен на номер " . $phoneValue;
								$responseResult["ERROR"] = true;
							}
                    	} else {
							$responseResult["MESSAGE"] = (is_array($responseResult["RESPONSE"])) ? implode(", ", $responseResult["RESPONSE"]) : $responseResult["RESPONSE"];
							$responseResult["ERROR"] = true;
						}
						return 2;
					break;
					case "CHECK_SMS":
						if ( $this->request->get("NUM") ) {
                        	$responseResult["RESPONSE"] = $this->checkSms( $this->request->get("NUM") );
                    	}
						
						if( empty($responseResult["RESPONSE"]["success"]) || !empty($responseResult["RESPONSE"]["data"]["result"]["errorCode"]) ) {
							
							if( !empty($responseResult["RESPONSE"]["data"]["result"]["errorCode"]) && $responseResult["RESPONSE"]["data"]["result"]["errorCode"] == 6 ) {
								$responseResult["MESSAGE"] = (!empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"])) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "Код введен неверно, повторите через 15 мин.";
							}
							
							if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
								$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
							} else {
								$responseResult["MESSAGE"] = "Неправильно введен код";
							}
							$responseResult["ERROR"] = true;
							
						} else {
							
							$fromFields = $this->getRequestFields();
							$requestType = (!empty($this->arParams["ACTION_TYPE"])) ? $this->arParams["ACTION_TYPE"] : "web_site_contact";
							$api = new Api([
								"action" => $requestType,
								"params" => $fromFields
							]);
							$responseResult["RESPONSE"] = $api->result();
							if( empty($responseResult["RESPONSE"]["success"]) || !empty($responseResult["RESPONSE"]["data"]["result"]["errorCode"]) ) {
								if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
									$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
								} else {
									$responseResult["MESSAGE"] = "Ошибка отправки запроса";
								}
								$responseResult["ERROR"] = true;
							} else {
								if( $RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N") ) {
                    				CFormResult::SetEvent($RESULT_ID);
                    				CFormResult::Mail($RESULT_ID);
									
									$responseResult["MESSAGE"] = "Сообщение отправлено";
									
									$this->resetForm();
									return 3;
                				} else {
									global $strError;
									$responseResult["MESSAGE"] = $strError;
									$responseResult["ERROR"] = true;
								}
							}
						}
						
						return 2;
						
					break;
				}
			} else if( $currentStep == 3 ) {
				$responseResult["ERROR"] = true;
				$responseResult["MESSAGE"] = "Ваш запрос отправлен.";
				unset($_SESSION[$uniqueId]);
				$this->resetForm();
			}
			
			return 1;
		}
		
		function executeComponent() {
			if( empty($this->arResult["ERROR"]) ) {
				
				Loader::IncludeModule("form");
        		Loader::IncludeModule("iblock");
				
				$this->arResult["COMPONENT_ID"] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
				$this->arResult["WEB_FORM_ID"] = $this->arParams["WEB_FORM_ID"];
				
				$this->arResult["STEP"] = 1;
				if( !empty($this->request["STEP"]) ) {
					$this->arResult["STEP"] = intval($this->request["STEP"]);
				}
				$this->arResult["PREV_STEP"] = $this->arResult["STEP"];
				
				/* */
				$this->arResult["INFO"] = Utils::getInfo();
				
				/* Получаем поля формы */
				$this->getFormFields();
				
				if( !empty($this->request["COMPONENT_ID"]) && !empty($this->request["STEP"]) && $this->arResult["COMPONENT_ID"] == $this->request["COMPONENT_ID"] ) {
					
					$responseResult = ["ERROR" => false, "MESSAGE" => "", "RESPONSE" => ""];
					$this->arResult["STEP"] = $this->checkStep($this->arResult["STEP"], $responseResult);
					$this->arResult["RESPONSE"] = $responseResult;
					
					$templateName = ($this->arResult["STEP"] > 1) ? "step-".$this->arResult["STEP"] : "template";
					
					$this->includeComponentTemplate($templateName);
				
				} else {
					$this->includeComponentTemplate();
				}
				
			} else {
				echo $this->arResult["ERROR"];
			}
		}
	}
?>