<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
?>

<title><?= strip_tags($arResult["ELEMENT"]["~NAME"]) ?></title>

<div class="subscription fixed">
	<a href="<?= $arResult["ELEMENT"]["LIST_PAGE_URL"] ?>" class="subscription__close js-pjax-link">
		<div></div>
		<div></div>
	</a>
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Выбор клуба</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Регистрация</div>
            <div class="subscription__stage-item" data-stage="3">3. Подтверждение</div>
        </div>
        <div class="subscription__common">
            <div class="subscription__title"><?= $arResult["ELEMENT"]["~NAME"] ?></div>
            <div class="subscription__desc"><?= $arResult["ELEMENT"]["PREVIEW_TEXT"] ?></div>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"]): ?>
                <div class="subscription__subheading">Включено в абонемент:</div>
                <ul class="subscription__include">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"] as $value): ?>
                        <li class="subscription__include-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"]): ?>
                <div class="subscription__subheading">Услуги в подарок:</div>
                <ul class="subscription__gift">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"] as $value): ?>
                        <li class="subscription__gift-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" data-stage="1">
            <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
		    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
				<input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
                <input type="hidden" name="step" value="2">
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
                    <div class="subscription__total-btn subscription__total-btn--form btn btn--white js-check-code" data-stage="2">Отправить</div>
                </div>
            </form>
        <div>
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
?>

<script>dataLayerSend('UX', 'openSmsCodePage', '<?=$strSend?>');</script>

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
<? endif; ?>