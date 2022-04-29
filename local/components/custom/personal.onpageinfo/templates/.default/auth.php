<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="personal-profile -modal is-hide-modile" >
    <div class="personal-profile-header -modal-header">
        <div class="personal-profile-closer -modal-closer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"/></svg>
        </div>
    </div>
    <form class="personal-profile-form -onpage" action="<?=$arResult['ajax']?>" method="post" enctype="multipart/form-data">

        <div class="personal-profile-body -modal-body">
            <div class="personal-profile-photo">
                <img src="<?=$arResult['LK_FIELDS']['PERSONAL_PHOTO']?>" height="200" width="200" class="profile-personal-photo">
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
                            <span class="info-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                        </div>
                    <?endforeach;?>
                    <a href="#update-head-items" class="update-head-items">Обновить</a>
                </div>
                <div class="personal-info-nothead-items">
                    <?foreach ($arResult['LK_FIELDS']['VISIBLE']['NOTHEAD'] as $FIELD):?>
                        <div class="personal-info-item">
                            <span class="info-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                            <span class="info-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
            <div class="personal-profile-btns">
                <a class="personal-profile-form-btn button-outline modal-button" href="<?=$arResult['PROFILE_URL']?>">перейти в профиль</a>
                <input class="personal-profile-form-btn button-outline modal-button" type="submit" value="Выйти">
            </div>
            <span class="error-message-text"></span>
        </div>
    </form>
</div>

<?php
//global $APPLICATION;
//$APPLICATION->IncludeFile(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/includes/change-photo.php',
//    [
//        'USER_OLD_PHOTO_ID'=>$arResult['LK_FIELDS']['OLD_PHOTO_ID'],
//        'LOGIN_URL'=>$arResult['LOGIN_URL'],
//        'USER_PERSONAL_PHOTO'=>$arResult['LK_FIELDS']['PERSONAL_PHOTO'],
//    ],
//    ['SHOW_BORDER' => false]);
//?>