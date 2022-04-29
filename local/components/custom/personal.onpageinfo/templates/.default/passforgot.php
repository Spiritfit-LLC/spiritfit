<div class="personal-getpass <?if ($arResult['PROFILE_PAGE']) echo '-personal'; else echo '-modal'?>">
    <div class="personal-getpass-header -modal-header">
        <div class="personal-getpass-title">
            <!--НУЖНО ХЕДЕР БРАТЬ ИЗ АДМИНКИ-->
            <span>Восстановление пароля</span>
        </div>
        <?if (!$arResult['PROFILE_PAGE']):?>
            <div class="personal-auth-closer -modal-closer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"/></svg>
            </div>
        <?endif;?>
    </div>
    <div class="personal-getpass-body <?if ($arResult['PROFILE_PAGE']) echo '-personal-body'; else echo '-modal-body'?>">
        <form class="personal-getpass-form" action="<?=$arResult['ajax']?>">
            <input type="hidden" name="STEP" value="1">
            <?foreach($arResult['FORM_FIELDS'][$arParams['PASSFORGOT_FORM_CODE']]['HIDDEN'] as $FIELD):?>
                <input
                    name="<?=$FIELD['NAME']?>"
                    type="<?=$FIELD['TYPE']?>"
                    value="<?=$FIELD['VALUE']?>"
                    autocomplete="off"
                    <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>>
            <?endforeach;?>
            <div class="personal-getpass-form-data">
                <?foreach($arResult['FORM_FIELDS'][$arParams['PASSFORGOT_FORM_CODE']]['VISIBLE'] as $FIELD):?>
                    <label for="<?=$FIELD['NAME']?>" class="-width-40"><?=$FIELD['PLACEHOLDER']?></label>
                    <input
                        class="input input--light input--text -width-60 personal-form-input"
                        name="<?=$FIELD['NAME']?>"
                        id="<?=$FIELD['NAME']?>"
                        placeholder="<?=$FIELD['PLACEHOLDER']?>"
                        type="<?=$FIELD['TYPE']?>"
                        value="<?=$FIELD['VALUE']?>"
                        <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>>
                    <?if ($FIELD["COMMENT"]):?>
                        <span class="input-comment"><?=$FIELD["COMMENT"]?></span>
                    <?endif?>
                <?endforeach;?>
                <div class="code-block">
                    <label for="reg_code" class="-width-40">SMS Код</label>
                    <input type="text" class="input input--light input--text reg_code" name="reg_code">
                    <a class="refreshcode">
                        <svg class="refresh-btn" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 361.095 361.095" style="enable-background:new 0 0 361.095 361.095;" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M182.595,325.678c-63.183,0-120.133-42.217-138.267-102.567c-2.833-9.067-12.183-14.167-21.25-11.333
                                        c-9.067,2.833-14.167,12.183-11.333,21.25c22.95,75.933,91.517,126.65,170.85,126.65c98.317,0,178.5-80.183,178.5-178.5
                                        s-80.183-178.5-178.5-178.5c-55.817,0-108.233,26.633-141.667,69.7l-7.083-56.1c-1.133-9.35-9.633-15.867-18.983-14.733
                                        C5.511,2.678-1.005,11.178,0.128,20.528l13.317,103.7c1.133,8.5,8.5,14.733,16.717,14.733c0.567,0,1.417,0,1.983,0l102.567-11.617
                                        c9.35-1.133,16.15-9.35,15.017-18.7s-9.35-16.15-18.7-15.017l-68.85,7.65c26.633-39.95,71.683-64.6,120.417-64.6
                                        c79.617,0,144.5,64.883,144.5,144.5S262.211,325.678,182.595,325.678z"/>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
            <input class="personal-getpass-form-btn button-outline -modal-btn" type="submit" value="отправить код">
            <span class="error-message-text"></span>
        </form>
    </div>
</div>