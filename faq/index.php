<?
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
	echo "<title>FAQ</title>";
	$APPLICATION->SetTitle("FAQ");
	$APPLICATION->SetPageProperty("keywords", "spiritfit, fitness, faq");
	$APPLICATION->SetPageProperty("description", "Наиболее часто задаваемые вопросы и ответы сети клубов Spirit Fitness.");
	$APPLICATION->SetPageProperty("title", "Вопросы и ответы - фитнес-клуб Spirit Fitness");
} else {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	$APPLICATION->SetTitle("FAQ");
	$APPLICATION->SetPageProperty("keywords", "spiritfit, fitness, faq");
	$APPLICATION->SetPageProperty("description", "Наиболее часто задаваемые вопросы и ответы сети клубов Spirit Fitness.");
	$APPLICATION->SetPageProperty("title", "Вопросы и ответы - фитнес-клуб Spirit Fitness");
}
?>

<? if ($_REQUEST["ajax_menu"] && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'): ?>
	<? $APPLICATION->IncludeComponent(
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
		);
	?>
	<?$page = $APPLICATION->GetCurPage();
	  $arSEOData = Utils::setSeoDiv($page, $APPLICATION);?>
	<div id="seo-div" hidden="true"
		data-title="<?=$arSEOData['META_TITLE']?>" 
		data-description="<?=$arSEOData['META_DESCRIPTION']?>" 
		data-keywords="<?=$arSEOData['META_KEYWORDS']?>"
		data-image="<?=$arSEOData['OG_IMG']?>"></div>
<? else: ?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"faq", 
				array(
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"ADD_SECTIONS_CHAIN" => "Y",
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
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"FILTER_NAME" => "",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => "13",
					"IBLOCK_TYPE" => "content",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "6",
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
					"PROPERTY_CODE" => array(
						0 => "SIZE",
						1 => "PRICE",
						2 => "",
					),
					"SET_BROWSER_TITLE" => "Y",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "Y",
					"SET_TITLE" => "Y",
					"SHOW_404" => "Y",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_BY2" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_ORDER2" => "ASC",
					"STRICT_SECTION_CHECK" => "N",
					"COMPONENT_TEMPLATE" => "faq",
					"FILE_404" => ""
				),
				false
			);?>
<? endif; ?>

<?
if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>