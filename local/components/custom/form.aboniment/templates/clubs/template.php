<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
$arInfoProps = Utils::getInfo()['PROPERTIES'];

?>

<div class="club__subheading">Пробная тренировка</div>
<div class="training__aside">
    <div class="training__aside-stage" data-stage="1">
        <h2 class="training__aside-title">
            <?if($arParams["TEXT_FORM"]){?><?= $arParams["TEXT_FORM"] ?>
            <?}else{?>Испытайте возможности клуба и&nbsp;получите скидку до &minus;40%
            <?}?>
        </h2>
        <form class="training__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
            <?= getClientParams($arParams["WEB_FORM_ID"]) ?>
            <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="sub_id" value="<?= $arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE'] ?>">
            <?//клуб?>
            <input type="hidden" class="club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" value="<?= $arParams["NUMBER"] ?>">
            <input type="hidden" class="text_form" name="text_form" value="<?= $arParams["TEXT_FORM"] ?>">
            <!-- Список клубов -->
            <div class="subscription__aside-form-row subscription__aside-form-row--club">
                <input class="input input--light input--long input--text input--club" type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"
                <? endif; ?>>
            </div>

            <div class="subscription__aside-form-row subscription__aside-form-row--club">
                <input class="input input--light input--long input--text input--club" type="text" placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["email"]["REQUIRED"]): ?>required="required"
                <? endif; ?>>
            </div>

            <div class="subscription__aside-form-row subscription__aside-form-row--club">
                <input class="input input--light input--long input--tel input--club" type="tel" placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"
                <? endif; ?>
                >
            </div>
            <div class="subscription__aside-form-row subscription__aside-form-row--club subscription__aside-form-row--margin">
                <label class="input-label input-label--club">
                    <input class="input input--checkbox input--checkbox-club" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>">
                    <div class="input-label__text input-label__text--club"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                </label>
            </div>

            <div class="subscription__aside-form-row subscription__aside-form-row--club subscription__aside-form-row--margin">
                <label class="input-label input-label--club">
                    <input class="input input--checkbox input--checkbox-club" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>">
                    <div class="input-label__text input-label__text--club"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                </label>
            </div>
            <input type="hidden" name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="0">
            <input class="subscription__total-btn--club subscription__total-btn--reg btn btn--white" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1" name="web_form_submit">
            <div class="popup popup--legal-information">
                <div class="popup__bg"></div>
                <div class="popup__window">
                    <div class="popup__close">
                        <div></div>
                        <div></div>
                    </div>
                    <div class="popup__wrapper">
                        <div class="popup__heading">Юридическая информация</div>
                        <div class="popup__legal-information-wrapper">
                            <div class="popup__legal-information">
                                <p class="popup__legal-information__subtitle"></p>
                            </div>
                        </div>
                        <div class="popup__bottom">
                            <div class="popup__privacy-policy">
                                <label class="input-label">
                                    <input class="input input--checkbox" type="checkbox" name="form_checkbox_legal-information">
                                    <div class="input-label__text">C условиями
                                        Оферты ознакомлен
                                    </div>
                                </label>
                            </div>
                            <input class="popup__btn btn subscription__total-btn subscription__total-btn--reg subscription__total-btn--legal-training" type="submit" value="Согласен" data-stage="1" name="web_form_submit" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>