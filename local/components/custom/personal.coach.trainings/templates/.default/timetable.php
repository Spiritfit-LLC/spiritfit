<div class="tw-timetable">

    <div class="tw-timetable__slots">
        <div class="tw-timetable__section">
            <div class="tw-timetable__section-times-container">
            <?$index=0;?>
            <? foreach ($arResult["TIMETABLE"] as $timekey=>$TIME): ?>

                <div class="tw-timetable__section-timeitem <?=$TIME["TYPE"]?>"
                     data-time="<?=$timekey?>"
                     data-index="<?=$index?>"
                    <?if ($TIME["TYPE"]=="busy"):?>
                        data-client="<?=$TIME["WORKOUT"]["client"]?>"
                        data-workout-id="<?=$TIME["WORKOUT"]["id"]?>"
                        data-workout-type="<?=!empty($TIME["WORKOUT"]["type"])?$TIME["WORKOUT"]["type"]:"Персональная тренировка"?>"
                        data-changeble="<?=$TIME["WORKOUT"]["changeble"]?>"
                        onclick="show_busy(this)"
                    <?else:?>
                        onclick="choose_slots(this)"
                    <?endif;?>>
                    <?=$timekey?>
                </div>
                <?$index++?>

            <?endforeach;?>
            </div>
        </div>
    </div>
</div>
