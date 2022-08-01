<div class="LK_TRIALWORKOUT" data-type="form">
    <form class="tw_form" data-additional-timetable="<?=$arResult["ADDITIONAL_TIMETABLE"]?>"  data-componentname="<?=$arResult['COMPONENT_NAME']?>">
        <input type="hidden" name="ACTION" value="setSlot">
        <input type="hidden" name="clubid" value="<?=$arResult["CLUB_ID"]?>">
        <input type="hidden" name="date" value="<?=$arResult["DATE"]?>">
        <div class="timepicker-ui">
            <input type="hidden" name="tw_time" class="timepicker-ui-input"/>
        </div>
        <?if (!empty($arResult["TW_ACTION"])):?>
            <input type="hidden" name="tw_action" value="<?=$arResult["TW_ACTION"]?>">
        <?endif;?>
        <div class="form-submit-result-text"></div>
    </form>
</div>