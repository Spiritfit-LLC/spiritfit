<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="personal-profile-block auth-page">
    <div class="personal-profile__left-block">
        <div style="position:relative;">
            <div class="personal-profile__tabs">
                <?
                foreach ($arResult['FORM_FIELDS'] as $ID=>$FORM):?>
                    <div class="personal-profile__tab-item <?if ($FORM['ACTIVE']==true) echo 'active'?>" data-id="<?=$ID?>">
                        <div class="tab-item__name"><?=$FORM['NAME']?></div>
                    </div>
                <?
                endforeach;
                ?>
            </div>
            <div class="show-all-section-icon is-hide-desktop">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
            </div>
        </div>
        <div class="left-block-border -right"></div>
    </div>
    <div class="personal-profile__center-block">
        <?
        foreach ($arResult['FORM_FIELDS'] as $ID=>$SECTION):?>
            <div class="personal-section" style="display: none" data-id="<?=$ID?>">
                <div class="personal-section__title"><?=$SECTION['NAME']?></div>
                <form class="personal-section-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                    <input type="hidden" name="WEB_FORM_ID" value="<?=$SECTION['WEB_FORM_ID']?>">
                    <input type="hidden" name="FORM_STEP" value="1">
                    <?if (!empty($SECTION['ACTION'])):?>
                        <input type="hidden" name="ACTION" value="<?=$SECTION['ACTION']?>">
                    <?endif;?>
                    <?foreach ($SECTION['FIELDS'] as $FIELD):?>
                        <?if ($FIELD['TYPE']=='hidden'):?>
                            <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                            <?
                            continue;
                        endif;
                        ?>
                        <div class="personal-section-form__item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                           <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                           <?if($FIELD['TYPE']=='SELECT') echo 'select-item';?>
                                                        <?if ($FIELD['TYPE']=='password') echo 'auth-password';?>"
                            <?if ($FIELD['TYPE']=='password'&&$arResult['AUTH_FORM_CODE']==$ID):?> style="display: none"<?endif;?>>
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
                                                value="<?=$FIELD['VALUE'][$i]?>"
                                                id="<?=$FIELD['NAME'].'_'.$i?>"
                                                <?if ($FIELD['REQUIRED']) echo 'required';?>
                                            >
                                            <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                                        </div>
                                    <?endfor;?>
                                </div>
                            <?elseif ($FIELD['TYPE']=='SELECT'):?>
                                <select class="input input--light input--select" name="<?=$FIELD['NAME']?>" autocomplete="off" <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?> >
                                    <? foreach ($FIELD['ITEMS'] as $CLUB):?>
                                        <option value="<?=$CLUB["VALUE"]?>"><?=$CLUB["STRING"]?></option>
                                    <? endforeach; ?>
                                </select>
                            <?else:?>
                                <input
                                    class="personal-section-form__item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                        <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                                    type="<?if ($FIELD['TYPE']=='date') echo 'text'; else echo $FIELD['TYPE'];?>"
                                    name="<?=$FIELD['NAME']?>"
                                    value="<?=$FIELD['VALUE']?>"
                                    id="<?=$FIELD['NAME']?>"
                                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                                    <?if ($FIELD['TYPE']=='date'):?> data-toggle="datepicker" <?endif;?>
                                    <?if ($FIELD['TYPE']=='checkbox' && (int)$FIELD['VALUE']==1) echo 'checked'?>
                                    <?if (!empty($FIELD['VALIDATOR'])){ echo $FIELD['VALIDATOR'];}?>
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
                        <div class="escapingBallG-animation">
                            <div id="escapingBall_1" class="escapingBallG"></div>
                        </div>
                    <?endif;?>

                    <div class="form-submit-result-text"></div>
                </form>
            </div>
        <?
        endforeach;
        ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.personal-profile__tab-item:not(.child)').click(function(){
            var btn_value=$(this).find('.tab-item__name').text();
            dataLayerSend('UX', 'clickAccountButtons', btn_value)
        })
    });
</script>