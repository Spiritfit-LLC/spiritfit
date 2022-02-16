<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$clubs = Clubs::getList();
$clubsJson = Clubs::clubsJsonShedule($arResult["SCHEDULE"]);
if(strpos($clubsJson, "'") !== false){
    $clubsJson = str_replace("'", '"', $clubsJson);
}

?>
<section class="b-timetable" id="timetable" data-timetable='<?=$clubsJson?>'>
    <div class="content-center">
        <div class="b-timetable__heading">
            <h2 class="b-timetable__title">Расписание групповых занятий</h2>
            <div class="b-timetable__switch-holder">
                <select class="b-timetable__switch">
                    <? foreach ($clubs as $itemClub) { ?>
                        <? $selected = ($arParams['CLUB_NUMBER'] == $itemClub['PROPERTY_NUMBER_VALUE'] ? 'selected' : '') ?>
						<option value="<?=$itemClub['ID']?>" <?=$selected?>><?=$itemClub['NAME']?></option>
					<? } ?>
                </select>
            </div>
        </div>
        <div class="b-timetable__content-wrap">
            <div class="b-timetable__content">
                <?if($arResult["SCHEDULE"]):?>
                    <?foreach($arResult["SCHEDULE"] as $number => $arClubShedule):?>
                        <? if($arParams['CLUB_NUMBER'] == $number){ ?>
                            <?foreach($arClubShedule as $day => $arDayShedule):?>
                                <div class="b-timetable__col">
                                    <div class="b-timetable__head"><?=$day;?></div>
                                    <?foreach($arDayShedule as $day => $arTraining):?>
                                        <div class="b-timetable-item">
                                            <div class="b-timetable-item__name"><?=$arTraining['NAME'];?></div>
                                            <div class="b-timetable-item__text"><?=$arTraining['TIME'];?>, <?=$arTraining['COACH'];?></div>
                                            <div class="b-timetable-item__hidden-content">
                                                <button class="b-timetable-item__close"></button>
                                                <div class="b-timetable-item__name"><?=$arTraining['NAME'];?></div>
                                                <div class="b-timetable-item__text"><?=$arTraining['DESCRIPTION'];?></div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            <?endforeach;?>
                        <? } ?>
                    <?endforeach;?>
                <?endif;?>
            </div>
        </div>
    </div>
</section>