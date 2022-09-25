<?
define('HIDE_SLIDER', true);
//define('HOLDER_CLASS', 'company-holder');
//define('H1_BIG_COLORFUL', true);
define('BREADCRUMB_H1_ABSOLUTE', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Page\Asset;


Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.css');


$APPLICATION->SetTitle("Разовое посещение");

$APPLICATION->SetPageProperty("description", "Разовое бесплатное посещение фитнес-клуба Spirit Fitness💥 Тарифы от 1700 ₽ 💵 с ежемесячной оплатой, бесплатная пробная тренировка 🔥 Запишитесь прямо сейчас!");
$APPLICATION->SetPageProperty("title", "Разовое посещение фитнес-клуба: бесплатное занятие в тренажерном зале");

$settings = Utils::getInfo();
?>
    <style>
        .b-screen:after{
            content:none;
        }
        section.single-visit-desc {
            /*margin-bottom: 80px;*/
            background: white;
            color: black;
            padding: 80px 0;
        }
        .single-visit-desc__text div{
            margin-bottom: 20px;
        }
        .single-visit-desc__text ul {
            display: block;
            position: relative;
            list-style: none;
            margin: 10px 0px;
            padding-left: 50px;
        }
        .single-visit-desc__text ul > li {
            display: block;
            position: relative;
            box-sizing: border-box;
            padding-left: 18px;
            margin-bottom: 2px;
        }
        .single-visit-desc__text ul > li:before {
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
        .single-visit-desc__text b {
            font-weight: 600;
        }
        .single-visit-desc__text li{
            margin: 10px 0;
        }
        .single-visit-desc__text {
            line-height: 1.5em;
        }
        .b-image-plate-block{
            margin-bottom: 0;
        }
        @media screen and (max-width: 1200px){
            .single-visit-desc__text ul{
                padding-left:20px!important;
            }
            section.single-visit-desc {
                padding: 50px 0;
            }
            section.single-visit-desc .b-cards-slider__title {
                margin-bottom: 30px;
            }
            .b-image-plate-block{
                margin-bottom: 50px;
            }
            section.single-visit-services{
                margin-bottom: 50px;
            }
        }
    </style>

    <div class="content-center" style="margin-bottom: 80px;">
        <div class="page-hiden-slider__header">
            <div class="page-desc-short">
                <div class="page-desc-short__text">
                    <?=htmlspecialcharsback($settings["PROPERTIES"]["SINGLE_VISIT_SHORT_DESC"]["VALUE"]["TEXT"])?>
                </div>
                <div class="page-desc-short__btn">
                    <a class="page-desc__request-btn button-outline" href="#form-request">Оставить заявку</a>
                </div>
            </div>
            <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
                <div class="owl-carousel">
                    <?foreach ($settings["PROPERTIES"]["SINGLE_VISIT_BANNER_IMGS"]["VALUE"] as $IMG):?>
                        <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')">

                        </div>
                    <?
                    endforeach;?>
                </div>
            </div>
        </div>
    </div>
    <section class="single-visit-services">
        <div class="content-center">
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$settings["PROPERTIES"]["SINGLE_VISIT_SERVICES_TITLE"]["VALUE"]?></h2>
                </div>
            </div>
            <?if (!empty($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"])):?>
            <?if (!is_array($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"])){
            $settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"]=[$settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"]];
        }?>
            <div class="sv-services__list">
                <?foreach ($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"] as $SERVICE):?>
                <div class="sv-services__item">
                    <?=$SERVICE?>
                </div>
                <?endforeach;?>
            </div>
            <?endif;?>
        </div>
    </section>
    <?
//    $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'pt-block'], ['SHOW_BORDER' => false]);
    $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'probnaya-trenirovka-block'], ['SHOW_BORDER' => false]);
    ?>
    <section id="form-request">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                "WEB_FORM_FIELDS" => array(
                    0 => "club",
                    1 => "name",
                    2 => "phone",
                    3 => "email",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" => "3",
                "TEXT_FORM" => "Оставьте заявку на разовое посещение в клуба сети SPIRIT.FITNESS"
            ),
            false);
        ?>
    </section>
    <section class="single-visit-desc">
        <div class="content-center">
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$settings["PROPERTIES"]["SINGLE_VISIT_DESC_TITLE"]["VALUE"]?></h2>
                </div>
            </div>
            <div class="single-visit-desc__text">
                <?=htmlspecialcharsback($settings["PROPERTIES"]["SINGLE_VISIT_DESC"]["VALUE"]["TEXT"])?>
            </div>
        </div>
    </section>











<!--<style>-->
<!--    .b-screen:after{-->
<!--        content:none;-->
<!--    }-->
<!--</style>-->
<!---->
<!--<section class="single-visit-services">-->
<!--    <div class="content-center">-->
<!--        <div class="b-cards-slider__heading">-->
<!--            <div class="b-cards-slider__title">-->
<!--                <h2>--><?//=$settings["PROPERTIES"]["SINGLE_VISIT_SERVICES_TITLE"]["VALUE"]?><!--</h2>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?//if (!empty($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"])):?>
<!--        --><?//if (!is_array($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"])){
//            $settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"]=[$settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"]];
//        }?>
<!--        <div class="sv-services__list">-->
<!--            --><?//foreach ($settings["PROPERTIES"]["SINGLE_VISIT_SERVICES"]["VALUE"] as $SERVICE):?>
<!--            <div class="sv-services__item">-->
<!--                --><?//=$SERVICE?>
<!--            </div>-->
<!--            --><?//endforeach;?>
<!--        </div>-->
<!--        --><?//endif;?>
<!--    </div>-->
<!--</section>-->
<!--<section>-->
<!---->
<!--    --><?//
//    $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => 'single-visit-block', 'blockTitle' => "Как проходит пробная тренировка?"], ['SHOW_BORDER' => false]);
//    ?>
<!--</section>-->
<!--<section id="form-request-single-visit">-->
<!--    --><?//
//    $APPLICATION->IncludeComponent(
//        "custom:form.request.new",
//        "on.page.block",
//        array(
//            "COMPONENT_TEMPLATE" => "on.page.block",
//            "WEB_FORM_ID" => "23",
//            "WEB_FORM_FIELDS" => array(
//                0 => "club",
//                1 => "name",
//                2 => "phone",
//                3 => "email",
//                4 => "personaldata",
//                5 => "rules",
//                6 => "privacy",
//            ),
//            "FORM_TYPE" => "3",
//            "CLUB_ID" => "",
//            "TEXT_FORM" => "Оставьте заявку на разовое посещение клуба сети SPIRIT.FITNESS"
//        ),
//        false);
//    ?>
<!--</section>-->


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>