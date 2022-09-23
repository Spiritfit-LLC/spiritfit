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
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<div class="landing__abonements-wrapper">
    <div class="landing__abonements-slider">
        <?foreach($arResult["ITEMS"] as $key => $arItem):?>
            <?
            $arItem["PREVIEW_TEXT"] = strip_tags($arItem["PREVIEW_TEXT"]);
            $arItem["PREVIEW_TEXT"] = mb_strimwidth($arItem["PREVIEW_TEXT"], 0, 325, "...");

            $arDataAbonement = Abonement::getItem($arItem['ID'], 265);
            $arDataAbonement = CUtil::PhpToJSObject($arDataAbonement);
            $arDataAbonement = str_replace("'", '"', $arDataAbonement);

            $imageSrc = "";
            if( !empty($arItem['PREVIEW_PICTURE']) && empty($arItem['PREVIEW_PICTURE_WEBP']['WEBP_SRC']) ) {
                $imageSrc = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 379, 'height' => 580), BX_RESIZE_IMAGE_EXACT)["src"];
            } else {
                $imageSrc = $arItem['PREVIEW_PICTURE_WEBP']['WEBP_SRC'];
            }
            ?>
            <div class="b-cards-slider__item v3-abonement">
                <div class="b-twoside-card"  data-sub_id="<?=$arItem['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>">
                    <div class="b-twoside-card__inner">
                        <div class="b-twoside-card__content" style="background-image: url(<?=$imageSrc?>);">
                            <? if(!empty($arItem["MIN_PRICE2"])) { ?>
                                <div class="b-twoside-card__price"><?=GetMessage("LANDING_ABONEMENT_PRICE")?> <span class="val"><?=$arItem["MIN_PRICE2"]?></span> <span class="rub">₽</span></div>
                            <? } ?>
                            <div class="b-twoside-card__detail"><?=GetMessage("LANDING_ABONEMENT_DETAIL")?></div>
                        </div>
                        <div class="b-twoside-card__hidden-content">
                            <div class="b-twoside-card__name"><?=$arItem["~NAME"]?></div>
							<? if( !empty($arItem["PROPERTIES"]["INCLUDE"]["VALUE"]) ) { ?>
                                <div class="corp-abonement__front-list">
                                    <? foreach($arItem["PROPERTIES"]["INCLUDE"]["VALUE"] as $listItem) { ?>
                                        <div class="corp-abonement__front-list-item"><?=$listItem?></div>
                                    <? } ?>
                                </div>
                            <? } ?>
                            <? if ($arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]): ?>
                                <div class="b-twoside-card__footnote"><?= $arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
                            <? endif; ?>
                            <a class="button get-abonement <?=$arItem['PROPERTIES']['ADDITIONAL_CLASS']['VALUE']?> choose-abonement-btn" href="<?=$arItem['DETAIL_PAGE_URL']?>" data-sub_id="<?=$arItem['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>" data-leaderid=""><?=GetMessage("LANDING_ABONEMENT_GET")?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>
</div>