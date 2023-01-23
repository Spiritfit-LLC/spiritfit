<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/map-main.css");
?>

<?php
$GLOBALS['arUtpFilter'] =
    [
        'ID' => $arResult["PROPERTIES"]["CLUB_UTP"]["VALUE"],
    ];
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "utp.main",
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
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", ""),
        "FILTER_NAME" => "arUtpFilter",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => Utils::GetIBlockIDBySID("UTP_CLUB"),
        "IBLOCK_TYPE" => "-",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "",
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
        "PROPERTY_CODE" => array("", ""),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "",
        "STRICT_SECTION_CHECK" => "N"
    )
);?>



<?php
echo $APPLICATION->AddBufferContent("showAbonementTitle");
?>

<?
$ABONEMENT_RESULT=$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "abonement.club",
    Array(
        "CLUB_ID"=>$arResult["ID"],
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "/abonement/#CODE#/".$arResult["PROPERTIES"]["NUMBER"]["VALUE"].'/',
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("CODE", "NAME", "PREVIEW_PICTURE", "IBLOCK_SECTION_CODE"),
        "FILTER_NAME" => "arAbonementFilter",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "9",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "",
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
        "PROPERTY_CODE" => array("BASE_PRICE", "INCLUDE", "ADDITIONAL_CLASS", "PRICE_SIGN_DETAIL", "HIDDEN", "FOR_PRESENT", "PRICE", "TITLE", "CARD_BASE_PRICE"),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "",
        "STRICT_SECTION_CHECK" => "N"
    ), false, false, true
);?>
<?php
if (count($ABONEMENT_RESULT["ELEMENTS"])>0){
    $APPLICATION->SetPageProperty("SHOW_ABONEMENT_TITLE", true);
}
else{
    $APPLICATION->SetPageProperty("SHOW_ABONEMENT_TITLE", false);
}

function showAbonementTitle(){
    global $APPLICATION;
    if ($APPLICATION->GetPageProperty("SHOW_ABONEMENT_TITLE")){
        ob_start();?>
        <div class="content-center" id="abonements">
            <div class="b-section__title">
                <h2>Абонементы</h2>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
?>

<div class="section-margin-top">
    <? if( (empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['SHOW_FORM']['VALUE']))
        || (!empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['HIDE_LINK_FORM']['VALUE'])) ) :?>
        <? if($arResult["PROPERTIES"]["SOON"]["VALUE"] == 'Y'):?>
            <div id="form-request">
                <?
                $APPLICATION->IncludeComponent(
                    "custom:form.request.new",
                    "on.page.block",
                    array(
                        "AJAX_MODE" => "N",
                        "COMPONENT_TEMPLATE" => "on.page.block",
                        "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                        "WEB_FORM_FIELDS" => array(
                            0 => "name",
                            1 => "phone",
                            2 => "email",
                            3 => "personaldata",
                            4 => "rules",
                            5 => "privacy",
                        ),
                        "FORM_TYPE" => $arResult["FORM_TYPE"],
                        "CLUB_ID" => $arResult["ID"],
                        "TEXT_FORM" => $arResult["FORM_TITLE"],
                    ),
                    false);
                ?>
            </div>
        <?else:?>
            <div id="form-request">
                <?
                $APPLICATION->IncludeComponent(
                    "custom:form.request.new",
                    "on.page.block",
                    array(
                        "AJAX_MODE" => "N",
                        "COMPONENT_TEMPLATE" => "on.page.block",
                        "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                        "WEB_FORM_FIELDS" => array(
                            0 => "name",
                            1 => "phone",
                            2 => "email",
                            3 => "personaldata",
                            4 => "rules",
                            5 => "privacy",
                        ),
                        "FORM_TYPE" => $arResult["FORM_TYPE"],
                        "TEXT_FORM" => $arResult["FORM_TITLE"],
                        "CLUB_ID"=>$arResult["ID"],
                    ),
                    false);
                ?>
            </div>
        <?endif;?>
    <?endif;?>
</div>

