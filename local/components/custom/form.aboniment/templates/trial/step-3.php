<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
?>

<div class="subscription fixed">
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Выбор клуба</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="3">3. Подтверждение</div>
        </div>
        <div class="subscription__ready" style="display: block;">
			<div class="subscription__title">Абонемент готов</div>
			<div class="subscription__desc"><?= $arResult["ELEMENT"]["~DETAIL_TEXT"] ?></div>
			<div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
			<div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
			</div>
		</div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" data-stage="1">
            <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
		    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
				<input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
                <input type="hidden" name="step" value="3">
                
                <? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
                    <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <? endforeach; ?>
                <div class="subscription__aside-form-row">
                    <select disabled class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>">
                        <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                            <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="subscription__aside-form-row">
                    <input
                        autocomplete="off"
                        class="input input--light input--long input--text" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>"
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                </div>
                
                <div class="subscription__aside-form-row">
                    <input
                        autocomplete="off"
                        class="input input--light input--long input--tel" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" 
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                </div>
                <div class="subscription__aside-form-row subscription__aside-form-row--last">
                    <label class="input-label">
                    <input class="input input--checkbox" type="checkbox" name="agreement3" checked="checked">
                    <div class="input-label__text">Cогласен с <a href="#">Договором афферты</a>,<a href="#">Правилами клуба</a>, <a href="#">Списанием денежных средств</a></div>
                    </label>
                </div>
            </form>
            <div class="subscription__bottom">
            <div class="subscription__total">
                <div class="subscription__total-text"><?= $arResult["arQuestions"]["price"]["TITLE"] ?></div>
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
            <? if ($arResult["ELEMENT"]["PRICES"][0]["PRICE"]): ?>
                <a href="<?= $arResult["RESPONSE"]["data"]["result"]["result"]["formUrl"] ?>" target="_blank"
                class="subscription__total-btn subscription__total-btn--pay btn btn--white js-btn-pay">
                    Получить счет
                </a>
            <? endif; ?>
            </div>
        </div>
    </div>
    
<?
// send name of club and abonement
$selectClub = '';
foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
    if(!empty($itemClub['SELECTED']) || $itemClub['NUMBER'] == $_SESSION['CLUB_NUMBER']){
        $selectClub = $itemClub['MESSAGE'];               
    }
} 

$abonementName = str_replace('<br>', ' ', $arResult['ELEMENT']['~NAME']);
if(!empty($selectClub)){
    $strSend = strip_tags($selectClub).'/'.$abonementName;
}else{
    $strSend = '-/'.$abonementName;
}
?>
<script>dataLayerSend('UX', 'openMembershipReadyPage', '<?=$strSend?>');</script>
<? /*if($arParams["PREV_STEP"] == 2) { ?>
	<script>dataLayerSend('conversion', 'sendFormTrialWorkout', '');</script>
<? }*/ ?>

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