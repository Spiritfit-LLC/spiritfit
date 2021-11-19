<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?$APPLICATION->IncludeComponent(
	"custom:form.aboniment", 
	"modal-trial", 
	array(
		"AJAX_MODE" => "N",
		"WEB_FORM_ID" => "3",
		"ADD_ELEMENT_CHAIN" => "N",
		"CLUB_NUMBER" => $_REQUEST['CLUB_NUMBER'],
		"THANKS" => "Наш менеджер свяжется с Вами, ожидайте.",
	),
	false
);?>