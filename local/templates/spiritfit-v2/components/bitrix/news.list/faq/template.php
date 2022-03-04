<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<section class="b-faq">
    <div class="content-center">
        <div id="faq" class="b-faq__content">
            <h2><?=!empty($arParams["BLOCK_TITLE"]) ? $arParams["BLOCK_TITLE"] : "FAQ" ?></h2>
            <div class="b-faq__tabs js-tabs-parent">
                <ul class="b-faq__tab-links is-hide-mobile">
                    <?
                    $k = 0;
                    foreach($arResult["SECTIONS"] as $section):?>
                        <li <?=(!empty($section['UF_ANCHOR'])) ? 'id="'.$section['UF_ANCHOR'].'"' : '' ?> class="b-faq__tab-link <?=($k == 0 ? 'is-active' : '')?>"><?=$section['NAME']?></li>
                        <? $k++; ?>
                    <?endforeach;?>
                </ul>
                <div class="b-faq__tabs-list">
                    <?
                    $q = 0;
                    foreach($arResult["SECTIONS"] as $section):?>
                        <div class="b-faq__tab <?=($q == 0 ? 'is-active' : '')?>">
                            <div class="b-faq__tab-header is-hide-desktop"><?=$section['NAME']?></div>
                            <div class="b-faq__tab-content">
                                <div class="b-accordion">
                                    <?foreach($section["ITEMS"] as $key => $arItem):?>
                                        <?
                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                        ?>
                                            <div class="b-accordion__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                                <div class="b-accordion__heading"><?=$arItem['NAME']?></div>
                                                <div class="b-accordion__content"><?=$arItem['~PREVIEW_TEXT']?></div>
                                            </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                        <? $q++; ?>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>
</section>