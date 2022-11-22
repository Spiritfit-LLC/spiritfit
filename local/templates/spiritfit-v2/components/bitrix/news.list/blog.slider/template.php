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

?>
<? if(!empty($arResult["ITEMS"])){  ?>
    <div class="b-screen__content">
        <div class="b-screen__slider">
            <div class="b-info-slider">
                <div class="b-info-slider__nav"></div>
                <div class="b-info-slider__slider">
                    <?foreach($arResult["ITEMS"] as $arItem):?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        $btnText = $arItem['PROPERTIES']['BANNER_BTN_TEXT']['VALUE'];
                        $btnLink = $arItem['PROPERTIES']['BANNER_BTN_LINK']['VALUE'];
                        $btnType = $arItem['PROPERTIES']['BANNER_BTN_TYPE']['VALUE_XML_ID'];
                        if($btnType == 'anchor'){
                            $btnText = 'Участвовать';
                            $btnLink = '#js-pjax-clubs';
                        }

                        $arItem["PREVIEW_TEXT"] = strip_tags($arItem["PREVIEW_TEXT"]);
                        $arItem["PREVIEW_TEXT"] = mb_strimwidth($arItem["PREVIEW_TEXT"], 0, 345, "...");
                        ?>
                        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="b-info-slider__item">
                            <div class="b-info-slider__suptitle"><?=(!empty($arItem['PROPERTIES']['BANNER_TITLE']['VALUE'])) ? $arItem['PROPERTIES']['BANNER_TITLE']['VALUE'] : "Акция"?></div>
                            <div class="b-info-slider__title"><?=$arItem["NAME"]?></div>
                            <div class="b-info-slider__text"><?=$arItem["~PREVIEW_TEXT"]?></div>
                            <a class="b-info-slider__btn button" href="<?=(!empty($btnLink) ? $btnLink : '#')?>" onclick="setConversion('SliderBtnConversion');" style="width: 100%"><?=(!empty($btnText) ? $btnText : 'Заказать')?></a>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>

    </div>
<? } ?>
