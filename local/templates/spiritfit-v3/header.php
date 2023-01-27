<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
use \Bitrix\Main\Page\Asset;


global $settings;
$settings = Utils::getInfo();
$clubs = Clubs::getList();
?>
<!DOCTYPE html>
<html class="no-js" lang="ru">
<head>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="yandex-verification" content="3be504833b18c0ad" />
    <meta name="google-site-verification" content="bXF84za5VozYsItcZs-HDDJhCtg6kCzQUbSi2ot70Fk" />
    <meta property="og:title" content="<?=$APPLICATION->ShowTitle()?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?=$_SERVER['REQUEST_URI']?>"/>
    <meta property="og:image" content="<?=$APPLICATION->ShowViewContent('inhead');?>"/>
    <meta property="og:description" content="<?=$APPLICATION->GetDirProperty("description");?>"/>
    <meta property="og:site_name" content="<?=$settings['PROPERTIES']['OG_SITE_NAME']['VALUE']?>"/>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <? if( !empty($GLOBALS["NO_INDEX"]) ) { ?>
        <meta name="robots" content="noindex" />
    <? } ?>
    <?



        //LIBS
        Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/jquery-3.6.1.min.js");
        Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/yall/yall.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/select2/select2.css");
        Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/select2/select2.min.js");


        Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/script.js");

        Asset::getInstance()->addCSS(SITE_TEMPLATE_PATH . "/css/style.css");
    ?>
    <?$APPLICATION->ShowHead();?>

    <?php if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse')===false):?>
    <!-- Google Tag Manager -->
    <script data-skip-moving="true" async>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N3VHBWW');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3VHBWW"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <script src="<?=SITE_TEMPLATE_PATH."/js/sourcebuster.min.js"?>" id="sbjs"></script>
    <script>
        sbjs.init();
    </script>
    <?endif;?>
    <?php
    $page = $_SERVER['REQUEST_URI'];
    if(strpos($_SERVER['REQUEST_URI'], '?')){
        $page = explode('?', $page);
        $page = $page[0];
    }
    ?>
    <link rel="canonical" href="<?= 'https://' . $_SERVER['HTTP_HOST'] . $page; ?>"/>
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>
</head>
<body class="b-page">
<!-- VK counter -->
<script async type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?160",t.onload=function(){VK.Retargeting.Init("VK-RTRG-333642-hybZ4"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-333642-hybZ4" style="position:fixed; left:-999px;" alt=""/></noscript>
<?if (!defined("HIDE_BANNER")):?>
<?
//$APPLICATION->IncludeComponent("custom:promocode.banner", "new-year", array("BANNER_DISCOUNT" => "", "BANNER_TIME" => 3000,"PROMOCODE" => 0), false);
?>
<?endif;?>

<?if ($USER->IsAdmin()):?>
<div class="admin-panel">
    <?$APPLICATION->ShowPanel()?>
