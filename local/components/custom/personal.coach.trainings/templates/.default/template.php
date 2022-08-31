<div class="LK_COACHTRAININGS" data-componentname="<?=$arResult["COMPONENT_NAME"]?>">
    <div class="error-modal">
        <div class="error-modal__message"></div>
    </div>
    <div class="add-workout__form">
        <div class="personal-section-form__item">
            <div class="add-workout__input">
                <span class="personal-section-form__item-placeholder">Клиент</span>
                <input class="personal-section-form__item-value"
                        type="text"
                        name="add-workout__client"
                        required>
            </div>
        </div>
        <input type="button" class="personal-section-form__submit button-outline" value="добавить тренировку">
        <div class="escapingBallG-animation">
            <div id="escapingBall_1" class="escapingBallG"></div>
        </div>
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