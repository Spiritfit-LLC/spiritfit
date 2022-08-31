<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<?if($arResult["SCHEDULE"]):?>
<section class="b-timetable" id="timetable" style="padding: 30px">
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
            </div>
        </div>
        <div class="b-timetable__content-wrap">
            <div class="b-timetable__content">
                <div class="b-timetable__header">
                    <?foreach($arResult["SCHEDULE"] as $day => $arDayShedule):?>
                        <div class="b-timetable__column <?if($arDayShedule["ACTIVE"]){ echo "active";}?>">
                            <div class="b-timetable__head"><span class="b-timetable__head-day"><?=$day;?></span> <?=$arDayShedule["DATE"];?></div>
                        </div>
                    <?endforeach;?>
                </div>
                <div class="b-timetable__body">
                    <?foreach($arResult["SCHEDULE"] as $day => $arDayShedule):?>
                        <div class="b-timetable__col">
                            <?foreach($arDayShedule["TRAININGS"] as $TRAINING):?>
                                <div class="b-timetable-item">
                                    <div class="b-timetable-item__name"><?=$TRAINING['NAME'];?></div>
                                    <?if (!empty($TRAINING["COACH"])):?>
                                        <div class="b-timetable-item__text"><?=$TRAINING['TIME'];?>, <?=$TRAINING['COACH'];?></div>
                                    <?else:?>
                                        <div class="b-timetable-item__text"><?=$TRAINING['TIME'];?></div>
                                    <?endif?>

                                    <div class="b-timetable-item__hidden-content">
                                        <button class="b-timetable-item__close"></button>
                                        <div class="b-timetable-item__name"><?=$TRAINING['NAME'];?></div>
                                        <div class="b-timetable-item__text"><?=$TRAINING['DESCRIPTION'];?></div>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
            <div class="timetable-overlay">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </div>
        </div>
</section>
<?endif;?>