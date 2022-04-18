<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<? if ($_REQUEST["step"] == 1 && $arResult["isFormErrors"] != "Y"): ?>
    <div class="subscription__aside-stage" data-stage="2">
        <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <input type="hidden" name="step" value="2">

            <? foreach ($arResult["QUESTIONS"] as $code => $arField): 
                if ($arField["STRUCTURE"]['0']["FIELD_TYPE"] != "checkbox") {
                    $name = "form_" . $arField["STRUCTURE"]['0']["FIELD_TYPE"] . "_" . $arField["STRUCTURE"]['0']["ID"]; 
                } else {
                    $name = "form_" . $arField["STRUCTURE"]['0']["FIELD_TYPE"] . "_" . $code; 
                } ?>
                <input type="hidden" name="<?= $name ?>" value="<?= $_REQUEST[$name] ?>">
            <? endforeach; ?>
            
            <? $fieldPhone = "form_" . $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["FIELD_TYPE"] . "_" . $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["ID"]?>
            <div class="subscription__sent">
                <div class="subscription__sent-text">Код отправлен на номер</div>
                <div class="subscription__sent-tel"><?= $_REQUEST[$fieldPhone] ?></div>
            </div>
            
            <!-- Код для подтверждения -->
            <div class="subscription__aside-form-row subscription__aside-form-row--code">
                <? for ($i = 0; $i < 4; $i++): ?>
                    <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[<?= $i ?>]" placeholder="0" maxlength="1" min="0" max="9" pattern="[0-9]" required="required">
                <? endfor; ?>
            </div>

            <div class="subscription__promo">
                <input class="subscription__promo-input input input--light input--short input--text" type="text" placeholder="Подарочный промокод" name="promo">
                <a class="subscription__promo-btn" href="#">Применить</a>
            </div>

            <div class="subscription__bottom">
                <div class="subscription__total">
                    <div class="subscription__total-text">Итого к оплате</div>
                    <div class="subscription__total-value"> 
                        <div class="subscription__total-value-old">
                            <? if (false): ?>
                                <span><?= $arParams["PRICE"] ?> &#x20bd;</span></div>3000 &#x20bd;
                            <? else: ?>
                                </div><?= $arParams["PRICE"] ?> &#x20bd;
                            <? endif; ?>
                        </div>
                    <div class="subscription__total-subtext">Предложение действительно до 30.10.2018</div>
                </div>
                <div class="subscription__total-btn subscription__total-btn--form btn btn--white" data-stage="2">Купить</div>
            </div>
        </form>
    <div>
