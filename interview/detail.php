<?php
define('HIDE_BREADCRUMB', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);
define("PURPLE_GREY", true);
$GLOBALS["NO_INDEX"] = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!empty($_REQUEST["USER"])){
    $_SESSION["CLIENT_ID"]=$_REQUEST["USER"];

    $url =$_SERVER['REQUEST_URI'];
    $parts = parse_url($url);
    LocalRedirect($parts['path']);
    exit();
}

global $APPLICATION;
$APPLICATION->IncludeComponent("custom:interview", ".default", array(
    "INTERVIEW_ID"=>$_REQUEST["SECTION_ID"],
    "CLIENT_ID"=>$_SESSION["CLIENT_ID"]
), false);

unset($_SESSION["CLIENT_ID"]);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");