<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<script type="application/javascript">
    var personal_page_link=<?=$arResult["PROFILE_URL"]?>;
</script>
<div class="personal-page">
    <div class="personal-profile -onpersonal">
        <form class="personal-profile-form -onpersonal" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">
            <div class="personal-profile-body">
                <div class="personal-profile-photo">
                    <img src="<?=$arResult['LK_FIELDS']['PERSONAL_PHOTO']?>" height="100%" width="100%" class="profile-personal-photo">
                    <div class="change-profile-photo-btn">
                        <span>ПОМЕНЯТЬ ФОТО</span>
                    </div>
                </div>
                <?foreach($arResult['LK_FIELDS']['HIDDEN'] as $FIELD):?>
                    <input
                            name="<?=$FIELD['NAME']?>"
                            type="<?=$FIELD['TYPE']?>"
                            value="<?=$FIELD['VALUE']?>"
                            autocomplete="off"
                            <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>>
                <?endforeach;?>
                <div class="personal-profile-info">
                    <div class="personal-info-head-items">
                        <?foreach ($arResult['LK_FIELDS']['VISIBLE']['HEAD'] as $FIELD):?>
                            <div class="personal-info-item -important">
                                <span class="info-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                                <?if (!$arResult['CHANGE'] || !$FIELD['CHANGEBLE']):?>
                                    <span class="info-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                                <?else:?>
                                    <input class="info-item-value change-profile-input" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>" placeholder="<?=$FIELD['PLACEHOLDER']?>" type="<?=$FIELD['TYPE']?>">
                                <?endif;?>
                            </div>
                        <?endforeach;?>
                        <a href="#update-head-items" class="update-head-items">Обновить</a>
                    </div>
                    <div class="personal-info-nothead-items">
                        <?foreach ($arResult['LK_FIELDS']['VISIBLE']['NOTHEAD'] as $FIELD):?>
                            <div class="personal-info-item">
                                <span class="info-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                                <?if (!$arResult['CHANGE'] || !$FIELD['CHANGEBLE']):?>
                                    <span class="info-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                                <?else:?>
                                    <input
                                            class="info-item-value change-profile-input personal-form-input"
                                            value="<?=$FIELD['VALUE']?>"
                                            name="<?=$FIELD['NAME']?>"
                                            placeholder="<?=$FIELD['PLACEHOLDER']?>"
                                            type="<?=$FIELD['TYPE']?>"
                                            data-required_id="<?=$FIELD['REQUIRED_ID']?>"
                                            <? if ($FIELD['REQUIRED_FROM']){?> data-required_from="<?=$FIELD['REQUIRED_FROM']?>"<?}?>
                                            <? if ($FIELD['MIN_LENGTH']):?> minlength="<?=$FIELD['MIN_LENGTH']?>" <?endif;?>
                                            <? if ($FIELD['MAX_LENGTH']):?> maxlength="<?=$FIELD['MAX_LENGTH']?>" <?endif;?>
                                            <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?>
                                    >
                                <?endif;?>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
                <div class="personal-profile-btns">
                    <?if (!$arResult['CHANGE']):?>
                        <a class="personal-profile-form-btn button-outline modal-button" href="?change=Y">Редактировать</a>
                        <input class="personal-profile-form-btn button-outline modal-button" type="submit" value="Выйти">
                    <?else:?>
                        <a class="personal-profile-form-btn button-outline modal-button" href="<?=$arResult["PROFILE_URL"]?>">Назад</a>
                        <input class="personal-profile-form-btn button-outline modal-button" type="submit" value="сохранить">
                    <?endif;?>
                </div>
                <span class="error-message-text"></span>
            </div>
        </form>
    </div>
    <?if (!$arResult['CHANGE'] && !empty($arResult['ACTIVE_USERS_LIST'])):?>
        <div class="other-users-block">
            <div class="other-users-header">
                ПОЛЬЗОВАТЕЛИ САЙТА СЕГОДНЯ
            </div>
            <div class="other-users-body">
                <div class="search-block">

                </div>
                <div class="users-list-block">
                    <?foreach($arResult['ACTIVE_USERS_LIST'] as $arUser):?>
                        <div class="user-block">
                            <div class="user-profile-photo">
                                <img src="<?=$arUser['PERSONAL_PHOTO']?>" loading="lazy">
                            </div>
                            <div class="user-profile-info">
                                <?foreach ($arUser['VISIBLE'] as $GROUP):?>
                                    <div class="user-field-group">
                                        <?foreach ($GROUP as $FIELD):?>
                                            <div class="user-field-item">
                                                <?if(strlen($FIELD['PLACEHOLDER'])>0):?>
                                                    <span class="user-field-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                                                <?endif;?>
                                                <span class="user-field-value <?=$FIELD['ADDITIONAL_CLASSNAME']?>"><?=$FIELD['VALUE']?></span>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    <?endif;?>
</div>

