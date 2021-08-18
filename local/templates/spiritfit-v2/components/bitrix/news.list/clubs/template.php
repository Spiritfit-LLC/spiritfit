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
                <h2>Клубы</h2>
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
					if( !empty($arItem['PROPERTIES']['HIDE_LINK']['VALUE']) ) continue;
					
					// print_r($arItem);
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

					$soon = $arItem['PROPERTIES']['NOT_OPEN_YET']['VALUE'];
					?>
					
					<div class="b-cards-slider__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<? if($soon == 'Да'){ ?>
							<span class="b-oneside-card" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>');">
						<? }else{ ?>
							<a class="b-oneside-card" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>');">
						<? } ?>
							<span class="b-oneside-card__content">
								<img style="display: none;" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="Фитнес-клуб <?=$arItem["~NAME"]?>">
								<span class="b-oneside-card__label">
									<span class="b-oneside-card__label-text"><?=$arItem["~NAME"]?></span>
								</span>
							</span>
						<? if($soon == 'Да'){ ?>
							</span>
						<? }else{ ?>
							</a>
						<? } ?>
					</div>
				<? endforeach; ?>
			</div>
        </div>
    </div>
</section>