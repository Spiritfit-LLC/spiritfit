<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
	
	//send name of club and abonement
	$selectClub = '';
	$selectClubSession = '';
	foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
		if($itemClub['ID'] == $arResult["CLUB_ID"]) {
        	$selectClubSession = $itemClub['MESSAGE'];
    	}
    	if(!empty($itemClub['SELECTED'])){
        	$selectClub = $itemClub['MESSAGE'];               
    	}
	}
	if( empty($selectClub) ) {
    	$selectClub = $selectClubSession;
	}
	
	$abonementName = str_replace('<br>', ' ', $arResult['ELEMENT']['~NAME']);
	if( !empty($selectClub) ) {
		$strSend = strip_tags($selectClub).'/'.$abonementName;
	} else {
		$strSend = '-/'.$abonementName;
	}
?>
<div id="<?=$arResult["COMPONENT_ID"]?>" class="subscription fixed" data-step="<?=$arResult["STEP"]?>" data-strsend="<?=$strSend?>" data-abonementname="<?=strip_tags($abonementName)?>">
	<title><?=strip_tags($arResult["ELEMENT"]["~NAME"])?></title>
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Оформление</div>
            <div class="subscription__stage-item" data-stage="3">3. Оплата</div>
        </div>
        <div class="subscription__common">
            <div class="subscription__title"><?=$arResult["ELEMENT"]["~NAME"] ?></div>
            <div class="subscription__desc"><?=$arResult["ELEMENT"]["PREVIEW_TEXT"] ?></div>
			
			<? if( $arResult["ELEMENT"]["PRICES"] && !empty($arResult["CLUB_ID"]) ) { ?>
                <div class="subscription__label">
                    <? foreach ($arResult["ELEMENT"]["PRICES"] as $key => $arPrice) { ?>
						<? if( intval($arPrice["NUMBER"]) == 99 ) continue; ?>
                        <div class="subscription__label-item" data-mouth="<?=$key?>">
                            <?=$arPrice["SIGN"] ?>
                            <?if (strlen($arResult["ELEMENT"]["BASE_PRICE"]["PRICE"]) > 0){?>
                                <?if ($key == 0 && $arResult["ELEMENT"]["SALE"]) {?>
                                    - <b><?= $arResult["ELEMENT"]["SALE"] ?> руб.</b>
                                <?}elseif($key == 1 && $arResult["ELEMENT"]["SALE_TWO_MONTH"]){?>
                                    - <b><?= $arResult["ELEMENT"]["SALE_TWO_MONTH"] ?> руб.</b>
                                <?}else{?>
                                    <? if ($arPrice["PRICE"]  && $arPrice["PRICE"] != " "): ?>
                                        - <b><?= $arPrice["PRICE"] ?> руб.</b>
                                    <? endif; ?>
                                <?}?>
                            <?}?>
                        </div>
                    <? } ?>
                </div>
            <? } ?>
            <? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"]) ) { ?>
                <div class="subscription__subheading">Включено в абонемент:</div>
                <ul class="subscription__include">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"] as $value): ?>
                        <li class="subscription__include-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? } ?>
            <? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"]) ) { ?>
                <div class="subscription__subheading">Услуги в подарок:</div>
                <ul class="subscription__gift">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"] as $value): ?>
                        <li class="subscription__gift-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? } ?>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
			<form class="get-abonement" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
                <input type="hidden" name="STEP" value="<?=$arResult["STEP"]?>">
				<input type="hidden" name="CLUB_ID" value="<?=$arResult["CLUB_ID"]?>">
				<input type="hidden" name="ACTION" value="CHECK_SMS">
				<input type="hidden" name="NUM" value="">
				<input type="hidden" name="SUB_ID" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]?>">
				<? foreach($arResult["HIDDEN_FILEDS"] as $name => $value) { ?>
                    <?
						if( is_array($value) ) {
							reset($value);
							$fKey = key($value);
							$value = $value[$fKey];
							$name .= "[]";
						}
					?>
					<input type="hidden" name="<?=$name?>" value="<?=$value?>">
                <? } ?>
				<? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]) ) { ?>
                    <input type="hidden" name="additional" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["ADD_TO_1C"]["VALUE"]?>">
                <? } ?>
				
				<? if( !empty($arResult["RESPONSE"]["ERROR"]) ) { ?>
                    <div class="popup popup--call form-error-modal" style="display: block;">
                        <div class="popup__bg"></div>
                        <div class="popup__window">
                            <div class="popup__close">
                                <div></div>
                                <div></div>
                            </div>
                            <div class="popup__success"><?=$arResult["RESPONSE"]["MESSAGE"]?></div>
                        </div>
                    </div>
                <? } ?>
				<div class="subscription__sent">
                    <div class="subscription__sent-text">Код отправлен на номер</div>
                    <div class="subscription__sent-tel"><?=$arResult["SMS_PHONE"] ?></div>
                </div>
				<!-- Код для подтверждения -->
                <div class="subscription__aside-form-row subscription__aside-form-row--code">
                    <? for ($i = 0; $i < 5; $i++): ?>
                        <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="NUM_ARR[<?= $i ?>]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required">
                    <? endfor; ?>
                </div>
                <a class="get-abonement-resend" href="#resend">Получить код повторно</a>
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
					<input class="subscription__total-btn subscription__total-btn--form btn btn--white" type="submit" value="Купить">
                </div>
			</form>
        </div>
    </div>
</div>
<script>
	var strSend = '<?=$strSend?>';
	var strAbonement = '<?=$abonementName?>';
	var step = <?=$arResult["STEP"]?>;
</script>
    <?
?>