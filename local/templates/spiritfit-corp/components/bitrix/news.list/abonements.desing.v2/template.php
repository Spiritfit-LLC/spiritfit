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
                <? foreach($arResult["ITEMS"] as $key => $arItem) { ?>
                    <div class="b-cards-slider__item">
                        <div class="b-twoside-card corp-abonement-wrapper">
                            <div class="b-twoside-card__inner corp-abonement">
                                <div class="b-twoside-card__content" <?=(!empty($arItem['PICTURE_BACKGROUND'])) ? 'style="background-image: url(\''.$arItem["PICTURE_BACKGROUND"].'\');"' : '' ?>>
                                    <div class="corp-abonement__front-title"><span><?=$arItem['TITLE_FRONT']?></span></div>
									<? if( !empty($arItem["PICTURE"]) ) { ?>
										<div class="corp-abonement__front-picture">
											<span><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['TITLE_FRONT']?>" title="<?=$arItem['TITLE_FRONT']?>"></span>
										</div>
									<? } ?>
									<? if( !empty($arItem["TEXT1_FRONT"]) ) { ?>
										<div class="corp-abonement__front-list">
											<? foreach($arItem["TEXT1_FRONT"] as $listItem) { ?>
												<div class="corp-abonement__front-list-item"><?=$listItem?></div>
											<? } ?>
										</div>
									<? } ?>
									<? if( !empty($arItem["TEXT2_FRONT"]) ) { ?>
										<div class="corp-abonement__front-text2">
											<?=$arItem["TEXT2_FRONT"]?>
										</div>
									<? } ?>
									<? if( !empty($arItem["TEXT3_FRONT"]) ) { ?>
										<div class="corp-abonement__front-text3">
											<?=$arItem["TEXT3_FRONT"]?>
										</div>
									<? } ?>
									<div class="corp-abonement__front-buttons">
										<div class="table-cell">
											<span class="button"><?=(!empty($arItem["BUTTON_1_TEXT"])) ? $arItem["BUTTON_1_TEXT"] : "Подробнее"?></span>
										</div>
										<div class="table-cell">
											<a href="#open_form" class="to-contact-form button"><?=(!empty($arItem["BUTTON_2_TEXT"])) ? $arItem["BUTTON_2_TEXT"] : "Оставить заявку"?></a>
										</div>
									</div>
                                </div>
                                <div class="b-twoside-card__hidden-content">
                                    <div class="corp-abonement__back-title">
										<?=(!empty($arItem['TITLE_BACK'])) ? $arItem['TITLE_BACK'] : $arItem['TITLE_FRONT']?>
									</div>
									<? if( !empty($arItem["TEXT1_BACK"]) ) { ?>
										<div class="corp-abonement__front-list">
											<? foreach($arItem["TEXT1_BACK"] as $listItem) { ?>
												<div class="corp-abonement__front-list-item"><?=$listItem?></div>
											<? } ?>
										</div>
									<? } ?>
									<? if( !empty($arItem["BUTTON_3_TEXT"]) ) { ?>
										<div class="corp-abonement__back-button">
											<a href="<?=(!empty($arItem["BUTTON_3_URL"])) ? $arItem["BUTTON_3_URL"] : "#open_form" ?>" class="button <?=(empty($arItem["BUTTON_3_URL"])) ? "to-contact-form" : "" ?>"><?=$arItem["BUTTON_3_TEXT"]?></a>
										</div>
									<? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</section>