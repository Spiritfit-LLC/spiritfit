<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

session_start();
unset($_SESSION['FIRST_OPEN']);
?>

<div class="popup popup--call popup--appearing" <? if ($arResult['SUBMIT'] == "Y"): ?> style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__heading"><?= $arResult["INFO"]["PROPERTIES"]["NAME_CALLBACK_POPUP"]["VALUE"] ?></div>
        <form class="popup__form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
        <?= bitrix_sessid_post(); ?>
        <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
        <input type="hidden" name="step" value="1">
        <input type="hidden" name="type" value="5">

        <div class="popup__desc"><?= $arResult["INFO"]["PROPERTIES"]["TEXT_CALLBACK_POPUP"]["VALUE"] ?></div>

        <div class="popup__form-row">
            <input 
            class="input input--text input--name" required
            type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" 
            name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" 
            <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?> 
            maxlength="20">

            <input 
            class="input input--tel" required
            type="tel" placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" 
            name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" 
            <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>>

            <select class="input input--select js-call-select" required name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required="required">
                <option disabled="disabled" selected="selected"><?= $arResult["arQuestions"]["club"]["TITLE"] ?></option>
                <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                    <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
            <div class="popup__privacy__form-row">
                <label class="input-label">
                    <input 
                        class="input input--checkbox" 
                        type="checkbox"
                        required="required"
                        name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"]?>_privacy[]" 
                        <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?>
                        value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>"
                    >
                    <div class="input-label__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></div>
                </label>
            </div>
        <?}?>

        <input class="popup__btn btn js-callback-submit" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
        </form>
    </div>
</div>

<script>dataLayerSend('UX', 'openPopupForm', '')</script>