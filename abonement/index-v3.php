<?php
define('HIDE_SLIDER', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Абонементы");
$APPLICATION->SetPageProperty("description", "Удобная оплата от 1490 ₽ в месяц 💥 Полный безлимит по времени и услугам 💯 Специальные условия на покупку в этом месяце. Пробная тренировка бесплатно 💸");
$APPLICATION->SetPageProperty("title", "Абонементы фитнес-клуба Spirit Fitness: абонементы от 1490 ₽ в месяц");
?>

<?php
$GLOBALS['arAbonementFilter'] =
    [
        'PROPERTY_HIDDEN_VALUE' => false,
        '!IBLOCK_SECTION_ID' => false
    ];
?>
<div class="section-margin-top">
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "abonement.main",
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
        "DETAIL_URL" => "/abonement/#CODE#/",
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
        "PROPERTY_CODE" => array("BASE_PRICE", "INCLUDE", "ADDITIONAL_CLASS", "PRICE_SIGN", "HIDDEN", "FOR_PRESENT", "PRICE", "TITLE"),
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
</div>


<? $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "blocks.abonements",
    array(
        "COMPONENT_TEMPLATE" => "",
        "IBLOCK_TYPE" => "service",
        "IBLOCK_ID" => "18",
        "BLOCK_TITLE" => "",
        "ELEMENT_ID" => "",
        "ELEMENT_CODE" => "trenazhernyy-zal-main",
        "ADDITIONAL_CLASS" => '',
        "CHECK_DATES" => "Y",
        "FIELD_CODE" => array(),
        "PROPERTY_CODE" => array(
            0 => "BLOCK_TEXT",
            1 => "BLOCK_BTN_TEXT",
            2 => "BLOCK_LINK",
            3 => "BLOCK_VIDEO_YOUTUBE",
            4 => "BLOCK_PREVIEW",
            5 => "BLOCK_PHOTO",
            6 => "BLOCK_VIEW",
            7 => "BLOCK_TITLE_LINK",
        ),
        "IBLOCK_URL" => "",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "N",
        "SET_TITLE" => "N",
        "SET_CANONICAL_URL" => "N",
        "SET_BROWSER_TITLE" => "N",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "N",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "N",
        "META_DESCRIPTION" => "-",
        "SET_LAST_MODIFIED" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_ELEMENT_CHAIN" => "N",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "USE_PERMISSIONS" => "N",
        "STRICT_SECTION_CHECK" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "USE_SHARE" => "N",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Страница",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "FILE_404" => ""
    ),
    false
);?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>