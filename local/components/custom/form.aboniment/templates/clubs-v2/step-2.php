<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="training__aside">
    <div class="training__aside-stage" data-stage="1">
        <form class="training__aside-form_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
	    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
			<input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
            <input type="hidden" name="step" value="2">
			<input type="hidden" name="form_default_type" value="<?=(!empty($arParams['DEFAULT_TYPE_ID'])) ? $arParams['DEFAULT_TYPE_ID']  : '' ?>">
            <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
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
                    <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[<?= $i ?>]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required">
                <? endfor; ?>
            </div>
            <a class="subscription__code" href="#">Получить код повторно</a>
            
            <div class="subscription__bottom">
                <div class="subscription__total-btn subscription__total-btn--form btn btn--white js-check-code-training_v2" data-stage="2">Отправить</div>
            </div>
        </form>
    <div>
</div>

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
    <div class="popup popup--call" style="display: block;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success"><?= $errorMessage ?></div>
        </div>
    </div>
<? elseif(!empty($arResult["ERROR"])): ?>
    <div class="popup popup--call" style="display: block;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success"><?=$arResult["ERROR"]?></div>
        </div>
    </div>
<? endif; ?>
<script>
setTimeout(function() {
    $(".training__aside .input--num").on("keyup", function(evt) {
        if (evt.target.value > 1) {
            this.value = evt.target.value.slice(0, 1)
        }
        if (evt.target.value !== '') {
            $(this).next('.input--num').focus();
        }
        if (evt.key === 'Backspace') {
            $(this).prev('.input--num').focus();
        }
    });
}, 500);
</script>
<script>dataLayerSend('conversion', 'sendFormTrialWorkout', '');</script>
<script>
	var getCodeUrl = '<?=$templateFolder?>/sendCode.php';
</script>