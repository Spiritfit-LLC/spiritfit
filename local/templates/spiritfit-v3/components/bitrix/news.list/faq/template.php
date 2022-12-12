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
<section class="b-faq" itemscope itemtype="https://schema.org/FAQPage">
    <div class="content-center">
        <div class="b-section__title">
            <h2>FAQ</h2>
        </div>
    </div>
    <div class="content-center">
        <div id="faq" class="b-faq__content">
            <ul class="b-faq__tab-links hidden-phone hidden-tablet">
                <? $k=0; foreach ($arResult["SECTIONS"] as $SECTION):?>
                    <li class="b-faq__tab-link <?=($k==0)? 'active':''?>" style="width: <?=$arResult["SECTION_ITEM_WIDTH"]?>%"
                        data-layer="true"
                        data-layercategory="UX"
                        data-layeraction="clickFAQButtons"
                        data-layerlabel="<?=$SECTION['NAME']?>"
                    onclick="set_section(this, <?=$SECTION["ID"]?>)"><?=$SECTION['NAME']?></li>
                <?$k++; endforeach;?>
            </ul>
            <div class="b-faq__tabs-list">
                <?$q=0; foreach ($arResult["SECTIONS"] as $SECTION):?>
                    <div class="b-faq__tab-link b-faq__tab-header hidden-desktop <?=($q==0)? 'active':''?>"
                         data-layer="true"
                         data-layercategory="UX"
                         data-layeraction="clickFAQButtons" onclick="set_section(this, <?=$SECTION["ID"]?>)"><?=$SECTION['NAME']?></div>
                    <div class="b-faq__tab <?=($q==0)? 'active':''?>" data-section="<?=$SECTION["ID"]?>">
                        <div class="b-faq__tab-content">
                            <div class="b-accordion">
                                <?foreach($SECTION["ITEMS"] as $key => $arItem):?>
                                    <div class="b-accordion__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" onclick="open_accrodion(this)">
                                        <div class="b-accordion__heading" itemprop="name">
                                            <span><?=$arItem['NAME']?></span>
                                        </div>
                                        <div class="b-accordion__content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text"><?=$arItem['~PREVIEW_TEXT']?></div>
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                <?$q++; endforeach;?>
            </div>
        </div>
    </div>
</section>
<!--<section class="b-faq" itemscope itemtype="https://schema.org/FAQPage">-->
<!--    <div class="content-center">-->
<!--        <div id="faq" class="b-faq__content">-->
<!--            <div class="b-faq__tabs js-tabs-parent">-->
<!--                <ul class="b-faq__tab-links is-hide-mobile">-->
<!--                    --><?//
//                    $k = 0;
//                    foreach($arResult["SECTIONS"] as $section):?>
<!--                        <li --><?//=(!empty($section['UF_ANCHOR'])) ? 'id="'.$section['UF_ANCHOR'].'"' : '' ?><!-- class="b-faq__tab-link --><?//=($k == 0 ? 'is-active' : '')?><!--">--><?//=$section['NAME']?><!--</li>-->
<!--                        --><?// $k++; ?>
<!--                    --><?//endforeach;?>
<!--                </ul>-->
<!--                <div class="b-faq__tabs-list">-->
<!--                    --><?//
//                    $q = 0;
//                    foreach($arResult["SECTIONS"] as $section):?>
<!--                        <div class="b-faq__tab --><?//=($q == 0 ? 'is-active' : '')?><!--">-->
<!--                            <div class="b-faq__tab-header is-hide-desktop">--><?//=$section['NAME']?><!--</div>-->
<!--                            <div class="b-faq__tab-content">-->
<!--                                <div class="b-accordion">-->
<!--                                    --><?//foreach($section["ITEMS"] as $key => $arItem):?>
<!--                                        --><?//
//                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
//                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
//                                        ?>
<!--                                        <div class="b-accordion__item" id="--><?//=$this->GetEditAreaId($arItem['ID']);?><!--" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">-->
<!--                                            <div class="b-accordion__heading" itemprop="name">-->
<!--                                                --><?//=$arItem['NAME']?>
<!--                                            </div>-->
<!--                                            <div class="b-accordion__content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">-->
<!--                                                <div itemprop="text">--><?//=$arItem['~PREVIEW_TEXT']?><!--</div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    --><?//endforeach;?>
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?// $q++; ?>
<!--                    --><?//endforeach;?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->