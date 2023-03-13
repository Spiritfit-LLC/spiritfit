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

$this->addExternalJs(SITE_TEMPLATE_PATH . '/js/popup.js');
?>
<script>
    var personalFreeFreezingComponent = <?=CUtil::PhpToJSObject($component->getName())?>;
</script>
<div class="personal-freefreezing__content">
    <div class="popup__modal-title">
        <span><?=$arResult["TITLE"]?></span>
    </div>
    <?if (!empty($arResult["INFO"])):?>
        <div class="popup__modal-info">
            <div class="popup__modal-info-text">
                <?=$arResult["INFO"]?>
            </div>
        </div>
    <?endif;?>

    <?if ($arResult["FORM"]):?>
        <form id="personal-freefreezing__form">
            <div class="pff__count-select select2-black">
                <div class="pff-select-count-placeholder">
                    <span class="pff-form__placeholder">Выберите количество дней заморозки</span>
                </div>
                <select class="select2" name="count" autocomplete="off" required="required" id="pff-count-select">
                    <?foreach ($arResult["COUNTS"] as $val=>$placeholder):?>
                        <option value="<?=$val?>"><?=$placeholder?></option>
                    <?endforeach; ?>
                </select>
            </div>
            <div class="personal-freefreezing__submit">
                <input type="submit" class="button" value="Подтвердить" style="width: 100%">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </div>
        </form>
    <?endif;?>
</div>