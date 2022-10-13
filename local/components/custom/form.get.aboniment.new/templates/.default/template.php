<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
$arInfoProps = Utils::getInfo()['PROPERTIES'];


if($arResult["ELEMENT"]["PREVIEW_PICTURE"]) {
    $ogImage = CFile::GetPath($arResult["ELEMENT"]["PREVIEW_PICTURE"]);
} else {
    $ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
}
$APPLICATION->AddViewContent('inhead', $ogImage);


if( !isset($arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"]) ) {
    $arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"] = 0;
}
if( !isset($arResult["ELEMENT"]["PRICES"][0]["PRICE"]) ) {
    $arResult["ELEMENT"]["PRICES"][0]["PRICE"] = 0;
}




$CLUB=$arResult['CLUB'];
$FORM_FIELDS=$arResult['FORM_FIELDS']['FIELDS'];
$ELEMENT=$arResult["ELEMENT"];

//send name of club and abonement
$abonementName=$arResult['ELEMENT']['PROPERTIES']['CODE_ABONEMENT']['VALUE'];
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
?>


<div class="subscription fixed" data-abonementname="<?=strip_tags($abonementName)?>">
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
                        <li class="subscription__include-item"><?=$value?></li>
                    <?endforeach;?>
                </ul>
            <?endif;?>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
            <form class="get-abonement">
                <?=getClientParams($arResult['WEB_FORM_ID']);?>


                <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult['WEB_FORM_ID']?>">
                <input type="hidden" name="SUB_CODE" value="<?=$arResult['ELEMENT_CODE']?>">
                <input type="hidden" name="ACTION" value="<?=$arResult['ACTION']?>">
                <input type="hidden" name="STEP" value="LEGALINFO">
                <input type="hidden" name="FORM_TYPE" value="<?=$arResult['FORM_TYPE']?>">


                <?foreach ($FORM_FIELDS as $key=>$FIELD):?>
                    <?if ($FIELD['TYPE']=='hidden'):?>
                        <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                        <?
                        continue;
                    endif;
                    ?>
                <?endforeach;?>

                <div class="form-inputs">
                    <?if ($FORM_FIELDS['club']["TYPE"]=="SELECT"):?>
                        <div class="subscription__aside-form-row">
                            <span class="subscription__total-text">Выберите клуб</span>
                        </div>
                        <div class="subscription__aside-form-row">
                            <select class="input input--light input--long input--select get-abonement-club"
                                    name="<?=$FORM_FIELDS['club']['NAME']?>"
                                    autocomplete="off"
                                <?if ($FORM_FIELDS['club']['REQUIRED']) echo 'required';?>>
                                <option value="off" disabled selected>-</option>
                                <? foreach ($FORM_FIELDS['club']['ITEMS'] as $club):?>
                                    <option value="<?=$club["VALUE"]?>"
                                        <?if ($club['SELECTED']) echo 'selected';?>
                                            data-club_num="<?=$club['NUMBER']?>"><?=$club["STRING"]?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    <?else:?>
                        <input type="hidden" name="<?=$FORM_FIELDS['club']['NAME']?>" value="<?=$FORM_FIELDS['club']["VALUE"]?>" class="get-abonement-club">
                    <?endif;?>
                    <?if (!empty($arResult["LEADERS"])):?>
                        <div class="subscription__aside-form-row">
                            <span class="subscription__total-text">Выберите тренера</span>
                        </div>
                        <div class="subscription__aside-form-row">
                            <select class="input input--light input--long input--select get-abonement-leader"
                                    name="<?=$arResult["LEADERS"]['NAME']?>"
                                    autocomplete="off"
                                <?if ($arResult["LEADERS"]['REQUIRED']) echo 'required';?>>
                                <option value="off" disabled selected>-</option>
                                <? foreach ($arResult["LEADERS"]['ITEMS'] as $leader):?>
                                    <option value="<?=$leader["VALUE"]?>"
                                        <?if ($leader['SELECTED']) echo 'selected';?>><?=$leader["STRING"]?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    <?endif?>
                    <div class="subscription__aside-form-row">
                        <div>
                            <input class="input input--light input--short input--text"
                                   type="<?=$FORM_FIELDS['name']['TYPE']?>"
                                   placeholder="<?=$FORM_FIELDS['name']['PLACEHOLDER']?>"
                                   value="<?=$FORM_FIELDS['name']['VALUE']?>"
                                   name="<?=$FORM_FIELDS['name']['NAME']?>"
                                <?if ($FORM_FIELDS['name']['REQUIRED']) echo 'required';?>
                                <?if (!empty($FORM_FIELDS['name']['VALIDATOR'])) echo $FORM_FIELDS['name']['VALIDATOR'];?>
                                <?=$FORM_FIELDS['name']['PARAMS']?>>
                        </div>
                        <div>
                            <input class="input input--light input--short input--text"
                                   type="<?=$FORM_FIELDS['surname']['TYPE']?>"
                                   placeholder="<?=$FORM_FIELDS['surname']['PLACEHOLDER']?>"
                                   value="<?=$FORM_FIELDS['surname']['VALUE']?>"
                                   name="<?=$FORM_FIELDS['surname']['NAME']?>"
                                <?if ($FORM_FIELDS['surname']['REQUIRED']) echo 'required';?>
                                <?if (!empty($FORM_FIELDS['surname']['VALIDATOR'])) echo $FORM_FIELDS['surname']['VALIDATOR'];?>
                                <?=$FORM_FIELDS['surname']['PARAMS']?>>
                        </div>
                    </div>
                    <div class="subscription__aside-form-row">
                        <div>
                            <input class="input input--light input--short input--text"
                                   type="<?=$FORM_FIELDS['phone']['TYPE']?>"
                                   placeholder="<?=$FORM_FIELDS['phone']['PLACEHOLDER']?>"
                                   value="<?=$FORM_FIELDS['phone']['VALUE']?>"
                                   name="<?=$FORM_FIELDS['phone']['NAME']?>"
                                <?if ($FORM_FIELDS['phone']['REQUIRED']) echo 'required';?>
                                <?if (!empty($FORM_FIELDS['phone']['VALIDATOR'])) echo $FORM_FIELDS['phone']['VALIDATOR'];?>
                                <?=$FORM_FIELDS['phone']['PARAMS']?>>
                        </div>
                        <div>
                            <input class="input input--light input--short input--text"
                                   type="<?=$FORM_FIELDS['email']['TYPE']?>"
                                   placeholder="<?=$FORM_FIELDS['email']['PLACEHOLDER']?>"
                                   value="<?=$FORM_FIELDS['email']['VALUE']?>"
                                   name="<?=$FORM_FIELDS['email']['NAME']?>"
                                <?if ($FORM_FIELDS['email']['REQUIRED']) echo 'required';?>
                                <?if (!empty($FORM_FIELDS['email']['VALIDATOR'])) echo $FORM_FIELDS['email']['VALIDATOR'];?>
                                <?=$FORM_FIELDS['email']['PARAMS']?>>
                        </div>
                    </div>
                    <div class="subscription__promo subscription__aside-form-row">
                        <input class="input input--light input--short input--text"
                               id="pormocode-input"
                               type="<?=$FORM_FIELDS['promocode']['TYPE']?>"
                               placeholder="<?=$FORM_FIELDS['promocode']['PLACEHOLDER']?>"
                               <?
                               if (!empty($FORM_FIELDS['promocode']['VALUE'])){
                                   $PROMOCODE_VALUE=$FORM_FIELDS['promocode']['VALUE'];
                               }
                               elseif (!empty($_COOKIE["bn_promocode"]) && !boolval($ELEMENT["PROPERTIES"]["ONLINE"]["VALUE"])){
                                   $PROMOCODE_VALUE=$_COOKIE["bn_promocode"];
                               }
                               else{
                                   $PROMOCODE_VALUE=null;
                               }
                               ?>
                               value="<?=$PROMOCODE_VALUE?>"
                               name="<?=$FORM_FIELDS['promocode']['NAME']?>"
                            <?if ($FORM_FIELDS['promocode']['REQUIRED']) echo 'required';?>
                            <?if (!empty($FORM_FIELDS['promocode']['VALIDATOR'])) echo $FORM_FIELDS['promocode']['VALIDATOR'];?>
                            <?=$FORM_FIELDS['promocode']['PARAMS']?>>
                        <a class="get-abonement-promo" href="#apply">Применить</a>
                    </div>
                    <div class="subscription__code-new subscription__aside-form-row" style="display: none">

                        <input class="input input--light input--short input--text"
                               id="smscode-input"
                               type="text"
                               placeholder="Код из СМС"
                               name="sms-code">
                        <a class="get-abonement-code" href="#resend">Отправить еще раз</a>
                        <div class="code-message">
                            Для продолжения введите СМС код, который мы вам отправили.
                        </div>
                    </div>
                    <div class="subscription__bonuses subscription__aside-form-row" style="display: none">
                        <div class="bonusessum-placeholder">Допустимое количество бонусов: <span class="bonusessum-value"></span></div>
                        <input class="input input--light input--short input--text"
                               id="bonuses-input"
                               type="text"
                               placeholder="Бонусы"
                               name="bonuses"
                               oninput="this.value = this.value.replace (/[^0-9]/, '')">
                    </div>
                </div>


                <div class="form-checkboxes">
                    <div class="subscription__aside-form-row">
                        <label class="input-label">
                            <input class="input input--checkbox"
                                   type="<?=$FORM_FIELDS['personaldata']['TYPE']?>"
                                   name="<?=$FORM_FIELDS['personaldata']['NAME']?>[]"
                                   value="<?=$FORM_FIELDS['personaldata']['VALUE']?>"
                                <?if ($FORM_FIELDS['personaldata']['REQUIRED']) echo 'required';?>
                                <?=$FORM_FIELDS['personaldata']['PARAMS']?>>
                            <div class="input-label__text"><?=$FORM_FIELDS['personaldata']['PLACEHOLDER']?></div>
                        </label>
                    </div>

                    <div class="subscription__aside-form-row">
                        <label class="input-label">
                            <input class="input input--checkbox"
                                   type="<?=$FORM_FIELDS['rules']['TYPE']?>"
                                   name="<?=$FORM_FIELDS['rules']['NAME']?>[]"
                                   value="<?=$FORM_FIELDS['rules']['VALUE']?>"
                                <?if ($FORM_FIELDS['rules']['REQUIRED']) echo 'required';?>
                                <?=$FORM_FIELDS['rules']['PARAMS']?>>
                            <div class="input-label__text"><?=$FORM_FIELDS['rules']['PLACEHOLDER']?></div>
                        </label>
                    </div>

                    <div class="subscription__aside-form-row">
                        <label class="input-label">
                            <input class="input input--checkbox"
                                   type="<?=$FORM_FIELDS['privacy']['TYPE']?>"
                                   name="<?=$FORM_FIELDS['privacy']['NAME']?>[]"
                                   value="<?=$FORM_FIELDS['privacy']['VALUE']?>"
                                <?if ($FORM_FIELDS['privacy']['REQUIRED']) echo 'required';?>
                                <?=$FORM_FIELDS['privacy']['PARAMS']?>>
                            <div class="input-label__text"><?=$FORM_FIELDS['privacy']['PLACEHOLDER']?></div>
                        </label>
                    </div>
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
                    <input class="get-abonement-agree subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?=$arResult['FORM']["arForm"]["BUTTON"]?>">
                    <div class="escapingBallG-animation white">
                        <div id="escapingBall_1" class="escapingBallG"></div>
                    </div>
                </div>

                <div class="popup popup--legal-information">
                    <div class="popup__bg"></div>
                    <div class="popup__window">
                        <div class="popup__close">
                            <div></div>
                            <div></div>
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
                                               type="<?=$FORM_FIELDS['legalinfo']['TYPE']?>"
                                               name="<?=$FORM_FIELDS['legalinfo']['NAME']?>[]"
                                               value="<?=$FORM_FIELDS['legalinfo']['VALUE']?>"
                                            <?=$FORM_FIELDS['legalinfo']['PARAMS']?>>
                                        <div class="input-label__text"><?=$FORM_FIELDS['legalinfo']['PLACEHOLDER']?></div>
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
    var clubName = '<?=$clubName?>';
    var strAbonement = '<?=$abonementName?>';
</script>