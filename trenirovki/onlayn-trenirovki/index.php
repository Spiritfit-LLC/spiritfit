<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Онлайн тренировки");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'onlayn-trenirovki'], ['SHOW_BORDER' => false]); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>