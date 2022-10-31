<?
    define('BREADCRUMB_H1_ABSOLUTE', true);
    define('HIDE_SLIDER', true);
    define('H1_HIDE', true);

    //РАЗБИРАЕМ URL
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    $urlArr = explode('/', $url);
    if( empty($urlArr['3']) ) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /");
        exit();
    }

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

    $landingIblockCode = 'landing_v1';
    $landingIblockId = Utils::GetIBlockIDBySID($landingIblockCode);
    $elementCode = !empty($_REQUEST['ELEMENT_CODE']) ? $_REQUEST['ELEMENT_CODE'] : '';
	
    if( empty($landingIblockId) || empty($elementCode) ) {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
        require $_SERVER['DOCUMENT_ROOT'].'/404.php';
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
        exit;
    }

    //Подключаем компонент
    $APPLICATION->IncludeComponent("bitrix:news.detail",$landingIblockCode,Array(
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "USE_SHARE" => "N",
        "SHARE_HIDE" => "N",
        "SHARE_TEMPLATE" => "",
        "SHARE_HANDLERS" => array(),
        "SHARE_SHORTEN_URL_LOGIN" => "",
        "SHARE_SHORTEN_URL_KEY" => "",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "landings",
        "IBLOCK_ID" => $landingIblockId,
        "ELEMENT_ID" => "",
        "ELEMENT_CODE" => $elementCode,
        "CHECK_DATES" => "Y",
        "FIELD_CODE" => Array("ID"),
        "PROPERTY_CODE" => Array(
			0 => "HEADER_IMAGE",
			1 => "HEADER_TITLE1",
			2 => "HEADER_TITLE2",
			3 => "HEADER_DESCRIPTION",
			4 => "HEADER_SORT",
			5 => "BLOCK2_TITLE",
			6 => "BLOCK2_DESCRIPTION",
			7 => "BLOCK2_LIST",
			8 => "BLOCK2_SORT",
			9 => "BLOCK3_TITLE",
			10 => "BLOCK3_DESCRIPTION",
			11 => "BLOCK3_LIST",
			12 => "BLOCK3_LOGOS",
			13 => "BLOCK3_SORT",
			14 => "BLOCK4_FORM_ID",
			15 => "BLOCK4_SORT",
			16 => "BLOCK2_ACTIVE",
			17 => "BLOCK3_ACTIVE",
			18 => "BLOCK4_ACTIVE",
			19 => "BLOCK5_TITLE",
			20 => "BLOCK5_ACTIVE",
			21 => "BLOCK5_DATE",
			22 => "BLOCK5_SORT",
			23 => "BLOCK5_DESCRIPTION",
			24 => "BLOCK5_VIDEO_TITLE",
			25 => "BLOCK5_VIDEO_LINK"
		),
        "IBLOCK_URL" => "/home/bitrix/www/landings/v1/",
        "DETAIL_URL" => "",
        "SET_TITLE" => "Y",
        "SET_CANONICAL_URL" => "N",
        "SET_BROWSER_TITLE" => "Y",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "Y",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "Y",
        "META_DESCRIPTION" => "-",
        "SET_STATUS_404" => "Y",
        "SET_LAST_MODIFIED" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_ELEMENT_CHAIN" => "Y",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "USE_PERMISSIONS" => "N",
        "GROUP_PERMISSIONS" => Array(),
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_TEMPLATE" => "",
        "PAGER_SHOW_ALL" => "Y",
        "PAGER_BASE_LINK_ENABLE" => "Y",
        "SHOW_404" => "Y",
        "MESSAGE_404" => "",
        "STRICT_SECTION_CHECK" => "Y",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "arrPager",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    ));

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
