<?php

define('HIDE_SLIDER', true);
//define('HOLDER_CLASS', 'company-holder');
//define('H1_BIG_COLORFUL', true);
define('BREADCRUMB_H1_ABSOLUTE', true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/owl.carousel/owl.carousel.min.css');


$APPLICATION->SetTitle("Тренировки CYCLE");

$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "Тренировки CYCLE | SpiritFit.ru");

$settings = Utils::getInfo();
?>
<style>
    .b-screen:after{
        content:none;
    }
    section.page-white-description {
        background: white;
        color: black;
        padding: 80px;
        line-height: 1.5em;
        /* margin-bottom: 50px; */
    }
    .page-white-description div{
        margin-bottom: 20px;
    }
    .page-white-description ul {
        display: block;
        position: relative;
        list-style: none;
        margin: 10px 0px;
        padding-left: 50px;
    }
    .page-white-description ul > li {
        display: block;
        position: relative;
        box-sizing: border-box;
        padding-left: 18px;
        margin-bottom: 2px;
    }
    .page-white-description ul > li:before {
        content: "";
        display: block;
        position: absolute;
        left: 0px;
        top: 10px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #FC6120;
    }
    .page-white-description b {
        font-weight: 600;
    }
    .page-white-description li{
        margin: 10px 0;
    }
    .page-white-description ol li {
        padding-left: 20px;
    }
    .page-white-description a{
        font-weight: 500;
    }
    .page-white-description .desc__block {
        margin-bottom: 50px !important;
    }
    .desc__block:last-child{
        margin-bottom: 0!important;
    }
</style>
<div class="content-center">
    <div class="page-hiden-slider__header">
        <div class="page-desc-short">
            <div class="page-desc-short__text">
                <?=htmlspecialcharsback($settings["PROPERTIES"]["CYCLE_TRAINING_SHORT_DESC"]["VALUE"]["TEXT"])?>
            </div>
            <div class="page-desc-short__btn">
                <a class="page-desc__request-btn button-outline" href="#form-request">записаться</a>
            </div>
        </div>
        <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
            <div class="owl-carousel">
                <?foreach ($settings["PROPERTIES"]["CYCLE_TRAINING_BANNER_IMGS"]["VALUE"] as $IMG):?>
                    <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')">

                    </div>
                <?
                endforeach;?>
            </div>
        </div>
    </div>
</div>
<section class="cycle-description page-white-description" style="margin-top: 80px;">
    <div class="content-center">
        <?for ($i=0; $i<count($settings["PROPERTIES"]["CYCLE_TRAINING_DESC"]["VALUE"]); $i++):?>
            <div class="ft-desc__block desc__block">
                <?if (!empty($settings["PROPERTIES"]["CYCLE_TRAINING_DESC"]["DESCRIPTION"][$i])):?>
                    <div class="b-cards-slider__heading">
                        <div class="b-cards-slider__title">
                            <h2><?=$settings["PROPERTIES"]["CYCLE_TRAINING_DESC"]["DESCRIPTION"][$i]?></h2>
                        </div>
                    </div>
                <?endif;?>
                <div class="ft-desc__text">
                    <?=htmlspecialcharsback($settings["PROPERTIES"]["CYCLE_TRAINING_DESC"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
</section>
<section style="margin-top: 80px">
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
</section>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'atmosfera'], ['SHOW_BORDER' => false]); ?>
<section id="form-request" style="margin-top: 80px;">
    <?
    $APPLICATION->IncludeComponent(
        "custom:form.request.new",
        "on.page.block",
        array(
            "COMPONENT_TEMPLATE" => "on.page.block",
            "WEB_FORM_ID" => Utils::GetFormIDBySID($settings["PROPERTIES"]["CYCLE_TRAINING_FORM_SID"]["VALUE"]),
            "WEB_FORM_FIELDS" => array(
                0 => "club",
                1 => "name",
                2 => "phone",
                3 => "email",
                4 => "personaldata",
                5 => "rules",
                6 => "privacy",
            ),
            "FORM_TYPE" =>$settings["PROPERTIES"]["CYCLE_TRAINING_FORM_TYPE"]["VALUE"],
            "TEXT_FORM" => $settings["PROPERTIES"]["CYCLE_TRAINING_FORM_TITLE"]["VALUE"]
        ),
        false);
    ?>
</section>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>