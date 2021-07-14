<?
define('HIDE_BREADCRUMB', true);
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}
global $USER;

if ($_REQUEST["ajax_menu"] == 'true' && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'): ?>
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

	else: 		
		$APPLICATION->IncludeComponent(
			"custom:form.aboniment", 
			"trial-formonly", 
			array(
				"AJAX_MODE" => "N",
				"WEB_FORM_ID" => "3",
				"ADD_ELEMENT_CHAIN" => "N",
				"SKIP_CHECKS" => "Y"
			),
			false
		);
endif;

if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>