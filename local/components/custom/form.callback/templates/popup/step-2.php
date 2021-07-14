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
        <div class="popup__heading"><?= $arResult["INFO"]["PROPERTIES"]["NAME_CALLBACK"]["VALUE"] ?></div>
        <form class="popup__form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
        <?=getClientParams($arParams["WEB_FORM_ID"]) ?>
        <?= bitrix_sessid_post(); ?>
        <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
        <input type="hidden" name="step" value="2">

        <? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
            <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
        <? endforeach; ?>

        <div class="popup__form-row">
            <input 
            class="input input--text <? if (!empty($arResult["ERROR"])): ?>error<? endif; ?>" 
            type="text" placeholder="Код из sms" 
            name="code" 
            required="required"
            pattern="[0-9]{1,20}">
        </div>
        <input class="popup__btn btn js-callback-code-submit" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
        </form>
    </div>
</div>

<script>dataLayerSend('UX', 'sendFormCallback', '');</script>