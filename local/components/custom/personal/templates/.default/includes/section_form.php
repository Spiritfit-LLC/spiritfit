<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


if(!function_exists('GetPersonalSection')) {
    function GetPersonalSection($FIELD){?>
            <?if ($FIELD['TYPE']=='hidden'):?>
                <input type="hidden" name="<?=$FIELD['NAME']?>" value="<?=$FIELD['VALUE']?>" id="<?=$FIELD['HTML_ID']?>">
            <?return;?>
            <?elseif($FIELD["TYPE"]=="component"):?>
            <?
                global $APPLICATION;
                $APPLICATION->IncludeComponent($FIELD["COMPONENT_NAME"], $FIELD["COMPONENT_STYLE"]);
                return;
            ?>
            <?endif;?>
        <div class="personal-section-form__item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                               <?if($FIELD['TYPE']=='table') echo 'table-item';?>
                                                               <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                               <?if (!$FIELD['CHANGEBLE']) echo 'readonly-item';?>
                                                               <?if ($FIELD['TYPE']=='link') echo 'link-item';?>
                                                               <?if ($FIELD['TYPE']=='list') echo 'list-item';?>
                                                               <?if($FIELD['TYPE']=='SELECT') echo 'select-item';?>">

            <?if ($FIELD['TYPE']!='checkbox' && $FIELD['TYPE']!='link'):?>
                <span class="personal-section-form__item-placeholder"><?=$FIELD['PLACEHOLDER']?>
                    <?if (!empty($FIELD['CLUE']) && $FIELD["TYPE"]!="info"):?>
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
                                id="<?=$FIELD['NAME'].'_'.$i?>"
                                <?if ($FIELD['REQUIRED']) echo 'required';?>
                                <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                                <?if ($FIELD['VALUE'][$i]['CHECKED']) echo 'checked';?>
                                data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                                data-value="<?=$FIELD['DATA_VALUE']?>"
                            >
                            <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                        </div>
                    <?endfor;?>
                </div>
            <?elseif($FIELD['TYPE']=='link'):?>
                <a href="<?=$FIELD['VALUE']?>"
                    <?if (!empty($FIELD['CLICKABBLE'])) echo 'data-clickable="true"';?>
                   data-clue="<?=$FIELD['CLUE']['TEXT']?>"
                   data-value="<?=$FIELD['DATA_VALUE']?>"><?=$FIELD['PLACEHOLDER']?></a>
            <?elseif($FIELD['TYPE']=='textarea'):?>
                <textarea class="personal-section-form__item-value textarea"
                          data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                          data-value="<?=$FIELD['DATA_VALUE']?>"
                        <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                    <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR']?>
                        name="<?=$FIELD['NAME']?>"
                          id="<?=$FIELD['HTML_ID']?>"><?=$FIELD['VALUE']?></textarea>
            <?elseif($FIELD['TYPE']=='list'):?>
                <div class="list-item__body">
                <?foreach ($FIELD['VALUE'] as $V):?>
                    <input
                        class="personal-section-form__item-value list-item"
                        type="text"
                        name="<?=$FIELD['NAME']?>"
                        value="<?=$V?>"
                        id="<?=$FIELD['HTML_ID']?>"
                        data-required_id="<?=$FIELD['REQUIRED_ID']?>"
                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                    <?if ($FIELD['REQUIRED_FROM']){?> data-required_from="<?=$FIELD['REQUIRED_FROM']?>"<?}?>
                    <?if (!$FIELD['CHANGEBLE']){?> disabled="disabled" <?}?>
                        data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                        data-value="<?=$FIELD['DATA_VALUE']?>"
                    <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR']?>
                >
                <?endforeach;?>
                </div>
            <?elseif ($FIELD['TYPE']=='SELECT'):?>
                <select class="input input--light input--select" name="<?=$FIELD['NAME']?>" autocomplete="off" <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?> >
                    <? foreach ($FIELD['ITEMS'] as $CLUB):?>
                        <option value="<?=$CLUB["VALUE"]?>"><?=$CLUB["STRING"]?></option>
                    <? endforeach; ?>
                </select>
            <?elseif ($FIELD['TYPE']=='info'):?>
                <span class="personal-section-form__item-value"><?=htmlspecialcharsback($FIELD['VALUE'])?></span>
                <?if (!empty($FIELD['CLUE'])):?>
                    <div class="clue-icon">
                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/info-icon.svg');?>
                        <span class="clue-text" style="display: none"><?echo $FIELD['CLUE']['TEXT']?></span>
                    </div>
                <?endif;?>
            <?else:?>
                <?if (!empty($FIELD['OLD_SUM'])):?>
                    <div class="old-sum"><s><?=$FIELD['OLD_SUM']?></s>
                <?endif;?>
                <input
                    class="personal-section-form__item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                            <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                    type="<?if ($FIELD['TYPE']=='date' || $FIELD['TYPE']=='table' || $FIELD["TYPE"]=="address") echo 'text'; else echo $FIELD['TYPE'];?>"
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
                    data-value="<?=$FIELD['DATA_VALUE']?>"
                    <?if (!empty($FIELD['VALIDATOR'])) echo $FIELD['VALIDATOR']?>
                    <?if ($FIELD["TYPE"]=="address"):?>
                    data-dadata="true" data-dadata-type="ADDRESS"
                    <?endif;?>
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
                <?if ($FIELD['HTML_ID']=='client-email' && !$FIELD['CONFIRM']):?>
                    <a class="confirm-email-btn" href="#email-confirm">Подтвердить</a>
                <?elseif ($FIELD['HTML_ID']=='correct-email'):?>
                    <a class="confirm-email-btn" href="#email-change">Изменить</a>
                <?endif;?>
                <?if ($FIELD['TYPE']=='checkbox'):?>
                    <label for="<?=$FIELD['NAME']?>"><?=$FIELD['PLACEHOLDER']?></label>
                <?endif;?>
                <?if (!empty($FIELD['OLD_SUM'])):?>
                    </div>
                <?endif;?>
            <?endif;?>
            <?if(!empty($FIELD["NOTIFICATION"])):?>
                <div class="personal-section-form__item__notification" data-field="<?=$FIELD["USER_FIELD_CODE"]?>">Данные были обновлены</div>
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
                <div style="display: inline-block">
                    <?=$arParams['SECTION']['NAME']?>
                </div>
                <?if(!empty($SECTION["NOTIFICATIONS"])):?>
                    <div class="tab-item__notification" data-id="<?=$SECTION["ID"]?>">
                        <div class="tab-item__notification-count">
                            <?=$SECTION["NOTIFICATIONS"];?>
                        </div>
                    </div>
                <?endif;?>
            </div>

            <div class="sub-section__arrow-icon">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
            </div>
        </div>
    </div>
<?endif?>

<div class="personal-section <?if (!empty($arParams['PARENT'])) echo 'child'?>" style="display: none" data-id="<?=$arParams['SECTION_ID']?>" data-code="<?=$arParams['SECTION_CODE']?>">
    <?if (empty($arParams['PARENT'])):?>
        <div class="personal-section__title is-hide-mobile"><?=$arParams['SECTION']['NAME']?></div>
    <?endif?>

    <?if ($arParams['SECTION_CODE']=="lk_loyalty_program"):?>
        <?if (!empty($arParams["SECTION"]['USER_VISITS_LIST'])):?>
            <div class="personal-section__visits_count-container">
                <span class="personal-section-form__item-placeholder" style="margin-left: -20px;margin-bottom: 10px;">Мои посещения</span>
                <div class="personal-section__visits_count">
                    <?$visit_container_index=0?>
                    <?foreach($arParams["SECTION"]['USER_VISITS_LIST'] as $key=>$value):?>
                        <div class="visits-count-container" data-index="<?=$visit_container_index?>">
                            <div class="visits-count__block">
                                <div class="visits-count__occupancy" data-count="<?=$value["VALUE"]?>">
                                    <?=$value["VALUE"]?>
                                </div>
                            </div>
                            <div class="visits-count__month">
                                <?=$value["MONTH"]?>
                            </div>
                        </div>
                        <?$visit_container_index++;?>
                    <?endforeach;?>
                </div>
                <?if ($visit_container_index>5):?>
                <div class="personal-section__visits_count-controllers">
                    <div class="visits-count__controller left"></div>
                    <div class="visits-count__controller right"></div>
                </div>
                <?endif;?>
            </div>
        <?endif;?>

    <a class="personal-section-form__item-placeholder" href="#getHistory" style="margin-bottom: 10px;font-size: 16px;font-weight: 500;">Детали накопления бонусов</a>
    <div class="loyaltyhistory is-hide">
        <div class="loyaltyhistory-controls">
            <div class="loyaltyhistory-controls__item chart active">
                График
            </div>
            <div class="loyaltyhistory-controls__item list">
                Список
            </div>
        </div>
        <canvas id="loyaltyhistory-chart" class="loyaltyhistories-block chart"></canvas>
        <div class="loyaltyhistory-list is-hide loyaltyhistories-block list"></div>
        <span class="personal-section-form__item-placeholder" style="padding: 10px 0;float: right;">Так менялась сумма ваших бонусов за все время</span>
    </div>


    <?endif;?>

    <?if (!$arParams['IS_CORRECT'] && $SECTION['CODE']=="lk_profile"):?>
        <div class="profile-warnig-message">Пожалуйста, заполните все поля, чтобы активировать ваш профиль</div>
    <?endif;?>
    <?if (empty($arParams['PARENT']) || (!empty($arParams['PARENT']) && $arParams['SECTION']['FORM_TYPE']=='independent')):?>
    <form class="personal-section-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arParams['COMPONENT_NAME']?>">
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
                        'SECTION_CODE'=>$SECTION['CODE'],
                        'SECTION' => $SECTION,
                        'IS_CORRECT' => $arParams['IS_CORRECT'],
                        'PARENT'=>$arParams['SECTION_ID'],
                        'COMPONENT_NAME'=>$arParams['COMPONENT_NAME'],
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
                    'SECTION_CODE'=>$SECTION['CODE'],
                    'SECTION' => $SECTION,
                    'IS_CORRECT' => $arParams['IS_CORRECT'],
                    'PARENT'=>$arParams['SECTION_ID'],
                    'COMPONENT_NAME'=>$arParams['COMPONENT_NAME'],
                ),
                array(
                    'SHOW_BORDER' => true
                )
            );
        }
    } ?>
</div>


