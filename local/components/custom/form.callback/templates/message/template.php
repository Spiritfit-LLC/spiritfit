<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arField = ['name', 'email', 'phone'];
?>

<div class="popup popup--message" <? if ($_REQUEST["web_form_submit"]): ?>style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        
        <div class="form-standart form-standart_tpl-two-col form-standart_white-bg form-standart__popup-message">
            <div class="form-standart__plate">
                <div class="form-standart__title h2">Отправить сообщение</div>
        
                <form class="popup__form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
                    <?=getClientParams($arParams["WEB_FORM_ID"]) ?>
                    <?= bitrix_sessid_post(); ?>
                    <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                    <input type="hidden" name="step" value="100">
                    <?if ($arResult["CLIENT_TYPE"]):?>
                    <input type="hidden" name="typeSetClient" value="<?=$arResult["CLIENT_TYPE"]?>">
                    <?endif;?>


                    <div class="form-standart__fields-list">
                        <div class="form-standart__fields-col">

                            <div class="form-standart__field">
                                <div class="form-standart__item">
                                    <div class="form-standart__inputs">
                                        <select data-necessary="" name="form_<?= $arResult["arAnswers"]["theme"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["theme"]['0']["ID"] ?>" required="required">
                                            <option disabled="disabled" selected="selected"><?=$arResult["arQuestions"]["theme"]["TITLE"]?></option>
                                            <?foreach ($arResult["INFO"]['PROPERTIES']['MESSAGE_FORM_THEMES']['VALUE'] as $theme) {?>
                                                <option ><?=$theme?></option>
                                            <?}?>
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
                                            <input class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?= $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <?=($arResult["arQuestions"][$itemField]["REQUIRED"] ? 'required="required"' : '')?> value="<?=$_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" <?=$arResult["arAnswers"][$itemField]['0']["FIELD_PARAM"]?>/>
                                        </div>
                                        <div class="form-standart__message">
                                            <div class="form-standart__none">Заполните поле</div>
                                            <div class="form-standart__error">Поле заполнено некорректно</div>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                        </div>

                        <div class="form-standart__fields-col">
                            <div class="form-standart__field">
                                <div class="form-standart__item">
                                    <div class="form-standart__inputs">
                                        <textarea class="form-standart__textarea" name="form_<?= $arResult["arAnswers"]["message"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["message"]['0']["ID"] ?>" placeholder="<?=$arResult["arQuestions"]["message"]["TITLE"]?>"></textarea>
                                    </div>
                                    <div class="form-standart__message">
                                        <div class="form-standart__none">Заполните поле</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-standart__footer someclass">
                        <div class="form-standart__footer-wrapper">

                            <?if($arResult["arQuestions"]["personal"]['ACTIVE'] == "Y") {?>
                                <div class="form-standart__agreements">
                                    <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                                        <label class="b-checkbox">
                                            <input class="b-checkbox__input" type="checkbox" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"]?>_personal[]"  <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> id="agr1" data-necessary="" value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>" required="required"/>
                                            <span class="b-checkbox__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></span>
                                        </label>
                                        <div class="form-standart__message">
                                            <div class="form-standart__error">Необходимо ваше согласие</div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>

                            <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
                                <div class="form-standart__agreements">
                                    <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                                        <label class="b-checkbox">
                                            <input class="b-checkbox__input" type="checkbox" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"]?>_privacy[]"  <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> id="agr1" data-necessary="" value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>" required="required"/>
                                            <span class="b-checkbox__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></span>
                                        </label>
                                        <div class="form-standart__message">
                                            <div class="form-standart__error">Необходимо ваше согласие</div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>

                            <?if ($arResult["arQuestions"]["rules"]["ACTIVE"]  == "Y") {?>
                                <div class="form-standart__agreements">
                                    <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                                        <label class="b-checkbox">
                                            <input class="b-checkbox__input" type="checkbox" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"]?>_rules[]"  <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> id="agr2" data-necessary="" value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>" required="required"/>
                                            <span class="b-checkbox__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></span>
                                        </label>
                                        <div class="form-standart__message">
                                            <div class="form-standart__error">Необходимо ваше согласие</div>
                                        </div>
                                    </div>
                                </div>
                            <?}?>
                            
                            <div class="form-standart__buttons">
                                <input class="form-standart__submit button-outline js-callback-submit_v2" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.popup__form .js-callback-submit_v2[type="submit"]').click(function(){
            var form = $(this).parents('form');
            var valid = true;

            $(this).parents('form').find('input:required').each(function() {
                if ($(this).attr('type') == 'checkbox') {
                    if ($(this).prop('checked') == false) valid = false;
                } else {
                    if ($(this).val() == '') valid = false;
                }
            });

            if (valid) {
                if ($(form).find('input[name="typeSetClient"]').length > 0){
                    var setClientData= {
                        'phone':$(form).find('[type="tel"]').val(),
                        'email':$(form).find('[type="email"]').val(),
                        'setTypeClient':$(form).find('input[name="typeSetClient"]').val()
                    };
                    sendToUpMetrika(setClientData);
                    
                }
            }

        });
    });
</script>