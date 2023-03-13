<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/nouislider/nouislider.min.js');
$this->addExternalCss(SITE_TEMPLATE_PATH . '/vendor/nouislider/nouislider.min.css');

$ELEMENT=$arResult['ELEMENT'];
?>

<script>
    var params=<?=\Bitrix\Main\Web\Json::encode([
        'signedParameters'=>$this->getComponent()->getSignedParameters(),
        'componentName'=>$this->getComponent()->getName(),
        'model'=>$arResult["MODEL"],
        'bonuses'=>$arResult["BONUSES"]
    ])?>;
</script>

<div class="subscription fixed">
    <div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-step="1">1. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-step="2">2. Оформление</div>
            <div class="subscription__stage-item" data-step="3">3. Оплата</div>

        </div>
        <div class="subscription__common">
            <h1 class="subscription__title"><?=htmlspecialcharsback($ELEMENT['name'])?></h1>
            <div class="subscription__desc"><?=htmlspecialcharsback($ELEMENT['description'])?></div>

            <div class="subscription__label-prices-block">
                <div class="subscription__label">
                    <? foreach ($ELEMENT['prices'] as $arPrice):?>
                        <div class="subscription__label-item" data-month="<?=$arPrice['number']?>">
                            <?=$arPrice["longed"]?> - <span class="price-value"><?=$arPrice['price']?></span> руб.
                        </div>
                    <?endforeach;?>
                </div>
            </div>
            <? if(!empty($arResult['SERVICES'])):?>
                <div class="subscription__subheading">Услуги в подарок:</div>
                <ul class="subscription__gift">
                    <? foreach($arResult['SERVICES'] as $value):?>
                        <li class="subscription__gift-item"><?=$value?></li>
                    <?endforeach;?>
                </ul>
            <?endif;?>
            <?if(!empty($arResult["INCLUDE"]["VALUE"])):?>
                <div class="subscription__subheading">Включено в абонемент:</div>
                <ul class="subscription__include">
                    <?foreach($arResult["INCLUDE"]["VALUE"] as $value):?>
                        <li class="subscription__include-item"><?=htmlspecialcharsback($value)?></li>
                    <?endforeach;?>
                </ul>
            <?endif;?>
            <div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
                <div class="subscription__info-text">Для оплаты мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
            </div>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
            <form class="get-abonement order">
                <div class="form-inputs">
                    <?foreach($arResult['FIELDS'] as $ROW):?>
                        <?if(!empty($ROW['VALUE'])):?>
                            <div class="form-inputs__row">
                                <span class="form-inputs__row-placeholder"><?=$ROW['NAME']?></span>
                                <span class="form-inputs__value"><?=$ROW['VALUE']?></span>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>

                <div class="subscribtion__bottom-block">
                    <div class="subscription__bottom">
                        <div class="subscription__total">
                            <div class="subscription__total-text">ИТОГО К ОПЛАТЕ</div>
                            <div class="subscription__total-value">
                                <?if (!empty($arResult['CURRENT_PRICE_BASE'])):?>
                                    <div class="subscription__total-value-old">
                                        <span class="old-price"><?=$arResult['CURRENT_PRICE_BASE']?></span> <span>&#x20bd;</span>
                                    </div>
                                <?endif;?>
                                <span class="current_price"><?=$arResult['CURRENT_PRICE']?></span> &#x20bd;
                            </div>
                        </div>
                    </div>
                    <input class="get-abonement-agree subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="Оплатить">
                </div>
                <?if (!empty($arResult["BONUSES"])):?>
                    <div class="popup-bonuses__container is-hide popup-container" id="popup-bonuses__container">
                        <div class="popup__modal">
                            <div class="modal__closer" onclick="closeBonuses();">
                                <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
                            </div>
                            <div class="bonuses-modal__title">
                                Потратить баллы лояльности?
                            </div>
                            <div class="bonuses-modal__text">
                                На эту покупку получится потратить до <span id="bonuses-count"></span> баллов.<br>😍 1 бонус = 1 рубль.
                            </div>
                            <div style="font-size: 16px;display: block;margin-left: auto;width: max-content;margin-bottom: 10px;">-<span class="bonuses-sale"></span>₽</div>
                            <div class="bonuses-modal__slider">
                                <div id="bonuses-slider"></div>
                            </div>
                            <div class="total-price-block">
                                <div class="total-price__text">Итого к оплате:</div>
                                <div><span class="current_price"></span>₽</div>
                            </div>
                            <input type="hidden" name="bonuses" value="0" id="bonus-field">
                            <input type="submit" class="button-outline" value="Оплатить покупку" style="width: 100%;margin-top: 20px;">
                        </div>
                    </div>
                <?endif;?>

                <div class="popup-container is-hide" id="ajax-message__container">
                    <div class="popup__modal">
                        <div class="modal__closer" onclick="$('#ajax-message__container').fadeOut(300)">
                            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
                        </div>
                        <div id="modal__text">

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>