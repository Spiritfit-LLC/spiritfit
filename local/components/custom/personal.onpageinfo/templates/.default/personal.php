<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>


<div class="personal-profile-block">
    <div class="personal-profile__left-block">
        <div class="personal-profile__user">
            <div>
                <div class="personal-profile__user-photo">
                    <img src="<?=$arResult['LK_FIELDS']['HEAD']['PERSONAL_PHOTO']?>" height="100%" width="100%">
                    <form class="personal-profile__user-refresh-photo" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="ACTION" value="UPDATE_PERSONAL_PHOTO">
                        <input type="hidden" name="old-photo-id" value="<?=$arResult['LK_FIELDS']['OLD_PHOTO_ID']?>">
                        <input class="personal-profile__user-refresh-photo-file-input" type="file" name="new-photo-file" accept="image/*">
                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/refresh.svg');?>
                    </form>
                </div>
                <div class="personal-profile__user-result"></div>
            </div>
            <div class="personal-profile__user-head-items">
                <?foreach ($arResult['LK_FIELDS']['HEAD']['FIELDS'] as $FIELD):?>
                    <div class="personal-profile__user-head-item">
                        <?if (!empty($FIELD['SHOW_PLACEHOLDER'])):?>
                        <span class="user-head-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                        <?endif;?>
                        <span class="user-head-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                    </div>
                <?endforeach;?>
            </div>
        </div>
        <div style="position:relative;margin-top: 50px;">
            <div class="personal-profile__tabs">

                <?
                $i=0;
                foreach ($arResult['LK_FIELDS']['SECTIONS'] as $ID=>$SECTION):?>
                    <div class="personal-profile__tab-item <?if ($i==0) echo 'active';?>" data-id="<?=$ID?>">
                        <div class="tab-item__icon">
                            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$SECTION['ICON']);?>
                        </div>
                        <div class="tab-item__name">
                            <?=$SECTION['NAME']?>
                        </div>
                    </div>
                <?
                $i++;
                endforeach;
                ?>
                <div class="personal-profile__tab-item profile-exit-btn">
                    <div class="tab-item__icon">
                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/exit-btn.svg');?>
                    </div>
                    <div class="tab-item__name">
                        Выйти
                    </div>
                </div>
            </div>
            <div class="show-all-section-icon is-hide-desktop">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
            </div>
        </div>
        <div class="left-block-border -right"></div>

    </div>
    <div class="personal-profile__center-block">
        <?
        $i=0;
        foreach ($arResult['LK_FIELDS']['SECTIONS'] as $ID=>$SECTION):?>
            <div class="personal-section" style="display: none" data-id="<?=$ID?>">
                <div class="personal-section__title"><?=$SECTION['NAME']?></div>
                <form class="personal-section-form" autocomplete="off" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="SECTION_ID" value="<?=$ID?>">
                    <?if (!empty($SECTION['ACTION'])):?>
                    <input type="hidden" name="ACTION" value="<?=$SECTION['ACTION']?>">
                    <?endif;?>
                    <?foreach ($SECTION['FIELDS'] as $FIELD):?>
                    <div class="personal-section-form__item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                           <?if($FIELD['TYPE']=='table') echo 'table-item';?>
                                                           <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                           <?if (!$FIELD['CHANGEBLE']) echo 'readonly-item';?>">
                        <?if ($FIELD['TYPE']!='checkbox'):?>
                        <span class="personal-section-form__item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
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
                    <?if (!empty($SECTION['BTN_TEXT'])):?>
                        <input type="submit" class="personal-section-form__submit button-outline" value="<?=$SECTION['BTN_TEXT']?>">
                    <?endif;?>

                    <div class="form-submit-result-text"></div>
                </form>
            </div>
        <?
        $i++;
        endforeach;
        ?>
    </div>
</div>