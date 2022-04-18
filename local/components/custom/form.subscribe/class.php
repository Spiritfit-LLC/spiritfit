<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	
	use \Bitrix\Main\Loader;
	
	class FormCallbackComponent extends CBitrixComponent {
		
		function onPrepareComponentParams($arParams){
			if( !isset($arParams["FORM_TYPE"]) ) {
				$this->arResult["ERROR"] = "Не выбран тип формы";
			}
			if( !isset($arParams["ACTION_TYPE"]) ) {
				$this->arResult["ERROR"] = "Не выбран тип события отправки в 1С";
			}
			
			return $arParams;
		}

		private function doSend( $email ) {
			
			$metricaArr = [
				"form_text_38" => "src",
				"form_text_39" => "mdm",
				"form_text_40" => "cmp",
				"form_text_41" => "cnt",
				"form_text_42" => "trm",
			];

			$arTo = [];
			$arTo["client_id"] = $this->request->getCookieRaw("_ga");
			$arTo["type"] = $this->arParams["FORM_TYPE"];
			$arTo["email"] = $email;

			foreach( $metricaArr as $k => $v ) {
				$r = $this->request->get($k);
				if( !empty($r) ) {
					$arTo[$v] = $r;
				}
			}

			$requestType = $this->arParams["ACTION_TYPE"];

			$api = new Api([
				"action" => $requestType,
				"params" => $fromFields
			]);
			return $api->result();
		}
		
		function executeComponent() {
			if( empty($this->arResult["ERROR"]) ) {
				
        		Loader::IncludeModule("iblock");

        		$this->arResult["WEB_FORM_ID"] = 1;
        		$this->arResult["COMPONENT_ID"] = CAjax::GetComponentID($this->GetName(), $this->GetTemplate(), '');
        		$this->arResult["EMAIL"] = "";

        		if( !empty($this->request["COMPONENT_ID"]) && $this->arResult["COMPONENT_ID"] == $this->request["COMPONENT_ID"] ) {
        			$responseResult = ["ERROR" => false, "MESSAGE" => "", "RESPONSE" => ""];
        			$email = filter_input(INPUT_POST, 'email');
        			$agreement = filter_input(INPUT_POST, 'agreement');
        			if( !empty($email) && !empty($agreement) ) {
        				$this->arResult["EMAIL"] = $email;
        				$responseResult["RESPONSE"] = $this->doSend($email);
        				if( empty($responseResult["RESPONSE"]) || empty($responseResult["RESPONSE"]["success"]) || !empty($responseResult["RESPONSE"]["data"]["result"]["errorCode"]) ) {
							if( !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ) {
								$responseResult["MESSAGE"] = $responseResult["RESPONSE"]["data"]["result"]["userMessage"];
							} else {
								$responseResult["MESSAGE"] = "Ошибка отправки запроса";
							}
							$responseResult["ERROR"] = true;
						} else {
							$responseResult["MESSAGE"] = !empty($responseResult["RESPONSE"]["data"]["result"]["userMessage"]) ? $responseResult["RESPONSE"]["data"]["result"]["userMessage"] : "Вы успешно подписались на рассылку";
						}
        			} else {
        				$responseResult["MESSAGE"] = "Укажите адрес электронной почты и дайте согласие с политикой безопасности";
        				$responseResult["ERROR"] = true;
        			}
        			$this->arResult["RESPONSE"] = $responseResult;
        		}

				$this->includeComponentTemplate();
				
			} else {
				echo $this->arResult["ERROR"];
			}
		}
	}
?>