<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
</script>

<div class="form-standart form-standart_tpl-two-col form-standart_white-bg form-standart__popup-message">
    <div class="form-standart__plate">
        <div class="form-standart__title h2">Заявка на вакансию</div>
        <form class="popup__form popup__resume-form" data-componentname="<?=$arResult["COMPONENT_NAME"]?>" onsubmit="submit_resume(this)">
            <?
            //Новый стандарт для utm меток
            printGaFormInputs();
            ?>
            <div class="form-body">
                <div class="form-column left-column">
                    <?foreach ($arResult["LEFT_FIELDS"] as $FIELD):?>
                        <?if ($FIELD["TYPE"]=="checkbox"):?>
                            <div class="form-request-new__field form-request-new__field_agreement form-request-new__field_checkbox">
                                <label class="b-checkbox">
                                    <input class="b-checkbox__input" type="<?=$FIELD['TYPE']?>" <?if ($FIELD["REQUIRED"]) echo "required";?> name="<?=$FIELD["NAME"].'[]'?>" <?=$FIELD['PARAMS']?> value="<?=$FIELD['VALUE']?>">
                                    <span class="b-checkbox__text"><?=$FIELD["PLACEHOLDER"]?></span>
                                </label>
                            </div>
                        <?else:?>
                            <div class="form-request-new__item">
                                <label class="form-request-new__label"  for="<?=$FIELD["NAME"]?>"><?=$FIELD["PLACEHOLDER"]?></label>
                                <div class="form-request-new_inputs">
                                    <input class="form-request-new__input"
                                           type="<?=$FIELD['TYPE']?>"
                                           name="<?=$FIELD["NAME"]?>"
                                           id="<?=$FIELD["NAME"]?>"
                                        <?if ($FIELD["REQUIRED"]) echo "required";?>
                                           value="<?=$FIELD['VALUE']?>"
                                        <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR'];?>
                                        <?=$FIELD['PARAMS']?>/>
                                </div>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
                <div class="form-column right-column">
                    <?foreach ($arResult["RIGHT_FIELDS"] as $FIELD):?>
                        <?if($FIELD["TYPE"]=="file"):?>
                            <div class="form-request-new__item file-item">
                                <input type="file" id="<?=$FIELD["NAME"]?>" name="<?=$FIELD["NAME"]?>" class="is-hide" onchange="add_file(this);" multiple="false">
                                <button type="button" class="text-btn" onclick="$('#<?=$FIELD["NAME"]?>').click()" id="<?=$FIELD["NAME"]?>_trigger"><?=$FIELD["PLACEHOLDER"]?></button>
                            </div>
                        <?else:?>
                            <div class="form-request-new__item">
                                <label class="form-request-new__label" for="<?=$FIELD["NAME"]?>"><?=$FIELD["PLACEHOLDER"]?></label>
                                <div class="form-request-new_inputs">
                                    <input class="form-request-new__input"
                                           type="<?=$FIELD['TYPE']?>"
                                           name="<?=$FIELD["NAME"]?>"
                                           id="<?=$FIELD["NAME"]?>"
                                        <?if ($FIELD["REQUIRED"]) echo "required";?>
                                           value="<?=$FIELD['VALUE']?>"
                                        <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR'];?>
                                        <?=$FIELD['PARAMS']?>/>
                                </div>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
            </div>
            <?if (!empty($arResult["TEXTAREA"])):?>
            <div class="form-request-new__item">
                <label class="form-request-new__label textarea-item" for="<?=$arResult["TEXTAREA"]["NAME"]?>"><?=$arResult["TEXTAREA"]["PLACEHOLDER"]?></label>
                <div class="form-request-new_inputs">
                    <textarea name="<?=$arResult["TEXTAREA"]["NAME"]?>" style="padding: 10px;height: 150px;"></textarea>
                </div>
            </div>
            <?endif;?>
            <div class="form-request-new__agreements">
                <?foreach ($arResult["CHECKBOXES"] as $FIELD):?>
                    <div class="form-request-new__field form-request-new__field_agreement form-request-new__field_checkbox">
                        <label class="b-checkbox">
                            <input class="b-checkbox__input" type="<?=$FIELD['TYPE']?>" <?if ($FIELD["REQUIRED"]) echo "required";?> name="<?=$FIELD["NAME"].'[]'?>" <?=$FIELD['PARAMS']?> value="<?=$FIELD['VALUE']?>">
                            <span class="b-checkbox__text"><?=$FIELD["PLACEHOLDER"]?></span>
                        </label>
                    </div>
                <?endforeach;?>
            </div>
            <div style="text-align: center; margin-top:50px">
                <input type="submit" class="button" value="Отправить">
            </div>
        </form>
        <div class="result-message">

        </div>
    </div>
</div>

