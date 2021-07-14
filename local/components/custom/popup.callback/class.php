<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;

class PopupCallbackComponent extends CBitrixComponent{
    
    function onPrepareComponentParams($arParams) {

        return $arParams;
    }

    private function setSession() {
        if (!isset($_SESSION["PM_SHOW"])) {
            $arSettings = Utils::getInfo();
    
            $_SESSION["PM_TIME"] = $arSettings["PROPERTIES"]["TIME_CALLBACK"]["VALUE"] * 1000;
            $_SESSION["PM_CURRENT_TIME"] = time();
            $_SESSION["PM_SHOW"] = "N";
            $_SESSION["PM_ACTIVE"] =  $arSettings["PROPERTIES"]["ACTIVE_CALLBACK_POPUP"]["VALUE"];
        }
    }

    private function getSession() {
        return array(
            "PM_TIME" => $_SESSION["PM_TIME"],
            "PM_CURRENT_TIME" => $_SESSION["PM_CURRENT_TIME"],
            "PM_LAST_TIME" => time(),
            "PM_SHOW" => $_SESSION["PM_SHOW"],
            "PM_ACTIVE" => $_SESSION["PM_ACTIVE"]
        );
    }

    private function setShowPopup() {

        $_SESSION["PM_SHOW"] = "Y";

        return array(
            "SUCCESS" => "Y"
        );
    }

    function executeComponent(){
        global $APPLICATION;

        Asset::getInstance()->addJs("/local/components/custom/popup.callback/script.js");
        $this->setSession();

        if ($this->request["ajax"]) {
            $data = array();

            switch ($this->request["action"]) {
                case "getSession";
                    $data = $this->getSession();
                    break;
                case "setShowPopup";
                    $data = $this->setShowPopup();
                    break;
            }

            $APPLICATION->RestartBuffer();
            echo json_encode($data);
            die();
        }
    }
}
?>