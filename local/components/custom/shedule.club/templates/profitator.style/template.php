<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>


<?if($arResult["SCHEDULE"]):?>
<section class="b-timetable" id="timetable">
    <div class="content-center">
        <div class="b-timetable__heading">
            <h2 class="b-timetable__title">Расписание групповых занятий</h2>
            <!--noindex-->
            <div class="timetable-controls">
                <div class="b-timetable__switch-holder">
                    <select class="b-timetable__switch">
                        <? foreach ($arResult["CLUBS"] as $itemClub) { ?>
                            <option value="<?=$itemClub['PROPERTY_NUMBER_VALUE']?>" <?if($itemClub["SELECTED"]) echo "selected";?>><?=$itemClub['NAME']?></option>
                        <? } ?>
                    </select>
                </div>
                <button class="show-filters">Показать фильтр <span class="btn-arrow"></span></button>
            </div>
            <div class="b-timetable__filter is-hide">
                <div class="b-timetable__filter-block">
<!--                    <select class="filter-direction__switch">-->
<!--                        <option value="all">Все направления</option>-->
<!--                        --><?// foreach ($arResult["DIRECTIONS"] as $iteDirection) { ?>
<!--                            <option value="--><?//=$iteDirection['id']?><!--">--><?//=$iteDirection['name']?><!--</option>-->
<!--                        --><?// } ?>
<!--                    </select>-->
                    <select class="filter-loadlevel__switch filter-switch" multiple="multiple" data-placeholder="Уровень нагрузки" data-close="false">
                        <option value="all" class="all-select">Выбрать все</option>
                        <? foreach ($arResult["FILTERS"]["LOAD_LEVEL"] as $ID=>$LOAD_LEVEL) { ?>
                            <option value="<?=$ID?>"><?=$LOAD_LEVEL?></option>
                        <? } ?>
                    </select>
                    <div class="b-timetable__filter-checkbox">
                        <label class="b-checkbox">
                            <input type="checkbox" class="b-checkbox__input" name="not-virtual">
                            <span class="b-checkbox__text">Не показывать VIRTUAL</span>
                        </label>
                    </div>
                </div>
                <div class="b-timetable__filter-block">
                    <select class="filter-iwant__switch filter-switch" multiple="multiple" data-placeholder="Что я хочу" data-close="false">
                        <option value="all">Выбрать все</option>
                        <? foreach ($arResult["FILTERS"]["I_WANT"] as $ID=>$I_WANT) { ?>
                            <option value="<?=$ID?>"><?=$I_WANT?></option>
                        <? } ?>
                    </select>
                    <div class="b-timetable__filter-checkbox">
                        <?foreach($arResult["TIME_FILTER"] as $TIME_FILTER):?>
                            <label class="b-checkbox">
                                <input type="checkbox" class="b-checkbox__input" value="<?=$TIME_FILTER["VALUE"]?>" id="b-timetable__timefilter-<?=$TIME_FILTER["VALUE"]?>" name="b-timetable__time-filter">
                                <span class="b-checkbox__text"><?=$TIME_FILTER["NAME"]?></span>
                            </label>
                        <?endforeach;?>
                    </div>
                </div>
                <div class="b-timetable__filter-block">
                    <select class="filter-musculegroups__switch filter-switch" multiple="multiple" data-placeholder="Какие группы мышц" data-close="false">
                        <option value="all">Выбрать все</option>
                        <? foreach ($arResult["FILTERS"]["MUSCULE_GROUPS"] as $ID=>$MUSCULE_GROUP) { ?>
                            <option value="<?=$ID?>"><?=$MUSCULE_GROUP?></option>
                        <? } ?>
                    </select>
                    <button class="b-timetable__filter-clear" type="button">
                        <img class="b-timetable__filter-clear-closer" src="<?=SITE_TEMPLATE_PATH.'/img/icons/closer-white.png'?>">
                        Сбросить фильтры
                    </button>
                </div>
            </div>
            <!--/noindex-->
        </div>
        <!--noindex-->
        <div class="b-timetable__content-wrap">
            <div class="b-timetable__content">

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
                                    <div class="b-timetable__training-item <?if($TRAINING["ACTIVE"]){ echo "active current-item";}?> filter"
                                         data-id="<?=$TRAINING['UID']?>"
                                         data-filter-time="<?=$TRAINING['FILTER']["TIME"]?>"
                                         data-filter-musculegroup="<?=implode("|", array_column($TRAINING["FILTER"]["MUSCULE_GROUP"], "ID"))?>"
                                         data-filter-loadlevel="<?=implode("|", array_column($TRAINING["FILTER"]["LOAD_LEVEL"], "ID"))?>"
                                         data-filter-iwant="<?=implode("|", array_column($TRAINING["FILTER"]["I_WANT"], "ID"))?>"
                                         data-filter-virtual="<?=$TRAINING["VIRTUAL"] ? 'true' : 'false';?>">
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
                                            <?if($arResult["SHOW_TRIALWORKOUT_BTN"]):?>
                                                <?if (!$arResult["LK_TRIALWORKOUT"]):?>
                                                    <a href="/abonement/probnaya-trenirovka-/" class="get-trialworkout is-hide-mobile" data-form-id="<?=$arResult["AJAX_WEB_FORM_ID"]?>"
                                                       data-layer="true"
                                                       data-layercategory="UX"
                                                       data-layeraction="clickTrialWorkoutButton-Schedule"
                                                       data-layerlabel="<?=$APPLICATION->GetCurPage()?>">Пробная тренировка</a>
                                                <?else:?>
                                                    <a href="/personal/?SECTION=<?=$arResult["LK_TRIALWORKOUT_SECTION"]?>" class="get-trialworkout is-hide-mobile"
                                                       data-layer="true"
                                                       data-layercategory="UX"
                                                       data-layeraction="clickTrialWorkoutButton-Schedule"
                                                       data-layerlabel="<?=$APPLICATION->GetCurPage()?>">Пробная тренировка</a>
                                                <?endif;?>
                                            <?endif;?>
                                        </div>
                                        <div class="training-item__photo">
                                            <?if (!empty($TRAINING["MEDIA"]) && $TRAINING["MEDIA_TYPE"]=="IMG"):?>
                                                <img src="<?=$TRAINING["MEDIA"]?>" loading="lazy">
                                            <?elseif (!empty($TRAINING["MEDIA"]) && $TRAINING["MEDIA_TYPE"]=="VIDEO"):?>
                                                <video autoplay loop muted playsinline>
                                                    <source src="<?=$TRAINING["MEDIA"]?>" type="video/<?=pathinfo($TRAINING["MEDIA"], PATHINFO_EXTENSION)?>">
                                                </video>
                                            <?endif;?>
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
                <div class="filter-not-found is-hide">
                    <h3>К сожалению, по Вашим фильтрам не удалось найти тренировки</h3>
                    <span>Попробуйте изменить фильтры или рассмотреть варианты тренировок на другой день</span>
                </div>
                <?if (!$arResult["LK_TRIALWORKOUT"]):?>
                    <a href="/abonement/probnaya-trenirovka-/" class="get-trialworkout is-hide-desktop" data-form-id="<?=$arResult["AJAX_WEB_FORM_ID"]?>"
                       data-layer="true"
                       data-layercategory="UX"
                       data-layeraction="clickTrialWorkoutButton-Schedule"
                       data-layerlabel="<?=$APPLICATION->GetCurPage()?>">Пробная тренировка</a>
                <?else:?>
                    <a href="/personal/?SECTION=<?=$arResult["LK_TRIALWORKOUT_SECTION"]?>" class="get-trialworkout is-hide-desktop"
                       data-layer="true"
                       data-layercategory="UX"
                       data-layeraction="clickTrialWorkoutButton-Schedule"
                       data-layerlabel="<?=$APPLICATION->GetCurPage()?>">Пробная тренировка</a>
                <?endif;?>
            </div>

            <div class="timetable-overlay">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </div>
        </div>
        <!--/noindex-->
    </div>
</section>
<?endif;?>

