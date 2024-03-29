<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
	$arInfoProps = Utils::getInfo()['PROPERTIES'];
	
	if($arResult["ELEMENT"]["PREVIEW_PICTURE"]) {
		$ogImage = CFile::GetPath($arResult["ELEMENT"]["PREVIEW_PICTURE"]);
    } else {
		$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
    }
	
	if (!$_SERVER['HTTP_X_PJAX']) {
		$APPLICATION->AddViewContent('inhead', $ogImage);
	}
	
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
	<div id="seo-div" hidden="true" data-title="<?=$arResult['SEO']['ELEMENT_META_TITLE']?>" data-description="<?=$arResult['SEO']['ELEMENT_META_DESCRIPTION'] ?>" data-keywords="<?=$arResult['SEO']['ELEMENT_META_KEYWORDS']?>" data-image="<?=$ogImage?>"></div>
	<div class="subscription__main">
		<div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done">1. Регистрация</div>
            <div class="subscription__stage-item">2. Оформление</div>
            <div class="subscription__stage-item">3. Оплата</div>
        </div>
		<div class="subscription__common">
			<h1 class="subscription__title"><?= $arResult["ELEMENT"]["~NAME"] ?></h1>
            <div class="subscription__desc"><?= $arResult["ELEMENT"]["~PREVIEW_TEXT"] ?></div>
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
			<? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"]) ) { ?>
				<div class="subscription__subheading">Услуги в подарок:</div>
				<ul class="subscription__gift">
					<? foreach( $arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"] as $value ) { ?>
						<li class="subscription__gift-item"><?=$value?></li>
					<? } ?>
				</ul>
            <? } ?>
            <? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"]) ) { ?>
				<div class="subscription__subheading">Включено в абонемент:</div>
				<ul class="subscription__include">
					<? foreach( $arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"] as $value ) { ?>
						<li class="subscription__include-item"><?=$value?></li>
					<? } ?>
				</ul>
            <? } ?>
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
                <input type="hidden" name="typeSetClient" value="trialTraining" data-upmetric="">
				
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
					<select class="input input--light input--long input--select get-abonement-club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" autocomplete="off" required>
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
                    <input style="width: 100%;" class="input input--light input--short input--text" type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["name"]["REQUIRED"]) { ?>required="required"<? } ?>>
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
					<div class="subscription__bottom">
                    	<input type="hidden" name="form_<?=$arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="<?=$arResult["ELEMENT"]["SALE"] ? $arResult["ELEMENT"]["SALE"] : $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?>">
					</div>
					<input class="subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?=$arResult["arForm"]["BUTTON"]?>">
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