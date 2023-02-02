<div class="LK_TRIALWORKOUT" data-componentname="<?=$arResult["COMPONENT_NAME"]?>">
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
    <div class="personal-section-form__item select-item" id="tw_type_select">
        <span class="personal-section-form__item-placeholder">Выберите тип тренировки</span>
        <select class="input input--light input--select" name="tw_type" autocomplete="off" required="required">
            <?if ($arResult["NO_COACH"]):?>
                <option value="free" selected>Самостоятельно</option>
            <?endif;?>
            <option value="coach">С тренером</option>
        </select>
    </div>

    <div class="personal-section-form__item select-item" id="club_select">
        <span class="personal-section-form__item-placeholder">Клуб</span>
        <select class="input input--light input--select" name="club_num" autocomplete="off" required="required">
            <?foreach ($arResult['CLUBS_ARR'] as $CLUB):?>
                <option value="<?=$CLUB["VALUE"]?>" <?if ($_GET["CLUB"]==$CLUB["VALUE"]) echo "selected" ?>><?=$CLUB["STRING"]?></option>
            <?endforeach; ?>
        </select>
    </div>
    <div class="tw-dates__container">
        <div class="tw-dates__date-month"></div>
        <div class="tw-dates__days-container">
            <?
            $index=0;
            foreach($arResult["DAYS"] as $DAY):?>
                <div class="day-item  <?if ($DAY["WEEKEND"]) echo 'weekend '; if ($index==0) echo 'active '?>" data-date="<?=$DAY["DATE"]?>" data-month="<?=$DAY["MONTH"]?>" data-index="<?=$index?>">
                    <span class="tw-day-item__day"><?=$DAY["DAY"]?></span>
                    <span class="tw-day-item__week"><?=$DAY["WEEK"]?></span>
                </div>
                <?
                $index++;
            endforeach;?>
        </div>
        <div class="tw-dates-controllers">
            <div class="tw-dates__controller left"></div>
            <div class="tw-dates__controller right"></div>
        </div>
    </div>
    <div class="tw-days-timetable__container"></div>

    <div class="loading-overlay">
        <div class="escapingBallG-animation">
            <div id="escapingBall_1" class="escapingBallG"></div>
        </div>
    </div>
</div>