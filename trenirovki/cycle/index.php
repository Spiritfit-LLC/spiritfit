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


$APPLICATION->SetTitle("Cycle-тренировки в Spirit. Fitness");

$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "Cycle-тренировки | SpiritFit.ru");
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
        "CYCLE_SHORT_DESC",
        "CYCLE_BUTTON",
        "CYCLE_IMAGES",
        "CYCLE_PAGE_DESC",
        "CYCLE_FORM_TYPE",
        "CYCLE_FORM_SID",
        "CYCLE_FORM_TITLE"
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
unset($rows, $filter, $order);
$includeParams=[
    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_SHORT_DESC"]["VALUE"]["TEXT"],
    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_BUTTON"]["VALUE"],
    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_BUTTON"]["DESCRIPTION"],
    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_IMAGES"]["VALUE"],
];

$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
?>
    <section class="page-white-description">
        <div class="content-center">
            <div class="desc__block">
                <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_PAGE_DESC"]["VALUE"]); $i++):?>
                    <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_PAGE_DESC"]["DESCRIPTION"][$i])):?>
                        <div class="b-cards-slider__heading">
                            <div class="b-cards-slider__title">
                                <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_PAGE_DESC"]["DESCRIPTION"][$i]?></h2>
                            </div>
                        </div>
                    <?endif;?>
                    <div class="desc__block__text">
                        <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_PAGE_DESC"]["VALUE"][$i]["TEXT"])?>
                    </div>
                <?endfor;?>
            </div>
        </div>
    </section>
<div style="margin-top: 80px">
    <?php
    $APPLICATION->IncludeComponent(
        "custom:shedule.club",
        "profitator.style",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_CODE" => "clubs",
            "CLUB_NUMBER" => "11",
        ),
        false
    );
    ?>
</div>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'atmosfera'], ['SHOW_BORDER' => false]); ?>

<?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_FORM_TYPE"]["VALUE"])):?>
    <section id="form-request" style="margin-top: 80px;">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID($objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "club",
                    1 => "name",
                    2 => "phone",
                    3 => "email",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "FORM_TYPE" =>$objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_FORM_TYPE"]["VALUE"],
                "TEXT_FORM" => $objects[$ELEMENT_ID]["PROPERTIES"]["CYCLE_FORM_TITLE"]["VALUE"]
            ),
            false);
        ?>
    </section>
<?endif;?>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>