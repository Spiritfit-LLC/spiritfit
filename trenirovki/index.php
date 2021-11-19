<?
define('BREADCRUMB_H1_ABSOLUTE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тренировки");
$APPLICATION->SetPageProperty("title", "Тренировки - Spirit. Fitness - сеть фитнес-клубов в Москве и Московской области");
$APPLICATION->SetPageProperty("description", "Более 160 групповых уроков в расписании 🤩 от йоги до силовых. Звоните и наши консультанты все вам расскажут и погут подобрать групповые тренировки");

?>

<? $APPLICATION->IncludeFile('/local/include/group-workouts.php') ?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'trenazhernyy-zal'], ['SHOW_BORDER' => false]); ?>
<? 
// $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'onlayn-trenirovki'], , ['SHOW_BORDER' => false]); 
?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'zal-gruppovykh-trenirovok'], ['SHOW_BORDER' => false]); ?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'atmosfera'], ['SHOW_BORDER' => false]); ?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>