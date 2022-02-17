<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	global $USER;

use \Bitrix\Main\Loader;

class FormGetAbonimentComponent extends CBitrixComponent{

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
		
		$uniqueId = $this->arResult["COMPONENT_ID"] . $currentClubId . $element["ID"];
		foreach( $outArrPrice as $key => $arPrice ) {
            if( !empty($outArrBasePrice) && $arPrice["PRICE"] != $outArrBasePrice["PRICE"] && $arPrice["NUMBER"] == $outArrBasePrice["NUMBER"] ) {
				
                $outSale = $arPrice["PRICE"];
                
				if( isset($_SESSION[$uniqueId]["DISCOUNTS"]["SALE"]) ) {
					$outSale = $_SESSION[$uniqueId]["DISCOUNTS"]["SALE"];
				}
				if( isset($_SESSION[$uniqueId]["DISCOUNTS"]["SALE_TWO_MONTH"]) ) {
					$outSaleTwoMonth = $_SESSION[$uniqueId]["DISCOUNTS"]["SALE_TWO_MONTH"];
				}

                $outArrPrice[$key]["PRICE"] = $outArrBasePrice["PRICE"];
            } else if( !empty($outArrBasePrice) && !empty($_SESSION[$uniqueId]["DISCOUNTS"]["SALE"]) && $outArrBasePrice["NUMBER"] == 1) {
				$outSale = $_SESSION[$uniqueId]["DISCOUNTS"]["SALE"];
			} else if( !empty($outArrBasePrice) && !empty($_SESSION[$uniqueId]["DISCOUNTS"]["SALE_TWO_MONTH"]) && $outArrBasePrice["NUMBER"] == 2) {
				$outSaleTwoMonth = $_SESSION[$uniqueId]["DISCOUNTS"]["SALE_TWO_MONTH"];
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
		
		return ["PRICES" => $outArrPrice, "BASE_PRICE" => $outArrBasePrice, "SALE" => $outSale, "SALE_TWO_MONTH" => $outSaleTwoMonth];
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
		
		$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "CODE", "NAME", "PROPERTY_NUMBER", "PROPERTY_HIDE_LINK"));
		while ($res = $dbElements->fetch()) {
			if( !empty($res['PROPERTY_HIDE_LINK_VALUE']) && $res["ID"] != $currentClubId ) continue;
			if( $res["CODE"] === "fitnes-marafon-br-8-nedel" && $currentClubId === "00" ) continue;
			if( $res["CODE"] === "fitnes-marafon-br-4-nedeli" && $currentClubId === "00" ) continue;
			
			$inArray = false;
			foreach( $outArr as $item ) {
				if( $item["MESSAGE"] === $res["NAME"] ) {
					$inArray = true;
					break;
				}
			}
			
			if( !$inArray ) {
				$outArr[] = array(
					"ID" => $res["ID"],
					"MESSAGE" => $res["NAME"],
					"SELECTED" => $res["ID"] == $currentClubId ? "selected" : "",
					"NUMBER" => $res["PROPERTY_NUMBER_VALUE"]
				);
			}
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
		$arParam = ["phone" => $phone, "WEB_FORM_ID" => $this->arParams["WEB_FORM_ID"]];
		if( !empty($this->arResult["CLUB_NUMBER"]) ) {
			$arParam["club"] = $this->arResult["CLUB_NUMBER"];
		}
		$arParam["type"] = $this->arParams["FORM_TYPE"];
		if( empty($arParam["type"]) ) {
			$arParam["type"] = 1;
		}
		if( !empty($this->request["form_default_type"]) ) {
			$arParam["type"] = intval($this->request["form_default_type"]);
		}
		$uniqueId = $this->arResult["COMPONENT_ID"].$this->arResult["CLUB_ID"].$this->arResult["ELEMENT"]["ID"];
		if( !empty( $_SESSION[$uniqueId]["COUPON"] ) ) {
			$arParam["promo"] = $_SESSION[$uniqueId]["COUPON"];
		}
		
        $api = new Api(array(
            "action" => "request_sendcode",
            "params" => $arParam
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
	
    private function checkSms( $num ) {
        global $APPLICATION;

        $arParam = $this->getFormatFields();
        $arParam["code"] = $num;
		$arParam["WEB_FORM_ID"] = $this->arParams["WEB_FORM_ID"];
		
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
        $phone = $this->request->get($phoneName);
        $arParam["phone"] = preg_replace('![^0-9]+!', '', $phone);
		
		if($this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]){
            $arParam["additional"] = $this->arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"];
        }
        
        if($this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]){
            $arParam["subscriptionId"] = $this->arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"];
        }
		
		$arParam["type"] = $this->arParams["FORM_TYPE"];
		if( empty($arParam["type"]) ) {
			$arParam["type"] = 1;
		}
		if( !empty($this->request["form_default_type"]) ) {
			$arParam["type"] = intval($this->request["form_default_type"]);
		}
		
		if( !empty($this->arResult["CLUB_NUMBER"]) ) {
			$arParam["club"] = $this->arResult["CLUB_NUMBER"];
		}
		$uniqueId = $this->arResult["COMPONENT_ID"].$this->arResult["CLUB_ID"].$this->arResult["ELEMENT"]["ID"];
		if( !empty( $_SESSION[$uniqueId]["COUPON"] ) ) {
			$arParam["promo"] = $_SESSION[$uniqueId]["COUPON"];
		}
		
		$api = new Api(array(
            "action" => "request",
            "params" => $arParam
        ));
		
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
		
		$uniqueId = $this->arResult["COMPONENT_ID"].$this->arResult["CLUB_ID"].$this->arResult["ELEMENT"]["ID"];
		$actionName = $this->request->get("ACTION");
		$phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
		$phoneValue = "";
		if( !empty($this->request->get($phoneName)) ) {
			$phoneValue = $this->request->get($phoneName);
			$this->arResult["SMS_PHONE"] = $phoneValue;
		}
		if( $currentStep == 1 ) {
			if( !empty($actionName) ) {
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
					case "SEND_SMS": //Отправка СМС и, в случае успеха, переход на следующий шаг
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
					case "COUPON": //Отправка и применение купона
						$coupon = $this->request->get("COUPON");
						$subId = $this->request->get("SUB_ID");
						if( $coupon && $subId ) {
							$responseResult["RESPONSE"] = $this->checkCoupon( $coupon );
							
							if( empty($responseResult["RESPONSE"]["success"]) ) {
								if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
									$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
								} else {
									$responseResult["MESSAGE"] = "Купон не применен";
								}
								$responseResult["ERROR"] = true;
							} else {
								
								$month = [];
								if( !empty($responseResult["RESPONSE"]["data"]["result"]["result"][$subId]) ) {
									$month = $responseResult["RESPONSE"]["data"]["result"]["result"][$subId];
								}
								
								if( !isset($_SESSION[$uniqueId]) ) {
									$_SESSION[$uniqueId] = [];
									$_SESSION[$uniqueId]["COUPON"] = "";
									$_SESSION[$uniqueId]["DISCOUNTS"] = [];
								}
								
								foreach( $month as $item ) {
									if( $item["clubid"] == $this->arResult["CLUB_NUMBER"] ) {
										$_SESSION[$uniqueId]["COUPON"] = $coupon;
										$_SESSION[$uniqueId]["DISCOUNTS"]["SALE"] = $item["price"];
										if( !empty($item["prices"]) && $item["prices"]["month"] === 2 ) {
											$_SESSION[$uniqueId]["DISCOUNTS"]["SALE_TWO_MONTH"] = $item["prices"]["price"];
										}
										
										if( !empty($item["free"]) ) {
											$responseResult["ERROR"] = true;
											$responseResult["MESSAGE"] = (!empty($this->arParams["FREE_MESSAGE"])) ? $this->arParams["FREE_MESSAGE"] : "Бесплатный абонемент. Для верификации, мы спишем с карты небольшую сумму. Чтобы убедиться, что Вы человек, а не робот.";
										} else {
											$responseResult["ERROR"] = true;
											$responseResult["MESSAGE"] = "Ваш промокод применен";
										}
										break;
									}
								}
								
								if( empty($_SESSION[$uniqueId]["COUPON"]) ) {
									$responseResult["ERROR"] = true;
									$responseResult["MESSAGE"] = "Введен неверный промокод";
								}
							}
                    	} else {
							$responseResult["ERROR"] = true;
							$responseResult["MESSAGE"] = "Введите значение промокода";
						}
						return 1;
                    break;
				}
			}
		} else if( $currentStep == 2 ) { //Шаг 2, проверка кода из СМС и оформление абонемента
			if( !empty($actionName) ) {
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
								$responseResult["ERROR"] = true;
								$responseResult["MESSAGE"] = (!empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"])) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "Код введен неверно, повторите через 15 мин.";
							}
							
							if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
								$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
							} else {
								$responseResult["MESSAGE"] = "Неправильно введен код";
							}
							$responseResult["ERROR"] = true;
							
						} else {
							if( !empty( $responseResult["RESPONSE"]["data"]["result"]["result"]["formUrl"] ) ) {
								$responseResult["PAY_URL"] = $responseResult["RESPONSE"]["data"]["result"]["result"]["formUrl"];
							}
							if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST, "N")) {
                    			CFormResult::SetEvent($RESULT_ID);
                    			CFormResult::Mail($RESULT_ID);
                			}
                			return 3;
						}
						
						return 2;
						
					break;
				}
			}
		} else if( $currentStep == 3 ) { //Шаг 3, очистка формы, возвращение к шагу 1 и офорбражение модального окна с сообщение о завершении оформления абонемента
			$responseResult["ERROR"] = true;
			$responseResult["MESSAGE"] = "Ваш абонемент был успешно оформлен.";
			unset($_SESSION[$uniqueId]);
			$this->resetForm();
		}
		
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
			if( !empty($this->request["STEP"]) ) {
				$this->arResult["STEP"] = intval($this->request["STEP"]);
			}
			$this->arResult["PREV_STEP"] = $this->arResult["STEP"];
			
			$clubId = false;
			if( !empty($this->request["CLUB_ID"]) ) {
				$clubId = intval($this->request["CLUB_ID"]);
            } else if( !empty($this->arParams["CLUB_ID"]) ) {
				$clubId = intval($this->arParams["CLUB_ID"]);
			} else if( !empty($this->arParams["DEFAULT_CLUB_ID"]) ) {
				$clubId = intval($this->arParams["DEFAULT_CLUB_ID"]);
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
			/*if( empty($clubId) ) {
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
			}*/
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
			
			$this->arResult["OFERTA_TEXT"] = "";
			$siteSettings = Utils::getInfo();
			if( !empty($siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT']) ) {
				$this->arResult["OFERTA_TEXT"] = $siteSettings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'];
			}
			
			/*AJAX Actions*/
			if( !empty($this->request["COMPONENT_ID"]) && !empty($this->request["STEP"]) && $this->arResult["COMPONENT_ID"] == $this->request["COMPONENT_ID"] ) {
				$responseResult = ["ERROR" => false, "MESSAGE" => "", "RESPONSE" => ""];
				
				$this->arResult["STEP"] = $this->checkStep($this->arResult["STEP"], $responseResult);
				$this->arResult["RESPONSE"] = $responseResult;
				
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
				
				$uniqueId = $this->arResult["COMPONENT_ID"].$this->arResult["CLUB_ID"].$this->arResult["ELEMENT"]["ID"];
				unset($_SESSION[$uniqueId]);
				
				$this->includeComponentTemplate();
			}
			
		} else {
			echo $this->arResult["ERROR"];
		}
    }
}
?>