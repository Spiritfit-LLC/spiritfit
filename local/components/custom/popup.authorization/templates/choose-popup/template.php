<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--choose-video" <? if ($arResult['SUBMIT'] == "Y"): ?>style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__heading"><?=$arResult['INFO']['PROPERTIES']['CHOOSE_POPUP_NAME']['~VALUE']?></div>
        <? if ($arResult['INFO']['PROPERTIES']['CHOOSE_POPUP_TEXT']['~VALUE']['TEXT']) {?>
            <p class="video-popup-message"><?=$arResult['INFO']['PROPERTIES']['CHOOSE_POPUP_TEXT']['~VALUE']['TEXT']?></p>
        <? } ?>
        <div class="popup__btns-holder">
            <div class="popup__btns-holder_btn btn btn--orange js-popup-forbidden-video"><?=$arResult['INFO']['PROPERTIES']['CHOOSE_FORM_LEFT_BUTTON']['~VALUE']?></div>
            <a class="popup__btns-holder_btn btn btn--orange" href="<?=$arResult['INFO']['PROPERTIES']['CHOOSE_FORM_RIGHT_LINK']['~VALUE']?>" target="_blank"><?=$arResult['INFO']['PROPERTIES']['CHOOSE_FORM_RIGHT_BUTTON']['~VALUE']?></a>
        </div>
    </div>
</div>