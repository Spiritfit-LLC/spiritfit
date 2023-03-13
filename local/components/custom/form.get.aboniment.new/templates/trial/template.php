<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$CLUB=$arResult['CLUB'];
$FORM_FIELDS=$arResult['FORM_FIELDS']['FIELDS'];
$ELEMENT=$arResult["ELEMENT"];

if($FORM_FIELDS['club']["TYPE"]=="SELECT"){
    if(!empty($arResult['SELECTED_CLUB'])) {
        $clubName = strip_tags($arResult['SELECTED_CLUB']);
    } else {
        $clubName = '-';
    }
}
else{
    $clubName="Онлайн";
}

$abonementName=$arResult['ELEMENT']['PROPERTIES']['CODE_ABONEMENT']['VALUE'];
?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode([
        'signedParameters'=>$this->getComponent()->getSignedParameters(),
        'componentName'=>$this->getComponent()->getName(),
        'step'=>'LEGALINFO',
        'action'=>'getTrial',
        'datalayerLabel'=>$clubName."/".$abonementName
    ])?>;
</script>
<div class="subscription fixed">
    <div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-step="1">1. Регистрация</div>
            <div class="subscription__stage-item" data-step="2">2. Оформление</div>
            <div class="subscription__stage-item" data-step="3">3. Оплата</div>
        </div>
        <div class="subscription__common">
            <h1 class="subscription__title"><?=$ELEMENT["~NAME"] ?></h1>
            <div class="subscription__desc"><?=$ELEMENT["~PREVIEW_TEXT"] ?></div>

            <div class="subscription__label-prices-block">
                <? if(!empty($CLUB)):?>
                    <div class="subscription__label">
                        <? foreach ($CLUB['PRICE'] as $key => $arPrice):?>
                            <? if(intval($key) == 99 ) continue; ?>
                            <div class="subscription__label-item" data-month="<?=$key?>">
                                <?=$arPrice['SIGN']?> - <span class="price-value"><?=$arPrice['VALUE']?></span> руб.
                            </div>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>

            <div class="services-block" <? if(empty($CLUB['SERVICES'])){?>style="display: none" <?}?>>
                <? if(!empty($CLUB['SERVICES'])):?>
                    <div class="subscription__subheading">Услуги в подарок:</div>
                    <ul class="subscription__gift">
                        <? foreach($CLUB['SERVICES'] as $value):?>
                            <li class="subscription__gift-item"><?=$value?></li>
                        <?endforeach;?>
                    </ul>
                <?endif;?>
            </div>

            <?if(!empty($ELEMENT["PROPERTIES"]["INCLUDE"]["VALUE"])):?>
                <div class="subscription__subheading">Включено в абонемент:</div>
                <ul class="subscription__include">
                    <?foreach($ELEMENT["PROPERTIES"]["INCLUDE"]["VALUE"] as $value):?>
                        <li class="subscription__include-item"><?=htmlspecialcharsback($value)?></li>
                    <?endforeach;?>
                </ul>
            <?endif;?>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
            <form class="get-abonement" id="get-abonement-form">
                <?printGaFormInputs();?>
                <?foreach ($FORM_FIELDS as $key=>$FIELD):?>
                    <?if ($FIELD['TYPE']=='hidden'):?>
                        <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                        <? continue; endif; ?>
                <?endforeach;?>

                <div id="form-inputs-1" class="form-inputs">
                    <?foreach ($FORM_FIELDS as $key=>$FORM_FIELD):?>
                        <div class="subscription__aside-form-row <?=$FORM_FIELD["CLASSNAME"]?>">
                            <?if ($FORM_FIELD["TYPE"]=="SELECT"):?>
                                <select class="input input--light input--select"
                                        name="<?=$FORM_FIELD['NAME']?>"
                                        autocomplete="off"
                                    <?if ($FORM_FIELD['REQUIRED']) echo 'required';?>
                                        <?if ($FORM_FIELD["ID"]):?>id="<?=$FORM_FIELD["ID"]?>"<?endif?>>
                                    <option value="off" disabled selected><?=$FORM_FIELD["PLACEHOLDER"]?></option>
                                    <? foreach ($FORM_FIELD['ITEMS'] as $ITEM):?>
                                        <option value="<?=$ITEM["VALUE"]?>"
                                            <?if ($ITEM['SELECTED']) echo 'selected';?>
                                                data-club_num="<?=$ITEM['NUMBER']?>"><?=$ITEM["STRING"]?></option>
                                    <? endforeach; ?>
                                </select>
                            <?elseif ($FORM_FIELD["TYPE"]=="checkbox"):?>
                                <label class="input-label">
                                    <input class="input input--checkbox"
                                           type="<?=$FORM_FIELD['TYPE']?>"
                                           name="<?=$FORM_FIELD['NAME']?>[]"
                                           value="<?=$FORM_FIELD['VALUE']?>"
                                        <?if ($FORM_FIELD['REQUIRED']) echo 'required';?>
                                        <?=$FORM_FIELD['PARAMS']?>
                                           <?if ($FORM_FIELD["ID"]):?>id="<?=$FORM_FIELD["ID"]?>"<?endif?>>
                                    <div class="input-label__text"><?=$FORM_FIELD['PLACEHOLDER']?></div>
                                </label>
                            <?else:?>
                                <input class="input input--light input--text"
                                       type="<?=$FORM_FIELD['TYPE']?>"
                                       placeholder="<?=$FORM_FIELD['PLACEHOLDER']?>"
                                       value="<?=$FORM_FIELD['VALUE']?>"
                                       name="<?=$FORM_FIELD['NAME']?>"
                                    <?if ($FORM_FIELD['REQUIRED']) echo 'required';?>
                                    <?if (!empty($FORM_FIELD['VALIDATOR'])) echo $FORM_FIELD['VALIDATOR'];?>
                                    <?=$FORM_FIELD['PARAMS']?>
                                       <?if ($FORM_FIELD["ID"]):?>id="<?=$FORM_FIELD["ID"]?>"<?endif?>>
                                <?if ($key=="promocode"):?>
                                    <button class="form-field-btn is-hide" id="promocode-apply-btn" type="button">Применить</button>
                                <?endif;?>
                            <?endif;?>
                        </div>
                    <?endforeach;?>
                </div>
                <div id="form-inputs-2" class="form-inputs is-hide" style=" text-align: center">
                    <div class="subscription__aside-form-row long-row" style="margin-bottom: 0!important;">
                        <div class="form-row__placeholder">
                            <span class="sms-code-sent__text">Код отправлен на номер</span>
                            <span class="sms-code-sent__tel"></span>
                        </div>
                        <input class="input input--light input--text"
                               type="text"
                               name="sms_code_field"
                               id="sms-code-field">
                    </div>
                    <button id="resend-btn" type="button">Отправить код повторно</button>
                </div>
                <div class="subscribtion__bottom-block" <?if (empty($CLUB)){?> style="display: none"<?}?>>
                    <div class="subscription__bottom">
                        <div class="subscription__total">
                            <div class="subscription__total-text">ИТОГО К ОПЛАТЕ</div>
                            <div class="subscription__total-value">
                                <div class="subscription__total-value-old"  <? if(empty($CLUB["BASE_PRICE"])){?> style="display: none" <?}?>>
                                    <span class="old-price"><?=$CLUB["BASE_PRICE"]?></span> <span>&#x20bd;</span>
                                </div>
                                <span class="current_price"><?=$CLUB['CURRENT_PRICE']?></span> &#x20bd;
                            </div>
                            <?if(!empty($ELEMENT["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"])):?>
                                <div class="subscription__total-subtext"><?=$ELEMENT["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]?></div>
                            <?endif;?>
                        </div>
                    </div>
                    <input class="get-abonement-agree subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?=$arResult['FORM']["arForm"]["BUTTON"]?>" id="get-abonement-btn">
                    <div class="escapingBallG-animation white">
                        <div id="escapingBall_1" class="escapingBallG"></div>
                    </div>
                </div>

                <div class="popup popup--legal-information" id="legalinfo-popup">
                    <div class="popup__bg"></div>
                    <div class="popup__window">
                        <div class="modal__closer" onclick="close_legal()">
                            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
                        </div>
                        <div class="popup__wrapper">
                            <div class="popup__heading">Юридическая информация</div>
                            <div class="popup__legal-information-wrapper">
                                <div class="popup__legal-information">
                                    <?=$arResult["OFERTA_TEXT"]?>
                                </div>
                            </div>
                            <div class="popup__bottom">
                                <div class="popup__privacy-policy">
                                    <label class="input-label">
                                        <input class="input input--checkbox"
                                               type="checkbox"
                                               name="legalinfo"
                                               id="legalinfo-field">
                                        <div class="input-label__text">C условиями Оферты ознакомлен</div>
                                    </label>
                                </div>
                                <input class="popup__btn btn subscription__total-btn" type="submit" value="Согласен">
                                <div class="escapingBallG-animation orange">
                                    <div id="escapingBall_1" class="escapingBallG"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
