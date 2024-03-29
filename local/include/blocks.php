<?
if( !isset($blockTitle) ) {
    $blockTitle = "";
}
$APPLICATION->IncludeComponent(
	"bitrix:news.detail", 
	"blocks", 
	array(
        "COMPONENT_TEMPLATE" => "",
        "IBLOCK_TYPE" => "service",
        "IBLOCK_ID" => "18",
        "BLOCK_TITLE" => $blockTitle,
        "ELEMENT_ID" => "",
        "ELEMENT_CODE" => $arParams['ELEMENT_CODE'],
		"ADDITIONAL_CLASS" => (!empty($arParams['ADDITIONAL_CLASS'])) ? $arParams['ADDITIONAL_CLASS'] : '',
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