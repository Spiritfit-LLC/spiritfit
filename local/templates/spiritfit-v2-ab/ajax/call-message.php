<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"custom:form.callback", 
	"message", 
	array(
		"AJAX_MODE" => "N",
		"WEB_FORM_ID" => "4",
		"CLIENT_TYPE" => "siteFeedback"
	),
	false
);?>
