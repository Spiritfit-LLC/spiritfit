<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--call" <? if ($arResult['SUBMIT'] == "Y"): ?> style="display: block;" <? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__success"><?= $arResult["INFO"]["PROPERTIES"]["SUCCESS_MESSAGE_POPUP"]["VALUE"] ?></div>
    </div>
</div>