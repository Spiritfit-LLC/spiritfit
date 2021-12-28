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
<section class="b-cards-slider">
    <div class="content-center">
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2><?=($arParams['TITLE_BLOCK'] ? $arParams['TITLE_BLOCK'] : 'Абонементы')?></h2>
            </div>
            <div class="b-cards-slider__slider-nav">
            </div>
        </div>
    </div>
    <div class="b-cards-slider__slider-wrap">
        <div class="content-center">
            <div class="b-cards-slider__slider">
                <?foreach($arResult["ITEMS"] as $key => $arItem):?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    $arItem["PREVIEW_TEXT"] = strip_tags($arItem["PREVIEW_TEXT"]);
                    $arItem["PREVIEW_TEXT"] = mb_strimwidth($arItem["PREVIEW_TEXT"], 0, 325, "...");

                    $arDataAbonement = Abonement::getItem($arItem['ID'], 265); 
					$arDataAbonement = CUtil::PhpToJSObject($arDataAbonement);
					$arDataAbonement = str_replace("'", '"', $arDataAbonement);
					$imageSrc = "";
					if( !empty($arItem['PREVIEW_PICTURE']) ) {
						$imageSrc = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 379, 'height' => 580), BX_RESIZE_IMAGE_EXACT)["src"]; 
					}
					?>
					<script>
						if(window.abonement === undefined){ window.abonement = {} };
						window.abonement["<?=$arItem['ID']?>"] = <?=$arDataAbonement?>;
					</script>
                    
                    <div class="b-cards-slider__item">
                        <div class="b-twoside-card">
                            <div class="b-twoside-card__inner">
                                <div class="b-twoside-card__content"
                                    style="background-image: url(<?=$imageSrc?>);">
                                    <div class="b-twoside-card__label"><?=$arItem['~NAME']?></div>
                                </div>
                                <div class="b-twoside-card__hidden-content">
                                    <div class="b-twoside-card__title"><?=$arItem['~NAME']?></div>
                                    <div class="b-twoside-card__text"><?=$arItem["PREVIEW_TEXT"]?></div>
                                    <div class="b-twoside-card__prices">
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-twoside-card__prices-button button">Выбрать</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</section>