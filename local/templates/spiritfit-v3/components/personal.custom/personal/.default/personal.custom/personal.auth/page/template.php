<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
global $USER;
?>
<script>
    var personal_auth_component='<?=$this->getComponent()->getName()?>';
</script>

<div class="b-page__heading">
    <div class="<?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>">
        <div class="b-page__heading-inner <?if (defined('HIDE_SLIDER')) echo "black"?>">
            <? $APPLICATION->IncludeComponent(
                "bitrix:breadcrumb",
                "custom",
                array(
                    "START_FROM" => "0",
                    "PATH" => "",
                    "SITE_ID" => "s1"
                )
            ); ?>

            <div class="b-page__heading-title">
                <?if (defined('PAGE_TITLE')):?>
                    <h1 class="b-page__title <?if (defined('HIDE_SLIDER')) echo "black"?>
                    <?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>
                    <?if (defined('H1_BIG')) echo "title-big"?>"><?=PAGE_TITLE?></h1>
                <?else:?>
                    <h1 class="b-page__title <?if (defined('HIDE_SLIDER')) echo "black"?>
                    <?if (defined('H1_TEXT_CONTENT')) echo "text-content"?>
                    <?if (defined('H1_BIG')) echo "title-big"?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                <?endif;?>
                <?php if ($USER->IsAuthorized()):?>
                <button class="button bg-black " id="personal_exit">выйти</button>
                <?endif;?>
            </div>
            <a class="gradient-text" href="/personal/?update=Y">Перейти на старый дизайн</a>
        </div>
    </div>
</div>

<?php if (!$USER->IsAuthorized()):?>
<div class="personal-auth" id="personal-auth">
    <div class="personal-auth__content">
        <div class="personal-auth__tabs">
            <div class="personal-auth__link">
                <a href="#auth" class="personal-auth__btn <?=$arResult["ACTIVE_BTN"]=="auth"?"active":""?>">Вход</a>
            </div>
            <div class="personal-auth__link">
                <a href="#reg" class="personal-auth__btn <?=$arResult["ACTIVE_BTN"]=="reg"?"active":""?>">Регистрация</a>
            </div>
            <div class="personal-auth__link">
                <a href="#forgot" class="personal-auth__btn <?=$arResult["ACTIVE_BTN"]=="forgot"?"active":""?>">Забыл пароль</a>
            </div>
        </div>
        <div class="personal-auth__forms">
            <?foreach ($arResult["FORMS"] as $ID=>$FORM):?>
            <div class="personal-auth__form-content <?if ($FORM["ACTIVE"]):?> active <?endif?>" id="<?=$ID?>_FORM">
                <div class="personal-auth__title"><?=$FORM["NAME"]?></div>
                <form class="personal-auth__form" autocomplete="off" method="post" enctype="multipart/form-data" data-action="<?=$FORM["ACTION"]?>">
                    <input type="hidden" name="WEB_FORM_ID" value="<?=$FORM['WEB_FORM_ID']?>">
                    <input type="hidden" name="FORM_STEP" value="1">
                    <?foreach ($FORM["FIELDS"] as $FIELD):?>
                    <?if ($FIELD['TYPE']=='hidden'):?>
                        <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                        <?continue;
                    endif;?>
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
            </div>
            <?endforeach;?>
        </div>
    </div>
</div>
<?php endif;?>