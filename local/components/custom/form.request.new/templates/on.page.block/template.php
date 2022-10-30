<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
</script>

<div class="content-center block-margin">
    <div class="form-standart form-standart_tpl-hor form-standart_black-bg">
        <div class="form-standart__plate">
            <?if (!empty($arResult["FORM_TITLE"])):?>
                <div class="form-standart__title h2"><?=$arResult["FORM_TITLE"]?></div>
            <?endif;?>
            <form class="form-request-new" data-componentname="<?=$arResult["COMPONENT_NAME"]?>" data-signed="<?=$arResult["SIGNED"]?>" data-action="reg">
                <div class="message-modal">
                    <div class="message-body">
                    </div>
                </div>
                <?if (!empty($arResult["CLUB_ID"])):?>
                <input type="hidden" name="CLUB_ID" value="<?=$arResult["CLUB_ID"]?>">
                <?endif;?>
                <?
                //Новый стандарт для utm меток
                printGaFormInputs();
                ?>



                <div class="form-request-new__fields-list">
                    <?foreach($arResult["FORM_FIELDS"]["FIELDS"] as $FIELD):?>
                        <?if ($FIELD["TYPE"]=="checkbox"){
                                $CHECKBOXESFIELD[]=$FIELD;
                                continue;
                        }?>
                        <div class="form-request-new__field <?if (!empty($FIELD["VALUE"])) echo "is-not-empty"?>">
                            <?if ($FIELD["TYPE"]=="SELECT"):?>
                                <label class="form-request-new__label select-item"><?=$FIELD["PLACEHOLDER"]?></label>
                                <select name="<?=$FIELD["NAME"]?>" <? if ($FIELD["REQUIRED"]) echo "required";?>>
                                    <option disabled><?=$FIELD["PLACEHOLDER"]?></option>
                                    <? foreach ($FIELD['ITEMS'] as $ITEM):?>
                                        <option value="<?=$ITEM["VALUE"]?>"
                                            <?if ($ITEM['SELECTED']) echo 'selected';?>><?=$ITEM["STRING"]?></option>
                                    <? endforeach; ?>
                                </select>
                            <?else:?>
                                <label class="form-request-new__label"><?=$FIELD["PLACEHOLDER"]?></label>
                                <div class="form-request-new__item">
                                    <div class="form-request-new_inputs">
                                        <input class="form-request-new__input"
                                               type="<?=$FIELD['TYPE']?>"
                                               name="<?=$FIELD["NAME"]?>"
                                            <?if ($FIELD["REQUIRED"]) echo "required";?>
                                               value="<?=$FIELD['VALUE']?>"
                                            <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR'];?>
                                            <?=$FIELD['PARAMS']?>/>
                                    </div>
                                    <div class="form-request-new__message">
                                        <div class="form-request-new__none">Заполните поле</div>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>
                    <?endforeach;?>
                </div>
                <div class="form-request-new__code" style="display: none">
                    <div class="subscription__sent">
                        <div class="subscription__sent-text">Код отправлен на номер</div>
                        <div class="subscription__sent-tel"></div>
                    </div>
                    <div class="form-request-new__field code-field">
                        <label class="form-request-new__label">Код из СМС</label>
                        <div class="form-request-new__item">
                            <div class="form-request-new_inputs">
                                <input class="form-request-new__input code-input"
                                       type="text"
                                       name="request-code"/>
                            </div>
                            <div class="form-request-new__message">
                                <div class="form-request-new__none">Заполните поле</div>
                            </div>
                        </div>
                    </div>
                    <a class="subscription__code" href="#resend">Получить код повторно</a>
                </div>

                <div class="form-request-new__footer">
                    <div class="form-request-new__agreements">
                        <?foreach($CHECKBOXESFIELD as $FIELD):?>
                            <div class="form-request-new__field form-request-new__field_agreement form-request-new__field_checkbox">
                                <label class="b-checkbox">
                                    <input class="b-checkbox__input" type="<?=$FIELD['TYPE']?>" <?if ($FIELD["REQUIRED"]) echo "required";?> name="<?=$FIELD["NAME"].'[]'?>" <?=$FIELD['PARAMS']?> value="<?=$FIELD['VALUE']?>">
                                    <span class="b-checkbox__text"><?=$FIELD["PLACEHOLDER"]?></span>
                                </label>
                                <div class="form-request-new__message">
                                    <div class="form-request-new__error">Необходимо ваше согласие</div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>

                    <div class="form-request-new__buttons">
                        <input class="form-request-new__submit button-outline" type="submit" value="<?=$arResult['FORM']["arForm"]["BUTTON"]?>">
                        <div class="escapingBallG-animation orange">
                            <div id="escapingBall_1" class="escapingBallG"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
