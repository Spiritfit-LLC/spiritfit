<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div id="form_interview">
	<div class="popup__heading"><?= $arResult["arForm"]["NAME"] ?></div>
	<div class="subscription__sent">
		<div class="subscription__sent-text">Код отправлен на номер</div>
		<div class="subscription__sent-tel"><?= $arResult["SMS_PHONE"] ?></div>
	</div>
	<? if(!empty($arResult["ERROR"])) { ?>
		<div class="form__error"><?=$arResult["ERROR"]?></div>
	<? } ?>
	<form class="popup__form popup__resume-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?=POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
		<?=bitrix_sessid_post(); ?>
		<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="type" value="3">
		<input type="hidden" name="is_ajax" value="Y">
		
		<? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
			<input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
		<? endforeach; ?>
		
		<div class="popup__form-row">
			<input 
			class="input input--text <? if (!empty($arResult["ERROR"])): ?>error<? endif; ?>" 
			type="text" placeholder="Код из sms" 
			name="code" 
			required="required"
			pattern="[0-9]{1,20}">
		</div>
		<input class="popup__btn btn" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
	</form>
</div>