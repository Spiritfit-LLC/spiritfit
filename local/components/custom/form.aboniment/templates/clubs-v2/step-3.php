<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]); 
$arInfoProps = Utils::getInfo()['PROPERTIES'];
$settings = Utils::getInfo();

$arField = ['name', 'email', 'phone'];
?>
<div class="content-center block-margin">
<div class="form-standart form-standart_tpl-hor form-standart_black-bg">
    <div class="form-standart__plate">
        <div class="form-standart__title h2"><?if($arParams["TEXT_FORM"]){?><?=$arParams["TEXT_FORM"]?><?}else{?>Записаться на пробную тренировку<?}?></div>

        <!--<div class="subscription__title-success">Спасибо, ваша заявка принята! После 3-го ноября мы вам позвоним для консультации и оформления двухнедельного тест-драйва и получения скидки.</div>-->
        
        <form class="training__aside-form_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
	    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
            <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
            <input type="hidden" name="step" value="1">
            <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
            <input type="hidden" class="club" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" value="01">
            <input type="hidden" name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="0">


            <div class="form-standart__fields-list">
                <? foreach ($arField as $itemField) {
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
                                <input autocomplete="off" class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?= $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <?=($arResult["arQuestions"][$itemField]["REQUIRED"] ? 'required="required"' : '')?> value="" />
                            </div>
                            <div class="form-standart__message">
                                <div class="form-standart__none">Заполните поле</div>
                                <div class="form-standart__error">Поле заполнено некорректно</div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>

            <div class="form-standart__footer">
                <div class="form-standart__agreements">
                    <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                        <label class="b-checkbox">
                            <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>" id="agr1" data-necessary="">
                            
                            <span class="b-checkbox__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></span>
                        </label>
                        <div class="form-standart__message">
                            <div class="form-standart__error">Необходимо ваше согласие
                            </div>
                        </div>
                    </div>
                    <div class="form-standart__field form-standart__field_agreement form-standart__field_checkbox">
                        <label class="b-checkbox">
                            <input class="b-checkbox__input" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>" id="agr2" data-necessary="">
                            <span class="b-checkbox__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></span>
                        </label>
                        <div class="form-standart__message">
                            <div class="form-standart__error">Необходимо ваше согласие</div>
                        </div>
                    </div>
                </div>
                <div class="form-standart__buttons">
                    <input class="form-standart__submit button-outline" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1" name="web_form_submit">
                </div>
            </div> 


            <div class="popup popup--legal-information">
                <div class="popup__bg"></div>
                <div class="popup__window">
                    <div class="popup__close">
                        <div></div>
                        <div></div>
                    </div>
                    <div class="popup__wrapper">
                        <div class="popup__heading">Юридическая информация</div>
                        <div class="popup__legal-information-wrapper">
                            <div class="popup__legal-information">
                                <p class="popup__legal-information__subtitle"><?= $settings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'] ?></p>
                            </div>
                        </div>
                        <div class="popup__bottom">
                            <div class="popup__privacy-policy">
                                <label class="input-label">
                                    <input class="input input--checkbox" type="checkbox" name="form_checkbox_legal-information">
                                    <div class="input-label__text">C условиями
                                        Оферты ознакомлен
                                    </div>
                                </label>
                            </div>
                            <input class="popup__btn btn subscription__total-btn subscription__total-btn--reg subscription__total-btn--legal-training_v2" type="submit" value="Согласен" data-stage="1" name="web_form_submit" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>              
</div>

<!-- Вывод ошибки в popup -->
<? if ($arResult["RESPONSE"]["data"]["result"]["errorCode"] !== 0 && $arResult["RESPONSE"]["data"]["result"]["userMessage"] != "") { ?>
    <?
    $settings = Utils::getInfo(); 
    if ($settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]]) {
        $errorMessage = $settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]];
    } else {
        $errorMessage = $arResult["RESPONSE"]["data"]["result"]["userMessage"];
		}
    ?>
    <div class="popup popup--call" style="display: block;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success"><?= $errorMessage ?></div>
        </div>
    </div>
<?
	} else {
		?>
		<div class="popup popup--call" style="display: block;">
        	<div class="popup__bg"></div>
        	<div class="popup__window">
            	<div class="popup__close">
                	<div></div>
                	<div></div>
            	</div>
            	<div class="popup__success"><?=(!empty($arParams["CLUB_FORM_SUCCESS"])) ? $arParams["CLUB_FORM_SUCCESS"] : "Спасибо, ваша заявка принята!" ?></div>
        	</div>
    	</div>
        <script>dataLayerSend('conversion', 'sendFormTrialWorkout', '<?=$arResult['GA_LABEL']?>');</script>
		<?
	}
?>
<script src="<?=SITE_TEMPLATE_PATH?>/js/form-standart.js"></script>
<script>
setTimeout(function() {
    $(document).trigger('clubFormSuccessOpen');
    $(".input--checkbox").styler(); 
    $(".input--tel").inputmask("+7 (999) 999-99-99");
    $(document).find('.training__aside-form').trigger('reset');
}, 500);

$(document).ready(function(){
    $('.form-standart:not(.is-ready)').each(function() {
        initFormFields($(this));
    });

})
</script>

