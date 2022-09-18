<div class="personal-section" style="display: none" data-id="<?=$arParams['SECTION_ID']?>">
    <div class="personal-section__title is-hide-mobile"><?=$arParams['SECTION']['NAME']?></div>
    <?if (!$arParams['IS_CORRECT']):?>
        <div class="profile-warnig-message">Пожалуйста, заполните все поля, чтобы активировать ваш профиль</div>
    <?endif;?>
    <form class="personal-section-form" autocomplete="off" action="<?=$arParams['ajax']?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="SECTION_ID" value="<?=$arParams['SECTION_ID']?>">
        <?if (!empty($arParams['SECTION']['ACTION'])):?>
            <input type="hidden" name="ACTION" value="<?=$arParams['SECTION']['ACTION']?>">
        <?endif;?>
        <?foreach ($arParams['SECTION']['FIELDS'] as $FIELD):?>
            <div class="personal-section-form__item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                           <?if($FIELD['TYPE']=='table') echo 'table-item';?>
                                                           <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                           <?if (!$FIELD['CHANGEBLE']) echo 'readonly-item';?>">
                <?if ($FIELD['TYPE']!='checkbox'):?>
                    <span class="personal-section-form__item-placeholder"><?=$FIELD['PLACEHOLDER']?>
                        <?if (!empty($FIELD['CLUE'])):?>
                            <div class="clue-icon">
                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/info-icon.svg');?>
                                <span class="clue-text" style="display: none"><?echo $FIELD['CLUE']['TEXT']?></span>
                            </div>
                        <?endif;?>
                    </span>

                <?endif;?>
                <?if ($FIELD['TYPE']=='radio'):?>
                    <div style="margin-top: 5px;">
                        <?for ($i=0; $i<count($FIELD['VALUE']); $i++):?>
                            <div class="input-radio-item-block">
                                <input
                                    class="personal-section-form__item-value input-radio-btn"
                                    type="<?=$FIELD['TYPE']?>"
                                    name="<?=$FIELD['NAME']?>"
                                    value="<?=$FIELD['VALUE'][$i]['RADIO_VAL']?>"
                                    id="<?=$FIELD['NAME'].'_'.$i?>"
                                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                                    <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                                    <?if ($FIELD['VALUE'][$i]['CHECKED']) echo 'checked';?>
                                    data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                                >
                                <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                            </div>
                        <?endfor;?>
                    </div>
                <?else:?>
                    <input
                        class="personal-section-form__item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                        <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                        type="<?if ($FIELD['TYPE']=='date' || $FIELD['TYPE']=='table') echo 'text'; else echo $FIELD['TYPE'];?>"
                        name="<?=$FIELD['NAME']?>"
                        value="<?=$FIELD['VALUE']?>"
                        id="<?=$FIELD['NAME']?>"
                        data-required_id="<?=$FIELD['REQUIRED_ID']?>"
                        <?if ($FIELD['REQUIRED']) echo 'required';?>
                        <?if ($FIELD['REQUIRED_FROM']){?> data-required_from="<?=$FIELD['REQUIRED_FROM']?>"<?}?>
                        <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                        <?if ($FIELD['TYPE']=='date'):?> data-toggle="datepicker" <?endif;?>
                        <?if ($FIELD['TYPE']=='checkbox' && (int)$FIELD['VALUE']==1) echo 'checked'?>
                        data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                        <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR']?>
                    >
                    <?if ($FIELD['TYPE']=='password'):?>
                        <div class="show-password-icon">
                            <div class="pass-view active">
                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/pass-view.svg');?>
                            </div>
                            <div class="pass-hide">
                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/pass-hide.svg');?>
                            </div>
                        </div>
                    <?endif;?>
                    <?if ($FIELD['TYPE']=='checkbox'):?>
                        <label for="<?=$FIELD['NAME']?>"><?=$FIELD['PLACEHOLDER']?></label>
                    <?endif;?>
                <?endif;?>
            </div>
        <?endforeach;?>

        <!--ВРМЕНЕННОЕ РЕШЕНИЕ-->
        <?if ($arParams['SECTION_ID']==24):?>
            <a class="podarok-btn" style="margin: 20px 0 0 0;display: block;font-weight: 600; cursor: pointer; width: 50%">🎁 Фитнес другу</a>
        <?endif;?>
        <!--ВРМЕНЕННОЕ РЕШЕНИЕ-->

        <?if (!empty($arParams['SECTION']['BTN_TEXT'])):?>
            <input type="submit" class="personal-section-form__submit button-outline" value="<?=$arParams['SECTION']['BTN_TEXT']?>">
        <?endif;?>

        <!--ВРМЕНЕННОЕ РЕШЕНИЕ-->
        <?if ($arParams['SECTION_ID']==24):?>
            <input type="button" class="spisat-btn button-outline" value="списать" style="width: 100%; margin:20px 0px">

        <?endif;?>
        <!--ВРМЕНЕННОЕ РЕШЕНИЕ-->

        <div class="form-submit-result-text"></div>
    </form>
</div>