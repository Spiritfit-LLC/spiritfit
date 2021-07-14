<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $APPLICATION;
?>

<div class="timetable">
          <a class="timetable__close js-pjax-link" href="<?=$_SERVER['HTTP_REFERER']?>">
            <div></div>
            <div></div>
            </a>
          <h1 class="timetable__title timetable__title-custom">Расписание<br>групповых занятий</h1>
          <div class="timetable__filter">
            <div class="timetable__filter-date"></div>
            <?if($arResult["CLUB"]):?>
                <select class="timetable__filter-dept input input--select">
                    <?foreach($arResult["CLUB"] as $number => $name):?>
                        <option <?=(($arResult["CLUB_ACTIVE"]==$number) ? 'selected="selected" class="active_club"' : '');?> data-timetable="<?=$number*1;?>"><?=$name;?></option>
                    <?endforeach;?>
                </select>
            <?endif;?>
          </div>
            <script>
                $(document).ready(
                    function(){ 
                        $('.active_club').click();
                    }
                );
              
            </script>
          <div class="timetable__table">
            <div class="timetable__table-less"></div>
            <?if($arResult["SCHEDULE"]):?>
                <?foreach($arResult["SCHEDULE"] as $number => $arClubShedule):?>
                    <div class="timetable__container <?=(($arResult["CLUB_ACTIVE"]==$number) ? 'timetable__container-active  ps ps--active-x' : '');?>" data-timetable="<?=$number*1;?>">
                        <?foreach($arClubShedule as $day => $arDayShedule):?>
                            <div class="timetable__column">
                                <div class="timetable__column-head timetable__column-head--current"><?=$day;?></div>
                                <?foreach($arDayShedule as $day => $arTraining):?>
                                    <div class="timetable__column-item">
                                        <div class="timetable__column-item-name"><?=$arTraining['NAME'];?></div>
                                        <div class="timetable__column-item-time"><?=$arTraining['TIME'];?>, <?=$arTraining['COACH'];?></div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        <?endforeach;?>
                    </div>
                <?endforeach;?>
            <?endif;?>
            
            <div class="timetable__table-more"></div>
          </div>
          <div class="timetable__arrows">
            <div class="timetable__arrows-item timetable__arrows-item--left timetable__arrows-item--disabled"></div>
            <div class="timetable__arrows-item timetable__arrows-item--right"></div>
          </div>
            <?if($arResult["SETTINGS"]["PROPERTIES"]["SHEDULE"]["VALUE"]["0"]):?>
                <ul class="timetable__list">
                    <?foreach($arResult["SETTINGS"]["PROPERTIES"]["SHEDULE"]["VALUE"] as $num => $value):?>
                        <li class="timetable__list-item"><?=$value?></li>
                    <?endforeach;?>
                </ul>
            <?endif;?>
            <div class="application__cta">
				<div class="application__cta-text">С нашим приложением вы добьетесь любых поставленных спортивных целей!</div>
				<div class="application__cta-buttons">
					<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_APPSTORE"]["VALUE"] ?>" target="_blank">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/appstore.png' ?>" alt="appstore">
					</a>
					<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"] ?>" target="_blank">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/googleplay.png' ?>" alt="google play">
					</a>
				</div>
			</div>
</div>