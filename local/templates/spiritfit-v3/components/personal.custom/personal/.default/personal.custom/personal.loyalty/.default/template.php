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
?>

<?php if (!empty($arResult["ERROR"])){ echo $arResult["ERROR"]; return; }?>

<script>
    var personalLoyaltyComponent = <?=CUtil::PhpToJSObject($this->getComponent()->getName());?>;
</script>

<div class="personal-loyalty" id="personal-loyalty">
    <?if ($arResult["ISREG"]):?>
    <div class="pl-items">
        <div class="pl-item__block bonuses">
            <div class="pl-item__title">
                <span><?=$arResult["BALANCE"]["NAME"]?></span>
                <?if ($arResult["BALANCE"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["BALANCE"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>
            <div class="pl-item__bonuses-value purple"><?=$arResult["BALANCE"]["VALUE"]?></div>


            <div class="pl-item-block__footer">
                <?if (!empty($arResult["BALANCE"]["PAYMENT"]["NEXT"])):?>
                    <div class="pl-item__payment">
                        <div class="pl-item__payment-item">След. оплата: <?=$arResult["BALANCE"]["PAYMENT"]["NEXT"]?>
                            <?if ($arResult["BALANCE"]["PAYMENT"]["VALUE"]):?>
                                <span class="pl-item__payment-old"><?=(int)$arResult["BALANCE"]["PAYMENT"]["NEXT"] + (int)$arResult["BALANCE"]["PAYMENT"]["VALUE"]?></span>
                            <?endif;?>
                            руб.
                        </div>
                    </div>
                <?endif;?>

                <?if (!empty($arResult["BALANCE"]["PROMOCODE"]["VALUE"])):?>
                    <div class="pl-item__payment">
                        <div class="pl-item__payment-item gradient-text-block promocode-item"
                             data-sum="<?=(int)$arResult["BALANCE"]["PROMOCODE"]["SUM"]?>"
                             data-date="<?=$arResult["BALANCE"]["PROMOCODE"]["DATE"]?>"
                             onclick="copyPromocode('<?=$arResult["BALANCE"]["PROMOCODE"]["VALUE"]?>')"><?=$arResult["BALANCE"]["PROMOCODE"]["VALUE"]?></div>
                    </div>
                <?endif;?>

                <div class="pl-item-bonuses__btns">
                    <button class="button-outline pl-bonuses-btn <?=$arResult["BALANCE"]["PAYMENT"]["STATUS"]?>"
                            data-action="charge"
                            data-message="<?=$arResult["BALANCE"]["PAYMENT"]["STATUS_MESSAGE"]?>">списать</button>
                    <button class="button pl-bonuses-btn <?=$arResult["BALANCE"]["PROMOCODE"]["STATUS"]?>"
                            data-action="present"
                            data-message="<?=$arResult["BALANCE"]["PROMOCODE"]["STATUS_MESSAGE"]?>">подарить</button>
                </div>
            </div>

        </div>



        <div class="pl-item__block visits">
            <div class="pl-item__title">
                <span><?=$arResult["VISITS"]["NAME"]?></span>
                <?if ($arResult["VISITS"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["VISITS"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>
            <?if (!empty($arResult["VISITS"]["LIST"])):?>
                <div class="pl-item__all-visits">Всего: <?=$arResult["VISITS"]["ALL_VISITS"]?></div>
                <div class="pl-item__all-visits-block">
                    <div class="pl-item__controllers">
                        <div class="pl-item__visits-controller left"></div>
                        <div class="pl-item__visits-controller right"></div>
                    </div>
                    <div class="pl-item__all-visits-container">
                        <?$visit_container_index=0;?>
                        <?foreach($arResult["VISITS"]["LIST"] as $key=>$value):?>
                            <div class="pl-visits-count-container" data-index="<?=$visit_container_index?>">
                                <div class="pl-visits-count__month">
                                    <span><?=$value["VALUE"]?></span>
                                    <span class="pl-visits__month-value"><?=$value["MONTH"]?></span>
                                </div>
                                <div class="pl-visits__column">
                                    <div class="pl-visit__occupancy" style="height: <?=$value["HEIGHT"]?>%"></div>
                                </div>
                            </div>
                            <?$visit_container_index++;?>
                        <?endforeach;?>
                    </div>
                </div>
            <?else:?>
                <div class="pl-item__visits-message">
                    Информация по посещениям отсуствует
                </div>
            <?endif;?>
        </div>

        <div class="pl-item__block loyaltyhistory">
            <div class="pl-item__title">
                <span><?=$arResult["HISTORY"]["NAME"]?></span>
                <?if ($arResult["HISTORY"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["HISTORY"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>
            <div class="pl-item__loyaltyhistory">
                <div class="pl-item__lh-table" style="display: none">
                    <table class="table-fixed hidden-phone" id="pl-loyaltyhistory__table">
                        <thead>
                        <tr>
                            <th class="col-date">Дата</th>
                            <th class="col-event">Событие</th>
                            <th class="col-action">Действие</th>
                            <th class="col-count">Баллы</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="pl-loyaltyhistory__mobile visible-phone"></div>
                </div>
                <div class="pl-item__lh-message">
                    <span class="loader-circle pl-item__lh-loading" style="display: none"></span>
                    <button class="button" id="loyaltyhistory_btn">загрузить данные</button>
                </div>
            </div>
        </div>
    </div>
    <?else:?>
        <div class="popup-modal__container" id="pl-reg" style="display: flex">
            <div class="popup__modal">
                <a class="modal__closer" href="/personal/?v=2">
                    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
                </a>
                <div class="popup__modal-content">
                    <div class="popup__modal-title">Регистрация в программе</div>
                    <div class="popup__modal-info">
                        <?=$arResult["MESSAGE"]?>
                    </div>
                    <input type="button" class="button" value="Регистрация" style="width: 100%" id="personal-loyalty-reg-btn">
                    <div class="escapingBallG-animation">
                        <div id="escapingBall_1" class="escapingBallG"></div>
                    </div>
                </div>
                <div class="popup__modal-error" style="display: none">
                    <div class="popup__modal-title">
                        <div class="popup__modal-title-icon">
                            <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/warning-mark.svg')?>
                        </div>
                        <span>Что-то не так</span>
                    </div>
                    <div class="popup__modal-info error-text"></div>
                </div>
            </div>
        </div>
    <?endif;?>
</div>

<?if ($arResult["BALANCE"]["PAYMENT"]["STATUS"]=="unblock"):?>
<div class="popup-modal__container" id="pl-charge">
    <div class="popup__modal">
        <div class="modal__closer" onclick="$('#pl-charge').fadeOut(300)">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
        </div>
        <div class="popup__modal-content">
            <div class="popup__modal-title">Списать мои бонусы</div>
            <div class="popup__modal-info">
                Вы можете потратить не более <b class="red"><?=(int)$arResult["BALANCE"]["PAYMENT"]["LIMIT"]?></b> бонусов в счет следующего списания
            </div>
            <form id="pl-charge__form">
                <div class="personal-input__form-row">
                    <div class="personal-input__input">
                        <span class="personal-input__input-placeholder">Количество бонусов для списания</span>
                        <input class="personal-input__input-value"
                               name="pl_sum_input"
                               type="number"
                               id="pl_sum_input"
                               min="1"
                               max="<?=(int)$arResult["BALANCE"]["PAYMENT"]["LIMIT"]?>"
                               required
                        >
                    </div>
                </div>
                <input type="submit" class="button" value="Списать" style="width: 100%">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
            </form>
        </div>
        <div class="popup__modal-error" style="display: none">
            <div class="popup__modal-title">
                <div class="popup__modal-title-icon">
                    <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/warning-mark.svg')?>
                </div>
                <span>Что-то не так</span>
            </div>
            <div class="popup__modal-info error-text"></div>
        </div>
    </div>
</div>
<?endif;?>

<?if ($arResult["BALANCE"]["PROMOCODE"]["STATUS"]=="unblock"):?>
    <div class="popup-modal__container" id="pl-present">
        <div class="popup__modal">
            <div class="modal__closer" onclick="$('#pl-present').fadeOut(300)">
                <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
            </div>
            <div class="popup__modal-content">
                <div class="popup__modal-title">Подарить скидку другу</div>
                <div class="popup__modal-info">
                    <?if (!empty($arResult["BALANCE"]["PROMOCODE"]["LIMIT"])):?>
                        Вы можете потратить не более <?=(int)$arResult["BALANCE"]["PROMOCODE"]["LIMIT"]?> бонусов в качестве подарка
                    <?else:?>
                        Вы можете оплатить до 100% стоимости абонемента бонусами!
                    <?endif;?>
                </div>
                <form id="pl-present__form">
                    <span class="personal-input__input-placeholder">Номер получателя</span>
                    <div class="personal-input__form-row">
                        <div class="personal-input__input tel-input">
                            <div class="personal_form--tel-prenumber">+7</div>
                            <input class="personal-input__input-value"
                                   name="pl_present_recipient_input"
                                   type="tel"
                                   id="pl_present_recipient_input"
                                   required
                            >
                        </div>
                    </div>

                    <div class="personal-input__form-row">
                        <div class="personal-input__input">
                            <span class="personal-input__input-placeholder">Количество подарочных бонусов</span>
                            <input class="personal-input__input-value"
                                   name="pl_present_sum_input"
                                   type="number"
                                   id="pl_present_sum_input"
                                   required
                            >
                        </div>
                    </div>
                    <input type="submit" class="button" value="Списать" style="width: 100%">
                    <div class="escapingBallG-animation">
                        <div id="escapingBall_1" class="escapingBallG"></div>
                    </div>
                </form>
            </div>
            <div class="popup__modal-error" style="display: none">
                <div class="popup__modal-title">
                    <div class="popup__modal-title-icon">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/warning-mark.svg')?>
                    </div>
                    <span>Что-то не так</span>
                </div>
                <div class="popup__modal-info error-text"></div>
            </div>
        </div>
    </div>
<?endif;?>
