<?php
define('HIDE_SLIDER', true);
define('BREADCRUMB_H1_ABSOLUTE', true);
//define('H1_BIG_COLORFUL', true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/libs/owl.carousel/owl.carousel.min.css');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/service.page.js');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/service.page.css');


$APPLICATION->SetTitle("Разовое посещение");

$APPLICATION->SetPageProperty("description", "Разовое бесплатное посещение фитнес-клуба Spirit Fitness💥 Тарифы от 1700 ₽ 💵 с ежемесячной оплатой, бесплатная пробная тренировка 🔥 Запишитесь прямо сейчас!");
$APPLICATION->SetPageProperty("title", "Разовое посещение фитнес-клуба: бесплатное занятие в тренажерном зале");
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
        "SINGLEVISIT_SHORT_DESC",
        "SINGLEVISIT_BUTTON",
        "SINGLEVISIT_IMAGES",
        "SINGLEVISIT_PAGE_DESC",
        "SINGLEVISIT_FORM_TYPE",
        "SINGLEVISIT_FORM_SID",
        "SINGLEVISIT_FORM_TITLE"
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
unset($rows, $filter, $order);
$includeParams=[
    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_SHORT_DESC"]["VALUE"]["TEXT"],
    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_BUTTON"]["VALUE"],
    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_BUTTON"]["DESCRIPTION"],
    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_IMAGES"]["VALUE"],
];

$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
?>
<section class="page-white-description">
    <div class="content-center">
        <div class="desc__block">
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_SERVICES_TITLE"]["VALUE"]?></h2>
                </div>
            </div>
            <div class="desc__block__text">
                <div class="sv-services__list">
                <?foreach ($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_SERVICE"]["VALUE"] as $service):?>
                    <div class="sv-services__item"><?=htmlspecialcharsback($service)?></div>
                <?endforeach?>
                </div>
            </div>
            <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_PAGE_DESC"]["VALUE"]); $i++):?>
                <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_PAGE_DESC"]["DESCRIPTION"][$i])):?>
                    <div class="b-cards-slider__heading">
                        <div class="b-cards-slider__title">
                            <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_PAGE_DESC"]["DESCRIPTION"][$i]?></h2>
                        </div>
                    </div>
                <?endif;?>
                <div class="desc__block__text">
                    <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_PAGE_DESC"]["VALUE"][$i]["TEXT"])?>
                </div>
            <?endfor;?>
        </div>
    </div>
</section>
<?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_FORM_TYPE"]["VALUE"])):?>
    <section id="form-request" style="margin-top: 80px;">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID($objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "club",
                    1 => "name",
                    2 => "phone",
                    3 => "email",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" =>$objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_FORM_TYPE"]["VALUE"],
                "TEXT_FORM" => $objects[$ELEMENT_ID]["PROPERTIES"]["SINGLEVISIT_FORM_TITLE"]["VALUE"]
            ),
            false);
        ?>
    </section>
<?endif;?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>