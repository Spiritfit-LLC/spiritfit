<?
	define('HIDE_SLIDER', true);
	define('ANCHOR_TIMETABLE', true);
	define('HOLDER_CLASS', 'trainings');
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetPageProperty("title", "Групповые занятия по фитнесу в Москве: тренировки в сети клубов Spirit Fitness");
	$APPLICATION->SetPageProperty("description", "Групповые занятия по фитнесу в фитнес-клубах Spirit Fitness &#128165; Тарифы от 1490 ₽ &#128181; с ежемесячной оплатой, бесплатная пробная тренировка &#128293; Запишитесь прямо сейчас!");
	
	$settings = Utils::getInfo();
	$APPLICATION->SetTitle($settings["PROPERTIES"]["GT_TITLE2"]["VALUE"]);
	
	?>
	<div class="content-center company">
        <? if( false && !empty($settings["PROPERTIES"]["GT_TITLE2"]["VALUE"]) ) { ?>
        	<div class="b-cards-slider__heading">
            	<div class="b-cards-slider__title">
                	<h2><?=$settings["PROPERTIES"]["GT_TITLE2"]["VALUE"]?></h2>
            	</div>
			</div>
		<? } ?>

		<div class="company-description">
			<?=!empty($settings["PROPERTIES"]["GT_TEXT"]["~VALUE"]["TEXT"]) ? $settings["PROPERTIES"]["GT_TEXT"]["~VALUE"]["TEXT"] : "" ?>
		</div>
    </div>
	<?
	
	if( !empty($settings["PROPERTIES"]["MEMBERS_WORKOUTS"]["VALUE"]) ) {
		global $workoutsFilter;
		$workoutsFilter = ["ACTIVE" => "Y", "ID" => $settings["PROPERTIES"]["MEMBERS_WORKOUTS"]["VALUE"]];
	}
	$APPLICATION->IncludeFile('/local/include/group-workouts.php', ['blockTitle' => $settings["PROPERTIES"]["GT_TITLE1"]["VALUE"], "filterName" => "workoutsFilter", "class" => "workouts"]);
	
	if( !empty($settings["PROPERTIES"]["GT_TITLE3"]["VALUE"]) ) {
		?>
			<div class="content-center">
        		<div class="b-cards-slider__heading">
            		<div class="b-cards-slider__title">
                		<h2><?=$settings["PROPERTIES"]["GT_TITLE3"]["VALUE"]?></h2>
            		</div>
				</div>
			</div>
		<?
	}
	
	$APPLICATION->IncludeFile("/local/include/clubs.php");
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>