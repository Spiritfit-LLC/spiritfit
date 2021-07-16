<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Атмосфера");
$APPLICATION->SetPageProperty("title", "Атмосфера - сеть фитнес-клубов Spirit. Fitness в Москве");
$APPLICATION->SetPageProperty("description", "Современный и доступный фитнес-клуб Spirit. Fitness рядом с вами. Окунитесь в уникальную атмосферу клубов Spirit. Fitness. Новые тарифы! Удобная ежемесячная оплата, бесплатная пробная тренировка. Запишись прямо сейчас!");
?>
<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'atmosfera']); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>