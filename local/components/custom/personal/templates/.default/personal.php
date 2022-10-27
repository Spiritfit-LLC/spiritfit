<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<?foreach ($arResult['LK_FIELDS']['JS'] as $js):?>
    <script src="<?=$js?>?version=<?=uniqid()?>"></script>
<?endforeach;?>
<?foreach ($arResult['LK_FIELDS']['CSS'] as $css):?>
    <link href="<?=$css?>?version=<?=uniqid()?>" type="text/css" rel="stylesheet">
<?endforeach;?>

<div class="personal-profile-block">
    <div class="personal-profile__left-block">
        <div class="personal-profile__user">
            <div>
                <div class="personal-profile__user-photo">
                    <img src="<?=$arResult['LK_FIELDS']['HEAD']['PERSONAL_PHOTO']?>" height="100%" width="100%">
                    <form class="personal-profile__user-refresh-photo" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                        <input type="hidden" name="ACTION" value="updatePhoto">
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
                foreach ($arResult['LK_FIELDS']['SECTIONS'] as $SECTION):?>
                    <div class="personal-profile__tab-item <?if ($SECTION['ACTIVE']) echo 'active'; elseif($arResult["ACTIVE_SECTION"]==$SECTION['ID']) echo 'active';?>" data-id="<?=$SECTION['ID']?>">
                        <div class="tab-item__icon">
                            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$SECTION['ICON']);?>
                        </div>
                        <div class="tab-item__name">
                            <?=$SECTION['NAME']?>
                        </div>
                        <?if(!empty($SECTION["NOTIFICATIONS"])):?>
                            <div class="tab-item__notification" data-id="<?=$SECTION["ID"]?>">
                                <div class="tab-item__notification-count">
                                    <?=$SECTION["NOTIFICATIONS"];?>
                                </div>
                            </div>
                        <?endif;?>
                    </div>
                <?
                endforeach;
                ?>
                <? if( defined('QUIZ_AVTIVE') && IS_QUIZ_AVTIVE ) {
                    ?>
                    <div class="personal-profile__tab-item profile-quiz-btn"  data-link="<?=QUIZ_LINK?>">
                        <div class="tab-item__icon">
                            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/quiz-btn.svg');?>
                        </div>
                        <div class="tab-item__name">
                            <?=QUIZ_LINK_TITLE?>
                        </div>
                    </div>
                    <?
                } ?>
                <div class="personal-profile__tab-item profile-exit-btn"  data-componentName="<?=$arResult['COMPONENT_NAME']?>">
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
        global $APPLICATION;
        foreach ($arResult['LK_FIELDS']['SECTIONS'] as $SECTION){
            $APPLICATION->IncludeFile(
                str_replace($_SERVER['DOCUMENT_ROOT'], '',__DIR__) .'/includes/section_form.php',
                array(
                    'SECTION_ID'=>$SECTION['ID'],
                    "SECTION_CODE"=>$SECTION['CODE'],
                    'SECTION'=>$SECTION,
                    'IS_CORRECT'=>$arResult['LK_FIELDS']['IS_CORRECT'],
                    'COMPONENT_NAME'=>$arResult['COMPONENT_NAME'],
                ),
                array(
                    'SHOW_BORDER'=>true
                )
            );
        }?>
    </div>
</div>