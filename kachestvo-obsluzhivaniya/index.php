<?
define('HIDE_BREADCRUMB', true);
define('HIDE_SLIDER', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Качество обслуживания");
?>
<div class="quality form-interview-wrapper">
    <div class="quality__questionary">
        <?//<h2 class="quality__subheading">Оценка качества обслуживания</h2>?>
        <div class="primary-form quality__form" id="body_quality">
            <div class="primary-form__staging">
				<?
					$APPLICATION->IncludeComponent(
						"custom:form.interview", 
						".default", 
						array(
							"AJAX_MODE" => "N",
							"WEB_FORM_ID" => "9",
							"ADD_ELEMENT_CHAIN" => "N",
							"EXCEL_FILE" => "out_4fdyu693.xlsx",
							"IBLOCK_CODE" => "clubs",
							"SKIP_CHECKS" => "N",
							"THANKS" => "Спасибо, Ваше сообщение отправлено.",
						),
						false
					);
				?>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>