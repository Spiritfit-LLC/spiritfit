<?php
define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Тренировки");
$APPLICATION->SetPageProperty("title", "Тренировки - фитнес-программы для занятий в сети фитнес-клубов Spirit Fitness");
$APPLICATION->SetPageProperty("description", "Более 160 групповых уроков в расписании 🤩 от йоги до силовых. Звоните и наши консультанты все вам расскажут и помогут подобрать групповые тренировки");
?>
<style>
    #gym-slider,
    #group-workouts-slider,
    #atmosfera-slider{
        margin-top: 80px;
    }
</style>
<section id="group-workouts">
    <div class="content-center">
        <div class="b-section__title">
            <h2>Групповые тренировки</h2>
        </div>
    </div>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "workouts.group",
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
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "FIELD_CODE" => array("ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", ""),
            "FILTER_NAME" => "",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => "10",
            "IBLOCK_TYPE" => "service",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "INCLUDE_SUBSECTIONS" => "N",
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
            "PROPERTY_CODE" => array("", ""),
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
</section>
<section id="gym-slider">
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "blocks.abonements",
        array(
            "COMPONENT_TEMPLATE" => "",
            "IBLOCK_TYPE" => "service",
            "IBLOCK_ID" => "18",
            "BLOCK_TITLE" => "",
            "ELEMENT_ID" => "",
            "ELEMENT_CODE" => "trenazhernyy-zal",
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
            "FILE_404" => "",
            "TITLE_ON_TOP"=>false,
            "HIDE_TITLE"=>true
        ),
        false
    );?>
</section>
<section id="group-workouts-slider">
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "blocks.abonements",
        array(
            "COMPONENT_TEMPLATE" => "",
            "IBLOCK_TYPE" => "service",
            "IBLOCK_ID" => "18",
            "BLOCK_TITLE" => "",
            "ELEMENT_ID" => "",
            "ELEMENT_CODE" => "zal-gruppovykh-trenirovok",
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
            "FILE_404" => "",
            "TITLE_ON_TOP"=>false,
            "HIDE_TITLE"=>true
        ),
        false
    );?>
</section>

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

<section id="atmosfera-slider">
    <? $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "blocks.abonements",
        array(
            "COMPONENT_TEMPLATE" => "",
            "IBLOCK_TYPE" => "service",
            "IBLOCK_ID" => "18",
            "BLOCK_TITLE" => "",
            "ELEMENT_ID" => "",
            "ELEMENT_CODE" => "atmosfera",
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
            "FILE_404" => "",
            "TITLE_ON_TOP"=>false,
            "HIDE_TITLE"=>true
        ),
        false
    );?>
</section>
<section id="form" style="margin-top: 80px;">
    <?
    $APPLICATION->IncludeComponent(
        "custom:form.request.new",
        "on.page.block",
        array(
            "COMPONENT_TEMPLATE" => "on.page.block",
            "WEB_FORM_ID" => Utils::GetFormIDBySID("TRIAL_TRAINING_NEW"),
            "WEB_FORM_FIELDS" => array(
                0 => "club",
                1 => "name",
                2 => "phone",
                3 => "email",
                4 => "personaldata",
                5 => "rules",
                6 => "privacy",
            ),
            "FORM_TYPE" =>"3",
            "TEXT_FORM" => "ИСПЫТАЙТЕ ВОЗМОЖНОСТИ КЛУБА И ПОЛУЧИТЕ СПЕЦИАЛЬНЫЕ УСЛОВИЯ НА ПОКУПКУ АБОНЕМЕНТА",
        ));
    ?>
</section>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>