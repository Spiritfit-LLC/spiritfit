<div class="tw-scrollTo"></div>
<div class="tw-timetable-controllers" style="display: none">
    <button class="trainings-list-btn" type="button">Вывести списком</button>
</div>

<div class="tw-timetable-change-text" style="display: none">
    Выберите слоты, которые хотите добавить в свое расписание.
</div>


<div class="tw-timetable">
    <div class="personal-section-form__item radio-item" style="padding: 0">
        <div style="margin-top: 5px;">
            <div class="input-radio-item-block">
                <input
                    class="personal-section-form__item-value input-radio-btn"
                    type="radio"
                    name="slots"
                    value="CLIENTS"
                    id="CLIENTSLOTS"
                >
                <label for="CLIENTSLOTS">Занятые</label>
            </div>
            <div class="input-radio-item-block">
                <input
                        class="personal-section-form__item-value input-radio-btn"
                        type="radio"
                        name="slots"
                        value="FREE"
                        id="FREESLOTS"
                        checked
                >
                <label for="FREESLOTS">Свободные</label>
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
    <?
    $index=0;
    foreach ($arResult["TIMETABLE"] as $key=>$TIMES):?>
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
                    <div class="tw-timetable__section-timeitem <?=$TIME["TYPE"]?>"
                         data-time="<?=$timekey?>"
                         data-day-span="<?=$key?>"
                         data-index="<?=$index?>"
                         <?if ($TIME["TYPE"]=="busy"):?>
                         data-client="<?=$TIME["WORKOUT"]["client"]?>"
                         data-workout-id="<?=$TIME["WORKOUT"]["id"]?>"
                         data-workout-type="<?=$TIME["WORKOUT"]["type"]?>"
                         data-changeble="<?=$TIME["WORKOUT"]["changeble"]?>"
                         data-time-from="<?=$TIME["WORKOUT"]["time_from"]?>"
                         data-time-to="<?=$TIME["WORKOUT"]["time_to"]?>"
                        <?endif;?>>
                        <?=$timekey?>
                    </div>
                <?
                    $index+=1;
                ?>
                <?if ($TIME["TYPE"]=="busy" && $TIME["WORKOUT"]["id"]!=$workout_id):?>
                    <div class="tw-timetable__list-item" style="display: none" data-workout-id="<?=$TIME["WORKOUT"]["id"]?>">
                        <?if($TIME["WORKOUT"]["changeble"]):?>
                        <div class="workout-info-list__functions-btn"></div>
                        <div class="workout-info-list-functions is-hide">
                            <div class="workout-info-list-function__item cncl">Отменить тренировку</div>
                        </div>
                        <?endif?>
                        <div class="tw-list__info client">
                            <div class="tw-list__info-placeholder">
                                Клиент
                            </div>
                            <div class="tw-list__info-value">
                                <?=$TIME["WORKOUT"]["client"]?>
                            </div>
                        </div>
                        <div class="tw-list__info type">
                            <div class="tw-list__info-placeholder">
                                Тип тренировки
                            </div>
                            <div class="tw-list__info-value">
                                <?=$TIME["WORKOUT"]["type"]?>
                            </div>
                        </div>
                        <div class="tw-list__info time">
                            <div class="tw-list__info-placeholder">
                                Время
                            </div>
                            <div class="tw-list__info-value"></div>
                        </div>

                    </div>
                <?endif;?>
                <?
                    if($TIME["TYPE"]=="busy"){
                        $workout_id=$TIME["WORKOUT"]["id"];
                    }
                endforeach;?>
            </div>
        </div>
    <?endforeach;?>
</div>
<!--<div class="tw-timetable__change">-->
<!--    <button class="change-slots tw-timetable-change-btn" type="button">Изменить слоты</button>-->
<!--    <button class="save-slots is-hide tw-timetable-change-btn" type="button">Сохранить</button>-->
<!--</div>-->

