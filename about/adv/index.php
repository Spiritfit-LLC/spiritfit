<?php
define('HIDE_SLIDER', true);
define('ANCHOR_PERSONAL', true);
define('HOLDER_CLASS', 'trainings');
define('H1_BIG', true);


define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION;
$APPLICATION->SetPageProperty("description", "Сеть Spirit. Fitness приглашает рекламодателей к сотрудничеству. Предлагаем размещение рекламы в наших клубах, группах в соцсетях и на Spirit. TV.");
$APPLICATION->SetPageProperty("title", "Рекламные возможности | SpiritFit.ru");

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
        "ADV_*",
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
unset($rows, $filter, $order);

$PROPS=$objects[$ELEMENT_ID]["PROPERTIES"];
$APPLICATION->SetTitle($PROPS["ADV_PAGE_TITLE"]["VALUE"]);
$APPLICATION->AddViewContent('inhead', CFile::GetPath($PROPS["ADV_BANNER"]["VALUE"]));
?>
<style>
    .adv-traffic {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-end;
        background: linear-gradient(90deg,#e23834 3.26%,#8428dd 98.07%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .adv-traffic__item {
        width: 33.3%;
        flex: 0 0 33.3%;
        padding: 0 10px;
        display: flex;
        flex-direction: row;
        align-items: baseline;
        flex-wrap: nowrap;
        text-align: center;
        justify-content: center;

    }
    .adv-traffic__val {
        font-size: 28px;
        font-weight: 600;
        margin-right: 10px;
    }
    .adv-traffic__desc {
        font-size: 14px;
    }
    .adv-traffic__item:first-child{
        justify-content: flex-start;
    }
    .adv-traffic__item:last-child{
        justify-content: flex-end;
    }
    @media screen and (max-width: 1024px) {
        .adv-traffic__item{
            width: max-content;
            flex: 0 0 max-content;
            margin: 20px 0 0;
        }
    }
</style>
<?if (!empty($PROPS["ADV_BANNER"]["VALUE"])):?>
    <div class="banner-detail__img" style="background-image: url(<?=CFile::GetPath($PROPS["ADV_BANNER"]["VALUE"])?>)"></div>
    <div class="content-center">
        <?for($i=0; $i<count($PROPS["ADV_BANNER_TEXT"]["VALUE"]); $i++):?>
            <?if ($i==count($PROPS["ADV_BANNER_TEXT"]["VALUE"])-1 && count($PROPS["ADV_TRAFFIC"]["VALUE"])>0):?>
                <div class="adv-traffic">
                    <?for ($j=0; $j<count($PROPS["ADV_TRAFFIC"]["VALUE"]); $j++): ?>
                        <div class="adv-traffic__item">
                            <div class="adv-traffic__val">
                                <?=$PROPS["ADV_TRAFFIC"]["VALUE"][$j]?>
                            </div>
                            <div class="adv-traffic__desc">
                                <?=$PROPS["ADV_TRAFFIC"]["DESCRIPTION"][$j]?>
                            </div>
                        </div>
                    <?endfor?>
                </div>
            <?endif;?>
            <div class="banner-detail__description-item">
                <?if (!empty($PROPS["ADV_BANNER_TEXT"]["DESCRIPTION"][$i])):?>
                    <div class="banner-detail__description-title">
                        <h2 class="text-transform-none"><?=$PROPS["ADV_BANNER_TEXT"]["DESCRIPTION"][$i]?></h2>
                    </div>
                <?endif;?>
                <div class="banner-detail__description-content">
                    <?=htmlspecialcharsback($PROPS["ADV_BANNER_TEXT"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
<?php endif;?>
<?if (!empty($PROPS["ADV_FORM_EMAIL"]["VALUE"]) && !empty($PROPS["ADV_FORM_SID"]["VALUE"])):?>
    <section id="form">
        <?
        $APPLICATION->IncludeComponent(
            "custom:form.request.new",
            "on.page.block",
            array(
                "COMPONENT_TEMPLATE" => "on.page.block",
                "WEB_FORM_ID" => Utils::GetFormIDBySID($PROPS["ADV_FORM_SID"]["VALUE"]),
                "WEB_FORM_FIELDS" => array(
                    0 => "name",
                    1 => "phone",
                    2 => "email",
                    3 => "company",
                    4 => "personaldata",
                    5 => "rules",
                    6 => "privacy",
                ),
                "TEXT_FORM"=>$PROPS["ADV_FORM_TITLE"]["VALUE"],
                "REQUEST_TYPE"=>"EMAIL",
                "EMAIL"=>$PROPS["ADV_FORM_EMAIL"]["VALUE"],
                "REQUEST_HEADER"=>$PROPS["ADV_EMAIL_SUBJECT"]["VALUE"]
            ),
            false);
        ?>
    </section>
<?endif;?>


<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>