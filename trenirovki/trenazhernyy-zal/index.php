<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тренажерный зал");
$APPLICATION->SetPageProperty("title", "Тренажерный зал в Москве - современная сеть фитнес-клубов Spirit. Fitness");
$APPLICATION->SetPageProperty("description", "Современный и доступный фитнес-клуб Spirit. Fitness с просторными тренажерными залами. У нас более 150 единиц оборудования - в очереди на тренажер вы стоять не будете!");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'trenazhernyy-zal'], ['SHOW_BORDER' => false]); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>