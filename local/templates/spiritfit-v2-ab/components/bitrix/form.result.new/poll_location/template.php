<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if (strpos($arResult["FORM_NOTE"],'Ваша заявка  принята!')) {
	$arResult["FORM_NOTE"] = 'Благодарим за участие в опросе.';
}?>
<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>

<?$arResult["FORM_HEADER"] = str_replace('name="POLL"', 'id="poll_form" name="POLL"', $arResult["FORM_HEADER"])?>
<?=$arResult["FORM_HEADER"]?>
<input type="hidden" name="web_form_submit" value="Y">
<?
if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y")
{

/***********************************************************************************
					form header
***********************************************************************************/


	if ($arResult["isFormImage"] == "Y")
	{
	?>
	<a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" /></a>
	<?//=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
	<?
	} //endif
	?>

	<?=$arResult["FORM_DESCRIPTION"]?>
		
	<?
} // endif
	?>
<?
/***********************************************************************************
						form questions
***********************************************************************************/

	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
		{
			echo $arQuestion["HTML_CODE"];
		}
		else
		{
	?>

            <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
				<span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
            <?endif;?>

            <div class="primary-form__row">
			
                <span class="primary-form__label "><?=$arQuestion["CAPTION"]?></span>

                <?$inputStructure = $arQuestion['STRUCTURE'][0];?>

                <?if ($inputStructure['QUESTION_ID'] == 78) {?>
                    <input type="text" class="input input--light input--fluid input--text" name="form_<?=$inputStructure['FIELD_TYPE']?>_<?=$inputStructure['ID'] ?>">

                <?}elseif ($inputStructure['QUESTION_ID'] == 79) {?>
	                <select class="input input--light input--fluid input--select" name="form" id="select" required>
	                    <option value="">Выберите подходящий вариант</option>
	                    <?foreach ($arResult["CLUBS"] as $arItem){?>
	                        <option value="<?=$arItem?>"><?=$arItem?></option>
	                    <?}?>
	                </select>
	                <input type="hidden" id="club" name="form_<?=$inputStructure['FIELD_TYPE']?>_<?=$inputStructure['ID'] ?>">                
				<?}elseif($inputStructure['FIELD_TYPE'] == 'email'){?>
					<input type="text" class="input input--light input--fluid input--text" name="email">
				<? }else{ ?>
                    <?$arQuestion["HTML_CODE"] = str_replace('class="inputselect"', 'class="input input--light input--fluid input--select"', $arQuestion["HTML_CODE"])?>

                    <?=$arQuestion["HTML_CODE"]?>
                <?}?>

            </div>

	<?
		}
	} //endforeach
	
if($arResult["isUseCaptcha"] == "Y")
{
?>
		<tr>
			<th colspan="2"><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b></th>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></td>
			<td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></td>
		</tr>
<?
} // isUseCaptcha
?>
	<font class="errortext" style="display: none"></font>
	<input class="subscription__total-btn--club subscription__total-btn--reg btn btn--white" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
			

<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>

<script>
	$(document).ready(function(){
		$('#select').change(function(){
			$('#club').val($('#select').val());
		});
	});
</script>