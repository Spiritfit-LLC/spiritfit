<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/datepicker/datepicker.min.js');
$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/datepicker/datepicker.ru-RU.js');
$this->addExternalCss(SITE_TEMPLATE_PATH . '/vendor/datepicker/datepicker.min.css');
$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/inputmask/jquery.inputmask.min.js');
?>
<script>
    var personalSettingsComponent = <?=CUtil::PhpToJSObject($this->getComponent()->getName())?>;
</script>
<div class="personal-settings" id="personal-settings">
    <div class="personal-settings__personal-info">
        <div class="personal-settings-info__title">Мои данные</div>
        <form id="personal-settings__form">
            <div class="personal-input__form-row">
                <div class="personal-input__input half">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-name"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-name"]["FORM_NAME"]?>"
                           type="text"
                           data-dadata-type="NAME"
                           data-dadata-part="NAME"
                           value="<?=$arResult["FIELDS"]["lk-settings-name"]["VALUE"]?>"
                           id="<?=$arResult["FIELDS"]["lk-settings-name"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-name"]["REQUIRED"]?"required":""?>>
                </div>
                <div class="personal-input__input half">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-surname"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-surname"]["FORM_NAME"]?>"
                           type="text"
                           data-dadata-type="NAME"
                           data-dadata-part="SURNAME"
                           value="<?=$arResult["FIELDS"]["lk-settings-surname"]["VALUE"]?>"
                           id="<?=$arResult["FIELDS"]["lk-settings-surname"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-surname"]["REQUIRED"]?"required":""?>>
                </div>
            </div>

            <div class="personal-input__form-row">
                <div class="personal-input__input">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-email"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-email"]["FORM_NAME"]?>"
                           type="email"
                           value="<?=$arResult["FIELDS"]["lk-settings-email"]["VALUE"]?>"
                           id="<?=$arResult["FIELDS"]["lk-settings-email"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-email"]["REQUIRED"]?"required":""?>>
                </div>
            </div>

            <div class="personal-input__form-row">
                <div class="personal-input__input">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-address"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-address"]["FORM_NAME"]?>"
                           type="text"
                           data-dadata-type="ADDRESS"
                           value="<?=$arResult["FIELDS"]["lk-settings-address"]["VALUE"]?>"
                           id="<?=$arResult["FIELDS"]["lk-settings-address"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-address"]["REQUIRED"]?"required":""?>>
                </div>
            </div>

            <div class="personal-input__form-row">
                <div class="personal-input__input half">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-birthday"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-birthday"]["FORM_NAME"]?>"
                           type="text"
                           value="<?=$arResult["FIELDS"]["lk-settings-birthday"]["VALUE"]?>"
                           id="<?=$arResult["FIELDS"]["lk-settings-birthday"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-birthday"]["REQUIRED"]?"required":""?>
                           data-toggle="datepicker"
                           data-minage="14">
                </div>
                <div class="personal-input__input half">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-gender"]["NAME"]?></span>
                    <div class="personal-input__radio-input">
                        <div class="personal-input__radio-btn">
                            <input class="personal-input__input-value input-radio-btn"
                                   name="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>"
                                   type="radio"
                                   value="M"
                                <?=$arResult["FIELDS"]["lk-settings-gender"]["VALUE"]=="M"?"checked":""?>
                                   id="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>_1"
                                <?=$arResult["FIELDS"]["lk-settings-gender"]["REQUIRED"]?"required":""?>>
                            <label for="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>_1">Мужской</label>
                        </div>
                        <div class="personal-input__radio-btn">
                            <input class="personal-input__input-value input-radio-btn"
                                   name="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>"
                                   type="radio"
                                   value="F"
                                <?=$arResult["FIELDS"]["lk-settings-gender"]["VALUE"]=="F"?"checked":""?>
                                   id="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>_2"
                                <?=$arResult["FIELDS"]["lk-settings-gender"]["REQUIRED"]?"required":""?>>
                            <label for="<?=$arResult["FIELDS"]["lk-settings-gender"]["FORM_NAME"]?>_2">Женский</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <?
            if (!empty($arResult["FIELDS"]["lk-settings-birthday"]["VALUE"])){
                $birthday = new DateTime($arResult["FIELDS"]["lk-settings-birthday"]["VALUE"]);
                $now=new DateTime();
                $interval=$birthday->diff($now);
                $year_count=$interval->y;
                if ($year_count>=18){
                    $CLASSNAME="hidden";
                }
            }
            else{
                $CLASSNAME="hidden";
            }
            if (!empty($arResult["FIELDS"]["lk-settings-consent"]["VALUE"])){
                $DROPZONE="hidden";
                $FILE_EXIST="exist";
            }
            else{
                $PREVIEW="hidden";
            }
            ?>

            <div class="personal-input__form-row parental_consent <?=$CLASSNAME?>">
                <div class="personal-input__input">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-consent"]["NAME"]?></span>
                    <input class="personal-input__input-value" style="display: none"
                           name="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>"
                           type="file"
                           id="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-consent"]["REQUIRED"]?"required":""?>
                           onchange="personal_file_input(this.files, '<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>')"
                           accept=".jpg, .jpeg, .png">

                    <?if (!empty($arResult["FIELDS"]["lk-settings-consent"]["VALUE"])):?>
                        <input type="hidden" id="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>_file_exist" value="1" name="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>_file_exist">
                    <?endif;?>

                    <div id="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>_dropzone" class="file-input__dropzone <?=$DROPZONE?>">
                        <div class="file-input__btn">
                            <div class="btn-trigger" onclick="$('#<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>').click()">
                                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/add-photo-icon.svg')?>
                            </div>
                            Добавить файл
                            <br>
                            (.jpg, .jpeg, .png)
                        </div>
                    </div>

                    <div class="file-input__preview <?=$PREVIEW?>" id="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>_media-preview">
                        <div class="personal-media_preview__item image <?=$FILE_EXIST?>" id="<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>_preview_item" <?if ($arResult["FIELDS"]["lk-settings-consent"]["VALUE"]):?> style="background-image: url('<?=CFile::GetPath($arResult["FIELDS"]["lk-settings-consent"]["VALUE"])?>')"<?endif;?>>
                            <div class="closer-small"  onclick="reset_file_input('<?=$arResult["FIELDS"]["lk-settings-consent"]["FORM_NAME"]?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="96px" height="96px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="personal-input__form-row">
                <div class="personal-input__input">
                    <span class="personal-input__input-placeholder"><?=$arResult["FIELDS"]["lk-settings-password"]["NAME"]?></span>
                    <input class="personal-input__input-value"
                           name="<?=$arResult["FIELDS"]["lk-settings-password"]["FORM_NAME"]?>"
                           type="password"
                           value=""
                           id="<?=$arResult["FIELDS"]["lk-settings-password"]["FORM_NAME"]?>"
                        <?=$arResult["FIELDS"]["lk-settings-password"]["REQUIRED"]?"required":""?>>
                </div>
                <div class="show-password-icon">
                    <button class="pass-show active password-btn" onclick="pass_show(this, '<?=$arResult["FIELDS"]["lk-settings-password"]["FORM_NAME"]?>')" type="button">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/pass-show.svg');?>
                    </button>
                    <button class="pass-hide password-btn" onclick="pass_hide(this, '<?=$arResult["FIELDS"]["lk-settings-password"]["FORM_NAME"]?>')" type="button">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/pass-hide.svg');?>
                    </button>
                </div>
            </div>

            <div class="personal-settings__form-message"></div>

            <input type="submit" class="button" value="Сохранить" style="width: 100%">
        </form>
    </div>
    <div class="personal-settings__card-info">
        <div class="personal-settings-info__title">Платежные данные</div>
        <div class="personal-settings__bankcard <?if (empty($arResult["FIELDS"]["lk-settings-bankcard"]["VALUE"]) || empty($arResult["FIELDS"]["lk-settings-bankcard"]["VALUE"]["name"])) echo "empty"?>">
            <?if (!empty($arResult["FIELDS"]["lk-settings-bankcard"]["VALUE"]["name"])):?>
                <div class="personal-settings__bankcard-placeholder"><?=$arResult["FIELDS"]["lk-settings-bankcard"]["NAME"]?></div>
                <div class="personal-settings__bankcard-info">
                    <div class="personal-settings__bankcard-icon">
                        <?=file_get_contents(__DIR__.'/images/bankcard-icon.svg')?>
                    </div>
                    <div class="personal-settings__bankcard-number"><?=$arResult["FIELDS"]["lk-settings-bankcard"]["VALUE"]["name"]?></div>
                </div>
                <div class="personal-settings__bankcard-type"><?=$arResult["FIELDS"]["lk-settings-bankcard"]["VALUE"]["type"]?></div>

            <?else:?>
                <span class="personal-input__input-placeholder">у вас не добавлено ни одной карты</span>
            <?endif;?>
        </div>
    </div>
</div>
