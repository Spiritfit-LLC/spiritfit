<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (empty($_SESSION['INVOICE_ID'])):?>
    <?php

    $url =$_SERVER['REQUEST_URI'];
    $parts = parse_url($url);

    if (empty($parts['query'])){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
        require $_SERVER['DOCUMENT_ROOT'].'/404.php';
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    }

    parse_str($parts['query'], $query);


    if (empty($query['InvoiceID'])){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
        require $_SERVER['DOCUMENT_ROOT'].'/404.php';
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    }


    $_SESSION['INVOICE_ID']=$query['InvoiceID'];
    LocalRedirect($parts['path']);
    ?>
<?endif;?>

<?php
use \Bitrix\Main\Page\Asset;

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');

$INVOICE_ID=$_SESSION['INVOICE_ID'];
unset($_SESSION['INVOICE_ID']);
?>

<?php
$APPLICATION->IncludeComponent('custom:form.abonement.emailorder',
    '',
    Array(
        'INVOICE_ID'=>$INVOICE_ID,
        "AJAX_MODE" => "N",
    ), false);
?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
