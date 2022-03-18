<?
	define('HIDE_SLIDER', true);
	define('HOLDER_CLASS', 'company-holder');
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	
	$APPLICATION->SetTitle("О компании");
	$APPLICATION->SetPageProperty("description", "");
	$APPLICATION->SetPageProperty("title", "");
	
	$settings = Utils::getInfo();

	?>
	<div class="content-center company">
        <? if( !empty($settings["PROPERTIES"]["ABOUT_TITLE1"]["VALUE"]) ) { ?>
        	<div class="b-cards-slider__heading">
            	<div class="b-cards-slider__title">
                	<h2><?=$settings["PROPERTIES"]["ABOUT_TITLE1"]["VALUE"]?></h2>
            	</div>
			</div>
		<? } ?>

		<div class="company-description">
			<?=!empty($settings["PROPERTIES"]["ABOUT_TEXT"]["~VALUE"]["TEXT"]) ? $settings["PROPERTIES"]["ABOUT_TEXT"]["~VALUE"]["TEXT"] : "" ?>
		</div>
    </div>
	<?

	$APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'o-kompanii', 'blockTitle' => $settings["PROPERTIES"]["ABOUT_TITLE2"]["VALUE"]], ['SHOW_BORDER' => false]);
	
	$APPLICATION->IncludeComponent(
		"custom:form.callback.v2",
		"", 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => "10",
			"FORM_TYPE" => "14",
			"ACTION_TYPE" => "web_site_contact",
			"BLOCK_TITLE" => $settings["PROPERTIES"]["ABOUT_TITLE3"]["VALUE"]
		),
		false
	);
	
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>