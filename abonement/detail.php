<?
define('BREADCRUMB_H1_ABSOLUTE', true);

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}
global $USER;
$trial = strpos($APPLICATION->GetCurPage(), "probnaya-trenirovka");

if ($_REQUEST["ajax_menu"] == 'true' && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'): ?>
	<? 

	else: ?>
		<div id="js-pjax-container">
			<? if ($trial){
				$APPLICATION->IncludeComponent(
					"custom:form.aboniment", 
					"trial", 
					array(
						"AJAX_MODE" => "N",
						"WEB_FORM_ID" => "3",
						"ADD_ELEMENT_CHAIN" => "N",
					),
					false
				);
			}else{
				$APPLICATION->IncludeComponent(
					"custom:form.aboniment", 
					"", 
					array(
						"AJAX_MODE" => "N",
						"WEB_FORM_ID" => "2",
						"ADD_ELEMENT_CHAIN" => "N",
					),
					false
				);
			}?>
		</div>
<?endif;

if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>