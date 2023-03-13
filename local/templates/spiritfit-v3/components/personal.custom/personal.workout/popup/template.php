<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$this->addExternalJs(SITE_TEMPLATE_PATH . '/js/popup.js');
?>
<script>
    var personalTrainingTemplateName = <?=CUtil::PhpToJSObject($templateName)?>;
    var personalTrainingComponent = <?=CUtil::PhpToJSObject($component->getName())?>;
</script>
<div class="personal-training__content">
    <div class="popup__modal-title">
        <span><?=$arResult["WORKOUT"]["NAME"]?></span>
    </div>

    <?if (!empty($arResult["WORKOUT"]["CLUE"])):?>
    <div class="popup__modal-info">
        <div class="popup__modal-info-text">
            <?=$arResult["CLUE_PREVIEW"]?>
        </div>
        <?if (!empty($arResult["CLUE_DETAIL"])):?>
            <div class="clue-detail-show gradient-text" data-code="<?=$arResult["WORKOUT"]["CODE"]?>">подробнее</div>
        <?endif;?>
    </div>
    <?endif;?>

    <?if (!empty($arResult["WORKOUT"]["VALUE"]["statusid"])):?>
        <div class="personal-training__exist popup__modal-main" id="personal-training__exist">
            <div class="pt__field-row">
                <div class="pt__field half">
                    <div class="pt__field-placeholder">Статус</div>
                    <div class="pt__field-value"><?=$arResult["WORKOUT"]["VALUE"]["status"]?></div>
                </div>
                <div class="pt__field half">
                    <div class="pt__field-placeholder">Клуб</div>
                    <div class="pt__field-value"><?=$arResult["WORKOUT"]["VALUE"]["club"]?></div>
                </div>
            </div>
            <div class="pt__field-row">
                <div class="pt__field half">
                    <div class="pt__field-placeholder">Дата</div>
                    <div class="pt__field-value"><?=$arResult["WORKOUT"]["VALUE"]["date"]?></div>
                </div>
                <div class="pt__field half">
                    <div class="pt__field-placeholder">Время</div>
                    <div class="pt__field-value"><?=$arResult["WORKOUT"]["VALUE"]["time"]?></div>
                </div>
            </div>
            <div class="pt__field-row">
                <div class="pt__field">
                    <div class="pt__field-placeholder">Тренер</div>
                    <div class="pt__field-value"><?=$arResult["WORKOUT"]["VALUE"]["coachname"]?></div>
                </div>
            </div>

            <?
            $STATUS=$arResult["WORKOUT"]["VALUE"]["statusid"];
            if ($STATUS!=4 && $STATUS!=5):?>
                <div class="pt__field-row buttons">
                    <?if ($STATUS==2):?>
                        <button class="pt-btn pt-action-btn" data-event="accept">
                            ✅<span class="gradient-text">Подтвердить</span>
                        </button>
                    <?endif;?>
                    <button class="pt-btn pt-action-btn" data-event="cancel">
                        ❌<span class="gradient-text">Отменить</span>
                    </button>
                </div>
                <button class="button pt-action-btn" data-event="edit" style="width: 100%">изменить</button>
            <?endif;?>
        </div>
    <?endif;?>


    <?if (empty($STATUS) || ($STATUS!=4 && $STATUS!=5)):?>
        <?
        if (empty($STATUS)){
            $ACTION="new";
        }
        else{
            $ACTION="edit";
        }
        ?>

        <form id="pt__form" class="pt__form popup__modal-main" <?if (!empty($STATUS)):?> style="display: none"<?endif;?>>
            <input type="hidden" name="action" value="<?=$ACTION?>">

            <div class="pt__select-club select2-black">
                <div class="pt-select-club-placeholder">
                    <span class="pt-form__placeholder">Выберите клуб</span>
                    <?if (!empty($STATUS)):?>
                        <button class="pt-btn pt-action-btn" data-event="go-back" type="button">
                            <span class="gradient-text">Назад</span>
                        </button>
                    <?endif;?>
                </div>
                <select class="select2" name="club" autocomplete="off" required="required" id="pt-club-select">
                    <?foreach ($arResult['CLUBS_ARR'] as $CLUB):?>
                        <option value="<?=$CLUB["VALUE"]?>" <?if ($CLUB["SELECTED"]) echo "selected";?>><?=$CLUB["STRING"]?></option>
                    <?endforeach; ?>
                </select>
            </div>
            <div class="pt__coach-switch">
                <div class="personal-input__radio-input">
                    <input class="personal-input__input-value input-radio-btn radio-pt-type"
                           name="pt_type"
                           type="radio"
                           value="coach"
                           id="pt_type_btn_1"
                           style="position: absolute; opacity: 0; z-index: -1" checked>
                    <label for="pt_type_btn_1">c тренером</label>
                </div>
<!--                --><?//if ($arResult["NO_COACH"]):?>
                    <div class="personal-input__radio-input">
                        <input class="personal-input__input-value input-radio-btn radio-pt-type"
                               name="pt_type"
                               type="radio"
                               value="free"
                               id="pt_type_btn_2"
                               style="position: absolute; opacity: 0; z-index: -1">
                        <label for="pt_type_btn_2">без тренера</label>
                    </div>
<!--                --><?//endif;?>
            </div>
            <div class="pt__dates">
                <div class="pt-form__placeholder">Выберите дату и время</div>
                <div class="pt__controllers">
                    <div class="pt__date-month"></div>
                    <div class="pt-dates__controllers">
                        <div class="pt-dates__controller left"></div>
                        <div class="pt-dates__controller right"></div>
                    </div>
                </div>
                <div class="pt__dates-container">
                    <?
                    $index=0;
                    foreach($arResult["DAYS"] as $DAY):?>
                        <input type="radio" name="date" value="<?=$DAY["DATE"]?>" <?if ($index==0) echo 'checked'?> class="pt_datetime-radiobtn day" id="pt-date__radio_<?=$index?>">
                        <label class="datetime-item day  <?if ($DAY["WEEKEND"]) echo 'weekend '; if ($index==0) echo 'active '?>" data-month="<?=$DAY["MONTH"]?>" for="pt-date__radio_<?=$index?>" data-index="<?=$index?>">
                            <span class="pt-day-item__day"><?=$DAY["DAY"]?></span>
                            <span class="pt-day-item__week"><?=$DAY["WEEK"]?></span>
                        </label>
                        <?
                        $index++;
                    endforeach;?>
                </div>
                <div class="pt-load-container">
                    <div class="pt-ajax__loader">
                        <span class="loader-circle"></span>
                    </div>
                    <div class="personal-training__timetable"></div>
                </div>
            </div>

        </form>
    <?endif;?>
</div>

