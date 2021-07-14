<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class FormAuthorizationForVideos extends CBitrixComponent{ 

    private $phone = false;

    private function checkStep() {
        $step = $this->request->get('step');
        
        if (empty($step)) {
            return 1;
        }

        if ($this->request->get('ajax_send')) {
            $this->arResult['SUBMIT'] = "Y";
            $this->arResult['PHONE'] = $this->request->get("form_phone_number");
        }

        if ($step == 1 && $this->request->get('ajax_send')) { 
            if (!empty($this->arResult['PHONE'])) {
                return $this->checkUserPhoneIn1C($this->arResult['PHONE']);
                
            } else {
                $this->arResult['ERROR'] = "Не введен номер телефона";
            }

            if ($this->arResult['ERROR'])  {
                return 1;
            }  
        }
    }

    private function checkUserPhoneIn1C($phone) {
        if($_COOKIE['video_token']){
            $this->arResult['auth_token'] = $_COOKIE['video_token'];
            return 2;
        } else {  
            $phone = preg_replace('![^0-9]+!', '', $phone);
            $api = new Api(array(
                "action" => "request_authorization_code",
                "params" => array(
                    "phone" => $phone,
                )
            ));

            $result = $api->result();
            if(!$result["success"] && $result["data"]['result']['errorCode']) {
                if ($result["data"]['result']['errorCode'] == 1 || $result["data"]['result']['errorCode'] == 2) {
                    $pageName = '';
                    if ($this->arResult["INFO"]["PROPERTIES"]["LINK_IN_MISTAKE_TEXT"]['~DESCRIPTION']) {
                        $pageName = $this->arResult["INFO"]["PROPERTIES"]["LINK_IN_MISTAKE_TEXT"]['~DESCRIPTION'];
                    } else {
                        $pageName = 'страницу';
                    }
                    $pageLink = '';
                    if ($this->arResult["INFO"]["PROPERTIES"]["LINK_IN_MISTAKE_TEXT"]['~VALUE']) {
                        $pageLink = $this->arResult["INFO"]["PROPERTIES"]["LINK_IN_MISTAKE_TEXT"]['~VALUE'];
                    } else {
                        $pageLink = '/abonement/';
                    }
                    $errorMessage = $this->arResult["INFO"]["PROPERTIES"]["TEXT_AUTHORIZATION_FOR_VIDEO_STEP2"]["~VALUE"]['TEXT'].' <a href="'. $pageLink .'" target="_blank">'. $pageName .'</a>';
                    $this->arResult['ERROR'] = $errorMessage;
                } elseif ($result["data"]['result']['errorCode'] == 3 || $result["data"]['result']['errorCode'] == 4 || $result["data"]['result']['errorCode'] == 5) {
                    $this->arResult['ERROR'] = $result["data"]['result']['userMessage'];
                }
                return 1;
            } elseif ($result["data"]['result']['result']['token']) {
                $this->arResult['auth_token'] = $result["data"]['result']['result']['token'];
                return 2;
            }
        }
    }

    private function checkSms($phone){
        global $APPLICATION;

        $arParam["phone"] = $phone;
        $arParam["code"] = $this->request->get('code');
        
        $api = new Api(array(
            "action" => "check_code",
            "params" => $arParam
        ));

        $result = $api->result();

        //if(!$result["success"]) {
            $this->arResult["ERROR"] = "Не правильно введен код из смс";
        //}
    }

    function executeComponent(){
        $this->arResult["INFO"] = Utils::getInfo();
                
        switch ($this->checkStep()) {
            case 1:
                $this->includeComponentTemplate();
                break;
            case 2:
                global $APPLICATION;
                $APPLICATION->RestartBuffer();
                if($this->arResult['auth_token']){
                    echo '<div><input type="hidden" name="auth_token" value="'.$this->arResult['auth_token'].'"></div>';
                } 
                die();  
                break;
        }
    }
}
?>