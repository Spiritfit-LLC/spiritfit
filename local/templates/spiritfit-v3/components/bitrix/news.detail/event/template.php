<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult["PROPERTIES"]["SLIDER"]["VALUE"])):?>
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "photo.slider",
        Array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_ELEMENT_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BROWSER_TITLE" => "-",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_CODE" => "",
            "ELEMENT_ID" => $arResult["PROPERTIES"]["SLIDER"]["VALUE"],
            "FIELD_CODE" => array("",""),
            "FILE_404" => "",
            "IBLOCK_ID" => Utils::GetIBlockIDBySID("sliders"),
            "IBLOCK_TYPE" => "",
            "IBLOCK_URL" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Страница",
            "PROPERTY_CODE" => array("STYLE","TITLE","PHOTO_DESCRIPTION","SLIDER_BTN","SLIDER_TITLE",""),
            "SET_BROWSER_TITLE" => "N",
            "SET_CANONICAL_URL" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "USE_PERMISSIONS" => "N",
            "USE_SHARE" => "N"
        ));
    ?>
<?php elseif (!empty($arResult["PROPERTIES"]["BANNER_HEAD"]["VALUE"])):?>
    <div class="content-center">
        <div class="banner_header">
            <img class="banner_header-image lazy hidden-phone" data-src="<?=CFile::GetPath($arResult["PROPERTIES"]["BANNER_HEAD"]["VALUE"])?>">
            <?
            if (!empty($arResult["PROPERTIES"]["BANNER_MOBILE_HEAD"]["VALUE"])){
                $img_mobile=CFile::GetPath($arResult["PROPERTIES"]["BANNER_MOBILE_HEAD"]["VALUE"]);
            }
            else{
                $img_mobile=CFile::GetPath($arResult["PROPERTIES"]["BANNER_HEAD"]["VALUE"]);
            }
            ?>
            <img class="banner_header-image lazy visible-phone" data-src="<?=$img_mobile?>">
        </div>
    </div>
<?php endif;?>



<?php if (!empty($arResult["PROPERTIES"]["PAGE_TITLE"]["VALUE"])):?>
    <div class="content-center">
        <div class="b-section__title text-center">
            <h2><?=$arResult["PROPERTIES"]["PAGE_TITLE"]["VALUE"]?></h2>
        </div>
    </div>
<?endif;?>
<div class="content-center">
    <div class="event-schedule">
        <?php if (!empty($arResult["PROPERTIES"]["CARDS_TITLE"]["VALUE"])):?>
                <div class="event-schedule__title text-center">
                    <h3 class="gradient-text"><?=$arResult["PROPERTIES"]["CARDS_TITLE"]["VALUE"]?></h3>
                </div>
        <?endif;?>
    </div>
    <?php if (!empty($arResult["PROPERTIES"]["CARDS"]["VALUE"])):?>
        <div class="event-schedule__cards">
            <?
                $dbRes=CIBlockElement::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y", "ID"=>$arResult["PROPERTIES"]["CARDS"]["VALUE"]), false, false, array("NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT"));
                while($arRes=$dbRes->Fetch()):?>
                    <div class="event-schedule__item">
                        <div class="event-schedule-twoside-card">
                            <div class="event-schedule-twoside-card__inner">
                                <div class="event-schedule-twoside-card__content lazy-img-bg" data-src="<?=CFile::GetPath($arRes["PREVIEW_PICTURE"])?>">
                                    <div class="event-schedule-twoside-card__label"><?=$arRes["NAME"]?></div>
                                </div>
                                <div class="event-schedule-twoside-card__hidden-content">
                                    <div class="event-schedule-twoside-card__title"><?=$arRes["NAME"]?></div>
                                    <div class="event-schedule-twoside-card__text"><?=$arRes["PREVIEW_TEXT"]?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endwhile;
            ?>
        </div>
    <?endif;?>
</div>
<?php if (!empty($arResult["PROPERTIES"]["BANNER_FOOTER"]["VALUE"])):?>
<div class="content-center">
    <div class="banner_footer">
        <img class="banner_footer-image lazy hidden-phone" data-src="<?=CFile::GetPath($arResult["PROPERTIES"]["BANNER_FOOTER"]["VALUE"])?>">
        <img class="banner_footer-image lazy visible-phone" data-src="<?=CFile::GetPath($arResult["PROPERTIES"]["BANNER_MOBILE_FOOTER"]["VALUE"])?>">
    </div>
</div>
<?endif;?>