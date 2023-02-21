<?php
define('HIDE_SLIDER', true);
define('ANCHOR_PERSONAL', true);
define('HOLDER_CLASS', 'trainings');
define('H1_BIG', true);

define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $APPLICATION;
$APPLICATION->SetPageProperty("description", "Партнеры компании | SpiritFit.ru.");
$APPLICATION->SetPageProperty("title", "Наши партнеры | SpiritFit.ru");

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'css/training-page.css');


$ELEMENT_ID = Utils::GetIBlockElementIDBySID("service-page-settings");
$objects = [];
$filter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => Utils::GetIBlockIDBySID("service-page"), 'ID' => $ELEMENT_ID];
$order = array();

$rows = CIBlockElement::GetList($order, $filter);
while ($row = $rows->fetch()) {
    $row['PROPERTIES'] = [];
    $objects[$row['ID']] =& $row;
    unset($row);
}

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
unset($rows, $filter, $order);

$PROPS=$objects[$ELEMENT_ID]["PROPERTIES"];
$APPLICATION->SetTitle($PROPS["PARTNERS_PAGE_TITLE"]["VALUE"]);
$APPLICATION->AddViewContent('inhead', CFile::GetPath($PROPS["PARTNERS_BANNER"]["VALUE"]));

?>
<style>
    .sb-add__title {
        display: inline-block;
        padding: 10px 50px;
        background: linear-gradient(90deg,#e23834 3.26%,#7a27f1 98.07%);
        border-radius: 13px;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: white;
    }
    .sb-add__block {
        margin-bottom: 50px;
    }
    .sb-add__column:first-child {
        padding-left: 0;
    }
    .sb-add__column {
        width: 50%;
        flex: 0 0 50%;
        padding: 0 50px;
    }
    .spiritbox-additions {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
    }
    .sb-add__item {
        padding-left: 30px;
        background-size: 18px;
        background-position: 0 3px;
        background-repeat: no-repeat;
        font-size: 18px;
        margin-bottom: 10px;
        /* font-weight: 500; */
    }
    @media screen and (max-width: 968px){
        .sb-add__title {
            margin: 0 auto 20px;
            display: block;
            width: max-content;
        }
        .spiritbox-additions {
            flex-direction: column;
        }
        .sb-add__column {
            padding: 0;
            width: 100%;
            flex: 0 0 100%;
        }
    }

    .spiritbox-additions b{
        font-weight: 900;
    }
</style>
<?if (!empty($PROPS["PARTNERS_BANNER"]["VALUE"])):?>
    <div class="banner-detail__img" style="background-image: url(<?=CFile::GetPath($PROPS["PARTNERS_BANNER"]["VALUE"])?>)"></div>
    <div class="content-center">
        <?for($i=0; $i<count($PROPS["PARTNERS_BANNER_TEXT"]["VALUE"]); $i++):?>
            <?if ($i==count($PROPS["PARTNERS_BANNER_TEXT"]["VALUE"])-1 && count($PROPS["PARTNERS_TRAFFIC"]["VALUE"])>0):?>
                <div class="PARTNERS-traffic">
                    <?for ($j=0; $j<count($PROPS["PARTNERS_TRAFFIC"]["VALUE"]); $j++): ?>
                        <div class="PARTNERS-traffic__item">
                            <div class="PARTNERS-traffic__val">
                                <?=$PROPS["PARTNERS_TRAFFIC"]["VALUE"][$j]?>
                            </div>
                            <div class="PARTNERS-traffic__desc">
                                <?=$PROPS["PARTNERS_TRAFFIC"]["DESCRIPTION"][$j]?>
                            </div>
                        </div>
                    <?endfor?>
                </div>
            <?endif;?>
            <div class="banner-detail__description-item">
                <?if (!empty($PROPS["PARTNERS_BANNER_TEXT"]["DESCRIPTION"][$i])):?>
                    <div class="banner-detail__description-title">
                        <h2 class="text-transform-none"><?=$PROPS["PARTNERS_BANNER_TEXT"]["DESCRIPTION"][$i]?></h2>
                    </div>
                <?endif;?>
                <div class="banner-detail__description-content">
                    <?=htmlspecialcharsback($PROPS["PARTNERS_BANNER_TEXT"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
    </div>
<?php endif;?>
<?php if (!empty($PROPS["PARTNERS_BOX_TITLE"]["VALUE"])):?>
    <div class="content-center">
        <div class="b-section__title">
            <h2><?=$PROPS["PARTNERS_BOX_TITLE"]["VALUE"]?></h2>
        </div>
    </div>
<?php endif?>

<?php if (!empty($PROPS["PARTNERS_BOX_INCLUDE"])):?>
    <?
    $additions=[];
    $column=1;
    for ($i=0; $i<count($PROPS["PARTNERS_BOX_INCLUDE"]["VALUE"]); $i++){
        if (empty($PROPS["PARTNERS_BOX_INCLUDE"]["DESCRIPTION"][$i])){
            continue;
        }
        $key=$PROPS["PARTNERS_BOX_INCLUDE"]["DESCRIPTION"][$i];
        $value=$PROPS["PARTNERS_BOX_INCLUDE"]["VALUE"][$i]["TEXT"];
        if (!key_exists($key, $additions)){
            $additions[$key]=[
                "COLUMN"=>$column,
                "VALUE"=>[]
            ];
            if ($column==1){
                $column=2;
            }
            else{
                $column=1;
            }
        }
        array_push($additions[$key]["VALUE"], $value);
    }
    ?>
    <div class="spiritbox-additions content-center">
        <div class="sb-add__column">
            <?foreach ($additions as $key=>$addition):?>
            <?if ($addition["COLUMN"]!=1) continue;?>
            <div class="sb-add__block">
                <div class="sb-add__title">
                    <?=$key?>
                </div>
                <?foreach ($addition["VALUE"] as $value):?>
                    <div class="sb-add__item" style="background-image: url(<?=SITE_TEMPLATE_PATH.'img/icons/abonement-check-mark.svg'?>);">
                        <?=htmlspecialcharsback($value)?>
                    </div>
                <?endforeach;?>
            </div>

            <?endforeach;?>
        </div>
        <div class="sb-add__column">
            <?foreach ($additions as $key=>$addition):?>
                <?if ($addition["COLUMN"]!=2) continue;?>
                <div class="sb-add__block">
                    <div class="sb-add__title">
                        <?=$key?>
                    </div>
                    <?foreach ($addition["VALUE"] as $value):?>
                        <div class="sb-add__item"  style="background-image: url(<?=SITE_TEMPLATE_PATH.'img/icons/abonement-check-mark.svg'?>);">
                            <?=htmlspecialcharsback($value)?>
                        </div>
                    <?endforeach;?>
                </div>

            <?endforeach;?>
        </div>
    </div>
<?php endif;?>
<?php if (!empty($PROPS["PARTNERS_ABONEMENT_TITLE"]["VALUE"])):?>
    <div class="content-center">
        <div class="b-section__title">
            <h2><?=$PROPS["PARTNERS_ABONEMENT_TITLE"]["VALUE"]?></h2>
        </div>
    </div>
<?php endif?>
<?php if (!empty($PROPS["PARTNERS_ABONEMENT"]["VALUE"])):?>
    <?php
    $GLOBALS['arAbonementFilter'] =
        [
            "ID" => $PROPS["PARTNERS_ABONEMENT"]["VALUE"]
        ];
    ?>
    <div class="section-margin-top">
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "abonement.main",
            Array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ADD_SECTIONS_CHAIN" => "N",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "3600",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "/abonement/#CODE#/",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "FIELD_CODE" => array("CODE", "NAME", "PREVIEW_PICTURE", "IBLOCK_SECTION_CODE"),
                "FILTER_NAME" => "arAbonementFilter",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => "9",
                "IBLOCK_TYPE" => "content",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "INCLUDE_SUBSECTIONS" => "Y",
                "MESSAGE_404" => "",
                "NEWS_COUNT" => "",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Новости",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("BASE_PRICE", "INCLUDE", "ADDITIONAL_CLASS", "PRICE_SIGN", "HIDDEN", "FOR_PRESENT", "PRICE", "TITLE"),
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SORT_BY1" => "SORT",
                "SORT_BY2" => "",
                "SORT_ORDER1" => "ASC",
                "SORT_ORDER2" => "",
                "STRICT_SECTION_CHECK" => "N"
            )
        );?>
    </div>
<?php endif;?>

<?php if (!empty($PROPS["PARTNERS_SLIDER"]["VALUE"])):?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "blocks.abonements",
        array(
            "COMPONENT_TEMPLATE" => "",
            "IBLOCK_TYPE" => "service",
            "IBLOCK_ID" => "18",
            "BLOCK_TITLE" => "",
            "ELEMENT_ID" => $PROPS["PARTNERS_SLIDER"]["VALUE"],
            "ELEMENT_CODE" => "",
            "ADDITIONAL_CLASS" => '',
            "CHECK_DATES" => "Y",
            "FIELD_CODE" => array(),
            "PROPERTY_CODE" => array(
                0 => "BLOCK_TEXT",
                1 => "BLOCK_BTN_TEXT",
                2 => "BLOCK_LINK",
                3 => "BLOCK_VIDEO_YOUTUBE",
                4 => "BLOCK_PREVIEW",
                5 => "BLOCK_PHOTO",
                6 => "BLOCK_VIEW",
                7 => "BLOCK_TITLE_LINK",
            ),
            "IBLOCK_URL" => "",
            "DETAIL_URL" => "",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "SET_TITLE" => "N",
            "SET_CANONICAL_URL" => "N",
            "SET_BROWSER_TITLE" => "N",
            "BROWSER_TITLE" => "-",
            "SET_META_KEYWORDS" => "N",
            "META_KEYWORDS" => "-",
            "SET_META_DESCRIPTION" => "N",
            "META_DESCRIPTION" => "-",
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "ADD_ELEMENT_CHAIN" => "N",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "USE_PERMISSIONS" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "USE_SHARE" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Страница",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => "",
            "FILE_404" => ""
        ),
        false
    );?>
<?php endif;?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
