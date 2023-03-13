<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;


define("AJAX_COMPONENT_SALT", "pdWPPnnv");

class AjaxComponent extends CBitrixComponent{
    function executeComponent()
    {
        $this->arResult["SIGNED_PARAMETERS"] = \Bitrix\Main\Component\ParameterSigner::signParameters(AJAX_COMPONENT_SALT, $this->arParams);
        $this->IncludeComponentTemplate();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $arParams = \Bitrix\Main\Component\ParameterSigner::unsignParameters(AJAX_COMPONENT_SALT, $_REQUEST["signed"]);

    global $APPLICATION;
    $APPLICATION->ShowAjaxHead();

    $APPLICATION->IncludeComponent(
        $arParams["COMPONENT"],
        $arParams["COMPONENT_TEMPLATE"],
        $arParams["COMPONENT_PARAMS"]
    );
}