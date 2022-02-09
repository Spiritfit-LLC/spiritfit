<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arInfoProps = Utils::getInfo()['PROPERTIES'];

$arField = ['name', 'phone', 'email'];
?>
<div class="form-standart form-standart_tpl-ver form-standart_white-bg">
        <div class="form-standart__plate">
            <div class="form-standart__title h2">Отправить заявку</div>
                
            <form class="subscription__aside-form-trial_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?=POST_FORM_CORP_ACTION_URI?>" method="POST" enctype="multipart/form-data">
                <?= getClientParams($arParams["WEB_FORM_ID"]) ?>
                <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="sub_id" value="<?= $arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE'] ?>">
                <input type="hidden" name="two_month" value="0">
                <input type="hidden" name="old_price" value="">
                <input class="actual-price" type="hidden" name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="1">
                <input type="hidden" name="abonement_code" value="">
                <input type="hidden" name="trial" value="Y">
                
                <div class="form-standart__fields-list">
                    <div class="form-standart__field">
                        <div class="form-standart__item">
                            <div class="form-standart__inputs">
                                <select data-placeholder="Выберите клуб" data-necessary="" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
                                    <option></option>
                                    <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                                        <option value="<?= $arItem["NUMBER"] ?>"><?= $arItem["MESSAGE"] ?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <div class="form-standart__message">
                                <div class="form-standart__none">Сделайте выбор</div>
                            </div>
                        </div>
                    </div>

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

                    <div class="form-standart__field subscription__promo">
                        <input class="form-standart__input subscription__promo-input" type="text" placeholder="ПРОМОКОД" name="promo">
                        <a class="subscription__promo-btn_v2" href="#">Применить</a>
                    </div>
                </div>
                
                

                <div class="form-standart__footer">
                    <div class="form-standart__agreements">
                        <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                            <label class="b-checkbox">
                                <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>" id="agr1" data-necessary="">
                                
                                <span class="b-checkbox__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></span>
                            </label>
                            <div class="form-standart__message">
                                <div class="form-standart__error">Необходимо ваше согласие</div>
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


                        <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
                            <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                                <label class="b-checkbox">
                                    <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"] ?>_privacy[]" <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>" id="agr3" data-necessary="">
                                    <span class="b-checkbox__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></span>
                                </label>
                                <div class="form-standart__message">
                                    <div class="form-standart__error">Необходимо ваше согласие</div>
                                </div>
                            </div>
                        <?}?>
                    </div>

                    <div class="subscription__bottom">
                    
                        <div class="subscription__total" style="display: none;">
                            <div class="subscription__total-text">ИТОГО К ОПЛАТЕ</div>
                            <div class="subscription__total-value">
                                <div class="subscription__total-value-old"><span></span></div>
                                <span class="subscription__total-actual"></span>
                            </div>
                            
                            <div class="subscription__total-subtext"></div>
                        </div>
                    </div>

                    <div class="form-standart__buttons">

                        <div style="display: none;">
                            <input  type="submit" class="subscription__total-btn subscription__total-btn--reg btn btn--white" data-stage="1" name="web_form_submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
                        </div>

                        <span onclick="clickBtn(this)" class="form-standart__submit button-outline" data-stage="1"><?= $arResult["arForm"]["BUTTON"] ?></span>
                    </div>
                </div>
				
				<? if (!empty($arResult["ERROR"])): ?>
                    <div class="popup popup--call form-error-modal" style="display: block;">
                        <div class="popup__bg"></div>
                        <div class="popup__window">
                            <div class="popup__close">
                                <div></div>
                                <div></div>
                            </div>
                            <div class="popup__success"><?=$arResult["ERROR"]?></div>
                        </div>
                    </div>
                <? endif; ?>
				
                
                <div class="popup popup--legal-information popup-info">
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
                                <input class="popup__btn btn subscription__total-btn subscription__total-btn--reg subscription__total-btn--legal-information_v2" type="submit" value="Согласен" data-stage="1" name="web_form_submit" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<? if($_REQUEST['step'] == 3){ ?>
    <script>$('#modalForm .form-standart').hide();</script>
    
    <div class="popup__success"><?=$arParams['THANKS']?></div> 
<? } ?>

<script>
    $(".input--checkbox").styler();
    $(".input--num").on("input", function() {
        this.value = this.value.replace(/[^0-9]/gi, "");
    });
    $(".input--name").on("input", function() {
        this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
    });
</script>