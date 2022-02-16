<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Page\Asset;

$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>

<!DOCTYPE html>
<html class="no-js" lang="ru">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
</html>
<head>
    <title>
        <? $APPLICATION->ShowTitle() ?>
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
<meta name="yandex-verification" content="3be504833b18c0ad" />
<meta name="google-site-verification" content="bXF84za5VozYsItcZs-HDDJhCtg6kCzQUbSi2ot70Fk" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta property="og:title" content="<?=$APPLICATION->ShowTitle()?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?=$_SERVER['REQUEST_URI']?>"/>
    <meta property="og:image" content="<?=$APPLICATION->ShowViewContent('inhead');?>"/>
    <meta property="og:description" content="<?=$APPLICATION->GetDirProperty("description");?>"/>
    <meta property="og:site_name" content="<?=$arInfoProps['OG_SITE_NAME']['VALUE']?>"/>
    
    <?Asset::getInstance()->addCSS(SITE_TEMPLATE_PATH . "/css/style.min.css?v1");?>
    <link rel="stylesheet" href="/local/templates/spiritfit/css/slick-lightbox.css">
    <link rel="stylesheet" href="/local/templates/spiritfit/css/custom.css">
    <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/libs.min.js'?>"></script>
    <script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/jquery.inputmask.min.js'?>"></script>
    <script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/slick-lightbox.min.js'?>"></script>
    <script defer type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/common.min.js'?>"></script>
    <script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/jquery.pjax.js'?>"></script>
<? if(isset($_GET['qwe'])): ?>
	<script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/script2.js'?>"></script>
<? else: ?>
	<script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/script.js?v6'?>"></script>
<? endif; ?>
    <script defer type="text/javascript" src="<?=SITE_TEMPLATE_PATH . '/js/bowser.js'?>"></script>

    <? $APPLICATION->ShowHead(); ?>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-N3VHBWW');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3VHBWW"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
    ?>
        <link rel="canonical" href="<?= 'https://' . $_SERVER['HTTP_HOST'] . $uri_parts[0]; ?>"/>
    
</head>

<body>
    <? $APPLICATION->ShowPanel(); ?>

    <!-- VK counter -->
    <script defer type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?160",t.onload=function(){VK.Retargeting.Init("VK-RTRG-333642-hybZ4"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-333642-hybZ4" style="position:fixed; left:-999px;" alt=""/></noscript>
 
    <div class="page-wrapper">
        <header class="header">
            <? 
                $settings = Utils::getInfo(); 
            ?>
            <a class="header__logo js-pjax-link" href="/">              
                <img alt="Spirit. Fitness" class="header__logo-img" src="<?= $settings["PROPERTIES"]["SVG"]["src"] ?>" alt="logo">
            </a>
            <a class="header__logo--white" href="/">
                <img alt="Spirit. Fitness" class="header__logo-img" src="<?= $settings["PROPERTIES"]["SVG_WHITE"]["src"] ?>" alt="logo">
            </a>
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu", 
                "main-menu", 
                array(
                    "ROOT_MENU_TYPE" => "top",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "top",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "COMPONENT_TEMPLATE" => "main-menu"
                ),
                false
            );?>
            <div class="header__burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
           
        </header>
        <?
        // задача становится периодической. не удалять. менять true/false, пути картинок и url ссылки
        if(true){?>
            <div class="header-banner">
                <a class="header-banner__href" href="/abonement/">
                    <img class="header-banner__img header-banner__img--small" src="/upload/header-img/2021-02-10/375x55.gif">
                    <img class="header-banner__img header-banner__img--medium" src="/upload/header-img/2021-02-10/1400x55.gif">
                    <img class="header-banner__img header-banner__img--large" src="/upload/header-img/2021-02-10/1920x55.gif">
                </a>
            </div>
        <?}?>
           
        
        <div class="content-wrapper">
            <main id="js-pjax-container">
                <?if (!defined('HIDE_BREADCRUMB')):?>
               <div class="block__section-breadcrumb">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "custom",
                        array(
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "s1"
                        )
                    ); ?>
                </div>
                <?endif;?>