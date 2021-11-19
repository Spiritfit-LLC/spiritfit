<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);

?>
<div class="form-standart form-standart_tpl-ver form-standart_white-bg">
    <div class="form-standart__plate">
        <div class="subscription fixed">
            <div class="subscription__aside">
                <div class="subscription__aside-stage" data-stage="1">
                    <form class="subscription__aside-form_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
                        <?=getClientParams($arParams["WEB_FORM_ID"]) ?>
                        <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
                        <input type="hidden" name="step" value="2">
                        <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
                        <input type="hidden" name="two_month" value="0">
                        <input type="hidden" name="abonement_code" value="<?=$_REQUEST['abonement_code']?>">

                        <? if ($arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]): ?>
                            <input type="hidden" name="additional" value="<?= $arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"] ?>">
                        <? endif; ?>

                        <? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
                            <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                        <? endforeach; ?>
                        
                        <div class="subscription__sent">
                            <div class="subscription__sent-text">Код отправлен на номер</div>
                            <div class="subscription__sent-tel"><?= $arResult["SMS_PHONE"] ?></div>
                        </div>
                        
                        <!-- Код для подтверждения -->
                        <div class="subscription__aside-form-row subscription__aside-form-row--code">
                            <? for ($i = 0; $i < 5; $i++): ?>
                                <input class="input input--num" type="text" maxlength="1" inputmode="numeric" name="num[<?= $i ?>]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required">
                            <? endfor; ?>
                        </div>
                        <a class="subscription__code_v2" href="#">Получить код повторно</a>
                        
                        <div class="subscription__bottom">
                            <div class="subscription__total">
                                <div class="subscription__total-text">Итого к оплате</div>
                                <div class="subscription__total-value">
                                    <? if ($arResult["ELEMENT"]["SALE"]): ?>
                                        <div class="subscription__total-value-old">
                                            <span><?= $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?> &#x20bd;</span>
                                        </div>
                                        <?= $arResult["ELEMENT"]["SALE"] ?> &#x20bd;
                                    <? else: ?>
                                        <?= $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?> &#x20bd;
                                    <? endif; ?>
                                </div>
                                <? if ($arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] && $arResult["ELEMENT"]["SALE"]): ?>
                                    <div class="subscription__total-subtext"><?= $arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
                                <? endif; ?>
                            </div>
                            <div class="subscription__total-btn subscription__total-btn--form btn js-check-code_v2" data-stage="2">Купить</div>
                        </div>
                    </form>
                <div>
            </div>
        </div>
    </div>
</div>
<?
// send name of club and abonement
$selectClub = '';
$selectClubSession = '';
foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
    if($itemClub['NUMBER'] == $_SESSION['CLUB_NUMBER']) {
        $selectClubSession = $itemClub['MESSAGE'];
    }
    if(!empty($itemClub['SELECTED'])){
        $selectClub = $itemClub['MESSAGE'];   
          
        // для шага 3
        $_SESSION['CLUB_NUMBER'] = $itemClub['NUMBER'];            
    }
}
if(empty($selectClub)){
    $selectClub = $selectClubSession;
}

$abonementName = str_replace('<br>', ' ', $arResult['ELEMENT']['~NAME']);
if(!empty($selectClub)){
    $strSend = strip_tags($selectClub).'/'.$abonementName;
}else{
    $strSend = '-/'.$abonementName;
}

?><script>dataLayerSend('UX', 'openSmsCodePage', '<?=$strSend?>');</script>
<? if($abonementName == 'Домашние тренировки') {
    ?><script>dataLayerSend('UX', 'sendContactFormHomeWorkout', '<?=$strSend?>')</script><?        
} else {
    ?><script>dataLayerSend('conversion', 'sendContactForm', '<?=$strSend?>')</script><?
}
?>
<!-- Вывод ошибки в popup -->
<? if ($arResult["RESPONSE"]["data"]["result"]["errorCode"] !== 0 && $arResult["RESPONSE"]["data"]["result"]["userMessage"] != ""): ?>
    <?
    $settings = Utils::getInfo(); 
    if ($settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]]) {
        $errorMessage = $settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]];
    } else {
        $errorMessage = $arResult["RESPONSE"]["data"]["result"]["userMessage"];
    }
    ?>
    <div class="popup popup--call popup-info" style="display: block;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success"><?= $errorMessage ?></div>
        </div>
    </div>
<? endif; ?>