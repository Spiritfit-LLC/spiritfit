<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
$arInfoProps = Utils::getInfo()['PROPERTIES'];
$settings = Utils::getInfo();
?>

<title><?= strip_tags($arResult["ELEMENT"]["~NAME"]) ?></title>

<div class="subscription fixed">
	<a href="<?= $arResult["ELEMENT"]["LIST_PAGE_URL"] ?>" class="subscription__close js-pjax-link">
		<div></div>
		<div></div>
	</a>
    <?
    if($arResult["ELEMENT"]["PREVIEW_PICTURE"]) {
		    $ogImage = CFile::GetPath($arResult["ELEMENT"]["PREVIEW_PICTURE"]);
    } else { 
		    $ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
    }
	?>
    <div id="seo-div" hidden="true"
	 	 data-title="<?=$arResult['SEO']['ELEMENT_META_TITLE']?>" 
		 data-description="<?=$arResult['SEO']['ELEMENT_META_DESCRIPTION']?>" 
         data-keywords="<?=$arResult['SEO']['ELEMENT_META_KEYWORDS']?>"
         data-image="<?=$ogImage?>"></div>
	<div class="subscription__main">
        <div class="subscription__stage">
            <div class="subscription__stage-item subscription__stage-item--done">1. Регистрация</div>
            <div class="subscription__stage-item">2. Оформление</div>
            <div class="subscription__stage-item">3. Оплата</div>
        </div>
        <div class="subscription__common">
            <div class="subscription__title"><?= $arResult["ELEMENT"]["~NAME"] ?></div>
            <div class="subscription__desc"><?= $arResult["ELEMENT"]["PREVIEW_TEXT"] ?></div>

            <? if ($arResult["ELEMENT"]["PRICES"]): ?>
                <div class="subscription__label">
                    <? foreach ($arResult["ELEMENT"]["PRICES"] as $key => $arPrice): ?>
                        <div class="subscription__label-item">
                            <?= $arPrice["SIGN"] ?>
                            <?if (strlen($arResult["ELEMENT"]["BASE_PRICE"]["PRICE"]) > 0){?>
                                <?if ($key == 0 && $arResult["ELEMENT"]["SALE"]) {?>
                                    - <b><?= $arResult["ELEMENT"]["SALE"] ?> &#x20bd;</b>
                                <?}elseif($key == 1 && $arResult["ELEMENT"]["SALE_TWO_MONTH"]){?>
                                    - <b><?= $arResult["ELEMENT"]["SALE_TWO_MONTH"] ?> &#x20bd;</b>
                                <?}else{?>
                                    <? if ($arPrice["PRICE"]  && $arPrice["PRICE"] != " "): ?>
                                            - <b><?= $arPrice["PRICE"] ?> &#x20bd;</b>
                                    <? endif; ?>
                                <?}?>
                            <?}?>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif; ?>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"]): ?>
                <div class="subscription__subheading">Услуги в подарок:</div>
                <ul class="subscription__gift">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"] as $value): ?>
                        <li class="subscription__gift-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"]): ?>
                <div class="subscription__subheading">Включено в абонемент:</div>
                <ul class="subscription__include">
                    <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"] as $value): ?>
                        <li class="subscription__include-item"><?= $value ?></li>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>
        </div>
    </div>
    <div class="subscription__aside">
        <div class="subscription__aside-stage" data-stage="1">
            <form class="subscription__aside-form" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
		    	<?=getClientParams($arParams["WEB_FORM_ID"]) ?>
                <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
                <input type="hidden" name="two_month" value="0">
                <!-- Список клубов -->
                <div class="subscription__aside-form-row subscription__aside-form-row--title">
                </div>
                <div class="subscription__aside-form-row">
                    <select class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>">
                        <option value="Выберите клуб">Выберите клуб</option>
                        <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                            <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>

                <div class="subscription__aside-form-row">
                    <input
                        class="input input--light input--short input--text" 
                        type="text" 
                        placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>"
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                    <input
                        class="input input--light input--short input--text" 
                        type="text" 
                        placeholder="<?= $arResult["arQuestions"]["surname"]["TITLE"] ?>"
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["surname"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["surname"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["surname"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["surname"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                </div>
                
                <div class="subscription__aside-form-row">
                    <input
                        class="input input--light input--short input--tel" 
                        type="tel" 
                        placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" 
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                    <input
                        class="input input--light input--short input--text" 
                        type="email" 
                        placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" 
                        value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>"
                        name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" 
                        <? if ($arResult["arQuestions"]["email"]["REQUIRED"]): ?>required="required"<? endif; ?>
                    >
                </div>
                
                <div class="subscription__promo">
                    <input 
                        class="subscription__promo-input input input--light input--short input--text" 
                        type="text" 
                        placeholder="ПРОМОКОД" 
                        name="promo"
                    >
                    <a class="subscription__promo-btn" href="#">Применить</a>
                </div>

                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input 
                            class="input input--checkbox" 
                            type="checkbox"
                            required="required"
                            name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"]?>_personal[]" 
                            <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?>
                            value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>"
                        >
                        <div class="input-label__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                    </label>
                </div>

                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input 
                            class="input input--checkbox" 
                            type="checkbox"
                            required="required"
                            name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"]?>_rules[]" 
                            <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?>
                            value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>"
                        >
                        <div class="input-label__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                    </label>
                </div>

                <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input 
                            class="input input--checkbox" 
                            type="checkbox"
                            required="required"
                            name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"]?>_privacy[]" 
                            <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?>
                            value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>"
                        >
                        <div class="input-label__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></div>
                    </label>
                </div>
                <?}?>

                <?if (strlen($arResult["ELEMENT"]["BASE_PRICE"]["PRICE"]) > 0 || $arResult["ELEMENT"]["PRICES"][0]["SIGN"] == 'Бесплатно'): ?>
                    <div class="subscription__bottom">
                        <div class="subscription__total">
                            <div class="subscription__total-text"><?= $arResult["arQuestions"]["price"]["TITLE"] ?></div>
                            <div class="subscription__total-value">
                                <? if ($arResult["ELEMENT"]["SALE"]): ?>
                                    <div class="subscription__total-value-old">
                                        <span><?= $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?> &#x20bd;</span>
                                    </div>
                                    <?= $arResult["ELEMENT"]["SALE"] ?> &#x20bd;
                                <? else: ?>
                                    <?= $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?> &#x20bd;
                                <? endif; ?>
                            </div>
                            <? if ($arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] && $arResult["ELEMENT"]["SALE"]): ?>
                                <div class="subscription__total-subtext"><?= $arResult["ELEMENT"]["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
                            <? endif; ?>
                        </div>
                        <input
                            type="hidden" 
                            name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"]?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>"
                            value="<?= $arResult["ELEMENT"]["SALE"] ? $arResult["ELEMENT"]["SALE"] : $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?>"
                            >
                        </div>
                        <input type="submit" class="subscription__total-btn subscription__total-btn--reg btn btn--white" data-stage="1" name="web_form_submit" value="<?= $arResult["arForm"]["BUTTON"] ?>">
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
                                        <input class="popup__btn btn subscription__total-btn subscription__total-btn--reg subscription__total-btn--legal-information" type="submit" value="Согласен" data-stage="1" name="web_form_submit" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
            </form>
        </div>
    </div>

    

<script>
    $(".input--checkbox").styler();
    // $(".input--tel").inputmask("+7 (999) 999-99-99");
    $(".input--num").on("input", function () {
        this.value = this.value.replace(/[^0-9]/gi, "");
    });
    $(".input--name").on("input", function () {
        this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
    });
    
 </script>
 <?
if (!$_SERVER['HTTP_X_PJAX']) {
	$APPLICATION->AddViewContent('inhead', $ogImage);
}
?>