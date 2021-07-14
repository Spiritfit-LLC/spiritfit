<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
                $this->arResult["arAnswers"]["club"][0]['ITEMS'] = Utils::getClubsForm($this->request->get("club"));
            }
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

        if ($this->request->get('ajax_send')) {
            $this->arResult['SUBMIT'] = "Y";
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
            $response = $this->checkSms();

            if($response["success"]){
                if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST)) {
                    CFormResult::SetEvent($RESULT_ID);
                    CFormResult::Mail($RESULT_ID);
                }

                $arParam = $this->getFormatFields();
                $arParam["type"] = (int)$type;

                $api = new Api(array(
                    "action" => "web_site_contact",
                    "params" => $arParam
                ));

                $result = $api->result();
    
                if ($result['data']['result']['errorCode'] == 0) {
                    return 3;
                } else {
                    $this->arResult["ERROR"] = "Не правильно выбран клуб";
                    return 2;
                }
            } else {
                $this->arResult["ERROR"] = "Не правильно введен код";
                return 2;
            }
        }
        
        if ($step == 100 && $this->request->get('ajax_send')) {
            if($RESULT_ID = CFormResult::Add($this->arParams["WEB_FORM_ID"], $_REQUEST)) {
                CFormResult::SetEvent($RESULT_ID);
                if (CFormResult::Mail($RESULT_ID)) 
                {
                    echo "Почтовое событие успешно создано.";
                }
                else // ошибка
                {
                    global $strError;
                    echo $strError;
                }
            }
            return 100;
        }
        
        if ($step == 150 && $this->request->get('ajax_send')) {
            return 150;
        }
    }

    private function sendSms($phone = null) {
        if($phone == null){
            $phoneName = "form_" . $this->arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $this->arResult["arAnswers"]["phone"]['0']["ID"];
            $phone = $this->request->get($phoneName);
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

    private function checkSms(){
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

    function executeComponent(){
        Loader::IncludeModule("form");
        $this->getFields();
        $this->arResult["INFO"] = Utils::getInfo();
        
        switch ($this->checkStep()) {
            case 100:
                $this->includeComponentTemplate('success');
                break;
            case 2:
                if (empty($this->arResult["ERROR"])) {
                    $this->sendSms();
                }
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