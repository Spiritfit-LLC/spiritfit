<div class="tw-timetable__controllers" style="display: none">
    <div class="tw-timetable__choosen"></div>
    <div class="tw-timetable__show">
        Показать слоты
    </div>
    <div class="personal-section-form__item select-item">
        <span class="personal-section-form__item-placeholder">Тренер</span>
        <select class="input input--light input--select" name="coach" autocomplete="off" required="required"></select>
    </div>
    <input type="submit" class="personal-section-form__submit button-outline trialworkout" value="Записаться">
    <div class="escapingBallG-animation">
        <div id="escapingBall_1" class="escapingBallG"></div>
    </div>
</div>
<div class="tw-timetable">
    <div class="personal-section-form__item radio-item">
        <div style="margin-top: 5px;">
            <div class="input-radio-item-block">
                <input
                        class="personal-section-form__item-value input-radio-btn"
                        type="radio"
                        name="slots"
                        value="FREE"
                        id="FREESLOTS"
                >
                <label for="FREESLOTS">Свободные слоты</label>
            </div>
            <div class="input-radio-item-block">
                <input
                        class="personal-section-form__item-value input-radio-btn"
                        type="radio"
                        name="slots"
                        value="ALL"
                        id="ALLSLOTS"
                        checked
                >
                <label for="ALLSLOTS">Все слоты</label>
            </div>
        </div>
    </div>
    <?foreach ($arResult["TIMETABLE"] as $key=>$TIMES):?>
        <div class="tw-timetable__section">
            <div class="tw-timetable__section-type">
                <?if ($key=="0MORNING"){
                    echo 'Утро';
                }
                elseif ($key=="1DAYTIME"){
                    echo 'День';
                }
                elseif($key=="2EVENING"){
                    echo "Вечер";
                }?>
            </div>
            <div class="tw-timetable__section-times-container">
                <?foreach ($TIMES as $timekey=>$TIME):?>
                    <div class="tw-timetable__section-timeitem <?if($TIME["TYPE"]=="NOTFREE") echo 'not-free'?>" data-time="<?=$timekey?>">
                        <?if ($TIME["TYPE"]=="NOTFREE"):?>
                            <div class="timeitem-warning">
                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/info-icon.svg');?>
                            </div>
                        <?endif;?>
                        <?=$timekey?>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    <?endforeach;?>
</div>
