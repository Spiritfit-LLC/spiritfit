<?
	define('HIDE_SLIDER', true);
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	
	$APPLICATION->SetTitle("Блог");
	$APPLICATION->SetPageProperty("description", "");
	$APPLICATION->SetPageProperty("title", "");
	
	$settings = Utils::getInfo();
	$currUrl = $APPLICATION->GetCurPage(false);
	
	$banner = "";
	if( !empty($settings["PROPERTIES"]["BLOG_BANNERS"]["VALUE"]) ) {
		foreach( $settings["PROPERTIES"]["BLOG_BANNERS"]["VALUE"] as $key => $value ) {
			$inPageUrl = (isset($settings["PROPERTIES"]["BLOG_BANNERS"]["DESCRIPTION"][$key])) ? trim($settings["PROPERTIES"]["BLOG_BANNERS"]["DESCRIPTION"][$key]) : "";
			if(  !empty($inPageUrl) && $inPageUrl == $currUrl ) {
				$banner = CFile::ResizeImageGet($value, ["width" => 150, "height" => 350], BX_RESIZE_IMAGE_EXACT, false)["src"];
				break;
			} else if( empty($inPageUrl) ) {
				$banner = CFile::ResizeImageGet($value, ["width" => 150, "height" => 350], BX_RESIZE_IMAGE_EXACT, false)["src"];
				break;
			}
		}
	}
	
	$APPLICATION->IncludeComponent("bitrix:news", "blog", 
		array(
			"DISPLAY_DATE" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"SEF_MODE" => "Y",
			"AJAX_MODE" => "N",
			"IBLOCK_TYPE" => "content",
			"IBLOCK_ID" => "22",
			"BANNER" => $banner,
			"NEWS_COUNT" => "20",
			"USE_SEARCH" => "N",
			"USE_RSS" => "N",
			"USE_RATING" => "N",
			"USE_CATEGORIES" => "N",
			"USE_REVIEW" => "N",
			"USE_FILTER" => "N",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"CHECK_DATES" => "Y",
			"PREVIEW_TRUNCATE_LEN" => "",
			"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
			"LIST_FIELD_CODE" => array(
				0 => "",
				1 => "",
				),
			"LIST_PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"DISPLAY_NAME" => "Y",
			"META_KEYWORDS" => "-",
			"META_DESCRIPTION" => "-",
			"BROWSER_TITLE" => "-",
			"DETAIL_SET_CANONICAL_URL" => "Y",
			"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
			"DETAIL_FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"DETAIL_PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"DETAIL_DISPLAY_TOP_PAGER" => "N",
			"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
			"DETAIL_PAGER_TITLE" => "Страница",
			"DETAIL_PAGER_TEMPLATE" => "",
			"DETAIL_PAGER_SHOW_ALL" => "Y",
			"STRICT_SECTION_CHECK" => "Y",
			"SET_TITLE" => "Y",
			"ADD_SECTIONS_CHAIN" => "Y",
			"ADD_ELEMENT_CHAIN" => "Y",
			"SET_LAST_MODIFIED" => "Y",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SET_STATUS_404" => "Y",
			"SHOW_404" => "Y",
			"MESSAGE_404" => "",
			"PAGER_BASE_LINK" => "",
			"PAGER_PARAMS_NAME" => "arrPager",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"USE_PERMISSIONS" => "N",
			"GROUP_PERMISSIONS" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Блог",
			"PAGER_SHOW_ALWAYS" => "Y",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"FILTER_NAME" => "",
			"FILTER_FIELD_CODE" => "",
			"FILTER_PROPERTY_CODE" => "",
			"NUM_NEWS" => "20",
			"NUM_DAYS" => "30",
			"YANDEX" => "N",
			"MAX_VOTE" => "5",
			"VOTE_NAMES" => array(
				0 => "0",
				1 => "1",
				2 => "2",
				3 => "3",
				4 => "4",
			),
			"CATEGORY_IBLOCK" => "",
			"CATEGORY_CODE" => "CATEGORY",
			"CATEGORY_ITEMS_COUNT" => "5",
			"MESSAGES_PER_PAGE" => "10",
			"USE_CAPTCHA" => "N",
			"REVIEW_AJAX_POST" => "N",
			"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
			"URL_TEMPLATES_READ" => "",
			"SHOW_LINK_TO_FORUM" => "N",
			"POST_FIRST_MESSAGE" => "N",
			"SEF_FOLDER" => "/blog/",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"USE_SHARE" => "Y",
			"SHARE_HIDE" => "Y",
			"SHARE_TEMPLATE" => "",
			"SHARE_HANDLERS" => array(
				0 => "twitter",
				1 => "delicious",
				2 => "lj",
				3 => "facebook",
			),
			"SHARE_SHORTEN_URL_LOGIN" => "",
			"SHARE_SHORTEN_URL_KEY" => "",
			"COMPONENT_TEMPLATE" => ".default",
			"AJAX_OPTION_ADDITIONAL" => "",
			"FILE_404" => "",
			"SEF_URL_TEMPLATES" => array(
				"news" => "",
				"section" => "#SECTION_CODE_PATH#/",
				"detail" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			)
		),
		false
	);
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>