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
use \Bitrix\Main\Page\Asset;

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');
$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/nouislider/nouislider.min.js');
$this->addExternalCss(SITE_TEMPLATE_PATH . '/vendor/nouislider/nouislider.min.css');
?>

<script>
    var personalServicesComponent = <?=CUtil::PhpToJSObject($this->getComponent()->getName());?>;
    var personaServiceTemplateFolder = <?=CUtil::PhpToJSObject(\Bitrix\Main\Component\ParameterSigner::signParameters($component->getName(), $templateFolder))?>;
</script>
<div class="personal-services">
    <? if (!empty($arResult["SUBS"]["LIST"]) && count($arResult["SUBS"]["LIST"])>0):?>
    <div class="personal-services__block-item subscriptions">
        <div class="personal-services__item-header">
            <div class="personal-services__item-title">
                <span><?=$arResult["SUBS"]["NAME"]?></span>
                <?if ($arResult["SUBS"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["SUBS"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
                </div>
            <div class="personal-services__item-count">подключено <span class="black"><?=$arResult["SUBS"]["ACTIVE_COUNT"]?>/<?=$arResult["SUBS"]["ALL_COUNT"]?></span></div>
        </div>

        <div class="personal-services__items">
            <?foreach ($arResult["SUBS"]["LIST"] as $item):?>
                <div class="personal-services__item">
                    <div class="personal-service__title"><?=$item["name"]?></div>
                    <div class="personal-service__switch">
                        <?if ($item["status"]=="block_on" || $item["status"]=="block_off"):?>
                            <span class="switch-lock__icon">
                                <?=file_get_contents(__DIR__.'/images/lock-icon.svg')?>
                            </span>
                        <?endif;?>
                        <label class="switch">
                            <input
                                    type="checkbox"
                                    class="switch personal-service__switch-checkbox subscription"
                                    <?if ($item["status"]=="on" || $item["status"]=="block_on") echo "checked";?>
                                    <?if ($item["status"]=="block_on" || $item["status"]=="block_off") echo "disabled";?>
                                    data-id="<?=$item["id"]?>"
                                    data-type="<?=$item["type"]?>"
                            >
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <?endif;?>

    <? if (!empty($arResult["SERVICES"]["LIST"]) && count($arResult["SERVICES"]["LIST"])>0):?>
    <div class="personal-services__block-item services">
        <div class="personal-services__item-header">
            <div class="personal-services__item-title">
                <span><?=$arResult["SERVICES"]["NAME"]?></span>
                <?if ($arResult["SERVICES"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["SERVICES"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>
            <div class="personal-services__item-count">включено <span class="black"><?=$arResult["SERVICES"]["ACTIVE_COUNT"]?>/<?=$arResult["SERVICES"]["ALL_COUNT"]?></span></div>
        </div>
        <div class="personal-services__items">
            <?foreach ($arResult["SERVICES"]["LIST"] as $item):?>
                <div class="personal-services__item">
                    <div class="personal-service__title"><?=$item["name"]?></div>
                    <div class="personal-service__switch">
                        <?if ($item["status"]=="block_on" || $item["status"]=="block_off"):?>
                            <span class="switch-lock__icon">
                                <?=file_get_contents(__DIR__.'/images/lock-icon.svg')?>
                            </span>
                        <?endif;?>
                        <label class="switch">
                            <input
                                    type="checkbox"
                                    class="switch personal-service__switch-checkbox service"
                                    <?if ($item["status"]=="on" || $item["status"]=="block_on") echo "checked";?>
                                    <?if ($item["status"]=="block_on" || $item["status"]=="block_off") echo "disabled";?>
                                    data-type="<?=$item["type"]?>"
                            >
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <?endif;?>
</div>

<? if (!empty($arResult["SUBS"]["LIST"])):?>
<div class="popup-modal__container" id="ps-modal">
    <div class="popup__modal">
        <div class="modal__closer" onclick="$('#ps-modal').fadeOut(300)">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
        </div>
        <div class="popup__modal-content">
            <div class="popup__modal-title">Управление подпиской</div>
            <div class="popup__modal-info"></div>

            <div class="personal-service__submit">
                <input type="submit" class="button" value="Подтвердить" style="width: 100%">
                <div class="escapingBallG-animation">
                    <div id="escapingBall_1" class="escapingBallG"></div>
                </div>
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

<? if (!empty($arResult["SERVICES"]["LIST"])):?>
    <div class="popup-modal__container" id="ps-modal-service">
        <div class="popup__modal">
            <div class="modal__closer" onclick="$('#ps-modal-service').fadeOut(300)">
                <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
            </div>
            <div class="popup__modal-content"></div>
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

<div class="popup-modal__container" id="popup-bonuses__container">
    <div class="popup__modal">
        <div class="modal__closer" onclick="$('#popup-bonuses__container').fadeOut(300);">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
        </div>
        <div class="popup__modal-content">
            <div class="popup__modal-title">Потратить баллы лояльности?</div>
            <div class="popup__modal-info">На эту покупку получится потратить до <span id="bonuses-count"></span> баллов.<br>😍 1 бонус = 1 рубль.</div>

            <div style="font-size: 16px;display: block;margin-left: auto;width: max-content;margin-bottom: 10px;">-<span class="bonuses-sale"></span>₽</div>
            <div class="bonuses-modal__slider">
                <div id="bonuses-slider"></div>
            </div>
            <div class="total-price-block">
                <div class="total-price__text">Итого к оплате:</div>
                <div><span class="current_price"></span>₽</div>
            </div>
            <form id="recalc-form">
                <input type="hidden" name="bonusses" value="0" id="bonus-field">
                <input type="hidden" name="invoice" value="" id="invoice-id-input">
                <div class="personal-service__submit">
                    <input type="submit" class="button" value="Оплатить покупку" style="width: 100%">
                    <div class="escapingBallG-animation">
                        <div id="escapingBall_1" class="escapingBallG"></div>
                    </div>
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


