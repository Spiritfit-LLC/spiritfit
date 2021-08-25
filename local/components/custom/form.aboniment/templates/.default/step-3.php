<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
?>

<title><?= strip_tags($arResult["ELEMENT"]["~NAME"]) ?></title>

<div class="subscription fixed">
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Оформление</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="3">3. Оплата</div>
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
                <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
                
                <? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
                    <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <? endforeach; ?>
                <div class="subscription__aside-form-row" style="display: none;">
                    <select disabled class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>">
                        <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                            <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="subscription__aside-form-row">
                    <input
                        autocomplete="off"
                        class="input input--light input--short input--text" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>"
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                    <input
                        autocomplete="off"
                        class="input input--light input--short input--text" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["surname"]["TITLE"] ?>"
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["surname"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["surname"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["surname"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                </div>
                
                <div class="subscription__aside-form-row">
                    <input
                        autocomplete="off"
                        class="input input--light input--short input--tel" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" 
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                    <input
                        autocomplete="off"
                        class="input input--light input--short input--text" 
                        type="text" 
                        disabled
                        placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" 
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["email"]["REQUIRED"]): ?>required="required"<? endif; ?>
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
<?
	if($arParams["PREV_STEP"] == 2 && $abonementName == 'Домашние тренировки') {
    	?><script>dataLayerSend('UX', 'sendContactFormHomeWorkout', '<?=$strSend?>')</script><?        
	} else if($arParams["PREV_STEP"] == 2) {
    	?><script>dataLayerSend('conversion', 'sendContactForm', '<?=$strSend?>')</script><?
	}
?>
<script>dataLayerSend('UX', 'openMembershipReadyPage', '<?=$strSend?>');</script>

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
<script>
	var getCodeUrl = '<?=$templateFolder?>/sendCode.php';
</script>