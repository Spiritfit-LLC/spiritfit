<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
$settings = Utils::getInfo();
?>
	</main>
    <footer class="b-footer">
        <div class="content-center">
            <div class="b-footer__content">
                <div class="b-footer__social"><a class="b-social-link" href="<?=$settings["PROPERTIES"]["LINK_INSTAGRAM"]["VALUE"]?>"
                        target="_blank"><span class="b-social-link__img-holder"><img class="b-social-link__img"
                                src="<?=SITE_TEMPLATE_PATH?>/img/icon-inst.svg" alt="@spiritmoscow" title="" /></span><span
                            class="b-social-link__text">Instagram</span></a>
                </div>
                <div class="b-footer__app-buttons">
                    <div class="b-app-list"><a class="b-app-list__button"
                            href="<?=$settings["PROPERTIES"]["LINK_APPSTORE"]["VALUE"]?>" target="_blank"><img
                                class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-app-store.svg" alt="Загрузите в App Store"
                                title="" /></a><a class="b-app-list__button" href="<?=$settings["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"]?>" target="_blank"><img
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
                <? $APPLICATION->IncludeFile("/local/templates/spiritfit-corp/ajax/modal.php"); ?>
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
                <? $APPLICATION->IncludeFile("/local/templates/spiritfit-corp/ajax/modal-trial.php"); ?>
            </div>
        </div>
    </div>

    <script src='/local/templates/spiritfit-corp/js/cookieAllowAccess.js' async></script>
</body>