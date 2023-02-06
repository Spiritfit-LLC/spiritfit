<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
    var tw_timetable_component=<?=CUtil::PhpToJSObject($this->getComponent()->getName())?>
</script>

<div class="LK_COACHTRAININGS">
    <div class="tw-request-error__container" onclick="$(this).fadeOut(300)"></div>
    <div class="tw-add-workout__container modal-container">

        <div class="tw-add-workout__form">
            <div class="modal-container__closer" onclick="$('.tw-add-workout__container').fadeOut(300)">
                <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
            </div>
            <div class="tw-add-worout__title">Добавить персональную тренировку</div>
            <div class="tw-add-workout">
                <div class="tw-busy-info__field">
                    <span class="tw-busy-field__placeholder">ФИО клиента</span>
                    <input class="personal-section-form__item-value" type="text" name="client_name" required id="tw-add__client-name">
                </div>
                <input type="button" class="personal-section-form__submit button-outline" value="Добавить" onclick="add_workout(this)">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="tw-busy__container modal-container">
        <div class="tw-busy-workout__modal">
            <div class="modal-container__closer" onclick="$('.tw-busy__container').fadeOut(300)">
                <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
            </div>
            <div class="tw-busy-workout__title" id="tw-busy-workout__title"></div>
            <div class="tw-busy-workout">
                <div class="tw-busy-info__field">
                    <div id="tw-busy-date"></div>
                    <span class="tw-busy-field__val" id="tw-busy-time"></span>

                    <div class="tw-cancel">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/trash-icon.svg')?>
                    </div>
                </div>
                <div class="tw-busy-info__field">
                    <span class="tw-busy-field__placeholder">Клиент</span>
                    <span class="tw-busy-field__val" id="tw-busy-client"></span>
                </div>
            </div>
        </div>
    </div>



    <div class="tw-dates__container">
        <div class="tw-dates__date-month"></div>
        <div class="tw-dates__days-container">
            <?$index=0;
            foreach($arResult["DAYS"] as $DAY):?>
                <div class="day-item  <?if ($DAY["WEEKEND"]) echo 'weekend '; if ($index==0) echo 'active '?>"
                     data-month="<?=$DAY["MONTH"]?>"
                     data-index="<?=$index?>"
                    onclick="get_timetable('<?=$DAY["DATE"]?>'); set_active_date(this, '<?=$DAY["DATE"]?>');"
                >
                    <span class="tw-day-item__day"><?=$DAY["DAY"]?></span>
                    <span class="tw-day-item__week"><?=$DAY["WEEK"]?></span>
                </div>
                <?
                $index++;
            endforeach;?>
        </div>
        <div class="tw-dates-controllers">
            <div class="tw-dates__controller left" onclick="date_controller('left')"></div>
            <div class="tw-dates__controller right" onclick="date_controller('right')"></div>
        </div>
    </div>
    <div class="tw-days-timetable__container"></div>
    <div class="loading-overlay">
        <div class="escapingBallG-animation">
            <div id="escapingBall_1" class="escapingBallG"></div>
        </div>
    </div>
</div>

