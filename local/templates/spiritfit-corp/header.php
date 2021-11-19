<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Page\Asset;

$arInfoProps = Utils::getInfo()['PROPERTIES'];
global $settings;
$settings = Utils::getInfo();
$page = $APPLICATION->GetCurPage();
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
    <meta property="og:site_name" content="<?=$arInfoProps['OG_SITE_NAME']['VALUE']?>"/>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <script defer type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <?
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery/jquery.min.js");

    // old
    Asset::getInstance()->addCSS(SITE_TEMPLATE_PATH . "/css/style.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/select2/select2.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/perfect-scrollbar/perfect-scrollbar.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/jquery.ui/jquery-ui.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/vendor/fancybox3/jquery.fancybox.min.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/global.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/custom.css");
    
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/sameheight/jquery.sameheight.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery.maskedinput/jquery.maskedinput.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/select2/select2.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/perfect-scrollbar/perfect-scrollbar.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/libs.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/jquery.inputmask.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/common.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/jquery.pjax.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery.ui/jquery-ui.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/fancybox3/jquery.fancybox.min.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/nicescroll/jquery.nicescroll.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/bowser.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/script.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/global.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/blocks.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/form-standart.js");
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/js/custom.js");
	
    ?>
    
    <? $APPLICATION->ShowHead(); ?>
    <?php
        $page = $_SERVER['REQUEST_URI'];
        if(strpos($_SERVER['REQUEST_URI'], '?')){
            $page = explode('?', $page);
            $page = $page[0];
        }
    ?>
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
	
	<script src="<?=SITE_TEMPLATE_PATH."/js/sourcebuster.min.js"?>" id="sbjs"></script>
	<script>
		sbjs.init();
	</script>
    <link rel="canonical" href="<?= 'https://' . $_SERVER['HTTP_HOST'] . $page; ?>"/>
</head>
<? $APPLICATION->ShowPanel(); ?>
<body class="b-page">
    <header class="b-header">
        <div class="content-center">
            <div class="b-header__content"><a class="b-header__logo-holder" href="https://spiritfit.ru/"><img class="b-header__logo-img"
                        src="<?= $settings["PROPERTIES"]["SVG_WHITE"]["src"] ?>" alt="Spirit Fitness" title="" /></a>

                <div class="b-header__mobile-clubs is-hide-desktop">
                    <form action="/clubs/" method="get"> 
                        <div class="b-club-search__input-wrap">
                            <input class="b-club-search__input" type="text" id="club-search" name="club" placeholder="Название клуба" />
                            <button class="b-club-search__submit" type="submit">Найти</button>
                        </div>
                    </form>
                </div>
                <div class="b-header__nav">
                    <nav class="b-top-menu">
                        <button class="b-top-menu__toggle is-hide-desktop">Меню</button>
                        <div class="b-top-menu__holder">
							
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
                            
                            <a class="b-top-menu__btn button-outline is-hide-desktop js-form-abonement" href="javascript:;"
                                data-webform-fancybox="./form-request.html" data-type="trial" data-abonementid="226" data-abonementcode="probnaya-trenirovka" data-code1c="pb">Пробная тренировка</a>
                        </div>
                    </nav>
                </div><a class="b-header-phone" href="tel:<?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?>"><?=$settings["PROPERTIES"]["PHONE"]["VALUE"]?></a><a
                    class="b-header__btn button-outline is-hide-mobile js-form-abonement" href="#" data-type="trial" data-abonementid="226" data-abonementcode="probnaya-trenirovka" data-code1c="pb">Пробная тренировка</a>
            </div>
        </div>
    </header>
    <main class="b-page__main" role="main">
			<?
				$GLOBALS['arFilterSlider'] = [
					[
						'LOGIC' => 'AND',
						[
							'LOGIC' => 'OR',
							['PROPERTY_BANNER_PAGES' => false],
							['PROPERTY_BANNER_PAGES' => $page],
						],
						[
							['PROPERTY_SITE' => 43]
						]
					]
				];
				
				$APPLICATION->IncludeComponent(
                	"bitrix:news.list",
                	"slider",
                	[
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
                    	"FIELD_CODE" => ["", ""],
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
                    	"PROPERTY_CODE" => ["BANNER_BTN_TEXT", "BANNER_BTN_LINK"],
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
                	]
            	);
			?>
        <? if($page != '/') {
        ?>
            <div class="b-page__heading <?=(defined('BREADCRUMB_H1_ABSOLUTE') ? 'b-page__heading_absolute' : '')?>">
                <div class="content-center">
                    <div class="b-page__heading-inner">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:breadcrumb",
                            "custom",
                            array(
                                "START_FROM" => "0",
                                "PATH" => "",
                                "SITE_ID" => "s1"
                            )
                        ); ?>
                        <? if(!defined('H1_HIDE')){ ?>
                            <h1 class="b-page__title"><?=$APPLICATION->ShowTitle(false)?></h1>
                        <? } ?>
                    </div>
                </div>
            </div>
        <? } ?>