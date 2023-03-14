<?php
define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');
define('HIDE_SLIDER', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );
global $APPLICATION;


if (empty($_REQUEST["SHORT_CODE"])){
    $APPLICATION->RestartBuffer();
    require $_SERVER['DOCUMENT_ROOT'].'/404.php';
}
else{
    if ($real_link = ClckApi::getRealClick($_REQUEST["SHORT_CODE"])){
        header("Location: ".$real_link);
        die();
    }
    else{
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'].'/404.php';
    }
}