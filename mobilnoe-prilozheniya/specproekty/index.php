<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Специальное");
$APPLICATION->SetPageProperty("title", "Спецпроекты мобильного приложения - приложение для самостоятельных тренировок сети фитнес-клубов Spirit Fitness");
$APPLICATION->SetPageProperty("description", "Спецпроекты Spirit. Fitness: программы онлайн-тренировок разных уровней сложности в мобильном приложении.");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'specproekty'], ['SHOW_BORDER' => false]); ?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>