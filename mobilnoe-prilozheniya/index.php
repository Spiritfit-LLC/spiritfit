<?
define('BREADCRUMB_H1_ABSOLUTE', true);

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	$APPLICATION->SetTitle("Мобильное приложение");
	$APPLICATION->SetPageProperty("title", "Мобильное приложение сети фитнес-клубов Spirit Fitness - приложение для самостоятельных тренировок ");
	$APPLICATION->SetPageProperty("description", "У нас есть мобильное приложение 📲 с разными программами тренировок, вы сможете подобрать для себя комплекс упражнений и заниматься дома или на улице.");
}
?>

<? if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true' && $_REQUEST["ajax_send"] == 'Y'): ?>
	<?$APPLICATION->IncludeComponent(
		"custom:form.aboniment", 
		"modal", 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => "2",
			"NUMBER" => $_REQUEST["club"],
			"TEXT_FORM" => $_REQUEST["text_form"],
			"THANKS" => "Наш менеджер свяжется с Вами, ожидайте.",
		),
		false
	);?>
<? else: ?>
	<div id="abonements">
		<? $GLOBALS['arrFilterAbonement'] = ['ID' => [427, 475, 476]] ?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"abonements",
			Array(
				"TITLE_BLOCK" => "Абонементы on-line",
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
				"FIELD_CODE" => array("", ""),
				"FILTER_NAME" => "arrFilterAbonement",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"IBLOCK_ID" => "9",
				"IBLOCK_TYPE" => "content",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "20",
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
					0 => "EMAIL",
					1 => "ADRESS",
					2 => "CORD_YANDEX",
					3 => "NUMBER",
					4 => "SOON",
					5 => "LINK_VIDEO",
					6 => "PHONE",
					6 => "TEXT_FORM",
					7 => "WORK",
					8 => "BASE_PRICE",
					9 => "PRICE",
					10 => "PRICE_SIGN_DETAIL",
					11 => "SIZE",
				),
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
			)
		);?>
	</div>
	<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'smart-trenirovki'], ['SHOW_BORDER' => false]); ?>
	<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'specproekty'], ['SHOW_BORDER' => false]); ?>

<? endif; ?>
<? if (!isset($_SERVER['HTTP_X_PJAX']))  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>