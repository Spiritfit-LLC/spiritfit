<?php
define('HIDE_SLIDER', true);
define('ANCHOR_ONLINE', true);
define('HOLDER_CLASS', 'trainings');
define('H1_BIG', true);


define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->SetPageProperty("title", "Онлайн-тренировки с Spirit Fitness: онлайн занятия фитнесом в домашних условиях");
$APPLICATION->SetPageProperty("description", "Онлайн-тренировки дома от фитнес-клуба Spirit Fitness &#128165; Тарифы от 1490 ₽ &#128181; с ежемесячной оплатой, бесплатная пробная тренировка &#128293; Запишитесь прямо сейчас!");

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'css/training-page.css')
?>

<?php
$ELEMENT_ID=Utils::GetIBlockElementIDBySID("service-page-settings");
$objects=[];
$filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>Utils::GetIBlockIDBySID("service-page"), 'ID'=>$ELEMENT_ID];
$order = array();

$rows = CIBlockElement::GetList($order, $filter);
while ($row = $rows->fetch()) {
    $row['PROPERTIES'] = [];
    $objects[$row['ID']] =& $row;
    unset($row);
}

$propertyFilter=[
    "CODE"=>[
        "ONLINE_TRAINING_*",
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
unset($rows, $filter, $order);

$PROPS=$objects[$ELEMENT_ID]["PROPERTIES"];

$APPLICATION->SetTitle($PROPS["ONLINE_TRAINING_PAGE_TITLE"]["VALUE"]);
?>
<?if (!empty($PROPS["ONLINE_TRAINING_BANNER"]["VALUE"])):?>
    <div class="banner-detail__img" style="background-image: url(<?=CFile::GetPath($PROPS["ONLINE_TRAINING_BANNER"]["VALUE"])?>)"></div>
    <div class="content-center">
        <?for($i=0; $i<count($PROPS["ONLINE_TRAINING_BANNER_TEXT"]["VALUE"]); $i++):?>
            <div class="banner-detail__description-item">
                <?if (!empty($PROPS["ONLINE_TRAINING_BANNER_TEXT"]["DESCRIPTION"][$i])):?>
                    <div class="banner-detail__description-title">
                        <h2 class="text-transform-none"><?=$PROPS["ONLINE_TRAINING_BANNER_TEXT"]["DESCRIPTION"][$i]?></h2>
                    </div>
                <?endif;?>
                <div class="banner-detail__description-content">
                    <?=htmlspecialcharsback($PROPS["ONLINE_TRAINING_BANNER_TEXT"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
<?endif;?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "blocks",
    array(
        "COMPONENT_TEMPLATE" => "",
        "IBLOCK_TYPE" => "service",
        "IBLOCK_ID" => "18",
        "BLOCK_TITLE" => "",
        "ELEMENT_ID" => "",
        "ELEMENT_CODE" => "kak-poluchit-dostup-k-onlayn-trenirovkam",
        "ADDITIONAL_CLASS" => '',
        "CHECK_DATES" => "Y",
        "FIELD_CODE" => array(),
        "PROPERTY_CODE" => array(
            0 => "BLOCK_TEXT",
            1 => "BLOCK_BTN_TEXT",
            2 => "BLOCK_LINK",
            3 => "BLOCK_VIDEO_YOUTUBE",
            4 => "BLOCK_PREVIEW",
            5 => "BLOCK_PHOTO",
            6 => "BLOCK_VIEW",
            7 => "BLOCK_TITLE_LINK",
        ),
        "IBLOCK_URL" => "",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "N",
        "SET_TITLE" => "N",
        "SET_CANONICAL_URL" => "N",
        "SET_BROWSER_TITLE" => "N",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "N",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "N",
        "META_DESCRIPTION" => "-",
        "SET_LAST_MODIFIED" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_ELEMENT_CHAIN" => "N",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "USE_PERMISSIONS" => "N",
        "STRICT_SECTION_CHECK" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "USE_SHARE" => "N",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Страница",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "FILE_404" => ""
    ),
    false
);?>
    <section id="form" style="margin-top: 80px;">
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
                "TEXT_FORM" => $PROPS["PERSONAL_TRAINING_FORM_TITLE"]["VALUE"],
            ));
        ?>
    </section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
