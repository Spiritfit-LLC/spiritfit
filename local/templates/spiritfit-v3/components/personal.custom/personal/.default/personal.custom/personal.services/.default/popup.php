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
$this->setFrameMode(false);
?>

<form id="ps-service__form">
    <div class="popup__modal-title">Управление услугой</div>
    <div class="popup__modal-info"><?=$arResult["DESCRIPTION"]?></div>

    <div class="personal-service__content">
        <?if (empty($arResult["LIST"])):?>
            <input name="service" value="<?=$arResult["ITEM"]["ID"]?>" type="hidden">
            <div class="ps-service__item">
                <div class="ps-service__item-title"><?=$arResult["ITEM"]["NAME"]?></div>
                <div class="ps-service__item-description"><?=$arResult["ITEM"]["DESCRIPTION"]?></div>
                <div class="ps-service__item-price">
                    <span>Цена:</span>
                    <?if (!empty($arResult["ITEM"]["BASEPRICE"])):?>
                        <span class="ps-service__item-oldprice"><?=$arResult["ITEM"]["BASEPRICE"]?></span>
                    <?endif;?>
                    <span><?=$arResult["ITEM"]["PRICE"]?></span>
                    <span>руб.</span>
                </div>
            </div>
        <?else:?>
            <div class="ps-service__select select2-black">
                <select class="select2" name="service" autocomplete="off" required="required" id="service-select">
                    <?$index=0;?>
                    <?foreach ($arResult["LIST"] as $item):?>
                        <option value="<?=$item["ID"]?>" <?if ($index==0):?> selected <?endif;?>><?=$item["NAME"]?></option>
                        <?$index++;?>
                    <?endforeach; ?>
                </select>
            </div>

            <?$index=0;?>
            <?foreach ($arResult["LIST"] as $item):?>
                <div class="ps-service__item list" data-id="<?=$item["ID"]?>" <?if ($index>0):?> style="display: none" <?endif;?>>
                    <div class="ps-service__item-description"><?=$item["DESCRIPTION"]?></div>
                    <div class="ps-service__item-price">
                        <span>Цена:</span>
                        <?if (!empty($item["BASEPRICE"])):?>
                            <span class="ps-service__price old"><?=$item["BASEPRICE"]?></span>
                        <?endif;?>
                        <span class="ps-service__price"><?=$item["PRICE"]?></span>
                        <span>руб.</span>
                    </div>
                </div>
                <?$index++;?>
            <?endforeach;?>
        <?endif;?>
    </div>

    <?if ($arResult["STATUS"]):?>
        <div class="personal-service__submit">
            <input name="type" value="<?=$arResult["TYPE"]?>" type="hidden">
            <input type="submit" class="button" value="Включить" style="width: 100%">
            <div class="escapingBallG-animation">
                <div id="escapingBall_1" class="escapingBallG"></div>
            </div>
        </div>
    <?endif;?>
</form>