<? if(!empty($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"])){
    $photoCount = count($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"]) - 1;

    ?>
    <section class="b-image-plate-block b-image-plate-block_simple-mobile section-margin-top" id="club-about">
        <div class="content-center">
            <div class="b-image-plate-block__content">
                <div class="b-image-plate-block__slider-nav">
                </div>
                <div class="b-image-plate-block__img-holder b-image-plate-block__img-holder_slider">
                    <? foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"] as $photo): ?>
                        <div class="b-image-plate-block__slide">
                            <img class="b-image-plate-block__slide-img" data-lazy="<?=$photo["SRC_1280"]?>" alt="" role="presentation" />
                        </div>
                    <? endforeach; ?>
                </div>

                <div class="b-image-plate-block__text-content text-center">
                    <div class="b-image-plate-block__text-content-inner">
                        <div class="b-image-plate-block__text">
                            <?
                            $i = 0;
                            while ($i <= $photoCount) {
                                $photoText = $arResult["PROPERTIES"]['PHOTO_DESC']['~VALUE'][$i];
                                if(!empty($photoText['TEXT'])){ ?>
                                    <div class="b-image-plate-block__text-item">
                                        <h2 class="slide-text-title"><?=$arResult["PROPERTIES"]['PHOTO_DESC']['~DESCRIPTION'][$i]?></h2>
                                        <div class="b-image-plate-block__text-item-wrap">
                                            <?=$photoText['TEXT']?>
                                        </div>
                                    </div>
                                <? }else{ ?>
                                    <div class="b-image-plate-block__text-item"></div>
                                <? }
                                $i++;
                            } ?>
                        </div>
                        <? if ($arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]){ ?>
                            <a class="b-image-plate-block__btn button visible-desktop" href="<?= $arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"] ?>">Открыть 3D тур</a>
                        <? } ?>
                        <?if ($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO']['VALUE']):?>
                            <a class="b-image-plate-block__btn button hidden-desktop"
                               href="#show-club-btn"
                               data-src="<?=CFile::GetPath($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO']['VALUE'])?>"
                               data-title="<?=$arResult['PROPERTIES']['AJAX_MOBILE_VIDEO_TITLE']['VALUE']?>"
                               data-poster="<?=CFile::GetPath($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO_POSTER']['VALUE'])?>">посмотреть клуб</a>
                        <?endif;?>
                    </div>
                </div>

            </div>
        </div>
        <?if ($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO']['VALUE']):?>
        <div class="club-video-container hidden-desktop">
            <div class="club-video-closer closer white" onclick="close_club_video()">
                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
            </div>
            <div class="club-video"></div>
        </div>
        <?endif;?>
    </section>
<? } ?>
<?if (!empty($arResult["PROPERTIES"]["TEAM"]["ITEMS"])):?>
<div class="content-center">
    <div class="b-section__title">
        <h2>Команда</h2>
    </div>
</div>
<div class="b-treners blockitem">
    <div class="content-center">
        <div class="b-treners__wrapper">
            <? foreach($arResult["PROPERTIES"]["TEAM"]["ITEMS"] as $ITEM):?>
                <div class="b-twoside-card b-treners__item">
                    <div class="b-twoside-card__inner">
                        <div class="b-twoside-card__content">
                            <?if (!empty($ITEM["PROPERTIES"]["VIDEO"]["VALUE"])):?>
                                <video autoplay muted loop playsinline class="b-twoside-card__image lazy" poster="<?=SITE_TEMPLATE_PATH.'/img/video-default-preloader.gif'?>">
                                    <?$path=CFile::GetPath($ITEM["PROPERTIES"]["VIDEO"]["VALUE"])?>
                                    <source data-src="<?=$path?>" type="video/<?=pathinfo($path, PATHINFO_EXTENSION)?>">
                                </video>
                            <?else:?>
                            <div class="b-twoside-card__image lazy-bg" data-src="<?=$ITEM["PICTURE"]?>"></div>
                            <?endif;?>
                            <div class="b-twoside-card__name">
                                <?=str_replace(" ", "<br/>", $ITEM["NAME"])?>
                            </div>
                            <div class="b-twoside-card__open">
                                Подробнее
                            </div>
                        </div>
                        <div class="b-twoside-card__hidden-content">
                            <div class="b-twoside-card__name"><?=$ITEM["NAME"]?></div>
                            <div class="b-twoside-card__description">
                                <!--noindex--><?=$ITEM['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT']?><!--/noindex-->
                            </div>
                        </div>

                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>
<?endif;?>
<? if($arResult['PROPERTIES']['SCHEDULE_JSON']['VALUE'] !== 'false' && !empty($arResult['PROPERTIES']['SCHEDULE_JSON']['VALUE'])){ ?>
    <? $APPLICATION->IncludeComponent(
        "custom:shedule.club",
        "profitator.style",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_CODE" => "clubs",
            "CLUB_NUMBER" => $arResult['PROPERTIES']['NUMBER']['VALUE'],
        ),
        false
    ); ?>
    <script>
        jQuery(function($) {
            $( document ).ready(function() {
                var hash = window.location.href.split('#').pop();
                if( typeof hash != "undefined" && hash == "timetableheader" ) {
                    $('html, body').animate({
                        scrollTop: $("#timetable").offset().top - 120
                    }, 1);
                }

            });
        });
    </script>
<? } ?>


<? if($arResult['PROPERTIES']['MAP_HIDDEN']['VALUE'] != 'Да'):?>
    <?
    $address = $arResult['PROPERTIES']['ADRESS']['VALUE'];
    $phone = $arResult['PROPERTIES']['PHONE']['VALUE'];
    $email = $arResult['PROPERTIES']['EMAIL']['VALUE'];
    $workHours = $arResult['PROPERTIES']['WORK']['VALUE'];
    $cord = $arResult['PROPERTIES']['CORD_YANDEX']['VALUE'];
    if(!empty($cord)){
        $cord = explode(',', $cord);
    }
    $mapName = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($arResult['NAME'])));
    $mapAdress = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($address['TEXT'])));
    if(strpos($mapAdress, '"')) $mapAdress = str_replace('"', '\'', $mapAdress);

    ?>
    <script>
        window.cord = [<?=$cord[0]?>, <?=$cord[1]?>];
        window.club = {
            id: "<?=$arResult['ID']?>",
            name: "<?=$mapName?>",
            title: "<?=$mapName?>",
            address: "<?=$mapAdress?>",
            phone: "<?=$phone?>",
            email: "<?=$email?>",
            workHours: "<?=$workHours?>",
            page: "<?=$APPLICATION->GetCurPage();?>",
        };
    </script>

    <?$APPLICATION->AddHeadString('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>',true)?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/map-leafletjs.min.js?version=12?>"></script>
    <link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
    <script src="//unpkg.com/leaflet-gesture-handling"></script>

    <div class="content-center">
        <div class="b-section__title">
            <h2>Контакты</h2>
        </div>
    </div>
    <div class="b-map">
        <div class="b-map__map-wrap">
            <div class="b-map__map map-clubs-detail" id="mapid"></div>
            <div class="b-map__content">
                <div class="content-center"  style="display: flex; justify-content: space-between">
                    <div class="b-map__info-plate">
                        <div class="b-map__info-plate-closer closer white hidden-desktop">
                            <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
                        </div>
                        <div class="b-map__info">
                            <h2 class="b-map__info-title"><?=$arResult['~NAME']?></h2>
                            <div class="b-map__contacts">
                                <? if(!empty($address)){
                                    $address['TEXT'] = htmlspecialcharsBack($address['TEXT']);
                                    ?>
                                    <div class="b-map__contact-item"><?=$address['TEXT']?></div>
                                <? } ?>
                                <div class="b-map__contact-item">
                                    <? if(!empty($phone)){ ?>
                                        <div><a class="invisible-link phone-btn" href="tel:<?=$phone?>" data-position="page"><?=$phone?></a></div>
                                    <? } ?>
                                    <? if(!empty($email)){ ?>
                                        <div><a class="invisible-link" href="mailto:<?=$email?>"><?=$email?></a></div>
                                    <? } ?>
                                </div>
                                <? if(!empty($workHours)){ ?>
                                    <div class="b-map__contact-item">
                                        <div><?=$workHours?></div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                        <div class="b-map__buttons">
                            <?
                            if( (empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['SHOW_FORM']['VALUE']))
                                || (!empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['HIDE_LINK_FORM']['VALUE'])) ) {
                                ?>
                                <a class="b-map__button button-outline" href="#form-request">Отправить заявку</a>
                            <? } ?>

                            <?if (!empty($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'])):?>
                                <a class="b-map__button button-outline custom-button" onclick="$('#route-window').fadeIn(300)">Как добраться</a>
                            <?endif;?>
                        </div>
                        <?if (!empty($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'])):?>
                            <div class="b-map__route-container hidden" id="route-window">
                                <div class="b-map__route">
                                    <div class="b-map__route-closer closer black" onclick="$('#route-window').fadeOut(300)">
                                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
                                    </div>
                                    <div class="b-map__route-header">
                                        <h2>Как добраться</h2>
                                        <div class="b-map__route-tabs">
                                            <div class="b-map__route-tab-links">
                                                <?
                                                $route_tab_link_width=100/count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);
                                                for($index=0;$index<count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);$index++):?>
                                                    <?
                                                    $PATH_TO=$arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'][$index];
                                                    $PATH_TO_NAME=$arResult['PROPERTIES']['PATH_TO_NEW']['DESCRIPTION'][$index];
                                                    if (empty($PATH_TO) || empty($PATH_TO_NAME)){
                                                        continue;
                                                    }
                                                    ?>
                                                    <div class="b-map__route-tab-link <?if($index==0) echo "active";?>" data-routetypeid="<?=$index?>" style="width: <?=$route_tab_link_width?>%">
                                                        <?=$PATH_TO_NAME?>
                                                    </div>
                                                    <div class="b-map__route-tabitem-desc <?if($index==0) echo "active";?> hidden-desktop" data-routetypeid="<?=$index?>">
                                                        <?=htmlspecialcharsBack($PATH_TO["TEXT"])?>
                                                        <div class="b-map__route-mapiframe">
                                                            <?
                                                            $sContent=htmlspecialcharsBack($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE']);
                                                            $sContent = preg_replace('|(width=").+(")|isU', 'width="100%"',$sContent);
                                                            $sContent = preg_replace('|(height=").+(")|isU', 'height="100%"',$sContent);
                                                            echo $sContent;
                                                            ?>
                                                            <div class="b-map__mapiframe-info">
                                                                <div class="b-map__mapiframe-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="24" viewBox="0 0 10 24" fill="none">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.20511 0.112501C2.87975 0.453801 1.9954 1.38351 1.73816 2.70598C1.48795 3.9926 2.3051 5.49312 3.55381 6.03976C4.55054 6.47613 5.4913 6.47613 6.48803 6.03976C7.73674 5.49312 8.55389 3.9926 8.30368 2.70598C8.16761 2.0065 7.85163 1.40784 7.38084 0.95755C6.9092 0.506617 6.48594 0.279191 5.80084 0.1085C5.21523 -0.0373825 4.78293 -0.0362625 4.20511 0.112501ZM0.534561 8.06964C0.437406 8.10717 0.277322 8.23585 0.178912 8.35548C0.00803351 8.56322 0 8.65477 0 10.4014V12.2299L0.241255 12.4881C0.462343 12.7248 0.532134 12.749 1.07807 12.7785L1.67364 12.8107V15.9936V19.1764L1.07807 19.2086C0.532134 19.2381 0.462343 19.2624 0.241255 19.499L0 19.7572V21.5952V23.4332L0.244101 23.6946L0.488285 23.9559L4.96845 23.9779L9.4487 24L9.72435 23.7644L10 23.5289V21.5952V19.6615L9.7267 19.428C9.48452 19.2211 9.39188 19.1945 8.91255 19.1945H8.37163L8.34904 13.8264L8.32636 8.45815L8.05305 8.22472L7.77975 7.99122L4.24552 7.99626C2.30167 7.99906 0.631799 8.03211 0.534561 8.06964Z" fill="#FF7628"/>
                                                                    </svg>
                                                                </div>
                                                                Проводим вас до самой раздевалки
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endfor;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="b-map__route-body visible-desktop">
                                        <div class="b-map__route-tab-desc">
                                            <?for($index=0;$index<count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);$index++):?>
                                                <?
                                                $PATH_TO=$arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'][$index];
                                                $PATH_TO_NAME=$arResult['PROPERTIES']['PATH_TO_NEW']['DESCRIPTION'][$index];
                                                if (empty($PATH_TO) || empty($PATH_TO_NAME)){
                                                    continue;
                                                }
                                                ?>
                                                <div class="b-map__route-tabitem-desc <?if($index==0) echo "active";?>" data-routetypeid="<?=$index?>">
                                                    <?=htmlspecialcharsBack($PATH_TO["TEXT"])?>
                                                </div>
                                            <?endfor;?>
                                        </div>
                                        <?if (!empty($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE'])):?>
                                            <div class="b-map__route-mapiframe">
                                                <?
                                                $sContent=htmlspecialcharsBack($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE']);
                                                $sContent = preg_replace('|(width=").+(")|isU', 'width="300"',$sContent);
                                                $sContent = preg_replace('|(height=").+(")|isU', 'height="300"',$sContent);
                                                echo $sContent;
                                                ?>
                                                <div class="b-map__mapiframe-info">
                                                    <div class="b-map__mapiframe-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="24" viewBox="0 0 10 24" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.20511 0.112501C2.87975 0.453801 1.9954 1.38351 1.73816 2.70598C1.48795 3.9926 2.3051 5.49312 3.55381 6.03976C4.55054 6.47613 5.4913 6.47613 6.48803 6.03976C7.73674 5.49312 8.55389 3.9926 8.30368 2.70598C8.16761 2.0065 7.85163 1.40784 7.38084 0.95755C6.9092 0.506617 6.48594 0.279191 5.80084 0.1085C5.21523 -0.0373825 4.78293 -0.0362625 4.20511 0.112501ZM0.534561 8.06964C0.437406 8.10717 0.277322 8.23585 0.178912 8.35548C0.00803351 8.56322 0 8.65477 0 10.4014V12.2299L0.241255 12.4881C0.462343 12.7248 0.532134 12.749 1.07807 12.7785L1.67364 12.8107V15.9936V19.1764L1.07807 19.2086C0.532134 19.2381 0.462343 19.2624 0.241255 19.499L0 19.7572V21.5952V23.4332L0.244101 23.6946L0.488285 23.9559L4.96845 23.9779L9.4487 24L9.72435 23.7644L10 23.5289V21.5952V19.6615L9.7267 19.428C9.48452 19.2211 9.39188 19.1945 8.91255 19.1945H8.37163L8.34904 13.8264L8.32636 8.45815L8.05305 8.22472L7.77975 7.99122L4.24552 7.99626C2.30167 7.99906 0.631799 8.03211 0.534561 8.06964Z" fill="#7a27f1"/>
                                                        </svg>
                                                    </div>
                                                    Проводим вас до самой раздевалки
                                                </div>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>

                        <?endif;?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?endif?>


<div itemscope itemtype="http://schema.org/ExerciseGym" style="display: none;">
    <span itemprop="name">Spirit.Fitness</span>
    <meta itemprop="legalName" content="ООО Рекорд Фитнес">
    <link itemprop="url" href="https://spiritfit.ru/">
    <? if( !empty($arResult['PREVIEW_PICTURE']['SRC']) ) { ?>
        <span itemprop="image">https://<?=$_SERVER['SERVER_NAME']?><?=$arResult['PREVIEW_PICTURE']['SRC']?></span>
        <? $this->SetViewTarget('inhead'); ?>https://<?=$_SERVER['SERVER_NAME']?><?=$arResult['PREVIEW_PICTURE']['SRC']?><? $this->EndViewTarget(); ?>
    <? } ?>
    <span itemprop="priceRange"><?=$arResult['ABONEMENTS_MIN_PRICE']?>-<?=$arResult['ABONEMENTS_MAX_PRICE']?></span>
    <img itemprop="logo" src="https://spiritfit.ru/images/logo.svg">
    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <meta itemprop="addressCountry" content="Россия">
        <span itemprop="streetAddress"><?=$arResult['ADDRESS_SHORT']?></span>
        <? if( !empty($arResult['PROPERTIES']['INDEX']['VALUE']) ) { ?>
            <span itemprop="postalCode"><?=$arResult['PROPERTIES']['INDEX']['VALUE']?></span>
        <? } ?>
        <span itemprop="addressLocality">Москва</span>
    </div>
    <span itemprop="telephone">8 (495) 266-40-94</span>
    <? if( !empty($arResult['PROPERTIES']['EMAIL']['VALUE']) ) { ?>
        <span itemprop="email"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></span>
    <? } ?>
    <meta itemprop="openingHours" content="Mo-Su 07:00-24:00">
</div>

<?if (!empty($arResult["RATING"])):?>
<div itemscope itemtype="http://schema.org/LocalBusiness" style="display: none;">
    <span itemprop="name"><?=$arResult["~NAME"]?></span>
    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <meta itemprop="addressCountry" content="Россия">
        <span itemprop="streetAddress"><?=$arResult['ADDRESS_SHORT']?></span>
        <? if( !empty($arResult['PROPERTIES']['INDEX']['VALUE']) ) { ?>
            <span itemprop="postalCode"><?=$arResult['PROPERTIES']['INDEX']['VALUE']?></span>
        <? } ?>
        <span itemprop="addressLocality">Москва</span>
    </div>
    <span itemprop="telephone"><?=$phone?></span>
    <span itemprop="email"><?=$email?></span>
    <time itemprop="openingHours"><?=$workHours?></time>
    <div itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating">
        <meta itemprop="bestRating" content="5">
        <meta itemprop="ratingValue" content="<?=$arResult["RATING"]?>">
        <span itemprop="ratingCount"><?=$arResult["RATING_COUNT"]?></span>
    </div>
</div>
<?endif;?>

