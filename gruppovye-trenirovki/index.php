<?
	define('HIDE_SLIDER', true);
	define('ANCHOR_TIMETABLE', true);
	define('HOLDER_CLASS', 'trainings');
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetTitle("Групповые тренировки");
	$APPLICATION->SetPageProperty("title", "Групповые тренировки - фитнес-клуб Spirit. Fitness");
	$APPLICATION->SetPageProperty("description", "Сеть фитнес-клубов Spirit. Fitness представляет разнообразные групповые тренировки на любой вкус. Наши тренировки включают в себя авторские форматы Spirit.Team и известные во всем мире программы Les Mills.");
	
	$settings = Utils::getInfo();
	
	if( !empty($settings["PROPERTIES"]["TRAINING_TITLE3"]["VALUE"]) ) {
		?>
			<div class="content-center">
        		<div class="b-cards-slider__heading">
            		<div class="b-cards-slider__title">
                		<h2><?=$settings["PROPERTIES"]["TRAINING_TITLE3"]["VALUE"]?></h2>
            		</div>
				</div>
			</div>
		<?
	}
	
	$APPLICATION->IncludeFile("/local/include/clubs.php");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'gruppovye-trenirovki'], ['SHOW_BORDER' => false]); ?>
<? $APPLICATION->IncludeFile('/local/include/group-workouts.php') ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>