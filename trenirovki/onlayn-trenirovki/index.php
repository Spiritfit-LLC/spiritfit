<?
	define('HIDE_SLIDER', true);
	define('ANCHOR_PERSONAL', true);
	define('HOLDER_CLASS', 'trainings');
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetPageProperty("title", "Онлайн-тренировки с Spirit Fitness: онлайн занятия фитнесом в домашних условиях");
	$APPLICATION->SetPageProperty("description", "Онлайн-тренировки дома от фитнес-клуба Spirit Fitness &#128165; Тарифы от 1490 ₽ &#128181; с ежемесячной оплатой, бесплатная пробная тренировка &#128293; Запишитесь прямо сейчас!");
	
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