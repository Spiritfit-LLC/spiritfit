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
			<div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
			<div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
			</div>
		</div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" style="display: block;">
            <form class="get-abonement" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
                <input type="hidden" name="STEP" value="<?=$arResult["STEP"]?>">
				<input type="hidden" name="CLUB_ID" value="<?=$arResult["CLUB_ID"]?>">
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
                <div class="subscription__aside-form-row" style="display: none;">
                    <select class="input input--light input--long input--select get-abonement-club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
						<? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem) {
								if(!isset($clubs_price[$arItem["ID"]])) continue;
								if($arItem["NUMBER"] == 99) continue;
							?>
                        	<option value="<?=$arItem["ID"]?>" <?=($arResult["CLUB_ID"] == $arItem["ID"] ? 'selected' : '')?>><?=$arItem["MESSAGE"]?></option>
						<? } ?>
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
                        <input class="input input--checkbox" type="checkbox" name="agreement3" checked="checked" disabled="disabled">
                        <div class="input-label__text">Cогласен с <a href="#">Договором афферты</a>,<a href="#">Правилами клуба</a>, <a href="#">Списанием денежных средств</a></div>
                    </label>
                </div>
				<div class="subscription__bottom">
					<div class="subscription__total">
						<div class="subscription__total-text"><?= $arResult["arQuestions"]["price"]["TITLE"] ?></div>
						<div class="subscription__total-value">
							<? if( !empty($arResult["ELEMENT"]["SALE"]) ) { ?>
								<div class="subscription__total-value-old">
									<span>
										<? if( !empty($arResult["CLUB_ID"]) && !empty($arResult["ELEMENT"]["PRICES"][0]["PRICE"]) ) { ?>
											<?=$arResult["ELEMENT"]["PRICES"][0]["PRICE"]?> &#x20bd;
										<? } else if( !empty($arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"]) ) { ?>
											<?=$arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"]?> &#x20bd;
										<? } ?>
									</span>	
								</div>
								<?=$arResult["ELEMENT"]["SALE"]?> &#x20bd;
							<? } else { ?>
								<?=( !empty($arResult["CLUB_ID"]) && !empty($arResult["ELEMENT"]["PRICES"][0]["PRICE"]) ) ? $arResult["ELEMENT"]["PRICES"][0]["PRICE"] : $arResult["ELEMENT"]["PROPERTIES"]["BASE_PRICE"]["VALUE"][0]["PRICE"]?> &#x20bd;
							<? } ?>
						</div>
						
						<? if( !empty($arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]) && !empty($arResult["ELEMENT"]["SALE"]) ) { ?>
							<div class="subscription__total-subtext"><?=$arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]?></div>
						<? } ?>
					</div>
                	<? if ($arResult["ELEMENT"]["PRICES"][0]["PRICE"]) { ?>
						<a href="<?=$arResult["RESPONSE"]["PAY_URL"]?>" class="subscription__total-btn subscription__total-btn--pay btn btn--white get-abonement-pay">
							Получить счет
						</a>
					<? } ?>
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