<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<div id="trial-subscription-fragment">
    <div class="subscription subscription--standalone js-page-trial-training">
        <div class="subscription__aside subscription__aside--standalone">
            <div class="subscription__aside-stage" data-stage="1">
                <form class="subscription__aside-form-trial" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="/abonement/modal.php?ELEMENT_CODE=probnaya-trenirovka" method="POST" enctype="multipart/form-data">
                    <?= getClientParams($arParams["WEB_FORM_ID"]) ?>
                    <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams["WEB_FORM_ID"] ?>">
                    <input type="hidden" name="step" value="1">
                    <input type="hidden" name="sub_id" value="<?= $arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE'] ?>">
                    <input type="hidden" name="form_checkbox_legal-information" value="1">
                    <div class="subscription__aside-form-row subscription__aside-form-row--title subscription__aside-form-row--standalone">

                    </div>
                    <!-- Список клубов -->

                    <?php
                    $clubs_price = array();
                    foreach ($arResult["ELEMENT"]["PROPERTIES"]["PRICE"]["VALUE"] as $tmp_price) {
                        $clubs_price[$tmp_price['LIST']] = $tmp_price['LIST'];
                    }
                    ?>
                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <span class="subscription__total-text">Отправьте заявку и получите скидку</span>
                    </div>
                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">

                        <select class="input input--light input--long input--select js-pjax-select" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
                            <option value="">-</option>
                            <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem):
                                if(!isset($clubs_price[$arItem["ID"]])) continue;
                            ?>
                            <option value="<?= $arItem["NUMBER"] ?>" <?= $arItem["SELECTED"] ?>><?= $arItem["MESSAGE"] ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>

                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <input class="input input--light input--long input--text" type="text" placeholder="<?= $arResult["arQuestions"]["name"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["name"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["name"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["name"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["name"]["REQUIRED"]): ?>required="required"
                        <? endif; ?>>
                    </div>

                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <input class="input input--light input--long input--text" type="text" placeholder="<?= $arResult["arQuestions"]["email"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["email"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["email"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["email"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["email"]["REQUIRED"]): ?>required="required"
                        <? endif; ?>>
                    </div>

                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <input class="input input--light input--long input--tel" type="tel" placeholder="<?= $arResult["arQuestions"]["phone"]["TITLE"] ?>" value="<?= $_REQUEST["form_" . $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"]["phone"]['0']["ID"]] ?>" name="form_<?= $arResult["arAnswers"]["phone"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["phone"]['0']["ID"] ?>" <? if ($arResult["arQuestions"]["phone"]["REQUIRED"]): ?>required="required"
                        <? endif; ?>>
                    </div>

                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <label class="input-label">
                            <input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["personal"]['0']["FIELD_TYPE"] ?>_personal[]" <?= $arResult["arAnswers"]["personal"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["personal"]['0']["ID"] ?>">
                            <div class="input-label__text"><?= $arResult["arQuestions"]["personal"]["TITLE"] ?></div>
                        </label>
                    </div>

                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
                        <label class="input-label">
                            <input class="input input--checkbox" type="checkbox" required="required" name="form_<?= $arResult["arAnswers"]["rules"]['0']["FIELD_TYPE"] ?>_rules[]" <?= $arResult["arAnswers"]["rules"]['0']["FIELD_PARAM"] ?> value="<?= $arResult["arAnswers"]["rules"]['0']["ID"] ?>">
                            <div class="input-label__text"><?= $arResult["arQuestions"]["rules"]["TITLE"] ?></div>
                        </label>
                    </div>

                    <?if($arResult["arQuestions"]["privacy"]['ACTIVE'] == "Y") {?>
                    <div class="subscription__aside-form-row subscription__aside-form-row--standalone">
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
        <style>
            .subscription__aside--standalone {
                width: 100%;
                height: 660px;
                margin-top: 0;
                margin-bottom: 0;
                padding: 70px 130px 40px;
                position: relative;
            }

            .subscription__aside-form-row--standalone {
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
            }

            .subscription__aside-form-row .input--long {
                width: 100%;
            }

            .subscription.subscription--standalone {
                padding: 0;
            }

            @media screen and (max-width: 1260px),
            screen and (min-aspect-ratio: 76/35) and (min-width: 1260px) {
                .subscription.subscription--standalone {
                    padding: 0;
                }

                .subscription__aside-form-row--standalone input.input--long {
                    width: 100%;
                }

                .subscription__aside--standalone {
                    padding-left: 10%;
                    padding-right: 10%;
                }
            }

            @media screen and (max-width: 768px) {
                .subscription__aside--standalone {
                    min-width: 100vw;
                }
            }
        </style>

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

                $('select.input--select').each(function() {
                    if (!$(this).parent().is('.jq-selectbox')) {
                        !!navigator.platform.match(/(iPhone|iPod|iPad|Pike)/i) || $(this).styler({
                            selectSmartPositioning: !1
                        })
                    }
                });

                var sendFormTrial = function(ext, url) {
                    var form = $(".subscription__aside-form-trial").serializeArray();

                    var data = {};
                    for (var i in form) {
                        data[form[i].name] = form[i].value;
                    }

                    for (var i in ext) {
                        data[i] = ext[i];
                    }
                    $.pjax({
                        timeout: false,
                        type: 'POST',
                        dataType: 'html',
                        data: data,
                        url: url,
                        container: '#trial-subscription-container',
                        push: false,
                        scrollTo: false,
                        fragment: '#trial-subscription-fragment'
                    });
                }
                $('.subscription__aside-form-trial').on('submit', function(e) {
                    e.preventDefault();
                    var action = $(this).attr('action');
                    sendFormTrial({
                        "ajax_send": "Y",
                        "club": $('.subscription__aside-form-trial').find('select.js-pjax-select').val(),
                        "ajax_menu": false
                    }, action);
                    return false;
                });
            })
        </script>

        <? // send name of club and abonement
        $selectClub = '';
        $selectClubSession = '';
        foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $itemClub) {
            if($itemClub['NUMBER'] == $_SESSION['CLUB_NUMBER']) {
                $selectClubSession = $itemClub['MESSAGE'];
            }
            if(!empty($itemClub['SELECTED'])){
                $selectClub = $itemClub['MESSAGE'];               
            }
        }

        if(empty($selectClub)){
            $selectClub = $selectClubSession;
        }

        $abonementName = str_replace('<br>', ' ', $arResult['ELEMENT']['~NAME']);
        if(!empty($selectClub)){
            $strSend = strip_tags($selectClub).'/'.$abonementName;
        }else{
            $strSend = '-/'.$abonementName;
        }
        $_SESSION['E_LABEL_SEND_CONTACT_FORM_SHORT'] = $_REQUEST['aboniment'];
        $eLabel = $_SESSION['E_LABEL_SEND_CONTACT_FORM_SHORT']."/".$selectClub;
        ?>
        <script>
         /*    if (window.dataLayerSend) {
                dataLayerSend('UX', 'openMembershipRegPage', '<?= $strSend ?>');
            } */
            (dataLayer = window.dataLayer || []).push({
                'eCategory': 'UX',
                'eAction': 'openMembershipRegPageShort',
                'eLabel': '<?= $eLabel ?>', // указывать название выбранного абонемента и клуба
                'eNI': false,
                'event': 'GAEvent'
            });
        </script>
    </div>
</div>