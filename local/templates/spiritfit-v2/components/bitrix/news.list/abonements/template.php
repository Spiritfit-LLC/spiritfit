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
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<section class="b-cards-slider" <?if (!empty($arParams["SLIDES_TO_SHOW_AND_SCROLL"])){?> data-slides="<?=$arParams["SLIDES_TO_SHOW_AND_SCROLL"]?>" <?}?>>
    <? if( isset($arParams['TITLE_BLOCK']) && $arParams['TITLE_BLOCK'] !== "N" ) { ?>
		<div class="content-center">
        	<div class="b-cards-slider__heading">
            	<div class="b-cards-slider__title">
                	<h2><?=($arParams['TITLE_BLOCK'] ? $arParams['TITLE_BLOCK'] : 'Абонементы')?></h2>
            	</div>
            	<div class="b-cards-slider__slider-nav"></div>
        	</div>
    	</div>
	<? } ?>
    <div class="b-cards-slider__slider-wrap">
        <div class="content-center">
            <div class="b-cards-slider__slider">
                <?foreach($arResult["ITEMS"] as $key => $arItem):?>
                    <?
                    if (!empty($arParams["ITEMS_ID"]) && !in_array($arItem["ID"], $arParams["ITEMS_ID"])){
                        continue;
                    }
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

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
					<script>
						if(window.abonement === undefined){ window.abonement = {} };
						window.abonement["<?=$arItem['ID']?>"] = <?=$arDataAbonement?>;
					</script>
                    
                    <div class="b-cards-slider__item v2-abonement">
                        <div class="b-twoside-card"  data-sub_id="<?=$arItem['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>">
                            <div class="b-twoside-card__inner">
                                <div class="b-twoside-card__content"
                                    style="background-image: url(<?=$imageSrc?>);">
                                    <div class="b-twoside-card__label"><?=$arItem['~NAME']?></div>
                                </div>
                                <div class="b-twoside-card__hidden-content">
                                    <div class="corp-abonement__back-title">
										<?=$arItem['~NAME']?>
									</div>
									<? if( !empty($arItem["PROPERTIES"]["INCLUDE"]["VALUE"]) ) { ?>
										<div class="corp-abonement__front-list">
                                            <!--noindex-->
											<? foreach($arItem["PROPERTIES"]["INCLUDE"]["VALUE"] as $listItem) { ?>
												<div class="corp-abonement__front-list-item"><?=htmlspecialcharsback($listItem)?></div>
											<? } ?>
                                            <!--/noindex-->
										</div>
									<? } ?>
                                    <? if ($arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]): ?>
                                        <div class="b-twoside-card__footnote"><?= $arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
                                    <? endif; ?>
                                </div>
                            </div>
                            <div class="b-twoside-card__footer">
                                <div class="abonement-min-price__face">
                                    <div class="b-twoside-card__prices-face">от <?=$arItem["MIN_PRICE"]?> <span class="rub">₽</span><?if ($arItem["MIN_PRICE_PER_MONTH"]):?> в месяц<?endif;?></div>
                                </div>
                                <a class="button-outline b-twoside-card-detail-btn">Подробнее</a>
                                <a class="button <?=$arItem['PROPERTIES']['ADDITIONAL_CLASS']['VALUE']?> choose-abonement-btn" href="<?=$arItem['DETAIL_PAGE_URL']?>" data-sub_id="<?=$arItem['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>" style="display: none;">Купить</a>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</section>