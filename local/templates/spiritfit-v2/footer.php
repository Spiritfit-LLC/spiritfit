<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
$settings = Utils::getInfo();
?>
<?php //if (!defined('PERSONAL_PAGE')):?>
<?php
//$APPLICATION->IncludeComponent(
//    "custom:personal.onpageinfo",
//    "",
//    Array(
//        "PROFILE_URL" => "/personal/",
//        "AUTH_FORM_CODE" => "AUTH",
//        "REGISTER_FORM_CODE" => "REGISTRATION",
//        "PASSFORGOT_FORM_CODE"=>'PASSFORGOT',
//        "SHOW_ERRORS" => "Y"
//    ),
//    false
//);
//?>
<?php //endif;?>
	</main>
	<footer class="b-footer">
		<div class="content-center">
			<?
				$APPLICATION->IncludeFile(SITE_DIR."local/include/footer_menu.php", Array("settings" => $settings), Array("MODE" => "html", "NAME" => "", "TEMPLATE" => ""));
			?>
            <div class="b-footer__content">
                <div class="b-footer__social">
					<div class="footer-phone-wrapper"><a class="footer-phone" href="tel:84951059797">8 495 105 97 97</a></div>
                </div>
                <div class="b-footer__app-buttons">
                    <div class="b-app-list"><a rel="nofollow" class="b-app-list__button"
                            href="<?=$settings["PROPERTIES"]["LINK_APPSTORE"]["VALUE"]?>" target="_blank"><img
                                class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-app-store.svg" alt="Загрузите в App Store"
                                title="" /></a><a rel="nofollow" class="b-app-list__button" href="<?=$settings["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"]?>" target="_blank"><img
                                class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-google-play.svg" alt="Доступно в Google Play"
                                title="" /></a>
                    </div>
                </div><a class="b-footer__btn button-outline custom-button" href="#feedback-choice"
                    data-fancybox="feedback-choice"
                    data-options="{'autoFocus' : false, 'backFocus': false}">Обратная связь</a>
            </div>
		</div>
		<div class="b-footer__feedback-choice text-center is-hide" id="feedback-choice">
            <div class="block-margin h2">Выберите действие</div>
            <div class="b-footer__buttons-wrapper">
                <div class="button-outline b-footer__button button-margin js-popup-call_v2">Заявка на звонок</div>
                <div class="button-outline b-footer__button button-margin js-popup-message_v2">Отправить сообщение</div>

            </div>
        </div>
	</footer>
    <div id="modalForm" class="popup modalForm" style="display: none;">
        <div class="popup__bg"></div>
        <div class="popup__window popup__window--modal-form">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div id="js-pjax-container" class="popup__wrapper-form">
                <? $APPLICATION->IncludeFile("/local/ajax/modal.php"); ?>
            </div>
        </div>
    </div>

    <div id="modalFormTrial" class="popup modalForm" style="display: none;">
        <div class="popup__bg"></div>
        <div class="popup__window popup__window--modal-form">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div id="js-pjax-container-trial" class="popup__wrapper-form">
                <? $APPLICATION->IncludeFile("/local/ajax/modal-trial.php"); ?>
            </div>
        </div>
    </div>
    <div style="display: none;">+7 (495) 292-67-97</div>
    <script src='/local/templates/spiritfit-v2/js/cookieAllowAccess.js' async></script>
	<script src="<?=SITE_TEMPLATE_PATH . "/vendor/jquery.maskedinput/jquery.maskedinput.js"?>"></script>
	<script src="<?=SITE_TEMPLATE_PATH . "/vendor/perfect-scrollbar/perfect-scrollbar.min.js"?>"></script>
	<script src="<?=SITE_TEMPLATE_PATH . "/vendor/nicescroll/jquery.nicescroll.js"?>"></script>
	<script src="<?=SITE_TEMPLATE_PATH . "/vendor/select2/select2.js"?>"></script>
    <script>
        (function (d, o, w, c) {
        a = d.createElement(o),
        m = d.getElementsByTagName(o)[0],
        a.async = 1;
        a.referrerPolicy = "no-referrer-when-downgrade";
        a.src = w;
        a.setAttribute('data-business', 'spiritfit');
        m.parentNode.insertBefore(a, m);
        })(document, 'script', '//upmetrics.ru/upmetric.min.js');
    </script>
</body>
<?
	$inHead = $APPLICATION->GetViewContent('inhead');
	if( empty($inHead) ) {
		$APPLICATION->AddViewContent('inhead', 'https://spiritfit.ru/images/logo_white.svg');
	}
?>