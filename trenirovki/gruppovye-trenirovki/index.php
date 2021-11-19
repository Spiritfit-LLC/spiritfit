<?
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Групповые тренировки");
$APPLICATION->SetPageProperty("title", "Групповые тренировки - фитнес-клуб Spirit. Fitness");
$APPLICATION->SetPageProperty("description", "Сеть фитнес-клубов Spirit. Fitness представляет разнообразные групповые тренировки на любой вкус. Наши тренировки включают в себя авторские форматы Spirit.Team и известные во всем мире программы Les Mills.");
?>

<? $APPLICATION->IncludeFile('/local/include/blocks-detail.php', ['ELEMENT_CODE' => 'gruppovye-trenirovki'], ['SHOW_BORDER' => false]); ?>
<? $APPLICATION->IncludeFile('/local/include/group-workouts.php') ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>