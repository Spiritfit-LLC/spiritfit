<?
define('HIDE_SLIDER', true);
//define('HOLDER_CLASS', 'company-holder');
//define('H1_BIG_COLORFUL', true);
define('BREADCRUMB_H1_ABSOLUTE', true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.css');


$APPLICATION->SetTitle("Ищем площадки под клубы");

$APPLICATION->SetPageProperty("description", "Площадки под клубы компании | SpiritFit.ru");
$APPLICATION->SetPageProperty("title", "Площадки под клубы | SpiritFit.ru");

$settings = Utils::getInfo();
?>
<style>
    .b-screen:after{
        content:none;
    }
</style>
<div class="content-center">
    <div class="page-hiden-slider__header">
        <div class="page-desc-short">
            <div class="page-desc-short__text">
                <?=htmlspecialcharsback($settings["PROPERTIES"]["PLATFORM_SHORT_DESC"]["VALUE"]["TEXT"])?>
            </div>
            <div class="page-desc-short__btn">
                <a class="page-desc__request-btn button-outline" href="#form-request">Оставить заявку</a>
            </div>
        </div>
        <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
            <div class="owl-carousel">
                <?foreach ($settings["PROPERTIES"]["PLATFORM_BANNER_IMGS"]["VALUE"] as $IMG):?>
                    <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')">

                    </div>
                <?
                endforeach;?>
            </div>
        </div>
    </div>
</div>
<section class="platform-requiments">
    <div class="content-center">
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2><?=$settings["PROPERTIES"]["PLATFORM_REQUIMENTS_TITILE"]["VALUE"]?></h2>
            </div>
        </div>
        <?if (!empty($settings["PROPERTIES"]["PLATFORM_REQUIMENTS"]["VALUE"])):?>
            <?if (!is_array($settings["PROPERTIES"]["PLATFORM_REQUIMENTS"]["VALUE"])){
                $settings["PROPERTIES"]["PLATFORM_REQUIMENTS"]["VALUE"]=[$settings["PROPERTIES"]["PLATFORM_REQUIMENTS"]["VALUE"]];
            }?>
            <ul class="platform-requiments__list">
                <?foreach ($settings["PROPERTIES"]["PLATFORM_REQUIMENTS"]["VALUE"] as $REQUIMENT):?>
                    <li class="platform-requiments__item">
                        <?=$REQUIMENT?>
                    </li>
                <?endforeach;?>
            </ul>
        <?endif;?>
        <?if (!empty($settings["PROPERTIES"]["PLATFORM_TEXT"]["VALUE"]["TEXT"])):?>
        <div class="platform-requiments__text">
            <?=htmlspecialcharsback($settings["PROPERTIES"]["PLATFORM_TEXT"]["VALUE"]["TEXT"])?>
        </div>
        <?endif;?>
    </div>
</section>
<section id="form-request">
    <?
    $APPLICATION->IncludeComponent(
        "custom:form.request.new",
        "on.page.block",
        array(
            "COMPONENT_TEMPLATE" => "on.page.block",
            "WEB_FORM_ID" => Utils::GetFormIDBySID($settings["PROPERTIES"]["PLATFORM_FORM_SID"]["VALUE"]),
            "WEB_FORM_FIELDS" => array(
                0 => "name",
                1 => "phone",
                2 => "email",
                3 => "address",
                4 => "personaldata",
                5 => "rules",
                6 => "privacy",
            ),
            "FORM_TYPE" =>$settings["PROPERTIES"]["PLATFORM_FORM_TYPE"]["VALUE"],
            "TEXT_FORM" => $settings["PROPERTIES"]["PLATFORM_FORM_TITLE"]["VALUE"]
        ),
        false);
    ?>
</section>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>