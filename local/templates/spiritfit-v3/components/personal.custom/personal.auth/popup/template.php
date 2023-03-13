<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>


<script>
    var enter_btn='<?=$arParams["ENTER_BTN"]?>';
    var personal_component='<?=$this->getComponent()->getName()?>';
</script>

<div class="popup-container <?if ($arResult["SHOW_FORM"]) echo "active";?>" id="auth-container">
    <?foreach ($arResult["FORMS"] as $ID=>$FORM):?>
        <div class="popup-modal__personal <?if ($FORM["ACTIVE"]):?> active <?endif?>" id="<?=$ID?>_MODAL">
            <div class="closer modal__closer">
                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
            </div>
            <div class="popup-personal__body">
                <div class="popup-personal__title"><?=$FORM["NAME"]?></div>
                <form class="popup-personal-form" autocomplete="off" method="post" enctype="multipart/form-data" data-action="<?=$FORM["ACTION"]?>">
                    <input type="hidden" name="WEB_FORM_ID" value="<?=$FORM['WEB_FORM_ID']?>">
                    <input type="hidden" name="FORM_STEP" value="1">
                    <?foreach ($FORM["FIELDS"] as $FIELD):?>
                        <?if ($FIELD['TYPE']=='hidden'):?>
                            <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                            <?
                            continue;
                        endif;
                        ?>
                        <div class="personal_form__input <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                        <?if ($FIELD['TYPE']=='password') echo 'password-item';?>"
                            <?if ($FIELD['TYPE']=='password'&&$ID=="AUTH"):?> style="display: none"<?endif;?>>

                            <div class="personal_form__input-placeholder"><?=$FIELD["PLACEHOLDER"]?></div>

                            <?if ($FIELD['TYPE']=='radio'):?>
                            <div style="margin-top: 5px;">
                                <?for ($i=0; $i<count($FIELD['VALUE']); $i++):?>
                                    <div class="input-radio-item-block">
                                        <input
                                                class="personal_form__input-value input-radio-btn"
                                                type="<?=$FIELD['TYPE']?>"
                                                name="<?=$FIELD['NAME']?>"
                                                value="<?=$FIELD['VALUE'][$i]?>"
                                                id="<?=$FIELD['NAME'].'_'.$i?>"
                                            <?if ($FIELD['REQUIRED']) echo 'required';?>
                                            <?=$FIELD['PARAMS']?>>
                                        <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                                    </div>
                                <?endfor;?>
                            </div>
                            <?else:?>
                                <div class="personal_form__input-container">
                                    <?if ($FIELD["TYPE"]=="tel"):?>
                                        <div class="personal_form--tel-prenumber">+7</div>
                                    <?endif;?>
                                    <input
                                            class="personal_form__input-value <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                                            type="<?if ($FIELD['TYPE']=='date') echo 'text'; else echo $FIELD['TYPE'];?>"
                                            name="<?=$FIELD['NAME']?>"
                                            value="<?=$FIELD['VALUE']?>"
                                            id="<?=$FIELD['NAME']?>"
                                        <?if ($FIELD['REQUIRED']) echo 'required';?>
                                        <?if (!empty($FIELD['VALIDATOR'])){ echo $FIELD['VALIDATOR'];}?>
                                        <?=$FIELD['PARAMS']?>
                                    >
                                    <?if ($FIELD['TYPE']=='password'):?>
                                        <div class="show-password-icon">
                                            <button class="pass-show active password-btn" onclick="pass_show(this, '<?=$FIELD['NAME']?>')" type="button">
                                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/pass-show.svg');?>
                                            </button>
                                            <button class="pass-hide password-btn" onclick="pass_hide(this, '<?=$FIELD['NAME']?>')" type="button">
                                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/pass-hide.svg');?>
                                            </button>
                                        </div>
                                    <?endif;?>
                                </div>
                            <?endif;?>
                        </div>
                    <?endforeach;?>

                    <div class="form-submit-result-text"></div>


                    <?if (!empty($FORM['BTN_TEXT'])):?>
                        <input type="submit" class="personal_form__submit button" value="<?=$FORM['BTN_TEXT']?>">
                        <div class="escapingBallG-animation">
                            <div id="escapingBall_1" class="escapingBallG"></div>
                        </div>
                    <?endif;?>

                </form>
                <div class="popup-personal__footer">
                    <?foreach ($arResult["FORMS"] as $I=>$F):?>
                        <?if ($I==$ID) continue;?>
                        <div class="popup-personal__footer-btn">
                            <a class="purple-text get-form-btn" href="#<?=$I?>_MODAL"><?=$F["NAME"]?></a>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    <?endforeach;?>
</div>
