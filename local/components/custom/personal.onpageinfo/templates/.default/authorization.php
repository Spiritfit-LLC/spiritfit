<div class="personal-auth <?if ($arResult['PROFILE_PAGE']) echo '-personal'; else echo '-modal'?>">
    <div class="personal-auth-header -modal-header">
        <div class="personal-auth-title">
            <!--НУЖНО ХЕДЕР БРАТЬ ИЗ АДМИНКИ-->
            <span>Авторизация в личном кабинете</span>
        </div>
        <?if (!$arResult['PROFILE_PAGE']):?>
        <div class="personal-auth-closer -modal-closer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"/></svg>
        </div>
        <?endif;?>
    </div>
    <div class="personal-auth-body <?if ($arResult['PROFILE_PAGE']) echo '-personal-body'; else echo '-modal-body'?>">
        <form class="personal-auth-form" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">
            <?foreach($arResult['FORM_FIELDS'][$arParams['AUTH_FORM_CODE']]['HIDDEN'] as $FIELD):?>
                <input
                    name="<?=$FIELD['NAME']?>"
                    type="<?=$FIELD['TYPE']?>"
                    value="<?=$FIELD['VALUE']?>"
                    <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>>
            <?endforeach;?>

            <div class="personal-auth-form-data -width-60">
                <?foreach($arResult['FORM_FIELDS'][$arParams['AUTH_FORM_CODE']]['VISIBLE'] as $FIELD):?>
                    <input
                        class="input input--light input--text personal-form-input"
                        name="<?=$FIELD['NAME']?>"
                        placeholder="<?=$FIELD['PLACEHOLDER']?>"
                        type="<?=$FIELD['TYPE']?>"
                        value="<?=$FIELD['VALUE']?>"
                        <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>>
                    <?if ($FIELD["COMMENT"]):?>
                        <span class="input-comment"><?=$FIELD["COMMENT"]?></span>
                    <?endif?>
                <?endforeach;?>
            </div>
            <div class="personal-auth-form-auth-links -width-40">
                <?if (!$arResult['PROFILE_PAGE']):?>
                <span class="auth-link" data-action="personal-getpass">ЗАБЫЛ ПАРОЛЬ</span>
                <span class="auth-link" data-action="personal-reg">РЕГИСТРАЦИЯ</span>
                <?else:?>
                <a href="?getpass=Y" class="auth-link">ЗАБЫЛ ПАРОЛЬ</a>
                <a href="?reg=Y" class="auth-link">РЕГИСТРАЦИЯ</a>
                <?endif;?>
            </div>
            <input class="personal-auth-form-btn button-outline -modal-btn" type="submit" value="войти">
            <span class="error-message-text"></span>
        </form>
    </div>
</div>