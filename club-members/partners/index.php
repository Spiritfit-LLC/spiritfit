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


$APPLICATION->SetTitle("Партнеры");

$APPLICATION->SetPageProperty("description", "Партнеры компании | SpiritFit.ru.");
$APPLICATION->SetPageProperty("title", "Наши партнеры | SpiritFit.ru");
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
        "PARTNERS_SHORT_DESC",
        "PARTNERS_BUTTON",
        "PARTNERS_IMAGES",
        "PARTNERS_PAGE_DESC",
        "PARTNERS_FORM_TYPE",
        "PARTNERS_FORM_SID",
        "PARTNERS_FORM_TITLE",
        "PARTNERS_SPIRITBOX_TITLE",
        "PARTNERS_SPIRITBOX_SERVICE",
        "PARTNERS_ABONEMENT_TITLE",
        "PARTNERS_ABONEMENT_DESC",
        "PARTNERS_ABONEMENT_ELEMENTS"
    ]
];

CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, $propertyFilter);
unset($rows, $filter, $order);
$includeParams=[
    "HEAD_DESC"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SHORT_DESC"]["VALUE"]["TEXT"],
    "BUTTON"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_BUTTON"]["VALUE"],
    "BUTTON_LINK"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_BUTTON"]["DESCRIPTION"],
    "HEAD_IMAGES"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_IMAGES"]["VALUE"],
];
$APPLICATION->IncludeFile("/local/include/service/header.php", $includeParams);
?>

