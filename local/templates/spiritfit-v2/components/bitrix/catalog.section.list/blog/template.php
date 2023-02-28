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
    <div class="blog-section-list">
    <?
        foreach ($arResult['SECTIONS'] as $arSection) {
    	    ?>
            <?if ($arSection["IS_CURRENT"]):?>
            <h1 style="display: none"><?=$arSection["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]?></h1>
            <?endif;?>
            <a class="blog-section <?=$arSection["IS_CURRENT"] ? "current" : "" ?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a><?
        }
    ?></div><?
