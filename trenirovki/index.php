<?
define('BREADCRUMB_H1_ABSOLUTE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тренировки");
$APPLICATION->SetPageProperty("title", "Тренировки - фитнес-программы для занятий в сети фитнес-клубов Spirit Fitness");
$APPLICATION->SetPageProperty("description", "Более 160 групповых уроков в расписании 🤩 от йоги до силовых. Звоните и наши консультанты все вам расскажут и помогут подобрать групповые тренировки");

?>
<section id="group-workouts">
    <? $APPLICATION->IncludeFile('/local/include/group-workouts.php') ?>
</section>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'trenazhernyy-zal'], ['SHOW_BORDER' => false]); ?>
<?
// $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'onlayn-trenirovki'], , ['SHOW_BORDER' => false]);
?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'zal-gruppovykh-trenirovok'], ['SHOW_BORDER' => false]); ?>
<? $APPLICATION->IncludeComponent(
    "custom:shedule.club",
    "profitator.style",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_CODE" => "clubs",
        "CLUB_NUMBER" => "11",
    ),
    false
); ?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'atmosfera'], ['SHOW_BORDER' => false]); ?>
<?
$APPLICATION->IncludeComponent(
    "custom:form.request.new",
    "on.page.block",
    array(
        "COMPONENT_TEMPLATE" => "on.page.block",
        "WEB_FORM_ID" => Utils::GetFormIDBySID("TRIAL_TRAINING_NEW"),
        "WEB_FORM_FIELDS" => array(
            0 => "club",
            1 => "name",
            2 => "phone",
            3 => "email",
            4 => "personaldata",
            5 => "rules",
            6 => "privacy",
        ),
        "FORM_TYPE" =>"3",
    ),
    false);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>