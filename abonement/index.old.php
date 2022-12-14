<?
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}

$APPLICATION->SetTitle("Абонементы");
$APPLICATION->SetPageProperty("description", "Удобная оплата от 1490 ₽ в месяц 💥 Полный безлимит по времени и услугам 💯 Специальные условия на покупку в этом месяце. Пробная тренировка бесплатно 💸");
$APPLICATION->SetPageProperty("title", "Абонементы фитнес-клуба Spirit Fitness: абонементы от 1490 ₽ в месяц");
?>

<style>
	.b-cards-slider__slider {
		margin: 20px -24px!important;
	}
</style>

<? if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true' && $_REQUEST["ajax_send"] == 'Y'): ?>
	<? 
		$component = ($_REQUEST["WEB_FORM_ID"] == "5" ? "custom:form.request" : "custom:form.aboniment");
		$template = "clubs-v2";
		
		if($_REQUEST['modal_form']){
			$template = 'modal';
		}
		
	?>
	<?$APPLICATION->IncludeComponent(
		$component, 
		$template, 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => $_REQUEST["WEB_FORM_ID"],
			"NUMBER" => $_REQUEST["club"],
			"TEXT_FORM" => $_REQUEST["text_form"],
			"THANKS" => "Наш менеджер свяжется с Вами, ожидайте.",
		),
		false
	);?>
<? else: ?>
	<?$GLOBALS['arrFilterAbonement'] = ['PROPERTY_HIDDEN_VALUE' => false]?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"abonements",
		Array(
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
			"FIELD_CODE" => array("",""),
			"FILE_404" => "",
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
			"PROPERTY_CODE" => array("SIZE","PRICE",""),
			"SET_BROWSER_TITLE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "Y",
			"SHOW_404" => "N",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_BY2" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N"
		)
	);?>
	<? $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => 'trenazhernyy-zal-main'], ['SHOW_BORDER' => false]); ?>
<? endif; ?>
<?
//$APPLICATION->IncludeComponent(
//    "custom:promocode.banner",
//    "purple",
//    Array(
//        "BANNER_DISCOUNT" => "1000 &#x20bd;",
//        "BANNER_TIME" => 3000,
//        "PROMOCODE" => "FITSUMMER"
//    )
//);
?>
<? if (!isset($_SERVER['HTTP_X_PJAX']))  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>