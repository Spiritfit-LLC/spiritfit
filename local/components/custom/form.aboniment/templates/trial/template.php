<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetTitle($arResult["ELEMENT"]["~NAME"]);
$arInfoProps = Utils::getInfo()['PROPERTIES'];
foreach( $arResult["arAnswers"]["club"][0]['ITEMS'] as &$item ) {
    if( $item["NUMBER"] == $arResult["CLUB_ID"] ) {
        $item["SELECTED"] = "selected";
    }
}
unset($item);
?>
<div class="popup popup-path_to js-page-trial-training">
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__heading">Как добраться</div>
        <? foreach ($arResult["CLUB_PROPS"] as $key => $prop) {
            if ($prop["PROPERTY_PATH_TO_VALUE"]["TEXT"]){?>
        <div class="popup__desc" data-id="<?= $key ?>">
            <p><?= $prop["PROPERTY_PATH_TO_VALUE"]["TEXT"] ?></p>
        </div>
        <? }
        } ?>
    </div>
</div>

<div class="subscription fixed js-page-trial-training">
    <?
    if($arResult["ELEMENT"]["PREVIEW_PICTURE"]) {
		    $ogImage = CFile::GetPath($arResult["ELEMENT"]["PREVIEW_PICTURE"]);
    } else {
		    $ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
    } ?>
    <div id="seo-div" hidden="true" data-title="<?= $arResult['SEO']['ELEMENT_META_TITLE'] ?>" data-description="<?= $arResult['SEO']['ELEMENT_META_DESCRIPTION'] ?>" data-keywords="<?= $arResult['SEO']['ELEMENT_META_KEYWORDS'] ?>" data-image="<?= $ogImage ?>"></div>

    <div class="subscription__main">

        <div class="subscription__stage">

            <div class="subscription__stage-item subscription__stage-item--done">1. Выбор клуба</div>
            <div class="subscription__stage-item">2. Регистрация</div>
            <div class="subscription__stage-item">3. Подтверждение</div>
        </div>
        <div class="subscription__common">
            <h1 class="subscription__title"><?= $arResult["ELEMENT"]["~NAME"] ?></h1>
            <div class="subscription__desc"><?= $arResult["ELEMENT"]["PREVIEW_TEXT"] ?></div>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"]): ?>
            <div class="subscription__subheading">Услуги в подарок:</div>
            <ul class="subscription__gift">
                <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["FOR_PRESENT"]["ITEMS"] as $value): ?>
                <li class="subscription__gift-item"><?= $value ?></li>
                <? endforeach; ?>
            </ul>
            <? endif; ?>

            <? if ($arResult["ELEMENT"]["PROPERTIES"]["INCLUDE"]["VALUE"]): ?>
            <div class="subscription__subheading">Включено в тренировку:</div>
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
                <?= getClientParams($arParams["WEB_FORM_ID"]) ?>
                <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="sub_id" value="<?= $arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE'] ?>">
                <?if ($arResult["CLIENT_TYPE"]):?>
                <input type="hidden" name="typeSetClient" value="<?=$arResult["CLIENT_TYPE"]?>">
                <?endif;?>

                <div class="subscription__aside-form-row subscription__aside-form-row--title">

                </div>
                <!-- Список клубов -->

                <?php
                $clubs_price = array();
                foreach ($arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $tmp_price) {
                    $clubs_price[$tmp_price['LIST']] = $tmp_price['LIST'];
                }
                ?>
                <div class="subscription__aside-form-row">
                    <span class="subscription__total-text">Выберите клуб</span>
                </div>
                <div class="subscription__aside-form-row">

                    <select class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
                        <option value="">-</option>
                        <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem):
                            if(!isset($clubs_price[$arItem["ID"]])) continue;
                        ?>
                        <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>

                <div class="subscription__aside-form-row">
                    <input class="input input--light input--long input--text" type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"
                    <? endif; ?>>
                </div>

                <div class="subscription__aside-form-row">
                    <input class="input input--light input--long input--text" type="text" placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["email"]["REQUIRED"]): ?>required="required"
                    <? endif; ?>>
                </div>

                <div class="subscription__aside-form-row">
                    <input class="input input--light input--long input--tel" type="tel" placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"
                    <? endif; ?>>
                </div>

                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>">
                        <div class="input-label__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                    </label>
                </div>

                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>">
                        <div class="input-label__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                    </label>
                </div>

                <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
                <div class="subscription__aside-form-row">
                    <label class="input-label">
                        <input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["privacy"]['0']["FIELD_TYPE"] ?>_privacy[]" <?= $arResult["arAnswers"]["privacy"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["privacy"]['0']["ID"] ?>">
                        <div class="input-label__text"><?= $arResult["arQuestions"]["privacy"]["TITLE"] ?></div>
                    </label>
                </div>
                <?}?>

                <?if (strlen($arResult["ELEMENT"]["BASE_PRICE"]["PRICE"]) > 0 || $arResult["ELEMENT"]["PRICES"][0]["SIGN"] == 'Бесплатно'): ?>
                <input type="hidden" name="form_<?= $arResult["arAnswers"]["price"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["price"]['0']["ID"] ?>" value="<?= $arResult["ELEMENT"]["SALE"] ? $arResult["ELEMENT"]["SALE"] : $arResult["ELEMENT"]["PRICES"][0]["PRICE"] ?>">
                <input class="subscription__total-btn subscription__total-btn--reg btn btn--white" type="submit" value="<?= $arResult["arForm"]["BUTTON"] ?>" data-stage="1" name="web_form_submit">
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
                                    <p class="popup__legal-information__subtitle"></p>
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

