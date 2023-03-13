<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?php
global $USER;
?>
<div class="content-center">
    <?php
    $APPLICATION->IncludeComponent(
        "personal.custom:personal.auth",
        "page",
        array(
            "AUTH_FORM_CODE" => "AUTH",
            "REGISTER_FORM_CODE" => "REGISTRATION_LITE",
            "PASSFORGOT_FORM_CODE"=>'PASSFORGOT',
            "CACHE_TIME" => 0,
            "CACHE_TYPE" => "N",
        ),
        $component
    );
    ?>
</div>
<?if ($USER->IsAuthorized()):?>
    <?php
    $page = $_SERVER['REQUEST_URI'];
    if(strpos($_SERVER['REQUEST_URI'], '?')){
        $page = explode('?', $page);
        $page = $page[0];
    }
    $GLOBALS['arFilterSlider'] = [
        [
            'LOGIC' => 'AND',
            [
                'LOGIC' => 'OR',
                ['PROPERTY_BANNER_PAGES' => false],
                ['PROPERTY_BANNER_PAGES' => $page],
            ],
            ['!PROPERTY_BANNER_PAGES_HIDE' => $page],
            [
                ['PROPERTY_SITE' => 42]
            ]
        ]
    ];
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "slider",
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
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "N",
            "DISPLAY_PICTURE" => "N",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "DISPLAY_TOP_PAGER" => "N",
            "FIELD_CODE" => array("", ""),
            "FILTER_NAME" => "arFilterSlider",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => "2",
            "IBLOCK_TYPE" => "content",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "INCLUDE_SUBSECTIONS" => "Y",
            "MESSAGE_404" => "",
            "NEWS_COUNT" => "10",
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
            "PROPERTY_CODE" => array("BANNER_BTN_TEXT", "BANNER_BTN_LINK", "BANNER_TITLE"),
            "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "SORT_BY1" => "SORT",
            "SORT_BY2" => "ID",
            "SORT_ORDER1" => "DESC",
            "SORT_ORDER2" => "DESC",
            "STRICT_SECTION_CHECK" => "N"
        ),
        $component
    );
    ?>
    <div class="content-center">
        <?php
        $APPLICATION->IncludeComponent(
            "personal.custom:personal.info",
            "",
            array(
                "USER_ID"=>$USER->GetID(),
                "MENU"=>$arResult["MENU"]
            ),
            $component
        );
        ?>
        <?php
        $APPLICATION->IncludeComponent(
            "personal.custom:personal.services",
            "",
            array(
            ),
            $component
        );
        ?>
    </div>

    <div class="content-center">
        <div id="personal-detached-services">
            <div class="personal-section-title">
                Услуги
            </div>
            <div class="pds__container">
                <?foreach($arResult["PDS"] as $pds):?>
                    <div class="pds__item">
                        <div class="personal-detached-service" id="<?=$pds["CLASSNAME"]?>">
                            <div class="pds__title">Воспользоваться</div>
                            <div class="pds__name">
                                <div class="pds__service-icon">
                                    <?=file_get_contents(__DIR__.'/images/pds__service-workout.svg')?>
                                </div>
                                <span><?=$pds["NAME"]?></span>
                            </div>
                            <div class="pds__icon-global">
                                <?=file_get_contents(__DIR__.'/images/pds__service-workout.svg')?>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
    <?foreach ($arResult["PDS"] as $pds):?>
        <?
        $APPLICATION->IncludeComponent(
            "custom:ajax.component",
            "popup",
            array(
                "COMPONENT" => $pds["COMPONENT"],
                "COMPONENT_TEMPLATE" => $pds["COMPONENT_TEMPLATE"],
                "COMPONENT_PARAMS" => $pds["COMPONENT_PARAMS"],
                "TRIGGER" => "#".$pds["CLASSNAME"],
                "TRIGGER_TYPE" => "click",
                "AJAX_OPTION_ADDITIONAL" => 'personal_'.$pds["CLASSNAME"],
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_HISTORY" => "N",
            )
        );
        ?>
    <?endforeach;?>
<?endif;?>
