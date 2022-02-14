<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
	
	if( !isset($arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"]) ) {
		$arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"] = 0;
	}
	if( !isset($arResult["ELEMENT"]["PRICES"][0]["PRICE"]) ) {
		$arResult["ELEMENT"]["PRICES"][0]["PRICE"] = 0;
	}
	
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
	<title><?=strip_tags($arResult["ELEMENT"]["~NAME"]) ?></title>
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Регистрация</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Оформление</div>
            <div class="subscription__stage-item subscription__stage-item--done" data-stage="3">3. Оплата</div>
        </div>
        <div class="subscription__ready" style="display: block;">
			<div class="subscription__title">Абонемент готов</div>
			<div class="subscription__desc"><?=$arResult["ELEMENT"]["~DETAIL_TEXT"] ?></div>
		</div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
			<form class="get-abonement" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
				<?=getClientParams($arParams["WEB_FORM_ID"]);?>
				<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
                <input type="hidden" name="STEP" value="<?=$arResult["STEP"]?>">
				<input type="hidden" name="CLUB_ID" value="<?=$arResult["CLUB_ID"]?>">
				<input type="hidden" name="ACTION" value="">
				<input type="hidden" name="SUB_ID" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["CODE_ABONEMENT"]["VALUE"]?>">
				
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
				
				<!-- Список клубов -->
				<div class="subscription__aside-form-row subscription__aside-form-row--title"></div>
				<?php
					$clubs_price = array();
					foreach ($arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $tmp_price) {
						$clubs_price[$tmp_price['LIST']] = $tmp_price['LIST'];
					}
				?>
				<div class="subscription__aside-form-row">
					<span class="subscription__total-text">Выберите клуб</span>
				</div>
				<div class="subscription__aside-form-row">
					<select class="input input--light input--long input--select get-abonement-club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
						<option value="">-</option>
						<? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem) {
							if(!isset($clubs_price[$arItem["ID"]])) continue;
							if($arItem["NUMBER"] == 99) continue;
						?>
                        	<option value="<?=$arItem["ID"]?>" <?=($arResult["CLUB_ID"] == $arItem["ID"] ? 'selected' : '')?>><?=$arItem["MESSAGE"]?></option>
						<? } ?>
                    </select>
                </div>
				
				<div class="subscription__aside-form-row">
                    <input class="input input--light input--short input--text" type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["name"]["REQUIRED"]) { ?>required="required"<? } ?>>
					<input class="input input--light input--short input--text" type="text" placeholder="<?= $arResult["arQuestions"]["surname"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["surname"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["surname"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["surname"]["REQUIRED"]) { ?>required="required"<? } ?>>
				</div>
				<div class="subscription__aside-form-row">
					<input class="input input--light input--short input--tel" type="tel" placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]) { ?>required="required"<? } ?>>
                    <input class="input input--light input--short input--text" type="email" placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["email"]["REQUIRED"]) { ?>required="required"<? } ?>>
				</div>
                <div class="subscription__aside-form-row">
                    <label class="input-label">
						<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?=$arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["personal"]['0']["ID"] ?>">
						<div class="input-label__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></div>
					</label>
				</div>
				<div class="subscription__aside-form-row">
					<label class="input-label">
						<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?=$arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["rules"]['0']["ID"] ?>">
						<div class="input-label__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></div>
					</label>
				</div>
				
				<? if( $arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y" ) { ?>
                	<div class="subscription__aside-form-row">
                    	<label class="input-label">
                        	<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"] ?>_privacy[]" <?=$arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["privacy"]['0']["ID"] ?>">
                        	<div class="input-label__text"><?=$arResult["arQuestions"]["privacy"]["TITLE"] ?></div>
                    	</label>
                	</div>
                <? } ?>
				
				<? if( isset($arResult["ELEMENT"]["BASE_PRICE"]["PRICE"]) || (!empty($arResult["ELEMENT"]["PRICES"][0]["SIGN"]) && $arResult["ELEMENT"]["PRICES"][0]["SIGN"] == "Бесплатно") ) { ?>
					
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
											<input class="input input--checkbox" type="checkbox" name="form_checkbox_legal-information">
											<div class="input-label__text">C условиями Оферты ознакомлен</div>
										</label>
									</div>
									<input class="popup__btn btn subscription__total-btn" type="submit" value="Согласен" disabled>
								</div>
							</div>
						</div>
					</div>
					
					<div class="subscription__bottom">
                    	<input type="hidden" name="form_<?=$arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="<?=$arResult["ELEMENT"]["SALE"] ? $arResult["ELEMENT"]["SALE"] : $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?>">
					</div>
					<span class="get-abonement-agree subscription__total-btn subscription__total-btn--reg btn btn--white"><?=$arResult["arForm"]["BUTTON"]?></span>
				<? } ?>
			</form>
        </div>
    </div>
</div>
<script>
	var strSend = '<?=$strSend?>';
	var strAbonement = '<?=$abonementName?>';
	var step = <?=$arResult["STEP"]?>;
</script>