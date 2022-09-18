<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$ELEMENT=$arResult['ELEMENT'];
?>


<div class="subscription fixed">
    <div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-step="1">1. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-step="2">2. Оформление</div>
            <div class="subscription__stage-item" data-step="3">3. Оплата</div>

        </div>
        <div class="subscription__common">
            <h1 class="subscription__title"><?=$arResult['PAGE_TYPE']?> «<?=$ELEMENT['name']?>»</h1>
            <div class="subscription__desc"><?=$ELEMENT['description']?></div>

            <div class="subscription__label-prices-block">
                <div class="subscription__label">
                    <?if (count($ELEMENT['prices'])>1):?>
                        <? foreach ($ELEMENT['prices'] as $arPrice):?>
                            <div class="subscription__label-item" data-month="<?=$arPrice['number']?>">
                                <?=$arResult['PRICE_SIGNS'][$arPrice['number']]?> - <span class="price-value"><?=$arPrice['price']?></span> руб.
                            </div>
                        <?endforeach;?>
                    <?else:?>
                        <div class="subscription__label-item" data-month="<?=$ELEMENT['prices'][0]['number']?>">
                            Цена - <span class="price-value"><?=$ELEMENT['prices'][0]['price']?></span> руб.
                        </div>
                    <?endif;?>

                </div>
            </div>
            <div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
                <div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
            </div>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
            <form class="get-abonement order">
                <div class="form-inputs">
                    <?foreach($arResult['US'] as $ROW):?>
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
                                <?if (!empty($arResult['BASE_PRICE'])):?>
                                <div class="subscription__total-value-old">
                                    <span class="old-price"><?=$arResult['BASE_PRICE']?></span> <span>&#x20bd;</span>
                                </div>
                                <?endif;?>
                                <span class="current_price"><?=$arResult['CURRENT_PRICE']?></span> &#x20bd;
                            </div>
                        </div>
                    </div>
                    <input class="get-abonement-agree subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="Оплатить">
                </div>
            </form>
        </div>
    </div>
</div>


<div id="message-modal">
    <div class="message-modal__content">
    </div>
</div>

<script>
    var componentName = <?=CUtil::PhpToJSObject($arResult['COMPONENT_NAME'])?>;
    var w_publicID=<?=CUtil::PhpToJSObject($arResult['W_PUBLICID'])?>;
    var w_description=<?=CUtil::PhpToJSObject($arResult['W_DESCRIPTION'])?>;
    var w_amount=parseFloat(<?=CUtil::PhpToJSObject($arResult['W_AMOUNT'])?>);
    var w_currency=<?=CUtil::PhpToJSObject($arResult['W_CURRENCY'])?>;
    var w_userID=<?=CUtil::PhpToJSObject($arResult['W_USERID'])?>;
    var w_invoiceID=<?=CUtil::PhpToJSObject($arResult['W_INVOICEID'])?>;
    var w_email=<?=CUtil::PhpToJSObject($arResult['W_EMAIL'])?>;
    var w_emailRequire=<?=CUtil::PhpToJSObject($arResult['W_EMAILREQUIRE'])?>;
    var w_data=<?=CUtil::PhpToJSObject($arResult['W_DATA'])?>;

    <?if($arResult['TYPE']=='subscription'):?>
    var succes_pay_desc='Ваш абонемент успешно оплачен!'
    <?elseif($arResult['TYPE']=='service'):?>
    var succes_pay_desc='Ваша услуга успешно оплачена!'
    <?endif;?>
</script>