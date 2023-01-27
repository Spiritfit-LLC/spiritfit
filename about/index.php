<?php
define('HIDE_SLIDER', true);
define('ANCHOR_PERSONAL', true);
define('HOLDER_CLASS', 'trainings');
define('H1_BIG', true);


define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION;
$APPLICATION->SetPageProperty("description", "Спирит Фитнес – это сеть фитнес клубов в Москве и Московской области 🏋 Вас впечатлит атмосфера наших клубов, тренера и оборудование 📞 8 (495) 266-40-95");
$APPLICATION->SetPageProperty("title", "О компании | SpiritFit.ru");

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'css/training-page.css');
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
        "ABOUT_*",
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
unset($rows, $filter, $order);

$PROPS=$objects[$ELEMENT_ID]["PROPERTIES"];
$APPLICATION->SetTitle($PROPS["ABOUT_PAGE_TITLE"]["VALUE"]);
$APPLICATION->AddViewContent('inhead', CFile::GetPath($PROPS["ABOUT_BANNER"]["VALUE"]));
?>
<style>
    .contact-item__title {
        font-size: 14pt;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .contacts-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    .contact-item__digital {
        margin-bottom: 20px;
    }
    .contact-item__social {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    a.social-link{
        margin-bottom: 10px;
    }
    @media screen and (max-width: 1024px) {
        .contact-item {
            min-width: 33%;
            margin-bottom: 50px;
        }
    }
    @media screen and (max-width: 497px) {
        .contacts-container {
            display: flex;
            flex-direction: column;
        }
        .contact-item:last-child{
            margin-bottom: 0;
        }

    }
</style>
<?if (!empty($PROPS["ABOUT_BANNER"]["VALUE"])):?>
    <div class="banner-detail__img" style="background-image: url(<?=CFile::GetPath($PROPS["ABOUT_BANNER"]["VALUE"])?>)"></div>
    <div class="content-center">
        <?for($i=0; $i<count($PROPS["ABOUT_BANNER_TEXT"]["VALUE"]); $i++):?>
            <div class="banner-detail__description-item">
                <?if (!empty($PROPS["ABOUT_BANNER_TEXT"]["DESCRIPTION"][$i])):?>
                    <div class="banner-detail__description-title">
                        <h2 class="text-transform-none"><?=$PROPS["ABOUT_BANNER_TEXT"]["DESCRIPTION"][$i]?></h2>
                    </div>
                <?endif;?>
                <div class="banner-detail__description-content">
                    <?=htmlspecialcharsback($PROPS["ABOUT_BANNER_TEXT"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
<?php endif;?>
<?php if (!empty($PROPS["ABOUT_SLIDER"]["VALUE"])):?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "blocks.abonements",
        array(
            "COMPONENT_TEMPLATE" => "",
            "IBLOCK_TYPE" => "service",
            "IBLOCK_ID" => "18",
            "BLOCK_TITLE" => "",
            "ELEMENT_ID" => $PROPS["ABOUT_SLIDER"]["VALUE"],
            "ELEMENT_CODE" => "",
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
<?php endif;?>
<?php if (!empty($PROPS["ABOUT_FORM_TYPE"])):?>
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
                "FORM_TYPE" =>$PROPS["ABOUT_FORM_TYPE"],
                "TEXT_FORM" => $PROPS["ABOUT_FORM_TITLE"]["VALUE"],
            ));
        ?>
    </section>
<?php endif;?>
<?php //if (false):?>
<section id="contacts">
    <div class="content-center">
        <div class="b-section__title">
            <h2>Контакты</h2>
        </div>
    </div>
    <div class="contacts-container content-center">
        <div class="contact-item">
            <h4 class="contact-item__title">Офис</h4>
            <div class="contact-item__local-address" style="background-image: url(<?=SITE_TEMPLATE_PATH.'/img/icons/map-point.svg'?>);padding-left: 20px;background-repeat: no-repeat;background-size: auto 18px;background-position-y: center;">
                    <a href="https://yandex.ru/maps/-/CCUzrIe2KB" target="_blank" class="review-link " >Москва, Карамышевская набережная, 44</a>
            </div>
        </div>
        <div class="contact-item">
            <h4 class="contact-item__title">Телефон</h4>
            <div class="contact-item__digital">
                <a href="tel:+74951059797" class="gradient-text">+7 (495) 105-97-97</a>
            </div>
            <h4 class="contact-item__title">Эл. почта</h4>
            <div class="contact-item__digital">
                <a href="mailto:example@spiritfit.ru" class="gradient-text">example@spiritfit.ru</a>
            </div>
        </div>
        <div class="contact-item">
            <h4 class="contact-item__title">Мы в соц. сетях</h4>
            <div class="contact-item__social">
                <a href="https://t.me/spiritfitness_official" class="gradient-text social-link">Telegram</a>
                <a href="https://vk.com/spiritmoscow" class="gradient-text social-link">Вконтакте</a>
                <a href="http://www.tiktok.com/@spiritfitness" class="gradient-text social-link">TikTok</a>
                <a href="https://zen.yandex.ru/id/6017f57288bc0d2cb7405dc6" class="gradient-text social-link">Дзен</a>
            </div>
        </div>
    </div>
</section>
<?php //endif;?>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>



<?php
//define('HIDE_SLIDER', true);
//define('BREADCRUMB_H1_ABSOLUTE', true);
////define('H1_BIG_COLORFUL', true);
//
//
//require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
//use Bitrix\Main\Page\Asset;
//
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.js');
//Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.css');
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/service.page.js');
//Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/service.page.css');
//
//
//$APPLICATION->SetTitle("О компании | SpiritFit.ru");
//
//$APPLICATION->SetPageProperty("description", "Спирит Фитнес – это сеть фитнес клубов в Москве и Московской области 🏋 Вас впечатлит атмосфера наших клубов, тренера и оборудование 📞 8 (495) 266-40-95");
//$APPLICATION->SetPageProperty("title", "О компании | SpiritFit.ru");
//?>
<?php
//$ELEMENT_ID=Utils::GetIBlockElementIDBySID("service-page-settings");
//$objects=[];
//$filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>Utils::GetIBlockIDBySID("service-page"), 'ID'=>$ELEMENT_ID];
//$order = array();
//
//$rows = CIBlockElement::GetList($order, $filter);
//while ($row = $rows->fetch()) {
//    $row['PROPERTIES'] = [];
//    $objects[$row['ID']] =& $row;
//    unset($row);
//}
//
//$propertyFilter=[
//    "CODE"=>[
//        "ABOUT_SHORT_DESC",
//        "ABOUT_BUTTON",
//        "ABOUT_IMAGES",
//        "ABOUT_PAGE_DESC1",
//        "ABOUT_PAGE_DESC2",
//        "ABOUT_PAGE_DESC3",
//        "ABOUT_FORM_TYPE",
//        "ABOUT_FORM_SID",
//        "ABOUT_FORM_TITLE",
//        "ABOUT_BLOCK"
//    ]
//];
//
//CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
//unset($rows, $filter, $order);
//$includeParams=[
//    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_SHORT_DESC"]["VALUE"]["TEXT"],
//    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BUTTON"]["VALUE"],
//    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BUTTON"]["DESCRIPTION"],
//    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_IMAGES"]["VALUE"],
//];
//
//$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
//?>
<!--<section class="page-white-description">-->
<!--    <div class="content-center">-->
<!--        <div class="desc__block">-->
<!--            --><?//for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["VALUE"]); $i++):?>
<!--                --><?//if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["DESCRIPTION"][$i])):?>
<!--                    <div class="b-cards-slider__heading">-->
<!--                        <div class="b-cards-slider__title">-->
<!--                            <h2>--><?//=$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["DESCRIPTION"][$i]?><!--</h2>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                --><?//endif;?>
<!--                <div class="desc__block__text">-->
<!--                    --><?//=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["VALUE"][$i]["TEXT"])?>
<!--                </div>-->
<!--            --><?//endfor;?>
<!--            <div class="desck-blog__big-text">-->
<!--                --><?//=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC2"]["VALUE"]["TEXT"])?>
<!--            </div>-->
<!--            --><?//for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["VALUE"]); $i++):?>
<!--                --><?//if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["DESCRIPTION"][$i])):?>
<!--                    <div class="b-cards-slider__heading">-->
<!--                        <div class="b-cards-slider__title">-->
<!--                            <h2>--><?//=$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["DESCRIPTION"][$i]?><!--</h2>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                --><?//endif;?>
<!--                <div class="desc__block__text">-->
<!--                    --><?//=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["VALUE"][$i]["TEXT"])?>
<!--                </div>-->
<!--            --><?//endfor;?>
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!--<div style="margin-top:80px">-->
<!--    --><?//
//    $dbRes=CIBlockElement::GetByID($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BLOCK"]["VALUE"]);
//    $code=$dbRes->Fetch()["CODE"];
//    ?>
<!--    --><?// $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => $code], ['SHOW_BORDER' => false]); ?>
<!--</div>-->
<?//if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TYPE"]["VALUE"])):?>
<!--    <section id="form-request" style="margin-top: 80px;">-->
<!--        --><?//
//        $APPLICATION->IncludeComponent(
//            "custom:form.request.new",
//            "on.page.block",
//            array(
//                "COMPONENT_TEMPLATE" => "on.page.block",
//                "WEB_FORM_ID" => Utils::GetFormIDBySID($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_SID"]["VALUE"]),
//                "WEB_FORM_FIELDS" => array(
//                    0 => "club",
//                    1 => "name",
//                    2 => "phone",
//                    3 => "email",
//                    4 => "personaldata",
//                    5 => "rules",
//                    6 => "privacy",
//                ),
//                "FORM_TYPE" =>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TYPE"]["VALUE"],
//                "TEXT_FORM" => $objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TITLE"]["VALUE"]
//            ),
//            false);
//        ?>
<!--    </section>-->
<?//endif;?>
<!---->
<?//require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>