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
<div class="blockitem">
    <div class="prise-detail content-center">
        <div class="table <?=!empty($arResult['DETAIL_PICTURE_SRC']) ? 'two' : ''?>">
            <? if( !empty($arResult['DETAIL_PICTURE_SRC']) ) {
                ?>
                <div class="table-cell">
                    <div class="prise-detail-image">
                        <img src="<?=$arResult['DETAIL_PICTURE_SRC']?>" alt="<?=$arResult['NAME']?>" title="<?=$arResult['NAME']?>">
                    </div>
                </div>
                <?
            } ?>
            <div class="table-cell">
                <h1 class="block-title"><?=$arResult['NAME']?></h1>
                <? if( !empty($arResult['PROPERTIES']['LINK']['VALUE']) ) {
                    ?><div class="block-description"><?=$arResult['PROPERTIES']['LINK']['VALUE']?></div><?
                } ?>
                <?=$arResult['DETAIL_TEXT']?>
            </div>
        </div>
    </div>
</div>