<style>
    .spiritbox-additions {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
    }
    .sb-add__column {
        width: 50%;
        flex: 0 0 50%;
        padding: 0 50px;
    }
    .sb-add__column:first-child{
        padding-left: 0;
    }
    .sb-add__column:last-child{
        padding-right: 0;
    }
    .sb-add__title {
        display: inline-block;
        padding: 10px 50px;
        background: white;
        color: black;
        border-radius: 13px;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    .sb-add__block {
        margin-bottom: 50px;
    }
    .sb-add__item {
        padding-left: 30px;
        background-image: url(/local/templates/spiritfit-v2/components/bitrix/news.list/abonements.main/img/checkbox.svg);
        background-size: 18px;
        background-position: 0 3px;
        background-repeat: no-repeat;
        font-size: 18px;
        margin-bottom: 10px;
        /* font-weight: 500; */
    }
    b {
        font-weight: bold;
    }
    .partners-abonements-block {
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .partners-abonement__column {
        width: 50%;
        flex: 0 0 50%;
    }
    .partners-abonements__desc {
        padding: 50px;
        background: white;
        color: black;
    }
    @media screen and (max-width: 968px) {
        .spiritbox-additions {
            flex-direction: column;
        }
        .sb-add__column {
            padding: 0;
            width: 100%;
            flex: 0 0 100%;
        }
        .sb-add__title {
            margin: 0 auto 20px;
            display: block;
            width: max-content;
        }
    }
    @media screen and (max-width: 768px){
        .partners-abonements-block {
            flex-direction: column-reverse;
            margin-bottom: 50px;
        }
        .partners-abonement__column {
            padding: 0!important;
            width: 100%;
            flex: 0 0 100%;
        }
    }
    @media screen and (max-width: 550px) {
        .spiritbox-additions {
            margin-top: 30px;
        }
    }
</style>
    <section class="page-black-description">
        <div class="content-center">
            <div class="desc__block">
                <div class="desc__block__text">
                    <?for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_PAGE_DESC"]["VALUE"]); $i++):?>
                        <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_PAGE_DESC"]["DESCRIPTION"][$i])):?>
                            <div class="b-cards-slider__heading">
                                <div class="b-cards-slider__title">
                                    <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_PAGE_DESC"]["DESCRIPTION"][$i]?></h2>
                                </div>
                            </div>
                        <?endif;?>
                        <div class="desc__block__text">
                            <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_PAGE_DESC"]["VALUE"][$i]["TEXT"])?>
                        </div>
                    <?endfor;?>
                </div>
            </div>
        </div>
    </section>
    <section class="spiritbox">
        <div class="content-center">
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SPIRITBOX_TITLE"]["VALUE"]?></h2>
                </div>
            </div>
            <?
            $additions=[];
            $column=1;
            for ($i=0; $i<count($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SPIRITBOX_SERVICE"]["VALUE"]); $i++){
                if (empty($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SPIRITBOX_SERVICE"]["DESCRIPTION"][$i])){
                    continue;
                }
                $key=$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SPIRITBOX_SERVICE"]["DESCRIPTION"][$i];
                $value=$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_SPIRITBOX_SERVICE"]["VALUE"][$i]["TEXT"];
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
            <div class="spiritbox-additions">
                <div class="sb-add__column">
                    <?foreach ($additions as $key=>$addition):?>
                    <?if ($addition["COLUMN"]!=1){
                        continue;
                    }?>
                    <div class="sb-add__block">
                        <div class="sb-add__title">
                            <?=$key?>
                        </div>
                        <?foreach ($addition["VALUE"] as $value):?>
                            <div class="sb-add__item">
                                <?=htmlspecialcharsback($value)?>
                            </div>
                        <?endforeach;?>
                    </div>

                    <?endforeach;?>
                </div>
                <div class="sb-add__column">
                    <?foreach ($additions as $key=>$addition):?>
                        <?if ($addition["COLUMN"]!=2){
                            continue;
                        }?>
                        <div class="sb-add__block">
                            <div class="sb-add__title">
                                <?=$key?>
                            </div>
                            <?foreach ($addition["VALUE"] as $value):?>
                                <div class="sb-add__item">
                                    <?=htmlspecialcharsback($value)?>
                                </div>
                            <?endforeach;?>
                        </div>

                    <?endforeach;?>
                </div>
            </div>
        </div>
    </section>
    <section class="abonemebts">
        <div class="content-center">
            <div class="b-cards-slider__heading">
                <div class="b-cards-slider__title">
                    <h2><?=$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_ABONEMENT_TITLE"]["VALUE"]?></h2>
                </div>
            </div>
            <div class="partners-abonements-block">
                <?if (!empty($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_ABONEMENT_DESC"]["VALUE"]["TEXT"])):?>
                <div class="partners-abonement__column" style="padding: 0 50px;">
                    <div class="partners-abonements__desc">
                        <?=htmlspecialcharsback($objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_ABONEMENT_DESC"]["VALUE"]["TEXT"])?>
                    </div>
                </div>
                <div class="partners-abonement__column">
                    <div class="partners-abonements">
                        <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "abonements",
                        Array(
                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "ADD_SECTIONS_CHAIN" => "Y",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "CACHE_FILTER" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "CHECK_DATES" => "Y",
                            "DETAIL_URL" => "",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DISPLAY_TOP_PAGER" => "N",
                            "FIELD_CODE" => array("",""),
                            "FILE_404" => "",
                            "FILTER_NAME" => "arrFilterAbonement",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "IBLOCK_ID" => "9",
                            "IBLOCK_TYPE" => "content",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "MESSAGE_404" => "",
                            "NEWS_COUNT" => "20",
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
                            "PROPERTY_CODE" => array("SIZE","PRICE",""),
                            "SET_BROWSER_TITLE" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "SET_META_DESCRIPTION" => "N",
                            "SET_META_KEYWORDS" => "N",
                            "SET_STATUS_404" => "N",
                            "SET_TITLE" => "N",
                            "SHOW_404" => "N",
                            "SORT_BY1" => "ACTIVE_FROM",
                            "SORT_BY2" => "SORT",
                            "SORT_ORDER1" => "ASC",
                            "SORT_ORDER2" => "ASC",
                            "STRICT_SECTION_CHECK" => "N",
                            "ITEMS_ID"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_ABONEMENT_ELEMENTS"]["VALUE"],
                            "SLIDES_TO_SHOW_AND_SCROLL"=>1,
                            "CONTENT_CENTER"=>false
                        )
                    );?>
                    </div>
                </div>
                <?else:?>
                    <div class="partners-abonements">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "abonements",
                            Array(
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "ADD_SECTIONS_CHAIN" => "Y",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                "DISPLAY_DATE" => "Y",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "Y",
                                "DISPLAY_PREVIEW_TEXT" => "Y",
                                "DISPLAY_TOP_PAGER" => "N",
                                "FIELD_CODE" => array("",""),
                                "FILE_404" => "",
                                "FILTER_NAME" => "arrFilterAbonement",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "IBLOCK_ID" => "9",
                                "IBLOCK_TYPE" => "content",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "MESSAGE_404" => "",
                                "NEWS_COUNT" => "20",
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
                                "PROPERTY_CODE" => array("SIZE","PRICE",""),
                                "SET_BROWSER_TITLE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "N",
                                "SHOW_404" => "N",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER1" => "ASC",
                                "SORT_ORDER2" => "ASC",
                                "STRICT_SECTION_CHECK" => "N",
                                "ITEMS_ID"=>$objects[$ELEMENT_ID]["PROPERTIES"]["PARTNERS_ABONEMENT_ELEMENTS"]["VALUE"],
                                "SLIDES_TO_SHOW_AND_SCROLL"=>4,
                                "CONTENT_CENTER"=>false
                            )
                        );?>
                    </div>
                <?endif;?>

            </div>
        </div>
    </section>
<? $APPLICATION->IncludeFile('/local/include/blocks.abonements.php', ['ELEMENT_CODE' => 'partnery-club-members', 'blockTitle' => "Подробнее о партнерах"], ['SHOW_BORDER' => false]); ?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");