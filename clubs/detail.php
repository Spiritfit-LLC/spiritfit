<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");?>

<?
CModule::IncludeModule("iblock");
$siteProperties = Utils::getInfo();

$successText = "";
if( !empty($siteProperties["PROPERTIES"]["CLUB_FORM_SUCCESS"]["VALUE"])) {
    $successText = $siteProperties["PROPERTIES"]["CLUB_FORM_SUCCESS"]["VALUE"];
}
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "club",
    Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_ELEMENT_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BROWSER_TITLE" => "-",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "CLUB_FORM_SUCCESS" => $successText,
        "COMPONENT_TEMPLATE" => "club",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
        "ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
        "FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"",),
        "FILE_404" => "",
        "IBLOCK_ID" => "6",
        "IBLOCK_TYPE" => "content",
        "IBLOCK_URL" => "",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Страница",
        "PROPERTY_CODE" => array(0=>"EMAIL",1=>"ADRESS",2=>"CORD_YANDEX",3=>"NUMBER",4=>"SOON",5=>"LINK_VIDEO",6=>"PHONE",7=>"TEXT_FORM",8=>"WORK",9=>"INDEX",10=>"HIDE_LINK",11=>"REVIEWS",12=>"CLUB_UTP", 13=>"NOT_OPEN_YET"),
        "SET_BROWSER_TITLE" => "Y",
        "SET_CANONICAL_URL" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_STATUS_404" => "Y",
        "SET_TITLE" => "Y",
        "SHOW_404" => "Y",
        "STRICT_SECTION_CHECK" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_SHARE" => "N"
    )
);?>


<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>