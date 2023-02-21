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

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slick.css");
?>
<div class="workout-group-slider">
    <div class="content-center">
        <div class="workout-group-slider__slider-wrap">
            <div class="workout-group-slider__slider">
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    $imageSrc = "";
                    if( !empty($arItem['PREVIEW_PICTURE']) ) {
                        $imageSrc = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 379, 'height' => 580), BX_RESIZE_IMAGE_EXACT)["src"];
                    }
                    ?>

                    <div class="workout-group-slider__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <div class="workout-group-twoside-card">
                            <div class="workout-group-twoside-card__inner">
                                <div class="workout-group-twoside-card__content" data-src="<?=$imageSrc?>">
                                    <div class="workout-group-twoside-card__label"><?=$arItem["~NAME"]?></div>
                                </div>
                                <div class="workout-group-twoside-card__hidden-content">
                                    <div class="workout-group-twoside-card__title"><?=$arItem["~NAME"]?></div>
                                    <div class="workout-group-twoside-card__text"><?=$arItem["PREVIEW_TEXT"]?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>
