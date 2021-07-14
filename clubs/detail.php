<?
define('HIDE_BREADCRUMB', true);
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}
?>

<? if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true' && $_REQUEST["ajax_send"] == 'Y'): ?>
<?
		$APPLICATION->IncludeComponent(
			($_REQUEST["WEB_FORM_ID"] == "5" ? "custom:form.request" : "custom:form.aboniment"), 
			"clubs", 
			array(
				"AJAX_MODE" => "N",
				"WEB_FORM_ID" => ($_REQUEST["WEB_FORM_ID"] == "5" ? "5" : "3"),
				"NUMBER" => $_REQUEST["clud"],
				"TEXT_FORM" => $_REQUEST["text_form"],
			),
			false
		);
	?>
<? elseif ($_REQUEST["ajax_menu"] && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'): ?>
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
		"SET_TITLE" => "N",
		"SET_CANONICAL_URL" => "N",
		"SET_BROWSER_TITLE" => "N",
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

<?
if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>