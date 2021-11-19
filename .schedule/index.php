<?
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
	echo "<title>Расписание групповых занятий</title>";
	$APPLICATION->SetTitle("Расписание групповых занятий");
	$APPLICATION->SetPageProperty("keywords", "spiritfit, fitness, расписание занятий, расписание");
	$APPLICATION->SetPageProperty("description", "Расписание групповых занятий клубов Spirit Fitness.");
	$APPLICATION->SetPageProperty("title", "Расписание групповых занятий фитнес-клубов Spirit Fitness");
} else {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	$APPLICATION->SetTitle("Расписание групповых занятий");
	$APPLICATION->SetPageProperty("keywords", "spiritfit, fitness, расписание занятий, расписание");
	$APPLICATION->SetPageProperty("description", "Расписание групповых занятий клубов Spirit Fitness.");
	$APPLICATION->SetPageProperty("title", "Расписание групповых занятий фитнес-клубов Spirit Fitness");
}
?>

<? if ($_REQUEST["ajax_menu"] && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true'): ?>
	<? $APPLICATION->IncludeComponent(
			"bitrix:menu", 
			"main-menu", 
			array(
				"ROOT_MENU_TYPE" => "top",
				"MAX_LEVEL" => "1",
				"CHILD_MENU_TYPE" => "top",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array(
				),
				"COMPONENT_TEMPLATE" => "main-menu"
			),
			false
		);
	?>
	<?$page = $APPLICATION->GetCurPage();
	$arSEOData = Utils::setSeoDiv($page, $APPLICATION);?>
	<div id="seo-div" hidden="true"
        data-title="<?=$arSEOData['META_TITLE']?>" 
        data-description="<?=$arSEOData['META_DESCRIPTION']?>" 
		data-keywords="<?=$arSEOData['META_KEYWORDS']?>"
		data-image="<?=$arSEOData['OG_IMG']?>"></div>
<? else: ?>
	<? $APPLICATION->IncludeComponent(
		"custom:shedule.club", 
		"", 
		array(
			"IBLOCK_TYPE" => "content",
			"IBLOCK_CODE" => "clubs"
		),
		false
	); ?>
<? endif; ?>

<?
if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>