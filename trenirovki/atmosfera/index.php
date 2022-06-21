<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Атмосфера");
$APPLICATION->SetPageProperty("title", "Атмосфера - сеть фитнес-клубов Spirit Fitness в Москве");
$APPLICATION->SetPageProperty("description", "Окунитесь в уникальную атмосферу 🎧 фитнесс-клубов Spirit. Fitness. Новое оборудование. Современный качественный дизайн. Удобная ежемесячная оплата!");
?>
<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'atmosfera']); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>