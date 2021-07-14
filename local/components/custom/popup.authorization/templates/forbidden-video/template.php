<?
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--subscribtion-video" <? if ($arResult['SUBMIT'] == "Y"): ?>style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__heading"><?= $arResult["INFO"]["PROPERTIES"]["NAME_AUTHORIZATION_FOR_VIDEO"]["VALUE"] ?></div>
        <? 
        $message = $arResult['INFO']['PROPERTIES']['POPUP_AUTHORIZATION_TEXT']['~VALUE']['TEXT'];

        if ($arResult["ERROR"]) {
            $message = $arResult["ERROR"];
        }            
        ?>
        <p class="subscribtion-video-error"><?=$message?></p>
        <form class="popup__form" name="authorization-video-page" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="step" value="1">

            <div class="popup__form-row popup__form-row--center-lg">
                <input 
                    class="input input--tel" required
                    type="tel" placeholder="<?= $arResult["INFO"]["PROPERTIES"]["PHONE_TEXT_AUTHORIZATION_FOR_VIDEO"]["VALUE"] ?>" 
                    name="form_phone_number"
                    inputmode="numeric"
                >
            </div>
            <input class="popup__btn btn js-forbidden-video-submit" type="submit" value="<?= $arResult["INFO"]["PROPERTIES"]["BUTTON_TEXT_AUTHORIZATION_FOR_VIDEO"]["VALUE"] ?>">
        </form>
    </div>
</div>