<!--<div class="personal-profile -onpage">-->
<!--    --><?//if (isset($_GET['change'])):?>
<!--    <form class="change-personal-profile personal-profile-body" action="--><?//=$arResult['LOGIN_URL']?><!--">-->
<!--        <input type="hidden" name="action" value="change_profile">-->
<!--        --><?//else:?>
<!--        <div class="personal-profile-body">-->
<!--            --><?//endif;?>
<!--            <div class="personal-profile-photo">-->
<!--                <img src="--><?//=$arResult['USER']['PERSONAL_PHOTO']?><!--" height="200" width="200" class="profile-personal-photo">-->
<!--                <div class="change-profile-photo-btn">-->
<!--                    <span>ПОМЕНЯТЬ ФОТО</span>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="personal-profile-info">-->
<!--                --><?//if (!isset($_GET['change'])):?>
<!--                    --><?//foreach ($arResult['USER_SHOWING_INFO'] as $INFO):?>
<!--                        <div class="personal-info-item -onpage --><?//if ($INFO['TYPE']=='IMPORTANT') echo '-important'?><!--">-->
<!--                            <span class="info-item-placeholder">--><?//=$INFO['HUMANIZED']?><!--</span>-->
<!--                            <span class="info-item-value">--><?//=$INFO['VALUE']?><!--</span>-->
<!--                        </div>-->
<!--                    --><?//endforeach;?>
<!--                --><?//else:?>
<!--                    --><?//foreach ($arResult['USER_SHOWING_INFO'] as $INFO):?>
<!--                        <div class="personal-info-item -onpage --><?//if ($INFO['TYPE']=='IMPORTANT') echo '-important';?><!--">-->
<!--                            --><?//if ($INFO['CHANGEBLE']):?>
<!--                                <span class="info-item-placeholder">--><?//=$INFO['HUMANIZED']?><!--</span>-->
<!--                                <input class="info-item-value change-profile-input" value="--><?//=$INFO['VALUE']?><!--" type="--><?//=$INFO['INPUT_TYPE']?><!--" name="--><?//=$INFO['INPUT_NAME']?><!--" required>-->
<!--                            --><?//else:?>
<!--                                <span class="info-item-placeholder">--><?//=$INFO['HUMANIZED']?><!--</span>-->
<!--                                <span class="info-item-value">--><?//=$INFO['VALUE']?><!--</span>-->
<!--                            --><?//endif;?>
<!--                        </div>-->
<!--                    --><?//endforeach;?>
<!---->
<!--                    <div class="personal-info-item -onpage">-->
<!--                        <span class="info-item-placeholder">Новый пароль</span>-->
<!--                        <input class="info-item-value  change-profile-input" type="password" name="new-passwd">-->
<!--                    </div>-->
<!--                    <div class="personal-info-item -onpage">-->
<!--                        <span class="info-item-placeholder">Подтверждение</span>-->
<!--                        <input class="info-item-value  change-profile-input" type="password" name="new-passwd-confirm">-->
<!--                    </div>-->
<!--                    <span class="error-message-text"></span>-->
<!--                --><?//endif;?>
<!--            </div>-->
<!--            <div class="personal-profile-btns">-->
<!--                --><?//if (!isset($_GET['change'])):?>
<!--                    <span class="personal-profile-form-btn button-outline -modal-btn -logout" data-action="--><?//=$arResult['LOGIN_URL']?><!--">выйти</span>-->
<!--                    <span class="personal-profile-form-btn button-outline -modal-btn personal-change-btn">Редактировать</span>-->
<!--                --><?//else:?>
<!--                    <a class="personal-profile-form-btn button-outline -modal-btn -logout" href="--><?//=$arResult["PROFILE_URL"]?><!--">назад</a>-->
<!--                    <input class="personal-profile-form-btn button-outline -modal-btn personal-save-btn" type="submit" value="сохранить">-->
<!--                --><?//endif;?>
<!--            </div>-->
<!---->
<!--            --><?//if (isset($_GET['change'])):?>
<!--    </form>-->
<!--    --><?//else:?>
<!--</div>-->
<?//if (isset($arResult['OTHER_USERS_LIST'])):?>
<!--    <div class="other-users-block">-->
<!--        <div class="other-users-header">-->
<!--            ПОЛЬЗОВАТЕЛИ САЙТА СЕГОДНЯ-->
<!--        </div>-->
<!--        <div class="other-users-body">-->
<!--            <div class="search-block">-->
<!---->
<!--            </div>-->
<!--            <div class="users-list-block">-->
<!--                --><?//foreach($arResult['OTHER_USERS_LIST'] as $arUser):?>
<!--                    <div class="user-block">-->
<!--                        <div class="user-profile-photo">-->
<!--                            <img src="--><?//=$arUser['PERSONAL_PHOTO']?><!--" loading="lazy">-->
<!--                        </div>-->
<!--                        <div class="user-profile-info">-->
<!--                            --><?//foreach ($arUser['FIELDS'] as $FIELD):?>
<!--                                <div class="user-field --><?//if ($FIELD['TYPE']=='IMPORTANT') echo '-important';?><!--">-->
<!--                                    --><?//if (isset($FIELD['HUMANIZED'])):?>
<!--                                        <span class="user-field-placeholder">--><?//=$FIELD['HUMANIZED']?><!--</span>:-->
<!--                                    --><?//endif;?>
<!--                                    <span class="user-field-value">--><?//=$FIELD['VALUE']?><!--</span>-->
<!--                                </div>-->
<!--                            --><?//endforeach;?>
<!--                        </div>-->
<!--                    </div>-->
<!--                --><?//endforeach;?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<?//endif;?>
<?//endif;?>
<!--</div>-->

<?php
//global $APPLICATION;
//$APPLICATION->IncludeFile(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/includes/change-photo.php',
//    [
//        'USER_OLD_PHOTO_ID'=>$arResult['LK_FIELDS']['OLD_PHOTO_ID'],
//        'AJAX_LINK'=>$arResult['ajax'],
//        'USER_PERSONAL_PHOTO'=>$arResult['LK_FIELDS']['PERSONAL_PHOTO'],
//    ],
//    ['SHOW_BORDER' => false]);
//?>