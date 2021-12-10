<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$arFieldsCol1 = ['name', 'surname',  'phone', 'email'];
	$arFieldsCol2 = ['position', 'salary', 'metro'];
?>
<div class="form-standart form-standart_tpl-two-col form-standart_white-bg form-standart__popup-message">
	<div class="form-standart__plate">
		<div class="form-standart__title h2">Заявка на вакансию</div>
		<? if(!empty($arResult["ERROR"])) { ?>
			<div class="form__error"><?=$arResult["ERROR"]?></div>
		<? } ?>
		<form class="popup__form popup__resume-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
			<input type="hidden" name="step" value="1">
			<input type="hidden" name="ajax_send" value="Y">
			<div class="form-standart__table-list">
				<div class="form-standart__table-row">
					<div class="form-standart__table-col">
						<?
							foreach ($arFieldsCol1 as $itemField) { 
								switch ($itemField) {
									case 'phone':
										$type = 'tel';
									break;
									case 'email':
										$type = 'email';
									break;
									default:
										$type = 'text';
									break;
								}
						?>
							<div class="form-standart__field">
								<label class="form-standart__label"><?=$arResult["arQuestions"][$itemField]["TITLE"]?></label>
								<div class="form-standart__item">
									<div class="form-standart__inputs">
										<input class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?=$arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <?=($arResult["arQuestions"][$itemField]["REQUIRED"] ? 'required="required"' : '')?> value="<?=$_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" />
									</div>
								</div>
							</div>
						<? } ?>	
					</div>
					<div class="form-standart__table-col">
						<?
							foreach ($arFieldsCol2 as $itemField) { 
								switch ($itemField) {
									case 'phone':
										$type = 'tel';
									break;
									case 'email':
										$type = 'email';
									break;
									default:
										$type = 'text';
									break;
								}
						?>
							<div class="form-standart__field">
								<label class="form-standart__label"><?=$arResult["arQuestions"][$itemField]["TITLE"]?></label>
								<div class="form-standart__item">
									<div class="form-standart__inputs">
										<input class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?=$arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" value="<?=$_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" />
									</div>
								</div>
							</div>
						<? } ?>	
						<div class="form-standart__field">
							<div class="form-standart__item">
								<div class="form-standart__inputs">
                                	<label class="file-selector">
										<?
											$arAnswer = $arResult["arAnswers"]["file_resume"]['0'];
											echo CForm::GetFileField(
                								$arAnswer["ID"],
                								$arAnswer["FIELD_WIDTH"],
                								"FILE",
                								0,
                								$arFile["USER_FILE_ID"],
                								$arAnswer["FIELD_PARAM"]
											);
										?>
										<span>Прикрепить резюме</span>
									</label>
								</div>
								<div class="form-standart__message">
									<div class="form-standart__none">Сделайте выбор</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-standart__table-list">
				<div class="form-standart__table-row">
					<div class="form-standart__table-col w100">
						<div class="form-standart__field">
							<div class="form-standart__item">
								<div class="form-standart__inputs">
									<textarea class="form-standart__textarea" name="form_<?=$arResult["arAnswers"]["description"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["description"]['0']["ID"] ?>" placeholder="<?=$arResult["arQuestions"]["description"]["TITLE"]?>"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-standart__footer someclass">
				<div class="form-standart__footer-wrapper">
					<? if($arResult["arQuestions"]["personal"]['ACTIVE'] == "Y") { ?>
						<div class="form-standart__agreements">
							<div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
								<label class="b-checkbox">
									<input class="b-checkbox__input" type="checkbox" name="form_<?=$arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["personal"]['0']["ID"]?>"  <?=$arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> id="agr1" data-necessary="" value="<?=$arResult["arAnswers"]["personal"]['0']["ID"] ?>" required="required" checked="checked"/>
									<span class="b-checkbox__text"><?=$arResult["arQuestions"]["personal"]["TITLE"] ?></span>
								</label>
							</div>
						</div>
					<? } ?>
					<? if($arResult["arQuestions"]["agreement"]['ACTIVE'] == "Y") { ?>
						<div class="form-standart__agreements">
							<div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
								<label class="b-checkbox">
									<input class="b-checkbox__input" type="checkbox" name="form_<?=$arResult["arAnswers"]["agreement"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["agreement"]['0']["ID"]?>"  <?=$arResult["arAnswers"]["agreement"]['0']["FIELD_PARAM"] ?> id="agr1" data-necessary="" value="<?=$arResult["arAnswers"]["agreement"]['0']["ID"] ?>" required="required" checked="checked"/>
									<span class="b-checkbox__text"><?=$arResult["arQuestions"]["agreement"]["TITLE"] ?></span>
								</label>
							</div>
						</div>
					<? } ?>
					<? if ($arResult["arQuestions"]["rules"]["ACTIVE"]  == "Y") { ?>
						<div class="form-standart__agreements">
							<div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
								<label class="b-checkbox">
									<input class="b-checkbox__input" type="checkbox" name="form_<?=$arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"]?>_<?=$arResult["arAnswers"]["rules"]['0']["ID"]?>"  <?=$arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> id="agr2" data-necessary="" value="<?=$arResult["arAnswers"]["rules"]['0']["ID"] ?>" required="required" checked="checked"/>
									<span class="b-checkbox__text"><?=$arResult["arQuestions"]["rules"]["TITLE"] ?></span>
								</label>
							</div>
						</div>
					<? } ?>
					<div class="form-standart__buttons">
						<input class="form-standart__submit button-outline" type="submit" value="<?=$arResult["arForm"]["BUTTON"] ?>">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>