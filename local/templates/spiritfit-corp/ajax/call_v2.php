<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$APPLICATION->IncludeComponent(
	"custom:form.callback", 
	"popup_v2", 
	array(
		"AJAX_MODE" => "N",
		"WEB_FORM_ID" => "1",
		"CLIENT_TYPE" => "callBack",
		"DEFAULT_TYPE_ID" => "13"
	),
	false
);?>
