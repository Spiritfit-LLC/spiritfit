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
            <?elseif ($FIELD["TYPE"]=="switch" && !empty($FIELD["ITEMS"])):?>
                <?foreach ($FIELD["ITEMS"] as $ITEM){
                    GetPersonalSection($ITEM);
                }
                return;
                ?>
            <?endif;?>
        <div class="personal-section-form__item
        <?if (!$FIELD['CHANGEBLE']):?>readonly-item<?endif?>
        <?if ($FIELD['TYPE']=='SELECT'):?>select-item<?else:?><?=$FIELD['TYPE']?>-item<?endif?>">

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
            <?elseif ($FIELD["TYPE"]=="switch"):?>
                <label class="personal-section-form__item-switch">
                    <input type="checkbox"
                           class="personal-section-form__item-value switch-item"
                           name="<?=$FIELD['NAME']?>"
                           id="<?=$FIELD['HTML_ID']?>"
                           <?=$FIELD["SWITCH"]["ON"]?>
                           <?=$FIELD["SWITCH"]["CHECKED"]?>
                           data-code="<?=$FIELD['USER_FIELD_CODE']?>"
                           data-required_id="<?=$FIELD['REQUIRED_ID']?>"
                           data-event="<?=$FIELD["event"]?>"
                           data-id="<?=$FIELD["id"]?>"
                    >
                    <span class="switch-item__slider"></span>
                </label>
            <?else:?>
                <?if (!empty($FIELD['OLD_SUM'])):?>
                    <div class="old-sum"><s><?=$FIELD['OLD_SUM']?></s>
                <?endif;?>

                <?if ($FIELD["TYPE"]=="file"):?>
                    <div class="file-input__dropzone <?if (!empty($FIELD["VALUE"])) echo 'is-hide'?>" id="<?=$FIELD['HTML_ID']?>_dropzone">
                        <div class="file-input__btn">
                            <div class="btn-trigger" onclick="$('#<?=$FIELD['HTML_ID']?>').click()">
                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/add-photo-icon.svg')?>
                            </div>
                            Добавить файл<br>(.jpg, .jpeg, .png)
                        </div>
                    </div>
                <?endif;?>
                <input
                    class="personal-section-form__item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                            <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?> <?if ($FIELD['TYPE']=='file') echo 'file-input'?>"
                    type="<?if ($FIELD['TYPE']=='date' || $FIELD['TYPE']=='table' || $FIELD["TYPE"]=="address") echo 'text'; else echo $FIELD['TYPE'];?>"
                    name="<?=$FIELD['NAME']?>"
                    id="<?=$FIELD['HTML_ID']?>"
                    data-required_id="<?=$FIELD['REQUIRED_ID']?>"
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
                    <?if ($FIELD["TYPE"]=="file"):?>
                    accept=".jpg, .jpeg, .png"
                    onchange="personal_file_input(this.files, '<?=$FIELD['HTML_ID']?>')"
                    <?else:?>
                    value="<?=$FIELD['VALUE']?>"
                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                    <?endif;?>
                >
                <?if ($FIELD["TYPE"]=="file"):?>
                    <div class="file-input__preview" id="<?=$FIELD['HTML_ID']?>_media-preview">
                        <?if (!empty($FIELD["VALUE"])):?>
                            <div class="personal-media_preview__item image exist" style="background-image: url('<?=$FIELD["VALUE"]?>')" id="<?=$FIELD['HTML_ID']?>_preview_item">
                                <div class="closer-small"  onclick="reset_file_input('<?=$FIELD["HTML_ID"]?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="96px" height="96px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg></div>
                            </div>
                            <input type="hidden" id="<?=$FIELD['HTML_ID']?>_file_exist" value="1" name="<?=$FIELD['NAME']?>_file_exist">
                        <?else:?>
                            <input type="hidden" id="<?=$FIELD['HTML_ID']?>_file_exist" value="0" name="<?=$FIELD['NAME']?>_file_exist">
                        <?endif;?>
                    </div>
                <?endif;?>
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
    <?elseif ($arParams['SECTION_CODE']=="trialworkout_zapis"):?>
        <div class="trialworkout-message">
            Пробная тренировка - отличная возможность познакомиться с клубом.
            <br>
            <br>
            <b>С тренером 55 мин:</b>
            <br>
            Тренер проведет исследование состава тела InBody, ознакомит вас с техникой безопасности, научит пользоваться оборудованием и поможет разобраться во всем многообразии тренажеров.
            <br>
            Во время тренировки вы научитесь правильно подбирать вес и обучитесь технике базовых упражнений.
            <br>
            <br>
            <b>Самостоятельно:</b>
            <br>
            Вы получите неограниченный доступ ко всем услугам клуба: тренажерный зал, кардио- функциональное и силовое оборудование, групповые тренировки по расписанию клуба, исследование InBody, финские сауны и хаммам.
            <br>
            <br>
            Выберите подходящий вам формат тренировки и время посещения.
            <br>
            Возьмите с собой любой документ для регистрации (можно в электронном виде), полотенце и бутылку воды. Если ваши планы изменятся, не забудьте изменить запись.
            <br>
            <br>
            Увидимся на тренировке!
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


