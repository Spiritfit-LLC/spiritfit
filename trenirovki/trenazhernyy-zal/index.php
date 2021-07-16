<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тренажерный зал");
$APPLICATION->SetPageProperty("title", "Тренажерный зал в Москве - современная сеть фитнес-клубов Spirit. Fitness");
$APPLICATION->SetPageProperty("description", "Современный и доступный фитнес-клуб Spirit. Fitness с просторным тренажерным залом рядом с вами. Новые тарифы! Удобная ежемесячная оплата, бесплатная пробная тренировка. Запишись прямо сейчас! ");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'trenazhernyy-zal'], ['SHOW_BORDER' => false]); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>