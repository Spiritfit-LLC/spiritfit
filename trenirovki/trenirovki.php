<?
define('HIDE_SLIDER', true);
define('ANCHOR_PERSONAL', true);
define('HOLDER_CLASS', 'trainings');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("title", "Тренировки - Spirit. Fitness - сеть фитнес-клубов в Москве и Московской области");
$APPLICATION->SetPageProperty("description", "Более 160 групповых уроков в расписании 🤩 от йоги до силовых. Звоните и наши консультанты все вам расскажут и погут подобрать групповые тренировки");

$settings = Utils::getInfo();
$APPLICATION->SetTitle($settings["PROPERTIES"]["TRAINING_TITLE1"]["VALUE"]);
?>
    <div class="content-center company">
        <? if( false && !empty($settings["PROPERTIES"]["TRAINING_TITLE1"]["VALUE"]) ) { ?>
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$settings["PROPERTIES"]["TRAINING_TITLE1"]["VALUE"]?></h2>
                </div>
            </div>
        <? } ?>
        <div class="company-description">
            <?=!empty($settings["PROPERTIES"]["TRAINING_TEXT"]["~VALUE"]["TEXT"]) ? $settings["PROPERTIES"]["TRAINING_TEXT"]["~VALUE"]["TEXT"] : "" ?>
        </div>
    </div>
<?

$APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'trenazhernyy-zal', 'blockTitle' => $settings["PROPERTIES"]["TRAINING_TITLE2"]["VALUE"]], ['SHOW_BORDER' => false]);

if( !empty($settings["PROPERTIES"]["TRAINING_TITLE3"]["VALUE"]) ) {
    ?>
    <div class="content-center">
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2><?=$settings["PROPERTIES"]["TRAINING_TITLE3"]["VALUE"]?></h2>
            </div>
        </div>
    </div>
    <?
}

$APPLICATION->IncludeFile("/local/include/clubs.php");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");