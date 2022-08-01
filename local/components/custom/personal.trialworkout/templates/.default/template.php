<div class="LK_TRIALWORKOUT" data-type="modal" data-header="Выберите слот" >
    <form class="tw_form" data-additional-timetable="<?=$arResult["ADDITIONAL_TIMETABLE"]?>" data-componentname="<?=$arResult['COMPONENT_NAME']?>">
        <input type="hidden" name="ACTION" value="setSlot">
        <input type="hidden" name="clubid" value="<?=$arResult["CLUB_ID"]?>">
        <input type="hidden" name="date" value="<?=$arResult["DATE"]?>">
        <input type="hidden" name="tw_time">

        <?if (!empty($arResult["TW_ACTION"])):?>
        <input type="hidden" name="tw_action" value="<?=$arResult["TW_ACTION"]?>">
        <?endif;?>

        <div class="tw_time tw_row">
            <span class="tw_select_placeholder">Время</span>
            <div class="tw_select_input">
                <div style="width: 30%; margin-right: 20px; display: inline-flex;">
                    <select name="tw_hour" class="hour-select"> </select>
                    <select name="tw_minute" class="minute-select"></select>
                </div>
                <a href="#choose_my_time" style="font-size: 16px;font-weight: 700;">Указать свое время</a>
            </div>

        </div>

        <div class="tw_coach tw_row">
            <span class="tw_select_placeholder">Тренер</span>
            <div class="tw_select_input">
                <select name="tw_coach" class="name-select"></select>
            </div>
        </div>
        <input type="submit" value="занять слот" class="button-outline tw_form_submit">
        <div class="escapingBallG-animation">
            <div id="escapingBall_1" class="escapingBallG"></div>
        </div>
        <div class="form-submit-result-text"></div>
    </form>
</div>