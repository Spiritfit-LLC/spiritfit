<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$arField = ["name", "phone", "email", "company"];
?>
<div id="<?=$arResult["COMPONENT_ID"]?>" data-step="<?=$arResult["STEP"]?>" class="checkbox-pre-style company-message">
	<h2 class="club__subheading"><?=!empty($arParams["BLOCK_TITLE"]) ? $arParams["BLOCK_TITLE"] : "Оставьте заявку" ?></h2>
	<div class="training__aside">
		<div class="training__aside-stage">
			<form class="get-contact" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
				<?=getClientParams($arParams["WEB_FORM_ID"]);?>
				<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
                <input type="hidden" name="STEP" value="<?=$arResult["STEP"]?>">
				<input type="hidden" name="ACTION" value="SEND_SMS">
				
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
				
				<div class="subscription__aside-form-row">
					<select name="form_<?= $arResult["arAnswers"]["theme"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["theme"]['0']["ID"] ?>" required="required">
						<option value="">Выберите тему обращения*</option>
						<? foreach ($arResult["INFO"]['PROPERTIES']['MESSAGE_FORM_THEMES']['VALUE'] as $theme) { ?>
                        	<option value="<?=$theme?>" <?=($theme === $arResult["SELECTED_THEME"]) ? "selected=\"selected\"" : ""?>><?=$theme?></option>
						<? } ?>
                    </select>
                </div>
				<? foreach ($arField as $itemField) { 
					switch ($itemField) {
						case 'phone': $type = 'tel'; break;
						case 'email': $type = 'email'; break;
						default: $type = 'text'; break;
					}
					$addClass = "";
					if( $type === "text" && $itemField !== "company" ) {
						$addClass = "input--name";
					}
				?>
					<div class="subscription__aside-form-row">
						<input class="input input--light input--short input--text <?=$addClass?>" type="<?=$type?>" placeholder="<?=$arResult["arQuestions"][$itemField]["TITLE"] ?><? if ( !empty($arResult["arQuestions"][$itemField]["REQUIRED"]) && $arResult["arQuestions"][$itemField]["REQUIRED"] === "Y" ) { ?>*<? } ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <? if(!empty($arResult["arQuestions"][$itemField]["REQUIRED"]) && $arResult["arQuestions"][$itemField]["REQUIRED"] === "Y") { ?>required="required"<? } ?>>
					</div>
				<? } ?>
				<? if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") { ?>
					<div class="subscription__aside-form-row">
						<label class="input-label">
                        	<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"] ?>_privacy[]" <?=$arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["privacy"]['0']["ID"] ?>">
                        	<div class="input-label__text"><?=$arResult["arQuestions"]["privacy"]["TITLE"] ?></div>
                    	</label>
                	</div>
				<? } ?>
				<? if($arResult["arQuestions"]["personal"]['ACTIVE'] == "Y") { ?>
					<div class="subscription__aside-form-row">
						<label class="input-label">
                        	<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?=$arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["personal"]['0']["ID"] ?>">
                        	<div class="input-label__text"><?=$arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                    	</label>
                	</div>
				<? } ?>
				<? if($arResult["arQuestions"]["rules"]['ACTIVE'] == "Y") { ?>
					<div class="subscription__aside-form-row">
						<label class="input-label">
                        	<input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?=$arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?=$arResult["arAnswers"]["rules"]['0']["ID"] ?>">
                        	<div class="input-label__text"><?=$arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                    	</label>
                	</div>
				<? } ?>
				<div class="subscription__aside-form-row submit">
					<input class="form-standart__submit button-outline" type="submit" value="<?=$arResult["arForm"]["BUTTON"]?>">
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	var step = <?=$arResult["STEP"]?>;
</script>