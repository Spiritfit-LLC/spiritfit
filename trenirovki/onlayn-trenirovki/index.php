<?
	define('HIDE_SLIDER', true);
	define('ANCHOR_PERSONAL', true);
	define('HOLDER_CLASS', 'trainings');
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetPageProperty("title", "Тренировки онлайн");
	$APPLICATION->SetPageProperty("description", "");
	
	$settings = Utils::getInfo();
	$APPLICATION->SetTitle($settings["PROPERTIES"]["OTRAINING_TITLE1"]["VALUE"]);
	?>
	<div class="content-center company">
        <? if( false && !empty($settings["PROPERTIES"]["OTRAINING_TITLE1"]["VALUE"]) ) { ?>
        	<div class="b-cards-slider__heading">
            	<div class="b-cards-slider__title">
                	<h2><?=$settings["PROPERTIES"]["OTRAINING_TITLE1"]["VALUE"]?></h2>
            	</div>
			</div>
		<? } ?>

		<div class="company-description">
			<?=!empty($settings["PROPERTIES"]["OTRAINING_TEXT"]["~VALUE"]["TEXT"]) ? $settings["PROPERTIES"]["OTRAINING_TEXT"]["~VALUE"]["TEXT"] : "" ?>
		</div>
    </div>
	<?
	
	$APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'onlayn-trenirovki', 'blockTitle' => $settings["PROPERTIES"]["OTRAINING_TITLE2"]["VALUE"]], ['SHOW_BORDER' => false]);
	
	if( !empty($settings["PROPERTIES"]["OTRAINING_TITLE3"]["VALUE"]) ) {
		?>
			<div class="content-center">
        		<div class="b-cards-slider__heading">
            		<div class="b-cards-slider__title">
                		<h2><?=$settings["PROPERTIES"]["OTRAINING_TITLE3"]["VALUE"]?></h2>
            		</div>
				</div>
			</div>
		<?
	}
	
	$APPLICATION->IncludeFile("/local/include/clubs.php");
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");