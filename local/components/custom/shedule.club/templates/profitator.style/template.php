<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>



<section class="b-timetable" id="timetable">
    <div class="content-center">
        <div class="b-timetable__heading">
            <h2 class="b-timetable__title">Расписание групповых занятий</h2>
            <div class="timetable-controls">
                <div class="b-timetable__switch-holder">
                    <select class="b-timetable__switch">
                        <? foreach ($arResult["CLUBS"] as $itemClub) { ?>
                            <option value="<?=$itemClub['PROPERTY_NUMBER_VALUE']?>" <?if($itemClub["SELECTED"]) echo "selected";?>><?=$itemClub['NAME']?></option>
                        <? } ?>
                    </select>
                </div>
<!--                <button class="show-filters">Показать фильтр <span class="btn-arrow"></span></button>-->
            </div>
            <div class="b-timetable__filter">

            </div>
        </div>
        <div class="b-timetable__content-wrap">
            <div class="b-timetable__content">
                <?if($arResult["SCHEDULE"]):?>
                    <div class="b-timetable__header">
                        <?foreach($arResult["SCHEDULE"] as $day => $arDayShedule):?>
                            <div class="b-timetable__column <?if($arDayShedule["ACTIVE"]){ echo "active";}?>" data-id="<?=$arDayShedule["UID"]?>">
                                <div class="b-timetable__head"><span class="b-timetable__head-day"><?=$day;?></span> <?=$arDayShedule["DATE"];?></div>
                            </div>
                        <?endforeach;?>
                    </div>
                    <div class="b-timetable__body">
                        <?foreach($arResult["SCHEDULE"] as $arDaySchedule):?>
                            <div class="b-timetable__day-item is-hide" data-id="<?=$arDaySchedule["UID"]?>">
                                <div class="b-timetable__day-times">
                                    <?foreach ($arDaySchedule["TRAININGS"] as $TRAINING):?>
                                        <div class="b-timetable__training-item <?if($TRAINING["ACTIVE"]){ echo "active current-item";}?>"
                                             data-id="<?=$TRAINING['UID']?>"
                                            data-coach="<?=$TRAINING["COACH_ID"]?>">
                                            <div class="training-item__name"><?=$TRAINING['NAME']?></div>
                                            <div class="training-item__body">
                                                <div class="trainin-time"><?=$TRAINING["TIME"]?></div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                                <div class="b-timetable__day-trainings">
                                    <?foreach($arDaySchedule["TRAININGS"] as $TRAINING):?>
                                        <div class="training-item__block <?if($TRAINING["ACTIVE"]){ echo "active";}?>" data-id="<?=$TRAINING['UID']?>">
                                            <div class="training-item__desc">
                                                <div class="training-item__name">
                                                    <?=$TRAINING['NAME']?>
                                                </div>
                                                <?if (!empty($TRAINING["COACH"])):?>
                                                    <div class="training-item__coach">
                                                        Тренер - <?=$TRAINING["COACH"]?>
                                                    </div>
                                                <?endif?>
                                                <div class="training-item__desc-body">
                                                    <?=$TRAINING['DESCRIPTION']?>
                                                </div>
                                            </div>
                                            <div class="training-item__photo">

                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>
            <div class="timetable-overlay">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </div>
        </div>
    </div>
</section>
