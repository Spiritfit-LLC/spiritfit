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
    var personalPromisePaymentComponent = <?=CUtil::PhpToJSObject($component->getName())?>;
</script>
<div class="personal-promisepayment__content">
    <div class="popup__modal-title">
        <span><?=$arResult["TITLE"]?></span>
    </div>
    <?if (!empty($arResult["INFO"])):?>
        <div class="popup__modal-info">
            <div class="popup__modal-info-text">
                <?=htmlspecialcharsback($arResult["INFO"])?>
            </div>
        </div>
    <?endif;?>

    <?if (empty($arResult["DATE"]) && ($arResult["TYPE"]=="appeal" && $arResult["FORM"])):?>
        <form id="personal-promisepayment__form">
        <input type="hidden" name="v" value="3">
        <input type="hidden" name="action" value="<?=$arResult["TYPE"]?>">

        <div class="personal-promisepayment__submit">
            <input type="submit" class="button" value="<?=$arResult["BTN_NAME"]?>" style="width: 100%">
            <div class="escapingBallG-animation">
                <div id="escapingBall_1" class="escapingBallG"></div>
            </div>
        </div>
    </form>
    <?endif;?>
</div>
