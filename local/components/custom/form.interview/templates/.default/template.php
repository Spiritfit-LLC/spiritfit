<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$starValues = ["administrator", "trainer", "dressing", "hall", "programs", "ambience", "comfort", "application", "application_isgood", "recommendation", "fullness"];
	$nextStep = "fullness";
?>
<div id="form_interview">
	<? if(!empty($arResult["ERROR"])) { ?>
		<div class="form__error-text"><?=str_replace(array(":", "?"), array("", ""), $arResult["ERROR"])?></div>
	<? } ?>
	<form class="form-interview" name="<?=$arResult["WEB_FORM_NAME"] ?>" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST" enctype="multipart/form-data">
		
		<input type="hidden" name="WEB_FORM_ID" value="<?=$arParams["WEB_FORM_ID"] ?>">
		<input type="hidden" name="step" value="1">
		<input type="hidden" name="ajax_send" value="Y">
		
		<div class="step-1">
			<div class="primary-form__title">Пожалуйста, ответьте на несколько вопросов о себе.</div>
			<? foreach ($arResult["arQuestions"] as $FIELD_SID => $arQuestion) { ?>
				<?
					if( $arQuestion["VARNAME"] === "agreement" || $arQuestion["VARNAME"] === "personal" || $arQuestion["VARNAME"] === "rules" ) continue;
					
					if( $nextStep === $arQuestion["VARNAME"] ) {
						?></div><div class="step-2"><div class="primary-form__title">А теперь мы попросим Вас оценить отдельные элементы по 5-балльной шкале, где 1 - очень плохо, хуже некуда и 5 - отлично, мне очень нравится.</div><?
					}
					
					$currentSelectName = "";
					$arAnswers = (isset($arResult["arAnswers"][$FIELD_SID])) ? $arResult["arAnswers"][$FIELD_SID] : [];
					if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
					{
						echo $arQuestion["HTML_CODE"];
					}
					else if( !empty($arAnswers) )
					{
						$fieldType = (!empty($arAnswers[0]["FIELD_TYPE"])) ? $arAnswers[0]["FIELD_TYPE"] : "text";
						if( in_array($arQuestion["VARNAME"], $starValues) ) {
							$fieldType = "stars";
						}
						if( isset($arAnswers["BASE"]) ) {
							?><input id="form_<?=$arQuestion["VARNAME"]?>" type="hidden" name="form_<?=$arAnswers["BASE"]["FIELD_TYPE"]?>_<?=$arAnswers["BASE"]["ID"]?>" value="<?=$arAnswers["BASE"]["VALUE"]?>"><?
							$currentSelectName = $arAnswers["BASE"]["VALUE"];
						}
						
						switch($fieldType) {
							case "text":
								foreach($arAnswers as $answer) {
									?>
									<div class="primary-form__row quality__form-row">
										<? if($arQuestion["VARNAME"] !== "phone") { ?>
											<textarea class="input input--light input--fluid input--text small placeholder-white" rows="1" maxlength="500" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" placeholder="<?=$arQuestion["TITLE"]?>" <?=($arQuestion["REQUIRED"] === "Y") ? "required" : "" ?>><?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?></textarea>
										<? } else { ?>
											<input class="input input--light input--fluid input--text small placeholder-white" type="tel" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" value="<?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?>" placeholder="<?=$arQuestion["TITLE"]?>">
										<? } ?>
            						</div>
									<?
								}
							break;
							case "textarea":
								foreach($arAnswers as $answer) {
									?>
									<div class="primary-form__row quality__form-row">
										<span class="primary-form__label "><?=$arQuestion["TITLE"]?><?=($arQuestion["REQUIRED"] === "Y") ? "*" : "" ?></span>
										<textarea class="input input--light input--fluid input--text small placeholder-white" rows="1" maxlength="500" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" <?=($arQuestion["REQUIRED"] === "Y") ? "required" : "" ?>><?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?></textarea>
            						</div>
									<?
								}
							break;
							case "stars":
								?>
								<div class="primary-form__row quality__form-row">
									<span class="primary-form__label"><?=$arQuestion["TITLE"]?><?=($arQuestion["REQUIRED"] === "Y") ? "*" : "" ?></span>
									<?
										foreach($arAnswers as $answer) {
									?>
										<span class="primary-form__subtitle"><?=$answer["MESSAGE"]?></span>
										<div class="primary-form__row-rating">
                							<ul class="quality__form-rating">
                    							<? for($i=5; $i>0; $i--) { ?>
                        							<li class="quality__form-star" data-value="<?=$i?>">
                            							<svg width="30" height="31" viewBox="0 0 30 31">
                                							<path d="M19.0428 10.5699L19.2699 11.0523L19.7969 11.1326L28.9307 12.524L22.2899 19.3094L21.936 19.671L22.0177 20.1704L23.5755 29.6901L15.4828 25.2298L15.0001 24.9638L14.5174 25.2298L6.42462 29.6901L7.98185 20.1703L8.06352 19.671L7.70968 19.3095L1.06953 12.524L10.2032 11.1326L10.7302 11.0523L10.9573 10.5699L15.0002 1.98325L19.0428 10.5699ZM6.19893 29.8145C6.19907 29.8144 6.19922 29.8143 6.19936 29.8142L6.1991 29.8144L6.19893 29.8145Z"/>
                            							</svg>
                        							</li>
                    							<? } ?>
                							</ul>
                							<input type="hidden" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID'] ?>" value="<?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?>" <?=($arQuestion["REQUIRED"] === "Y") ? "required" : "" ?>>
            							</div>
									<?
										}
									?>
								</div>
								<?
							break;
							case "checkbox":
								?>
								<div class="primary-form__row quality__form-row">
									<span class="primary-form__label"><?=$arQuestion["TITLE"]?><?=($arQuestion["REQUIRED"] === "Y") ? "*" : "" ?></span>
									<?
										foreach($arAnswers as $answer) {
											if( $answer["FIELD_TYPE"] == "checkbox" ) {
												?>
												<div class="primary-form__row-element">
													<label class="input-label">
                    									<input class="input input--checkbox" type="checkbox" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" <?=$answer['FIELD_PARAM']?> value="<?=$answer['ID']?>" <?=!empty($answer['VALUE']) ? "checked" : ""?>>
                    									<div class="input-label__text"><?=$answer["MESSAGE"]?></div>
                									</label>
												</div>
												<?
											} else {
												?>
												<div class="primary-form__row-element">
													<input class="input input--light input--fluid input--text small placeholder-white" type="text" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" value="<?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?>" placeholder="<?=$answer["MESSAGE"]?>">
													<!--<textarea class="input input--light input--fluid input--text small placeholder-white" rows="1" maxlength="500" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" placeholder="<?=$answer["MESSAGE"]?>"><?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?></textarea>-->
            									</div>
												<?
											}
										}
									?>
								</div>
								<?
							break;
							case "radio":
								?>
								<div class="primary-form__row quality__form-row <?=$arQuestion['VARNAME']?>">
									<span class="primary-form__label"><?=$arQuestion["TITLE"]?><?=($arQuestion["REQUIRED"] === "Y") ? "*" : "" ?></span>
									<?
										foreach($arAnswers as $answer) {
											if( $answer["FIELD_TYPE"] == "radio" ) {
												?>
												<div class="primary-form__row-element">
													<label class="input-label">
                    									<input class="input input--checkbox" type="radio" name="form_<?=$answer['FIELD_TYPE']?>_<?=$arQuestion['VARNAME']?>" <?=$answer['FIELD_PARAM']?> value="<?=$answer['ID']?>" <?=!empty($answer['VALUE']) ? "checked" : ""?>>
                    									<div class="input-label__text"><?=$answer["MESSAGE"]?></div>
                									</label>
												</div>
												<?
											} else {
												?>
												<div class="primary-form__row-element">
													<input class="input input--light input--fluid input--text small placeholder-white" type="text" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" value="<?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?>" placeholder="<?=$answer["MESSAGE"]?>" <?=empty($answer['VALUE']) ? 'style="display: none;"' : ''?>>
													<!--<textarea class="input input--light input--fluid input--text small placeholder-white" rows="1" maxlength="500" name="form_<?=$answer['FIELD_TYPE']?>_<?=$answer['ID']?>" placeholder="<?=$answer["MESSAGE"]?>"><?=!empty($answer['VALUE']) ? $answer['VALUE'] : ""?></textarea>-->
            									</div>
												<?
											}
										}
									?>
								</div>
								<?
							break;
							case "select":
								?>
									<div class="primary-form__row quality__form-row">
                						<span class="primary-form__label "><?=$arQuestion["TITLE"]?><?=($arQuestion["REQUIRED"] === "Y") ? "*" : "" ?></span>
                						<select class="input input--light input--fluid input--select" name="<?=$arQuestion["VARNAME"]?>" data-for="#form_<?=$arQuestion["VARNAME"]?>" <?=($arQuestion["REQUIRED"] === "Y") ? "required" : "" ?>>
                    						<option value="">Выберите подходящий вариант</option>
                    						<?
												foreach ($arAnswers as $key => $arItem)
												{
													if( $key === "BASE" ) {
														continue;
													}
											?>
                        						<option value="<?=$arItem["VALUE"]?>" <?=($currentSelectName === $arItem["VALUE"]) ? "selected" : "" ?>><?=$arItem["VALUE"]?></option>
                    						<?
												}
											?>
                						</select>
            						</div>
								<?
							break;
						}
					}
				?>
			<? } ?>
			<div class="form-standart__footer">
				
				<div class="form-standart__footer-wrapper">
					<? if(!empty($arResult["arQuestions"]["personal"]) && $arResult["arQuestions"]["personal"]['ACTIVE'] == "Y") { ?>
						<div class="primary-form__row-element">
							<label class="input-label">
                    			<input class="input input--checkbox" type="radio" name="form_<?=$arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["personal"]['0']["ID"]?>" value="<?=$arResult["arAnswers"]["personal"]['0']["ID"]?>" checked="checked">
                    			<div class="input-label__text"><?=$arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                			</label>
						</div>
					<? } ?>
					<? if(!empty($arResult["arQuestions"]["agreement"]) && $arResult["arQuestions"]["agreement"]['ACTIVE'] == "Y") { ?>
						<div class="primary-form__row-element">
							<label class="input-label">
                    			<input class="input input--checkbox" type="radio" name="form_<?=$arResult["arAnswers"]["agreement"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["agreement"]['0']["ID"]?>" value="<?=$arResult["arAnswers"]["agreement"]['0']["ID"]?>" checked="checked">
                    			<div class="input-label__text"><?=$arResult["arQuestions"]["agreement"]["TITLE"] ?></div>
                			</label>
						</div>
					<? } ?>
					<? if (!empty($arResult["arQuestions"]["rules"]) && $arResult["arQuestions"]["rules"]["ACTIVE"]  == "Y") { ?>
						<div class="primary-form__row-element">
							<label class="input-label">
                    			<input class="input input--checkbox" type="radio" name="form_<?=$arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["rules"]['0']["ID"]?>" value="<?=$arResult["arAnswers"]["rules"]['0']["ID"]?>" checked="checked">
                    			<div class="input-label__text"><?=$arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                			</label>
						</div>
					<? } ?>	
				</div>
			</div>
		</div>
		<div class="form-standart__buttons">
			<a class="subscription__total-btn--club subscription__total-btn--reg btn btn--white go-back" href="#back">Далее</a>
			<input class="subscription__total-btn--club subscription__total-btn--reg btn btn--white" type="submit" value="<?=$arResult["arForm"]["BUTTON"] ?>">
		</div>
	</form>
</div>