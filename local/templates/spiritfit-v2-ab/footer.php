<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$settings = Utils::getInfo();
?>
    </main>
    <footer class="b-footer">
        <div class="content-center">
            <?
            $APPLICATION->IncludeFile(SITE_DIR."local/include/footer_menu.php", Array("settings" => $settings), Array("MODE" => "html", "NAME" => "", "TEMPLATE" => ""));
            ?>
            <div class="b-footer__content">
                <div class="b-footer__app-buttons">
                    <div class="b-app-list"><a rel="nofollow" class="b-app-list__button"
                                               href="<?=$settings["PROPERTIES"]["LINK_APPSTORE"]["VALUE"]?>" target="_blank"><img
                                    class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-app-store.svg" alt="Загрузите в App Store"
                                    title="" /></a><a rel="nofollow" class="b-app-list__button" href="<?=$settings["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"]?>" target="_blank"><img
                                    class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-google-play.svg" alt="Доступно в Google Play"
                                    title="" /></a>
                    </div>
                </div>
                <div class="b-footer__social">
                    <div class="footer-phone-wrapper"><a class="footer-phone phone-btn" data-position="footer" href="tel:84951059797">8 495 105 97 97</a></div>
                </div>

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
        })(document, 'script', 'https://upmetrics.ru/upmetric.min.js');
    </script>
    <script>
        window.addEventListener('onBitrixLiveChat', function(event){
            var widget = event.detail.widget;
            widget.setOption('checkSameDomain', false);
        });

        window.addEventListener('b24:form:submit', (event) => {
            var form=event.detail.object;

            if (form.identification.id==10){
                dataLayerSend('conversion', 'sendFormCallback', '');
            }
            // var fields=event.detail.object.values();
            // var properties=event.detail.object.getProperties();
            //
            // if (window.sbjs.get.current !== undefined) {
            //     var current = window.sbjs.get.current;
            //     var utm={};
            //     utm.src=current.src;
            //     utm.mdm=current.mdm;
            //     utm.cmp=current.cmp;
            //     utm.cnt=current.cnt;
            //     utm.trm=current.trm;
            // }
            //
            // var data={
            //     "fields":fields,
            //     "properties":properties,
            //     "utm":utm
            // }
        });

        window.addEventListener('b24:form:init', (event) => {

            let form = event.detail.object;
            form.setProperty("google_id", getGaId());
            form.setProperty("yandex_id", getYaId());

            if (form.identification.id==10){
                form.setView({
                    type:'popup',
                    position:'center'
                })
            }

            form.getFields().forEach((field)=>{
                if (field.type==="phone"){
                    var phoneValidator=function(value){
                        var phone=value.replace(/[^0-9]/g,"");
                        return phone.length===11 && phone[1]==="9";
                    }
                    field.validators.push(phoneValidator);
                    return false;
                }
            });
        });

        window.addEventListener('b24:form:field:change:selected', (event)=>{
            let form = event.detail.object;
            var value=form.values().CONTACT_PHONE[0];

            if (value!==undefined){
                value="+7 (9"+value.substr(5, 13);
            }
            else{
                value="+7 (9";
            }

            form.getFields().forEach((field)=>{
                if (field.type==="phone")
                    field.items[0].value=value;
            });
        });

        window.addEventListener('b24:form:send:success', (event)=>{
            let form = event.detail.object;
            if (form.identification.id==10){
                setConversion("CallbackConversion");
            }
        });

        (function(w,d,u){
            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://portal.spiritfit.ru/upload/crm/site_button/loader_2_shz3j6.js');
    </script>
<?
$APPLICATION->IncludeComponent("bitrix:b24connector.openline.info","", Array(
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "DATA" => "",
        "GA_MARK" => ""
    )
);
?>
    <style>
        .bx-livechat-body{
            background-image: url("<?=SITE_TEMPLATE_PATH.'/img/open-lines-background.png'?>");
            /*background-color: black;*/
        }
        .bx-livechat-head{
            background-image: linear-gradient(90deg, #7f4790, #ff4f38);
        }
        .bx-livechat-bright-header .bx-livechat-title {
            color: white!important;
            font-weight: 700;
            font-size: 18px;
            font-family: 'Gotham Pro';
        }
        .bx-livechat-textarea-resize-handle{
            background-image: linear-gradient(90deg, #7f4790, #ff4f38);
        }
        .bx-im-textarea{
            padding:20px 0 0 0;
        }
        .b24-widget-button-inner-item{
            background-image: linear-gradient(90deg, #7f4790, #ff4f38);
            background-color: transparent;
        }
        .b24-widget-button-position-bottom-right{
            bottom:37px!important;
        }
        .b24-widget-button-pulse{
            border-color: #7f4790!important;
        }
        .b24-widget-button-inner-mask{
            background: #7f4790!important;
        }
        button.b24-form-btn{
            background-image: linear-gradient(90deg, #7f4790, #ff4f38);
        }
        .bx-imopenlines-form-result-container.bx-imopenlines-form-success {
            background-image: linear-gradient(90deg, #7f4790, #ff4f38)!important;
            border:none!important;
        }
        .b24-form-control-container input:-webkit-autofill {
            -webkit-box-shadow: inset 0 0 0 50px #272c2f !important; /* Цвет фона */
            -webkit-text-fill-color: #999 !important; /* цвет текста */
            color: #999 !important; /* цвет текста */
        }
        .bx-imopenlines-message-dialog-number{
            display: none!important;
        }
        button.b24-window-close {
            background-color: transparent;
        }
        .b24-widget-button-inner-block {
            background-image: linear-gradient(90deg, #7f4790, #ff4f38);
        }
        a.b24-widget-button-social-item.b24-widget-button-openline_livechat {
            background-color: #9f497a!important;
        }
        a.b24-widget-button-social-item.b24-widget-button-callback {
            background-color: #9f497a!important;
        }
        .b24-window-mounts {
            position: absolute;
            z-index: 10001;
        }
        .b24-form {
            position: absolute;
            z-index: 10000;
        }
        .b24-form-state.b24-form-success {
            background: white;
        }
        .b24-form-state-text {
            font-family: "Gotham Pro",sans-serif !important;
            font-weight: 600;
        }
        .b24-form-state-icon.b24-form-success-icon {
            display: none;
        }

    </style>
    </body>
<?
$inHead = $APPLICATION->GetViewContent('inhead');
if( empty($inHead) ) {
    $APPLICATION->AddViewContent('inhead', 'https://spiritfit.ru/images/logo_white.svg');
}
?>