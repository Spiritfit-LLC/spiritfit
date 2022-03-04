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
<section class="b-cards-slider">
    <div class="content-center">
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2><?=!empty($arParams["BLOCK_TITLE"]) ? $arParams["BLOCK_TITLE"] : "Групповые тренировки" ?></h2>
            </div>
            <div class="b-cards-slider__slider-nav">
            </div>
        </div>
    </div>
    <div class="b-cards-slider__slider-wrap">
        <div class="content-center">
            <div class="b-cards-slider__slider">
				<? foreach ($arResult["ITEMS"] as $arItem): ?>
					<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						
						$imageSrc = "";
						if( !empty($arItem['PREVIEW_PICTURE']) ) {
							$imageSrc = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 379, 'height' => 580), BX_RESIZE_IMAGE_EXACT)["src"]; 
						}
					?>

					<div class="b-cards-slider__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="b-twoside-card">
							<div class="b-twoside-card__inner">
								<div class="b-twoside-card__content" style="background-image: url(<?=$imageSrc?>);">
									<div class="b-twoside-card__label"><?=$arItem["~NAME"]?></div>
								</div>
								<div class="b-twoside-card__hidden-content">
									<div class="b-twoside-card__title"><?=$arItem["~NAME"]?></div>
									<div class="b-twoside-card__text"><?=$arItem["PREVIEW_TEXT"]?></div>
								</div>
							</div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
        </div>
    </div>
</section>