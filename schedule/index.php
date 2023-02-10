<?php
//define('HIDE_SLIDER', true);
define("HIDE_TITLE", true);

define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Расписание групповых тренировок");
$APPLICATION->SetPageProperty("description", "Расписание групповых тренировок");
$APPLICATION->SetPageProperty("title", "Расписание групповых тренировок");
?>

<?php
$APPLICATION->IncludeComponent(
    "custom:shedule.club",
    "profitator.style",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_CODE" => "clubs",
        "CLUB_ID" => $_REQUEST["ELEMENT_ID"],
    ),
    false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
