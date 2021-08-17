<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arField = ['name', 'surname', 'email', 'phone'];
?>
<div class="form-standart form-standart_tpl-ver form-standart_white-bg">
    <div class="form-standart__plate">
        <div class="subscription fixed">
            <div class="subscription__main">
                <div class="subscription__ready" style="display: block;">
                    <div class="subscription__title">Абонемент готов</div>
                    <div class="subscription__desc"><?= $arResult["ELEMENT"]["~DETAIL_TEXT"] ?></div>
                    <div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
                    <div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
                    </div>
                </div>
            </div>

            <div class="subscription__aside">
                <div class="subscription__aside-stage" data-stage="1">
                    <form class="subscription__aside-form_v2" name="<?= $arResult["WEB_FORM_NAME"] ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
                        <?=getClientParams($arParams["WEB_FORM_ID"]) ?>
                        <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>">
                        <input type="hidden" name="step" value="3">
                        <input type="hidden" name="sub_id" value="<?=$arResult["ELEMENT"]["PROPERTIES"]['CODE_ABONEMENT']['VALUE']?>">
                        <? foreach ($arResult["HIDDEN_FILEDS"] as $name => $value): ?>
                            <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                        <? endforeach; ?>
                        

                        <div class="form-standart__fields-list">
                            <div class="form-standart__field">
                                <div class="form-standart__item">
                                    <div class="form-standart__inputs" style="display: none;">
                                        <select disabled data-placeholder="Выберите клуб" data-necessary="" name="form_<?= $arResult["arAnswers"]["club"]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"]["club"]['0']["ID"] ?>" required>
                                            <option></option>
                                            <? foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as $key => $arItem): ?>
                                                <option value="<?= $arItem["NUMBER"] ?>"><?= $arItem["MESSAGE"] ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-standart__message">
                                        <div class="form-standart__none">Сделайте выбор</div>
                                    </div>
                                </div>
                            </div>

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
                                            <input autocomplete="off" class="form-standart__input" type="<?=$type?>" data-necessary="" name="form_<?= $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] ?>_<?= $arResult["arAnswers"][$itemField]['0']["ID"] ?>" <?=($arResult["arQuestions"][$itemField]["REQUIRED"] ? 'required="required"' : '')?> value="<?=$_REQUEST["form_" . $arResult["arAnswers"][$itemField]['0']["FIELD_TYPE"] . "_" . $arResult["arAnswers"][$itemField]['0']["ID"]] ?>" />
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
                                        <input class="b-checkbox__input" type="checkbox" required="required" name="agreement3" checked id="agr1" data-necessary="">
                                        
                                        <span class="b-checkbox__text">Cогласен с <a href="#">Договором афферты</a>, <a href="#">Правилами клуба</a>, <a href="#">Списанием денежных средств</a></span>
                                    </label>
                                    <div class="form-standart__message">
                                        <div class="form-standart__error">Необходимо ваше согласие</div>
                                    </div>
                                </div>

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

                                <? if ($arResult["ELEMENT"]["PRICES"][0]["PRICE"]): ?>
                                    <a href="<?= $arResult["RESPONSE"]["data"]["result"]["result"]["formUrl"] ?>" target="_blank"
                                    class="subscription__total-btn subscription__total-btn--pay btn btn--white js-btn-pay_v2">
                                        Получить счет
                                    </a>
                                <? endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?
            // send name of club and abonement
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

            ?>
			<?
				if($arParams["PREV_STEP"] == 2 && $abonementName == 'Домашние тренировки') {
    				?><script>dataLayerSend('UX', 'sendContactFormHomeWorkout', '<?=$strSend?>')</script><?        
				} else if($arParams["PREV_STEP"] == 2) {
    				?><script>dataLayerSend('conversion', 'sendContactForm', '<?=$strSend?>')</script><?
				}
			?>
			<script>dataLayerSend('UX', 'openMembershipReadyPage', '<?=$strSend?>');</script>
			<?

            $selectName = "form_".$arResult["arAnswers"]["club"]['0']["FIELD_TYPE"]."_".$arResult["arAnswers"]["club"]['0']["ID"];
            if(!empty($_REQUEST[$selectName])){ ?>
                <script>
                    $('#modalForm').find('select').val(<?=$_REQUEST[$selectName]?>);
                </script>
            <? } ?>

            <!-- Вывод ошибки в popup -->
            <? if ($arResult["RESPONSE"]["data"]["result"]["errorCode"] !== 0 && $arResult["RESPONSE"]["data"]["result"]["userMessage"] != ""): ?>
                <?
                $settings = Utils::getInfo(); 
                if ($settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]]) {
                    $errorMessage = $settings['PROPERTIES']["ERROR_MESSAGE"][$arResult["RESPONSE"]["data"]["result"]["errorCode"]];
                } else {
                    $errorMessage = $arResult["RESPONSE"]["data"]["result"]["userMessage"];
                }
                ?>
                <div class="popup popup--call popup-info" style="display: block;">
                    <div class="popup__bg"></div>
                    <div class="popup__window">
                        <div class="popup__close">
                            <div></div>
                            <div></div>
                        </div>
                        <div class="popup__success"><?= $errorMessage ?></div>
                    </div>
                </div>

            <? endif; ?>
        </div>
    </div>
</div>