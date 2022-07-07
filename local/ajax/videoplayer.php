<?php
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );

$APPLICATION->ShowAjaxHead();

$APPLICATION->IncludeComponent(
    'custom:videoplayer',
    '',
    array(
        "VIDEOFILE"=>$_GET['VIDEOFILE'],
        "POSTER"=>$_GET["POSTER"]
    ),
    false
);