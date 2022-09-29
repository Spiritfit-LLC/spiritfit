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

    $landingIblockCode = 'landing_v2';
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
			1 => "HEADER_DESCRIPTION",
			2 => "HEADER_SORT",
			3 => "BLOCK2_IMAGE",
			4 => "BLOCK2_TITLE1",
			5 => "BLOCK2_TITLE2",
			6 => "BLOCK2_LIST1",
			7 => "BLOCK2_LIST2",
			8 => "BLOCK2_SORT",
			9 => "BLOCK3_TITLE",
			10 => "BLOCK3_LIST",
			11 => "BLOCK3_SORT",
			12 => "BLOCK4_TITLE",
			13 => "BLOCK4_LIST",
			14 => "BLOCK4_SORT",
			15 => "BLOCK5_TITLE",
			16 => "BLOCK5_LIST",
			17 => "BLOCK5_IMAGE",
			18 => "BLOCK5_VIDEO",
			19 => "BLOCK5_SORT",
			20 => "BLOCK6_TITLE",
			21 => "BLOCK6_LIST",
			22 => "BLOCK6_SORT",
			23 => "BLOCK7_TITLE",
			24 => "BLOCK7_LIST",
			25 => "BLOCK7_GIFT_LIST",
			26 => "BLOCK7_SORT",
			27 => "BLOCK8_TITLE",
			28 => "BLOCK8_LIST",
			29 => "BLOCK8_SORT",
			30 => "BLOCK2_ACTIVE",
			31 => "BLOCK3_ACTIVE",
			32 => "BLOCK4_ACTIVE",
			33 => "BLOCK5_ACTIVE",
			34 => "BLOCK6_ACTIVE",
			35 => "BLOCK7_ACTIVE",
			36 => "BLOCK8_ACTIVE"
		),
        "IBLOCK_URL" => "/home/bitrix/www/landings/v2/",
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
