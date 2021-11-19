<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$arField = ['name', 'email', 'phone'];
?>
<div class="content-center block-margin">
    <div class="form-standart form-standart_tpl-hor form-standart_black-bg">
        <div class="form-standart__plate">
            <h2 class="form-standart__title">Записаться на пробную тренировку</h2>

            <form class="training__aside-form_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?=POST_FORM_CAREER_ACTION_URI?>" method="POST" enctype="multipart/form-data">
                <?= getClientParams($arParams["WEB_FORM_ID"]) ?>

                <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="sub_id" value="<?= $arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE'] ?>">
                <input type="hidden" class="club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" value="<?= $arParams["NUMBER"] ?>">
                <input type="hidden" class="text_form" name="text_form" value="<?= $arParams["TEXT_FORM"] ?>">
                <input type="hidden" name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="0">

                <div class="form-standart__fields-list">
                    <? foreach ($arField as $itemField) { 
                        switch ($itemField) {
                            case 'phone':
                                $type = 'tel';
                                break;
    
                            case 'email':
                                $type = 'email';
                                break;
                            
                            default:
                                $type = 'text';
                                break;
                        }
                        ?>
                        <div class="form-standart__field">
                            <label class="form-standart__label"><?=$arResult["arQuestions"][$itemField]["TITLE"]?></label>
                            <div class="form-standart__item">
                                <div class="form-standart__inputs">
                                    <input class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?= $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <?=($arResult["arQuestions"][$itemField]["REQUIRED"] ? 'required="required"' : '')?> value="<?=$_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" />
                                </div>
                                <div class="form-standart__message">
                                    <div class="form-standart__none">Заполните поле</div>
                                    <div class="form-standart__error">Поле заполнено некорректно</div>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>

                <div class="form-standart__footer">
                    <div class="form-standart__agreements">
                        <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                            <label class="b-checkbox">
                                <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>" id="agr1" data-necessary="">
                                
                                <span class="b-checkbox__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></span>
                            </label>
                            <div class="form-standart__message">
                                <div class="form-standart__error">Необходимо ваше согласие
                                </div>
                            </div>
                        </div>
                        <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                            <label class="b-checkbox">
                                <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>" id="agr2" data-necessary="">
                                <span class="b-checkbox__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></span>
                            </label>
                            <div class="form-standart__message">
                                <div class="form-standart__error">Необходимо ваше согласие</div>
                            </div>
                        </div>

                        <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                            <label class="b-checkbox">
                                <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"] ?>_privacy[]" <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>" id="agr2" data-necessary="">
                                <span class="b-checkbox__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></span>
                            </label>
                            <div class="form-standart__message">
                                <div class="form-standart__error">Необходимо ваше согласие</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-standart__buttons">
                        <input class="form-standart__submit button-outline" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1" name="web_form_submit">
                    </div>
                </div>                


                <div class="popup popup--legal-information" >
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
                                        <div class="input-label__text">C условиями Оферты ознакомлен </div>
                                    </label>
                                </div>
                                <input class="popup__btn btn subscription__total-btn subscription__total-btn--reg subscription__total-btn--legal-training_v2" type="submit" value="Согласен" data-stage="1" name="web_form_submit" disabled>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <div class="form-standart__success-message">Ожидайте...</div>
        </div>
    </div>
</div>

