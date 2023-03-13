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
$this->setFrameMode(false);
?>
<?if (!empty($arResult["TIMETABLE"]) && count($arResult["TIMETABLE"])>0):?>
    <?$index=0;?>
    <?foreach ($arResult["TIMETABLE"] as $key=>$TIMES):?>
        <div class="pt__timetable-section">
            <div class="pt__timetable-section-title">
                <?if ($key=="0MORNING"){
                    echo 'Утро';
                }
                elseif ($key=="1DAYTIME"){
                    echo 'День';
                }
                elseif($key=="2EVENING"){
                    echo "Вечер";
                }?>
            </div>
            <div class="pt__section-times-container">
                <?foreach ($TIMES as $TIME):?>
                    <input class="pt_datetime-radiobtn time" id="pt-time__radio_<?=$index?>" type="radio" name="time" value="<?=$TIME?>">
                    <label class="datetime-item time" for="pt-time__radio_<?=$index?>"><?=$TIME?></label>
                <?$index++;?>
                <?endforeach;?>
            </div>
        </div>
    <?endforeach;?>
    <?php
    if ($arResult["ACTION"]=="edit"){
        $btn_text="Изменить запись";
    }
    else{
        $btn_text="Создать запись";
    }
    ?>
    <input type="submit" class="button" style="width: 100%" disabled value="<?=$btn_text?>">
<?php else:?>
    <div class="pt__message">
        К сожалению, на эту дату нет доступных слотов. Попробуйте выбрать другую дату или клуб.
    </div>
<?php endif;?>