<? else: ?>
    <div class="subscription__aside-stage" data-stage="1">
        <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <? if ($arResult["isFormErrors"] == "Y"): ?>
                <?=$arResult["FORM_ERRORS_TEXT"];?>
            <? endif; ?>
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <input type="hidden" name="step" value="1">

            <!-- Список клубов -->
            <div class="subscription__aside-form-row">
                <select class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["QUESTIONS"]["club"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["club"]["STRUCTURE"]['0']["ID"] ?>">
                    <? foreach ($arResult["QUESTIONS"]["club"]['ITEMS'] as $key => $arItem): ?>
                        <option value="<?= $arItem["MESSAGE"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                    <? endforeach; ?>
                </select>
            </div>

            <div class="subscription__aside-form-row">
                <input
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["name"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["name"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["name"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
                <input
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["surname"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["surname"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["surname"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
            </div>
            
            <div class="subscription__aside-form-row">
                <input
                    autocomplete="off"
                    class="input input--light input--short input--tel" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["phone"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
                <input
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["email"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["email"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["email"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
            </div>

            <div class="subscription__aside-form-row">
                <label class="input-label">
                    <input 
                        class="input input--checkbox" 
                        type="checkbox" 
                        name="form_<?= $arResult["QUESTIONS"]["personal"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_personal[]" 
                        <?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_PARAM"] ?>
                        value="<?= $arResult["QUESTIONS"]["personal"]["STRUCTURE"]['0']["ID"] ?>"
                    >
                    <div class="input-label__text"><?= $arResult["QUESTIONS"]["personal"]["CAPTION"] ?></div>
                </label>
            </div>

            <div class="subscription__aside-form-row">
                <label class="input-label">
                    <input 
                        class="input input--checkbox" 
                        type="checkbox" 
                        name="form_<?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_rules[]" 
                        <?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_PARAM"] ?>
                        value="<?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["ID"] ?>"
                    >
                    <div class="input-label__text"><?= $arResult["QUESTIONS"]["rules"]["CAPTION"] ?></div>
                </label>
            </div>

            <? if ($arParams["PRICE"]): ?>
                <div class="subscription__bottom">
                    <div class="subscription__total">
                        <div class="subscription__total-text"><?= $arResult["QUESTIONS"]["price"]["CAPTION"] ?></div>
                        <div class="subscription__total-value"><?= $arParams["PRICE"] ?> &#x20bd;</div>
                        <input
                            type="hidden" 
                            name="form_<?= $arResult["QUESTIONS"]["price"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["price"]["STRUCTURE"]['0']["ID"] ?>"
                            value="<?= $arParams["PRICE"] ?>"
                        >
                    </div>
                    <input class="subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1" name="web_form_submit">
                </div>
            <? endif; ?>
        </form>
    </div>
<? endif; ?>

<? if ($_REQUEST["step"] == 2): ?>
    <div class="subscription__aside-stage" data-stage="3">
        <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <? if ($arResult["isFormErrors"] == "Y"): ?>
                <?=$arResult["FORM_ERRORS_TEXT"];?>
            <? endif; ?>
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <input type="hidden" name="step" value="1">

            <!-- Список клубов -->
            <div class="subscription__aside-form-row">
                <select class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["QUESTIONS"]["club"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["club"]["STRUCTURE"]['0']["ID"] ?>" disabled="disabled">
                    <? foreach ($arResult["QUESTIONS"]["club"]['ITEMS'] as $key => $arItem): ?>
                        <option value="<?= $arItem["MESSAGE"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                    <? endforeach; ?>
                </select>
            </div>

            <div class="subscription__aside-form-row">
                <input
                    disabled="disabled"
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["name"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["name"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["name"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
                <input
                    disabled="disabled"
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["surname"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["surname"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["surname"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
            </div>
            
            <div class="subscription__aside-form-row">
                <input
                    disabled="disabled"
                    autocomplete="off"
                    class="input input--light input--short input--tel" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["phone"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["phone"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
                <input
                    autocomplete="off"
                    class="input input--light input--short input--text" 
                    type="text" 
                    placeholder="<?= $arResult["QUESTIONS"]["email"]["CAPTION"] ?>" 
                    name="form_<?= $arResult["QUESTIONS"]["email"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["email"]["STRUCTURE"]['0']["ID"] ?>" 
                    <? if ($arField["REQUIRED"]): ?>required="required"<? endif; ?>
                >
            </div>

            <div class="subscription__aside-form-row">
                <label class="input-label">
                    <input
                        disabled="disabled"
                        class="input input--checkbox" 
                        type="checkbox" 
                        name="form_<?= $arResult["QUESTIONS"]["personal"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_personal[]" 
                        <?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_PARAM"] ?>
                        value="<?= $arResult["QUESTIONS"]["personal"]["STRUCTURE"]['0']["ID"] ?>"
                    >
                    <div class="input-label__text"><?= $arResult["QUESTIONS"]["personal"]["CAPTION"] ?></div>
                </label>
            </div>

            <div class="subscription__aside-form-row">
                <label class="input-label">
                    <input
                        disabled="disabled"
                        class="input input--checkbox" 
                        type="checkbox" 
                        name="form_<?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_rules[]" 
                        <?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["FIELD_PARAM"] ?>
                        value="<?= $arResult["QUESTIONS"]["rules"]["STRUCTURE"]['0']["ID"] ?>"
                    >
                    <div class="input-label__text"><?= $arResult["QUESTIONS"]["rules"]["CAPTION"] ?></div>
                </label>
            </div>

            <? if ($arParams["PRICE"]): ?>
                <div class="subscription__bottom">
                    <div class="subscription__total">
                        <div class="subscription__total-text"><?= $arResult["QUESTIONS"]["price"]["CAPTION"] ?></div>
                        <div class="subscription__total-value"><?= $arParams["PRICE"] ?> &#x20bd;</div>
                        <input
                            type="hidden" 
                            name="form_<?= $arResult["QUESTIONS"]["price"]["STRUCTURE"]['0']["FIELD_TYPE"]?>_<?= $arResult["QUESTIONS"]["price"]["STRUCTURE"]['0']["ID"] ?>"
                            value="<?= $arParams["PRICE"] ?>"
                        >
                    </div>
                    <input class="subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1">
                </div>
            <? endif; ?>
        </form>
    </div>
<? endif; ?>