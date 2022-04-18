<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($USER->IsAdmin()){
	file_put_contents(__DIR__.'/debug.txt', print_r($_REQUEST, true), FILE_APPEND);
	file_put_contents(__DIR__.'/debug.txt', print_r(" \n ============= \n", true), FILE_APPEND);
}



$APPLICATION->IncludeComponent(
	"custom:form.callback", 
	"popup", 
	array(
		"AJAX_MODE" => "N",
		"WEB_FORM_ID" => "1",
	),
	false
);?>