</div>
<?endif;?>
<?if (!defined("HIDE_HEADER")):?>
<header class="b-header">
    <div class="content-center">
        <div class="b-header__content">
            <a class="b-header__logo-holder visible-desktop" href="/">
                <img class="b-header__logo-img" src="<?=$settings["PROPERTIES"]["SVG_WHITE"]["src"]?>" alt="Spirit Fitness" title=""/>
            </a>
            <a class="b-header__logo-holder hidden-desktop" href="/">
                <img class="b-header__logo-img" src="<?= CFile::GetPath($settings["PROPERTIES"]["SVG_WHITE_MINI"]['VALUE'] );?>" alt="Spirit Fitness" title="" />
            </a>
            <a class="phone-btn hidden-desktop" data-position="mobile-header" href="tel:<?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?>" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/icons/icon-phone.svg'?>')"><?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?></a>
            <div class="b-header__nav">
                <nav class="b-top-menu">
                    <button class="b-top-menu__toggle hidden-desktop"
                            data-layer="true"
                            data-layercategory="UX"
                            data-layeraction="clickHamburgerButton">Меню</button>
                    <div class="b-top-menu__holder select2-black">
                        <div class="b-club-search active hidden-desktop">
                            <label class="b-club-search__label" for="#club-search">Найти клуб</label>
                            <select class="select2" data-placeholder="Название клуба" id="club-search">
                                <?foreach ($clubs as $club):?>
                                    <option value="/clubs/<?=$club["CODE"]?>/"><?=$club["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                        </div>
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

                        <?if (!PersonalUtils::IsClient()):?>
                            <a class="b-top-menu__btn button-outline hidden-desktop trial-training-btn" href="/abonement/probnaya-trenirovka-/#js-pjax-container"
                               data-layer="true"
                               data-layercategory="UX"
                               data-layeraction="clickTrialWorkoutButton"
                               data-layerlabel="burgerMenu" style="margin-bottom: 20px;">Пробная тренировка</a>
                            <a class="b-top-menu__btn button hidden-desktop" href="/abonement/"
                               data-layer="true"
                               data-layercategory="UX"
                               data-layeraction="clickBuyAbonementButton"
                               data-layerlabel="burgerMenu">Купить абонемент</a>
                        <?endif;?>

                        <a href="/personal/" class="b-top-menu__link hidden-desktop auth-btn"
                           data-layer="true"
                           data-layercategory="UX"
                           data-layeraction="clickLKbutton">
                            <?
                            global $USER;
                            if (!$USER->IsAuthorized()):?>
                                <div class="personal-btn__icon">
                                    <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/exit-btn.svg');?>
                                </div>
                                Личный кабинет
                            <?endif;?>
                        </a>
                    </div>
                </nav>
            </div>
            <?if (!PersonalUtils::IsClient()):?>
                <a class="b-top-menu__abonement-btn button hidden-tablet hidden-phone" href="/abonement/"
                   data-layer="true"
                   data-layercategory="UX"
                   data-layeraction="clickBuyAbonementButton"
                   data-layerlabel="header">Купить абонемент</a>
            <?endif;?>
            <div class="b-top-menu__right hidden-tablet hidden-phone">
                <a class="b-header-phone phone-btn main-phone-btn" data-position="header" href="tel:<?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?>"
                style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/icons/icon-phone.svg'?>')"><?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?></a>
                <div>
                    <a href="/personal/" class="personal-btn is-hide-mobile header-personal-btn"
                       data-layer="true"
                       data-layercategory="UX"
                       data-layeraction="clickLKbutton">
                        <?
                        if ($USER->IsAuthorized()):?>
                            Личный кабинет
                            <div class="personal-btn__icon">
                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/profile_icon.svg');?>
                            </div>
                        <?else:?>
                            Личный кабинет
                            <div class="personal-btn__icon">
                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/exit-btn.svg');?>
                            </div>
                        <?endif;?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<?endif;?>
<main class="b-page__main <?=(defined('HOLDER_CLASS') ? HOLDER_CLASS : '')?> <?if (defined('HIDE_HEADER')) echo "without-header";?>" role="main">
    <?if (!defined("HIDE_BREADCRUMB")):?>
        <div class="b-page__heading">
            <div class="content-center <?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>">
                <div class="b-page__heading-inner <?if (defined('HIDE_SLIDER')) echo "black"?>">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "custom",
                        array(
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "s1"
                        )
                    ); ?>
                    <?if (defined('PAGE_TITLE')):?>
                    <h1 class="b-page__title <?if (defined('HIDE_SLIDER')) echo "black"?>
                        <?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>
                        <?if (defined('H1_BIG')) echo "title-big"?>"><?=PAGE_TITLE?></h1>
                    <?else:?>
                    <h1 class="b-page__title <?if (defined('HIDE_SLIDER')) echo "black"?>
                        <?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>
                        <?if (defined('H1_BIG')) echo "title-big"?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                    <?endif;?>
                </div>
            </div>
        </div>
    <?endif?>
    <?if (!defined('HIDE_SLIDER')):?>
        <? if (strpos($page, '/clubs/') !== false) {
            $GLOBALS['arFilterSlider'] = [
                [
                    'LOGIC' => 'AND',
                    ['PROPERTY_BANNER_PAGES' => $page],
                    ['PROPERTY_SITE' => 42],
                    ['!PROPERTY_BANNER_PAGES_HIDE' => $page]
                ]
            ];
        } else {
            $GLOBALS['arFilterSlider'] = [
                [
                    'LOGIC' => 'AND',
                    [
                        'LOGIC' => 'OR',
                        ['PROPERTY_BANNER_PAGES' => false],
                        ['PROPERTY_BANNER_PAGES' => $page],
                    ],
                    ['!PROPERTY_BANNER_PAGES_HIDE' => $page],
                    [
                        ['PROPERTY_SITE' => 42]
                    ]
                ]
            ];
        } ?>
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "slider",
            Array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ADD_SECTIONS_CHAIN" => "N",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "N",
                "DISPLAY_PICTURE" => "N",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "FIELD_CODE" => array("", ""),
                "FILTER_NAME" => "arFilterSlider",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => "2",
                "IBLOCK_TYPE" => "content",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "INCLUDE_SUBSECTIONS" => "Y",
                "MESSAGE_404" => "",
                "NEWS_COUNT" => "10",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Новости",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("BANNER_BTN_TEXT", "BANNER_BTN_LINK", "BANNER_TITLE"),
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SORT_BY1" => "SORT",
                "SORT_BY2" => "ID",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "DESC",
                "STRICT_SECTION_CHECK" => "N"
            )
        );
        ?>
    <?endif;?>
