<?
define('HIDE_SLIDER', true);
//define('HOLDER_CLASS', 'company-holder');
//define('H1_BIG_COLORFUL', true);
define('BREADCRUMB_H1_ABSOLUTE', true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.css');


$APPLICATION->SetTitle("Рекламные возможности");

$APPLICATION->SetPageProperty("description", "Сеть Spirit. Fitness приглашает рекламодателей к сотрудничеству. Предлагаем размещение рекламы в наших клубах, группах в соцсетях и на Spirit. TV.");
$APPLICATION->SetPageProperty("title", "Рекламные возможности | SpiritFit.ru");




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
                <?=htmlspecialcharsback($settings["PROPERTIES"]["ADV_SHORT_DESC"]["VALUE"]["TEXT"])?>
            </div>
            <div class="page-desc-short__btn">
                <a class="page-desc__request-btn button-outline" href="#form-request">Оставить заявку</a>
            </div>
        </div>
        <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
            <div class="owl-carousel">
                <?foreach ($settings["PROPERTIES"]["ADV_BANNER_IMGS"]["VALUE"] as $IMG):?>
                <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')">

                </div>
                <?
                endforeach;?>
            </div>
        </div>
    </div>
</div>



    <section class="adv-opportunities">
        <div class="content-center">
            <?if (!empty($settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"])):?>
                <?if (!is_array($settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"])){
                    $settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"]=[$settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"]];
                    $settings["PROPERTIES"]["ADV_ADVANTAGES"]["DESCRIPTION"]=[$settings["PROPERTIES"]["ADV_ADVANTAGES"]["DESCRIPTION"]];
                }?>
                <div class="adv-advantages__list">
                    <?for ($i=0; $i<count($settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"]); $i++):?>
                        <?
                        $DESC=$settings["PROPERTIES"]["ADV_ADVANTAGES"]["DESCRIPTION"][$i];
                        $VALUE=$settings["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"][$i];
                        if (empty($DESC) || empty($VALUE)){
                            continue;
                        }
                        ?>
                        <div class="adv-advantages__item">
                            <div class="adv-advantages__val">
                                <?=$VALUE?>
                            </div>
                            <div class="adv-advantages__desc">
                                <?=$DESC?>
                            </div>
                        </div>
                    <?endfor;?>
                </div>
            <?endif;?>
            <?if (!empty($settings["PROPERTIES"]["ADV_TEXT"]["VALUE"]["TEXT"])):?>
                <div class="adv-opportunities__text">
                    <?=htmlspecialcharsback($settings["PROPERTIES"]["ADV_TEXT"]["VALUE"]["TEXT"])?>
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
                "WEB_FORM_ID" => Utils::GetFormIDBySID($settings["PROPERTIES"]["ADV_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "name",
                    1 => "phone",
                    2 => "email",
                    3 => "company",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" =>$settings["PROPERTIES"]["ADV_FORM_TYPE"]["VALUE"],
                "TEXT_FORM" => $settings["PROPERTIES"]["ADV_FORM_TITLE"]["VALUE"]
            ),
            false);
        ?>
    </section>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>