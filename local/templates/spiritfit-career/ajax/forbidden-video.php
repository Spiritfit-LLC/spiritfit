<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"custom:popup.authorization", 
	"choose-popup", 
	array(
		"AJAX_MODE" => "N",
	),
	false
);?>
