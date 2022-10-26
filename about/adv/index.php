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


$APPLICATION->SetTitle("Рекламные возможности");

$APPLICATION->SetPageProperty("description", "Сеть Spirit. Fitness приглашает рекламодателей к сотрудничеству. Предлагаем размещение рекламы в наших клубах, группах в соцсетях и на Spirit. TV.");
$APPLICATION->SetPageProperty("title", "Рекламные возможности | SpiritFit.ru");
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
        "ADV_SHORT_DESC",
        "ADV_BUTTON",
        "ADV_IMAGES",
        "ADV_PAGE_DESC",
        "ADV_FORM_TYPE",
        "ADV_FORM_SID",
        "ADV_FORM_TITLE",
        "ADV_ADVANTAGES"
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
unset($rows, $filter, $order);
$includeParams=[
    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_SHORT_DESC"]["VALUE"]["TEXT"],
    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_BUTTON"]["VALUE"],
    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_BUTTON"]["DESCRIPTION"],
    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_IMAGES"]["VALUE"],
];
$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
?>
<section class="page-black-description">
<div class="content-center">
    <div class="desc__block">
        <div class="desc__block__text">
            <div class="adv-advantages__list">
            <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"]); $i++):?>
                <?
                $DESC=$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_ADVANTAGES"]["DESCRIPTION"][$i];
                $VALUE=$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_ADVANTAGES"]["VALUE"][$i];
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
            <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_PAGE_DESC"]["VALUE"]); $i++):?>
                <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_PAGE_DESC"]["DESCRIPTION"][$i])):?>
                    <div class="b-cards-slider__heading">
                        <div class="b-cards-slider__title">
                            <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_PAGE_DESC"]["DESCRIPTION"][$i]?></h2>
                        </div>
                    </div>
                <?endif;?>
                <div class="desc__block__text">
                    <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_PAGE_DESC"]["VALUE"][$i]["TEXT"])?>
                </div>
            <?endfor;?>
        </div>
    </div>
</div>
</section>
<?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_FORM_TYPE"]["VALUE"])):?>
    <section id="form-request">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID($objects[$ELEMENT_ID]["PROPERTIES"]["ADV_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "name",
                    1 => "phone",
                    2 => "email",
                    3 => "company",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" =>$objects[$ELEMENT_ID]["PROPERTIES"]["ADV_FORM_TYPE"]["VALUE"],
                "TEXT_FORM" => $objects[$ELEMENT_ID]["PROPERTIES"]["ADV_FORM_TITLE"]["VALUE"]
            ),
            false);
        ?>
    </section>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>