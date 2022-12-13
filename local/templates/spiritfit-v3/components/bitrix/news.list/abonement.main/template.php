<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
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

$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slick.css");
?>

<section class="b-cards-slider">
    <div class="content-center">
        <div class="sliders-section__controls">
            <?$i=0;?>
            <?foreach ($arResult["SECTIONS"] as $SECTION):?>
                <button class="slider-section__item <?if ($i==0) echo 'active'?>" onclick="select_section(this, '<?=$SECTION["ID"]?>')" data-section-id="<?=$SECTION["ID"]?>">
                    <?=$SECTION["NAME"]?>
                </button>
            <?$i++?>
            <?endforeach;?>
        </div>
        <div class="b-cards-slider__slider-wrap">
            <div class="b-cards-slider__slider">
                <?foreach($arResult["ITEMS"] as $ITEM):?>
                    <div class="b-cards-slider__item abonement" data-sections='<?=\Bitrix\Main\Web\Json::encode($ITEM["SECTIONS"])?>'>
                        <?if (false):?>
                        <div class="slider-abonement__item-picture" style="background-image: url('<?=$ITEM["PREVIEW_PICTURE"]["SRC"]?>')"></div>
                        <?endif;?>
                        <div class="slider-abonement__item-info">
                            <div class="slider-abonement__item-title">
                                <?=htmlspecialcharsback($ITEM["PROPERTIES"]["TITLE"]["VALUE"]["TEXT"])?>
                            </div>
                            <div class="slider-abonement__item-price" <?if ($ITEM["MIN_PRICE"]==0):?> style="justify-content: center" <?endif?>>
                                <?if ($ITEM["MIN_PRICE"]!=0):?>
                                    <?if (!empty($ITEM["PROPERTIES"]["PRICE_MAIN_SIGN"]["VALUE"][0])):?>
                                        <div><?=$ITEM["PROPERTIES"]["PRICE_MAIN_SIGN"]["VALUE"][0]?></div>
                                    <?else:?>
                                        <div>Ежемесячный платеж</div>
                                    <?endif?>
                                <?endif?>
                                <div class="abonement-price">
                                    <?if ($ITEM["MIN_PRICE"]==0):?>
                                        Бесплатно
                                    <?else:?>
                                        <?=$ITEM["MIN_PRICE"]?><span class="rub">₽</span>
                                    <?endif?>
                                </div>
                            </div>

                            <?if (false):?>
                            <div class="slider-abonement__item-price" style="margin-top: -16px; height: 39px">
                                <?if (!empty($ITEM["MIN_PRICE2"])):?>
                                    <?if (!empty($ITEM["PROPERTIES"]["PRICE_MAIN_SIGN"]["VALUE"][1])):?>
                                        <div><?=$ITEM["PROPERTIES"]["PRICE_MAIN_SIGN"]["VALUE"][1]?></div>
                                    <?else:?>
                                        <div>Первый месяц от</div>
                                    <?endif?>
                                    <div class="abonement-price white-text">
                                        <?=$ITEM["MIN_PRICE2"]?><span class="rub">₽</span>
                                    </div>
                                <?endif;?>
                            </div>
                            <?endif;?>



                            <div class="slider-abonement__item-sale" style="height: <?=$arResult["PRESENT_HEIGHT"]?>px">
                                <?foreach ($ITEM["PROPERTIES"]["PRESENTS"]["VALUE"] as $PRESENT):?>
                                <div class="abonement-sale-container" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/icons/abonement-sale.svg'?>')">
                                    <div><?=$PRESENT?></div>
                                </div>
                                <?endforeach;?>
                                <?if (!empty($ITEM["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"])):?>
                                    <span class="abonement-sale-date"><?=$ITEM["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]?></span>
                                <?endif;?>
                            </div>
                            <?if (!empty($ITEM["PROPERTIES"]["INCLUDE"]["VALUE"])):?>
                            <div class="slider-abonement__item-include-list">
                                <!--noindex-->
                                <? foreach($ITEM["PROPERTIES"]["INCLUDE"]["VALUE"] as $listItem) { ?>
                                    <div class="abonement__item-include-item"><?=htmlspecialcharsback($listItem)?></div>
                                <? } ?>
                                <!--/noindex-->
                            </div>
                            <?endif?>
                            <a href="<?=$ITEM["DETAIL_PAGE_URL"]?>" class="button abonement-item__btn">Купить</a>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</section>