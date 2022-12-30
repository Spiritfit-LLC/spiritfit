<?php
define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');
?>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");?>

<?
$APPLICATION->SetTitle("Программа лояльности");
$APPLICATION->SetPageProperty("title", "Программа лояльности - фитнес-клуб Spirit. Fitness");
$APPLICATION->SetPageProperty("description", "");

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'css/loyalty-program.css');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'js/loyalty-program.js');

$settings = Utils::getInfo();


CModule::IncludeModule("iblock");
?>

<section id="loyalty-levels">
    <div class="content-center">
        <div class="b-section__title">
            <h2>Уровни лояльности</h2>
        </div>
    </div>
    <div class="content-center">
        <div class="loyalty-levels__header">
            <div class="loyalty-levels__desc">
                Чем чаще и дольше вы тренируетесь — тем выше уровень лояльности и больше привилегий.
            </div>
            <div class="loyalty-levels__lk-btn">
                <a href="<?=$settings["PROPERTIES"]["LOYALITY_LOGIN"]["VALUE"]?>" class="gradient">Личный кабинет</a>
            </div>
        </div>
        <div class="loyalty-levels__body">
            <?
                $levels=CIBlockElement::GetList(
                        array("SORT"=>"ASC"),
                        array("IBLOCK_ID"=>Utils::GetIBlockIDBySID("loyalty-level"), "ACTIVE"=>"Y"),
                        false,
                        false,
                        array("PREVIEW_PICTURE", "PREVIEW_TEXT", "PROPERTY_BONUSES_COUNT", "NAME"));
            ?>
            <?while($LOYALTY_LEVEL=$levels->Fetch()):?>
                <div class="loyalty-level__item">
                    <div class="level-item__image lazy-img-bg" data-src="<?=CFile::GetPath($LOYALTY_LEVEL["PREVIEW_PICTURE"])?>"></div>
                    <div class="loyalty-level-item__body">
                        <h3 class="level-item__title bold"><?=$LOYALTY_LEVEL["NAME"]?> <span class="gradient-text">Spirit.</span></h3>
                        <div class="level-item__desc">
                            <div class="level-item__text">
                                <?=$LOYALTY_LEVEL["PREVIEW_TEXT"]?>
                            </div>
                            <div class="level-item__text">
                                <?=htmlspecialcharsback($LOYALTY_LEVEL["PROPERTY_BONUSES_COUNT_VALUE"]["TEXT"])?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endwhile;?>
        </div>
    </div>
</section>
<section id="loyalty-сonditions">
    <div class="content-center">
        <div class="b-section__title">
            <h2>Условия программы лояльности</h2>
        </div>
    </div>
    <?
    $сonditions=CIBlockElement::GetList(
        array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>Utils::GetIBlockIDBySID("loyalty-conditions"), "ACTIVE"=>"Y"),
        false,
        false);
    ?>
    <div class="content-center">
        <div class="loyalty-сonditions__body">
            <?while($LOYALTY_CONDITION=$сonditions->GetNextElement()):?>
                <?$PROPS=$LOYALTY_CONDITION->GetProperties()?>
                <div class="loyalty-сondition__item white">
                    <h3 class="loyalty-сondition__title"><?=$PROPS["TITLE"]["VALUE"]?></h3>
                    <?if (!empty($PROPS["LIST"]["VALUE"])):?>
                        <div class="loyalty-сondition__list">
                            <?foreach ($PROPS["LIST"]["VALUE"] as $ITEM):?>
                                <div class="loyalty-сondition__list-item" style="background-image: url(<?=SITE_TEMPLATE_PATH.'img/icons/abonement-check-mark.svg'?>);"><?=$ITEM?></div>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                    <?if (!empty($PROPS["GRADIENT"]["VALUE"])):?>
                    <div class="gradient-text bold"><?=$PROPS["GRADIENT"]["VALUE"]?></div>
                    <?endif;?>
                    <?if (!empty($PROPS["BTN"]["VALUE"])):?>
                        <a class="button" href="<?=$PROPS["BTN"]["DESCRIPTION"]?>"><?=$PROPS["BTN"]["VALUE"]?></a>
                    <?endif;?>
                </div>
            <?endwhile;?>
        </div>
    </div>
</section>
<section id="loyalty-actions">
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "faq",
        array(
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
            "FIELD_CODE" => array(
                0 => "",
                1 => "",
            ),
            "FILTER_NAME" => "",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => Utils::GetIBlockIDBySID("loyalty-faq"),
            "IBLOCK_TYPE" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "MESSAGE_404" => "",
            "NEWS_COUNT" => "6",
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
            "PROPERTY_CODE" => array(
                0 => "SIZE",
                1 => "PRICE",
                2 => "",
            ),
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
            "COMPONENT_TEMPLATE" => "faq",
            "FILE_404" => "",
            "TITLE"=>"Накопление и списание бонусов"
        ),
        false
    );?>
</section>
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>