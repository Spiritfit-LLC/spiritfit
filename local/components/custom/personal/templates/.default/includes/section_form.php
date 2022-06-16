<?php
if(!function_exists('GetPersonalSection')) {
    function GetPersonalSection($FIELD){?>
            <?if ($FIELD['TYPE']=='hidden'):?>
                <input type="hidden" name="<?=$FIELD['NAME']?>" value="<?=$FIELD['VALUE']?>" id="<?=$FIELD['HTML_ID']?>">
            <?return?>
            <?endif;?>
        <div class="personal-section-form__item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                               <?if($FIELD['TYPE']=='table') echo 'table-item';?>
                                                               <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                               <?if (!$FIELD['CHANGEBLE']) echo 'readonly-item';?>
                                                               <?if ($FIELD['TYPE']=='link') echo 'link-item';?>">

            <?if ($FIELD['TYPE']!='checkbox' && $FIELD['TYPE']!='link'):?>
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
                                id="<?=$FIELD['HTML_ID']?>"
                                <?if ($FIELD['REQUIRED']) echo 'required';?>
                                <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                                <?if ($FIELD['VALUE'][$i]['CHECKED']) echo 'checked';?>
                                data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                            >
                            <label for="<?=$FIELD['HTML_ID']?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                        </div>
                    <?endfor;?>
                </div>
            <?elseif($FIELD['TYPE']=='link'):?>
                <a href="<?=$FIELD['VALUE']?>"
                    <?if (!empty($FIELD['CLICKABBLE'])) echo 'data-clickable="true"';?>
                   data-clue="<?=$FIELD['CLUE']['TEXT']?>"><?=$FIELD['PLACEHOLDER']?></a>
            <?elseif($FIELD['TYPE']=='textarea'):?>
                <textarea class="personal-section-form__item-value textarea"
                          data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                        <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                    <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR']?>
                        name="<?=$FIELD['NAME']?>"
                          id="<?=$FIELD['HTML_ID']?>"><?=$FIELD['VALUE']?></textarea>
            <?else:?>
                <?if (!empty($FIELD['OLD_SUM'])):?>
                    <div class="old-sum"><s><?=$FIELD['OLD_SUM']?></s>
                <?endif;?>
                <input
                    class="personal-section-form__item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                            <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                    type="<?if ($FIELD['TYPE']=='date' || $FIELD['TYPE']=='table') echo 'text'; else echo $FIELD['TYPE'];?>"
                    name="<?=$FIELD['NAME']?>"
                    value="<?=$FIELD['VALUE']?>"
                    id="<?=$FIELD['HTML_ID']?>"
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
                <?if (!empty($FIELD['OLD_SUM'])):?>
                    </div>
                <?endif;?>
            <?endif;?>
        </div>
        <?
    }
}
?>
<?if (!empty($arParams['PARENT'])):?>
    <div class="personal-profile__tab-item child <?if ($arParams['SECTION']['ACTIVE']) echo 'active';?>"
         data-id="<?=$arParams['SECTION']['ID']?>"
         data-parent_id="<?=$arParams['PARENT']?>"
    >

        <?if (!empty($arParams['SECTION']['ICON'])):?>
            <div class="tab-item__icon">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$arParams['SECTION']['ICON']);?>
            </div>
        <?endif?>

        <div class="sub-section__header">
            <div class="tab-item__name">
                <?=$arParams['SECTION']['NAME']?>
            </div>
            <div class="sub-section__arrow-icon">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
            </div>
        </div>
    </div>
<?endif?>

<div class="personal-section <?if (!empty($arParams['PARENT'])) echo 'child'?>" style="display: none" data-id="<?=$arParams['SECTION_ID']?>">
    <?if (empty($arParams['PARENT'])):?>
        <div class="personal-section__title is-hide-mobile"><?=$arParams['SECTION']['NAME']?></div>
    <?endif?>

    <?if (!$arParams['IS_CORRECT']):?>
        <div class="profile-warnig-message">Пожалуйста, заполните все поля, чтобы активировать ваш профиль</div>
    <?endif;?>
    <?if (empty($arParams['PARENT']) || (!empty($arParams['PARENT']) && $arParams['SECTION']['FORM_TYPE']=='independent')):?>
    <form class="personal-section-form" autocomplete="off" method="post" enctype="multipart/form-data">
        <?if (!empty($arParams['SECTION']['ACTION'])):?>
            <input type="hidden" name="ACTION" value="<?=$arParams['SECTION']['ACTION']?>">
        <?endif;?>
        <?endif;?>
        <!--    --><?//if (!empty($arParams['PARENT']) && $arParams['SECTION']['FORM_TYPE']=='in_parent'):?>
        <!--        <input type="hidden" name="sub_section" value="true">-->
        <!--    --><?//endif;?>
        <input type="hidden" name="SECTION_ID[]" value="<?=$arParams['SECTION_ID']?>">
        <?foreach ($arParams['SECTION']['FIELDS'] as $FIELD_ITEM):?>
            <?if (empty($FIELD_ITEM['GROUP'])):
                GetPersonalSection($FIELD_ITEM);
            else:?>
                <div class="field-group">
                    <?foreach ($FIELD_ITEM["GROUP"] as $FIELD):
                        GetPersonalSection($FIELD);
                    endforeach;?>
                </div>
            <?endif;?>
        <?endforeach;?>
        <?
        global $APPLICATION;
        foreach ($arParams['SECTION']['SECTIONS'] as $SECTION) {
            if ($SECTION['FORM_TYPE']=='in_parent'){
                $APPLICATION->IncludeFile(
                    str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) . '/section_form.php',
                    array(
                        'SECTION_ID' => $SECTION['ID'],
                        'SECTION' => $SECTION,
                        'IS_CORRECT' => $arParams['IS_CORRECT'],
                        'PARENT'=>$arParams['SECTION_ID'],
                    ),
                    array(
                        'SHOW_BORDER' => true
                    )
                );
            }
        } ?>
        <?if (empty($arParams['PARENT']) || (!empty($arParams['PARENT']) && $arParams['SECTION']['FORM_TYPE']=='independent')):?>
        <?if (!empty($arParams['SECTION']['BTN_TEXT'])):?>
            <input type="submit" class="personal-section-form__submit button-outline" value="<?=$arParams['SECTION']['BTN_TEXT']?>">
            <div class="escapingBallG-animation">
                <div id="escapingBall_1" class="escapingBallG"></div>
            </div>
        <?endif;?>
        <div class="form-submit-result-text"></div>
    </form>
<?endif;?>

    <?
    foreach ($arParams['SECTION']['SECTIONS'] as $SECTION) {
        if ($SECTION['FORM_TYPE']=='independent'){
            $APPLICATION->IncludeFile(
                str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__) . '/section_form.php',
                array(
                    'SECTION_ID' => $SECTION['ID'],
                    'SECTION' => $SECTION,
                    'IS_CORRECT' => $arParams['IS_CORRECT'],
                    'PARENT'=>$arParams['SECTION_ID'],
                ),
                array(
                    'SHOW_BORDER' => true
                )
            );
        }
    } ?>
</div>