<? 
$showAdditional = false;
if($showAdditional){ ?>
    <div class="abonement-additional-block">
        <? if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"])) { ?>
        <div class="club__subheading js-photogallery-title">Фотогалерея</div>
        <div class="club__slider js-photogallery-body">
            <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"] as $key => $photo) { ?>
            <div class="club__slider-item" data-src="<?= $photo ?>">
                <div class="club__slider-item-inner">
                    <div class="club__slider-item-text"><?= $arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["DESCRIPTION"][$key] ?></div>
                </div>
            </div>
            <? } ?>
        </div>
        <? } ?>
        <? if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"])) { ?>
        <div class="club__subheading">О клубе</div>
        <div class="video" style="background-image: url('<?= $arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"] ?>')">
            <div class="video__info">
                <div class="video__info-icon"></div>
                <div class="video__info-text">Смотреть <br> видео о клубе</div>
            </div>
        </div>
        <? } ?>
        <? if (!empty($arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"])) { ?>
        <div class="popup popup--video">
            <div class="popup__bg"></div>
            <div class="popup__window">
                <iframe width="1020" height="530" src="<?= $arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"] ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen="allowfullscreen"></iframe>
                <div class="popup__close">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <? } ?>
        <? if (isset($arResult["CLUB_PROPS"])) {

            $cord1 = $cord2 = "";
            foreach ($arResult["CLUB_PROPS"] as $key => $value) {
                $cord = explode(',', $value["PROPERTY_CORD_YANDEX_VALUE"]);
                if ($cord1 != "")
                    $cord1 .= ",".$cord[0];
                else
                    $cord1 = $cord[0];
                if ($cord2 != "")
                    $cord2 .= ",".$cord[1];
                else
                    $cord2 = $cord[1];
            }
            ?>
        <div class="club__subheading">Мы на карте</div>
        <div class="club__map" id="map" data-coord-mark-lat="<?= $cord1 ?>" data-coord-mark-lng="<?= $cord2 ?>" data-abonement="Y">
            <? foreach ($arResult["CLUB_PROPS"] as $key => $value) { ?>
            <div class="club_info" data-id="<?= $key ?>">
                <div class="club__map-address">
                    <?= $value["PROPERTY_ADRESS_VALUE"]["TEXT"] ?>
                    <?if ($value["PROPERTY_PATH_TO_VALUE"]["TEXT"]){ ?>
                    <div class="btn btn_path_to">
                        <span>Как добраться</span>
                    </div>
                    <? } ?>
                </div>
                <div class="club__map-contacts">
                    <a href="tel:<?= $value["PROPERTY_PHONE_LINK"] ?>"><?= $value["PROPERTY_PHONE_VALUE"] ?></a>
                    <br>
                    <a href="mailto:<?= $value["PROPERTY_EMAIL_VALUE"] ?>"><?= $value["PROPERTY_EMAIL_VALUE"] ?></a>
                </div>
                <?if ($value["PROPERTY_PATH_TO_VALUE"]["TEXT"]){ ?>
                <div class="btn btn_path_to">
                    <span>Как добраться</span>
                </div>
                <? } ?>
            </div>
            <? } ?>
        </div>
        <? } ?>
        <div class="club__links">
            <a class="club__links-item trial-button-link" href="#">
                <div class="club__links-item-text">Оставить заявку</div>
            </a>
        </div>
    </div>
<? } ?>

<script>
    $(document).ready(function() {
        $(".input--checkbox").styler();
        $(".input--tel").inputmask("+7 (999) 999-99-99");
        $(".input--num").on("input", function() {
            this.value = this.value.replace(/[^0-9]/gi, "");
        });
        $(".input--name").on("input", function() {
            this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
        });
    })
</script>
<?
// send name of club and abonement
$selectClub = '';
foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
    if(!empty($itemClub['SELECTED']) || $itemClub['NUMBER'] == $_SESSION['CLUB_NUMBER']){
        $selectClub = $itemClub['MESSAGE'];
    }
}

$abonementName = str_replace('<br>', ' ', $arResult['ELEMENT']['~NAME']);
if(!empty($selectClub)){
    $strSend = strip_tags($selectClub).'/'.$abonementName;
}else{
    $strSend = '-/'.$abonementName;
}
?>
<script>
    dataLayerSend('UX', 'openMembershipRegPage', '<?= $strSend ?>');
</script>
<?

if (!$_SERVER['HTTP_X_PJAX']) {
   $APPLICATION->AddViewContent('inhead', $ogImage);
}
    ?>
<script>
    $(document).ready(function(){
        $('.subscription__aside-form .popup__btn[type="submit"]').click(function(){
            var form = $(this).parents('form');
            var valid = true;

            $(this).parents('form').find('input:required').each(function() {
                if ($(this).attr('type') == 'checkbox') {
                    if ($(this).prop('checked') == false) valid = false;
                } else {
                    if ($(this).val() == '') valid = false;
                }
            });
            if (valid) {
                if ($(form).find('input[name="typeSetClient"]').length > 0){
                    var setClientData= {
                        'phone':$(form).find('[type="tel"]').val(),
                        'email':$(form).find('input[placeholder="<?=$arResult["arQuestions"]["email"]["TITLE"]?>"]').val(),
                        'setTypeClient':$(form).find('input[name="typeSetClient"]').val()
                    };

                    sendToUpMetrika(setClientData);
                    
                }
            }

        });
    });
</script>