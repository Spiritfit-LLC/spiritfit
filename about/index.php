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


$APPLICATION->SetTitle("О компании | SpiritFit.ru");

$APPLICATION->SetPageProperty("description", "Спирит Фитнес – это сеть фитнес клубов в Москве и Московской области 🏋 Вас впечатлит атмосфера наших клубов, тренера и оборудование 📞 8 (495) 266-40-95");
$APPLICATION->SetPageProperty("title", "О компании | SpiritFit.ru");
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
        "ABOUT_SHORT_DESC",
        "ABOUT_BUTTON",
        "ABOUT_IMAGES",
        "ABOUT_PAGE_DESC1",
        "ABOUT_PAGE_DESC2",
        "ABOUT_PAGE_DESC3",
        "ABOUT_FORM_TYPE",
        "ABOUT_FORM_SID",
        "ABOUT_FORM_TITLE",
        "ABOUT_BLOCK"
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
unset($rows, $filter, $order);
$includeParams=[
    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_SHORT_DESC"]["VALUE"]["TEXT"],
    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BUTTON"]["VALUE"],
    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BUTTON"]["DESCRIPTION"],
    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_IMAGES"]["VALUE"],
];

$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
?>
<section class="page-white-description">
    <div class="content-center">
        <div class="desc__block">
            <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["VALUE"]); $i++):?>
                <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["DESCRIPTION"][$i])):?>
                    <div class="b-cards-slider__heading">
                        <div class="b-cards-slider__title">
                            <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["DESCRIPTION"][$i]?></h2>
                        </div>
                    </div>
                <?endif;?>
                <div class="desc__block__text">
                    <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC1"]["VALUE"][$i]["TEXT"])?>
                </div>
            <?endfor;?>
            <div class="desck-blog__big-text">
                <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC2"]["VALUE"]["TEXT"])?>
            </div>
            <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["VALUE"]); $i++):?>
                <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["DESCRIPTION"][$i])):?>
                    <div class="b-cards-slider__heading">
                        <div class="b-cards-slider__title">
                            <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["DESCRIPTION"][$i]?></h2>
                        </div>
                    </div>
                <?endif;?>
                <div class="desc__block__text">
                    <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_PAGE_DESC3"]["VALUE"][$i]["TEXT"])?>
                </div>
            <?endfor;?>
        </div>
    </div>
</section>
<div style="margin-top:80px">
    <?
    $dbRes=CIBlockElement::GetByID($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_BLOCK"]["VALUE"]);
    $code=$dbRes->Fetch()["CODE"];
    ?>
    <? $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => $code], ['SHOW_BORDER' => false]); ?>
</div>
<?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TYPE"]["VALUE"])):?>
    <section id="form-request" style="margin-top: 80px;">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID($objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "club",
                    1 => "name",
                    2 => "phone",
                    3 => "email",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" =>$objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TYPE"]["VALUE"],
                "TEXT_FORM" => $objects[$ELEMENT_ID]["PROPERTIES"]["ABOUT_FORM_TITLE"]["VALUE"]
            ),
            false);
        ?>
    </section>
<?endif;?>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>