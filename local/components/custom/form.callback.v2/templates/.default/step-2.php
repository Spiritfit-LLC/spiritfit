<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div id="<?=$arResult["COMPONENT_ID"]?>" data-step="<?=$arResult["STEP"]?>" class="checkbox-pre-style company-message">
	<h2 class="club__subheading"><?=!empty($arParams["BLOCK_TITLE"]) ? $arParams["BLOCK_TITLE"] : "Оставьте заявку" ?></h2>
	<div class="training__aside">
		<div class="training__aside-stage">
			<form class="get-abonement" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
				<?=getClientParams($arParams["WEB_FORM_ID"]);?>
				<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
                <input type="hidden" name="STEP" value="<?=$arResult["STEP"]?>">
				<input type="hidden" name="ACTION" value="CHECK_SMS">
				<input type="hidden" name="NUM" value="">
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
					<input class="subscription__total-btn subscription__total-btn--form btn btn--white" type="submit" value="Купить">
                </div>
			</form>
		</div>
	</div>
</div>
<script>
	var step = <?=$arResult["STEP"]?>;
</script>