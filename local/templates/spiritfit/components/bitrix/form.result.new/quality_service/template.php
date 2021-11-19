<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if (strpos($arResult["FORM_NOTE"],'Ваша заявка  принята!')) {
    $arResult["FORM_NOTE"] = 'Благодарим за участие в опросе. Мы рады возможности сделать нашу работу лучше.';
}?>
<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>

<?$arResult["FORM_HEADER"] = str_replace('name="QUALITY_SERVICE"', 'id="quality_form" name="QUALITY_SERVICE"', $arResult["FORM_HEADER"])?>
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

        <?$inputStructure = $arQuestion['STRUCTURE'][0];?>

        <?if ($inputStructure['QUESTION_ID'] == 67) {?>

            <div class="primary-form__row quality__form-row">
                <span class="primary-form__label "><?=$arQuestion["CAPTION"]?></span>
                <select class="input input--light input--fluid input--select" name="form" id="select" required>
                    <option value="">Выберите подходящий вариант</option>
                    <?foreach ($arResult["CLUBS"] as $arItem){?>
                        <option value="<?=$arItem?>"><?=$arItem?></option>
                    <?}?>
                </select>
                <input type="hidden" id="club" name="form_<?=$inputStructure['FIELD_TYPE']?>_<?=$inputStructure['ID'] ?>">
            </div>

        <?}elseif ($inputStructure['QUESTION_ID'] == 80){?>
            <div class="primary-form__row quality__form-row">
                <textarea class="input input--light input--fluid input--text" rows="1" maxlength="500" name="form_<?=$inputStructure['FIELD_TYPE']?>_<?=$inputStructure['ID'] ?>" placeholder="<?=$arQuestion["CAPTION"]?>"></textarea>
            </div>
        <? }elseif($inputStructure['FIELD_TYPE'] == 'email'){ ?>
            <input class="input input--light input--fluid input--text" name="email" placeholder="<?=$arQuestion["CAPTION"]?>">
        <?}else{?>

            <div class="primary-form__row quality__form-row">
                <span class="primary-form__label"><?=$arQuestion["CAPTION"]?></span>
                <ul class="quality__form-rating">
                    <?for($i=5;$i>0;$i--){?>
                        <li class="quality__form-star" data-value="<?=$i?>">
                            <svg width="30" height="31" viewBox="0 0 30 31">
                                <path d="M19.0428 10.5699L19.2699 11.0523L19.7969 11.1326L28.9307 12.524L22.2899 19.3094L21.936 19.671L22.0177 20.1704L23.5755 29.6901L15.4828 25.2298L15.0001 24.9638L14.5174 25.2298L6.42462 29.6901L7.98185 20.1703L8.06352 19.671L7.70968 19.3095L1.06953 12.524L10.2032 11.1326L10.7302 11.0523L10.9573 10.5699L15.0002 1.98325L19.0428 10.5699ZM6.19893 29.8145C6.19907 29.8144 6.19922 29.8143 6.19936 29.8142L6.1991 29.8144L6.19893 29.8145Z"/>
                            </svg>
                        </li>
                    <?}?>
                </ul>
                <input type="hidden" name="form_<?=$inputStructure['FIELD_TYPE']?>_<?=$inputStructure['ID'] ?>">
            </div>

        <?}?>

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

        $('.quality__form-star').click(function() {
            var value = $(this).data('value');
            $(this).addClass('selected').siblings().removeClass('selected');
            $(this).closest('.quality__form-rating').siblings('input').val(value);

        })

        $('.quality__form-star').hover(function () {
            $(this).closest('.quality__form-rating').addClass('hovered');
            $(this).addClass('current').siblings().removeClass('current');
        }, function () {
            $(this).closest('.quality__form-rating').removeClass('hovered');
            $(this).removeClass('current');
        });
	});
</script>
