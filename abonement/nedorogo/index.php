<?

define('HIDE_SLIDER', true);
//define('HOLDER_CLASS', 'company-holder');
//define('H1_BIG_COLORFUL', true);
define('BREADCRUMB_H1_ABSOLUTE', true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/libs/owl.carousel/owl.carousel.min.css');


$APPLICATION->SetTitle("Недорогие фитнес клубы Spirit.Fitness");

$APPLICATION->SetPageProperty("description", "Абонементы недорого в фитнес-клуб в Москве 💥 Дешевые тарифы от 1700 ₽ 💵 с ежемесячной оплатой, бесплатная пробная тренировка 🔥 Запишитесь прямо сейчас!");
$APPLICATION->SetPageProperty("title", "Недорогие абонементы в фитнес-клуб в Москве: дешевые цены на тренажерный зал Spirit Fitness
");


$settings = Utils::getInfo();
?>
<style>
    .b-screen:after{
        content:none;
    }
    .cheap-desc__text div{
        margin-bottom: 20px;
    }
    .cheap-desc__text ul {
        display: block;
        position: relative;
        list-style: none;
        margin: 10px 0px;
        padding-left: 50px;
    }
    .cheap-desc__text ul > li {
        display: block;
        position: relative;
        box-sizing: border-box;
        padding-left: 18px;
        margin-bottom: 2px;
    }
    .cheap-desc__text ul > li:before {
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
    .cheap-desc__text b {
        font-weight: 600;
    }
    .cheap-desc__text li{
        margin: 10px 0;
    }
    .cheap-desc__text {
        line-height: 1.5em;
        margin-bottom: 50px;
    }
    .cheap-desc__text ol li {
        padding-left: 20px;
    }
    .cheap-desc__text a{
        font-weight: 500;
    }
    section.cheap-description {
        background: white;
        color: black;
        padding: 50px 0;
        margin-bottom: 50px;
    }
</style>
<div class="content-center">
    <div class="page-hiden-slider__header">
        <div class="page-desc-short">
            <div class="page-desc-short__text">
                <?=htmlspecialcharsback($settings["PROPERTIES"]["CHEAP_SHORT_DESC"]["VALUE"]["TEXT"])?>
            </div>
            <div class="page-desc-short__btn">
                <a class="page-desc__request-btn button-outline" href="/abonement/probnaya-trenirovka-/">Пробная тренировка</a>
            </div>
        </div>
        <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
            <div class="owl-carousel">
                <?foreach ($settings["PROPERTIES"]["CHEAP_BANNER_IMGS"]["VALUE"] as $IMG):?>
                    <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')"></div>
                <?
                endforeach;?>
            </div>
        </div>
    </div>
</div>
<section class="cheap-description">
    <div class="content-center">
        <?for ($i=0; $i<count($settings["PROPERTIES"]["CHEAP_DESC"]["VALUE"]); $i++):?>
            <div class="cheap-desc__block">
                <?if (!empty($settings["PROPERTIES"]["CHEAP_DESC"]["DESCRIPTION"][$i])):?>
                <div class="b-cards-slider__heading">
                    <div class="b-cards-slider__title">
                        <h2><?=$settings["PROPERTIES"]["CHEAP_DESC"]["DESCRIPTION"][$i]?></h2>
                    </div>
                </div>
                <?endif;?>
                <div class="cheap-desc__text">
                    <?=htmlspecialcharsback($settings["PROPERTIES"]["CHEAP_DESC"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
</section>
<? $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => 'trenazhernyy-zal-main'], ['SHOW_BORDER' => false]); ?>
<section id="form-request">
    <?
    $APPLICATION->IncludeComponent(
        "custom:form.request.new",
        "on.page.block",
        array(
            "COMPONENT_TEMPLATE" => "on.page.block",
            "WEB_FORM_ID" => Utils::GetFormIDBySID($settings["PROPERTIES"]["CHEAP_FORM_SID"]["VALUE"]),
            "WEB_FORM_FIELDS" => array(
                0 => "club",
                1 => "name",
                2 => "phone",
                3 => "email",
                4 => "personaldata",
                5 => "rules",
                6 => "privacy",
            ),
            "FORM_TYPE" =>$settings["PROPERTIES"]["CHEAP_FORM_TYPE"]["VALUE"],
            "TEXT_FORM" => $settings["PROPERTIES"]["CHEAP_FORM_TITLE"]["VALUE"]
        ),
        false);
    ?>
</section>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>