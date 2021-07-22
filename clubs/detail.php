<?
define('BREADCRUMB_H1_ABSOLUTE', true);

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}
?>

<? if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true' && $_REQUEST["ajax_send"] == 'Y' && $_REQUEST["WEB_FORM_ID"] == '2'): ?>
	<? 
		$template = "clubs-v2";

		if($_REQUEST['modal_form']){
			$template = 'modal';
		}
		
	?>
	<?$APPLICATION->IncludeComponent(
		"custom:form.aboniment", 
		$template, 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => "2",
			"NUMBER" => (!empty($_REQUEST["club"]) ? $_REQUEST["club"] : $_SESSION['CLUB_NUMBER']),
			"TEXT_FORM" => $_REQUEST["text_form"],
			"THANKS" => "Наш менеджер свяжется с Вами, ожидайте.",
			"ABONEMENT_DETAIL" => "Y",
		),
		false
	);?>
<? else: ?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.detail", 
		"club-detail", 
		array(
			"COMPONENT_TEMPLATE" => "club-detail",
			"IBLOCK_TYPE" => "content",
			"IBLOCK_ID" => "6",
			"ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
			"ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
			"CHECK_DATES" => "Y",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
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
				8 => "",
			),
			"IBLOCK_URL" => "",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_GROUPS" => "Y",
			"SET_TITLE" => "Y",
			"SET_CANONICAL_URL" => "N",
			"SET_BROWSER_TITLE" => "Y",
			"BROWSER_TITLE" => "-",
			"SET_META_KEYWORDS" => "Y",
			"META_KEYWORDS" => "-",
			"SET_META_DESCRIPTION" => "Y",
			"META_DESCRIPTION" => "-",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_ELEMENT_CHAIN" => "Y",
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
			"SET_STATUS_404" => "Y",
			"SHOW_404" => "Y",
			"MESSAGE_404" => "",
			"FILE_404" => ""
		),
		false
	);?>
<? endif; ?>


<? if (!isset($_SERVER['HTTP_X_PJAX']))  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>