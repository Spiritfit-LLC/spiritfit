<?
	define('HIDE_SLIDER', true);
	define('BREADCRUMB_H1_ABSOLUTE', true);
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	
	$APPLICATION->SetTitle("О компании");
	$APPLICATION->SetPageProperty("description", "");
	$APPLICATION->SetPageProperty("title", "");
	
	$settings = Utils::getInfo();
	
	$APPLICATION->IncludeComponent(
		"custom:form.callback.v2", 
		"", 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => "10",
			"FORM_TYPE" => "1",
			"ACTION_TYPE" => "web_site_contact",
			"BLOCK_TITLE" => ""
		),
		false
	);
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>