<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<? if ($arResult["isFormNote"] != "Y"): ?>
    <div class="popup popup--call" <? if ($_REQUEST["web_form_submit"]): ?>style="display: block;"<? endif; ?>>
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__heading"><?= $arResult["arForm"]["NAME"] ?></div>
            <? if ($arResult["isFormErrors"] == "Y"): ?>
                <?=$arResult["FORM_ERRORS_TEXT"];?>
            <? endif; ?>
            <form class="popup__form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <?= bitrix_sessid_post(); ?>
		    <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <div class="popup__form-row">
                <? foreach ($arResult["QUESTIONS"] as $name => $arField): ?>
                    <? if ($name == 'club'): ?>
                        <select class="input input--select" name="form_<?= $arField["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arField["STRUCTURE"]['0']["ID"] ?>">
                            <option disabled="disabled" selected="selected"><?= $arField["CAPTION"] ?></option>
                            <? foreach ($arField['ITEMS'] as $arItem): ?>
                                <option value="<?= $arItem["NUMBER"] ?>"><?= $arItem["MESSAGE"] ?></option>
                            <? endforeach; ?>
                        </select>
                    <? endif; ?>
                    <? if ($name == 'phone'): ?>
                        <input class="input input--tel" type="tel" placeholder="<?= $arField["CAPTION"] ?>" name="form_<?= $arField["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arField["STRUCTURE"]['0']["ID"] ?>" <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>>
                    <? endif; ?>
                    <? if ($name == 'name'): ?>
                        <input class="input input--text" type="text" placeholder="<?= $arField["CAPTION"] ?>" name="form_<?= $arField["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arField["STRUCTURE"]['0']["ID"] ?>" <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?> pattern="[А-Яа-я]{1,20}">
                    <? endif; ?>
                <? endforeach; ?>
            </div>
            <input class="popup__btn btn" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" name="web_form_submit">
            </form>
        </div>
    </div>
<? else: ?>
    <div class="popup popup--call" style="display: block;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success">Ваша заявка принята</div>
        </div>
    </div>
<? endif; ?>