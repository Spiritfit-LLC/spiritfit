<?php
define('HIDE_BREADCRUMB', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);


define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

$GLOBALS["NO_INDEX"] = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (empty($_SESSION["ID"]) && !empty($_REQUEST["ID"])){
    $_SESSION["ID"]=$_REQUEST["ID"];

    $url =$_SERVER['REQUEST_URI'];
    $parts = parse_url($url);
    LocalRedirect($parts['path']);
    exit();
}
elseif (empty($_SESSION["ID"]) && empty($_REQUEST["ID"])){
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/404.php';
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    exit();
}
$CLIENT_ID=$_SESSION["ID"];
unset($_SESSION["ID"]);

global $APPLICATION;
$APPLICATION->IncludeComponent("custom:client.auth", "", array(
    "CLIENT_ID"=>$CLIENT_ID
), false);



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");