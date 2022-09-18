<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );

$APPLICATION->ShowAjaxHead();

$APPLICATION->IncludeComponent(
    "custom:form.abonement.ajax",
    "popup",
    array(
        "AJAX_MODE" => "N",
        "WEB_FORM_ID" => is_numeric($_POST["WEB_FORM_ID"])?$_POST["WEB_FORM_ID"]:Utils::GetFormIDBySID($_POST["WEB_FORM_ID"]),
        "ADD_ELEMENT_CHAIN" => "N",
        "ABONEMENT_CODE"=>$_POST["ABONEMENT_CODE"],
        "AJAX_ACTION"=>$_POST["AJAX_ACTION"],
        "FORM_TYPE"=>$_POST["FORM_TYPE"]
    ),
    false
);?>