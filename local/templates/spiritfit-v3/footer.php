<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $settings;
?>
</main>
<?if (!defined("HIDE_FOOTER")):?>
<footer class="b-footer">
    <div class="content-center">
        <div class="footer-menu">
            <div class="footer-menu-cell">
                <div class="footer-menu-title"><a href="/about/">О компании</a></div>
                <ul>
                    <li><a href="https://career.spiritfit.ru/">Карьера</a></li>
                    <li><a href="/about/adv/">Рекламные возможности</a></li>
                    <li><a href="/about/platform/">Площадки под новые клубы</a></li>
                    <li><a href="/upload/form/Политика.pdf">Политика обработки персональных данных</a></li>
                    <li><a href="https://corp.spiritfit.ru">Корпоративное членство</a></li>
                </ul>
            </div>
            <div class="footer-menu-cell">
                <div class="footer-menu-title"><a href="/trenirovki/">Тренировки</a></div>
                <ul>
                    <li><a href="/training/gym/">Тренажерный зал</a></li>
                    <li><a href="/training/group-trainings/">Групповые тренировки</a></li>
                    <li><a href="/training/personal-training/">Персональный тренинг</a></li>
                    <li><a href="/training/online-trainings/">Тренировки онлайн</a></li>
                    <li><a href="/trenirovki/fitnes-testirovanie/">Фитнес-тестирование</a></li>
                    <li><a href="/schedule/">Расписание</a></li>
                </ul>
            </div>
            <div class="footer-menu-cell">
                <div class="footer-menu-title">Услуги</div>
                <ul>
                    <li><a href="/services/sauna/">Сауны и хаммам</a></li>
                </ul>
            </div>
            <div class="footer-menu-cell">
                <div class="footer-menu-title"><a href="/club-members/" class="dont-touch">Членам клуба</a></div>
                <ul>
                    <li><a href="/abonement/">Абонементы</a></li>
                    <li><a href="https://corp.spiritfit.ru/">Корпоративная программа</a></li>
                    <li><a href="/loyalty-program/">Программа лояльности</a></li>
                    <li><a href="/club-members/partners/">Партнёры и привилегии</a></li>
                    <li><a href="/faq/">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-menu-cell">
                <div class="footer-menu-title">Жизнь Spirit.People</div>
                <ul>
                    <li><a href="/spirittv/">Spirit. TV</a></li>
                    <li><a href="/blog/">Блог</a></li>
                    <li><a href="/akcii/">Акции</a></li>
                </ul>
                <a rel="nofollow" class="b-social-link" href="https://t.me/spiritfitness_official" target="_blank"><span class="b-social-link__img-holder"><img class="b-social-link__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/telegram-brands.svg" alt="@spiritmoscow" title=""></span><span class="b-social-link__text"></span></a>
                <a rel="nofollow" class="b-social-link" href="https://vk.com/spiritmoscow" target="_blank"><span class="b-social-link__img-holder"><img class="b-social-link__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/vk-brands.svg" alt="@spiritmoscow" title=""></span><span class="b-social-link__text"></span></a>
                <a rel="nofollow" class="b-social-link" href="http://www.tiktok.com/@spiritfitness" target="_blank"><span class="b-social-link__img-holder"><img class="b-social-link__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/tiktok-brands.svg" alt="@spiritmoscow" title=""></span><span class="b-social-link__text"></span></a>
                <a rel="nofollow" class="b-social-link" href="https://zen.yandex.ru/id/6017f57288bc0d2cb7405dc6" target="_blank"><span class="b-social-link__img-holder"><img class="b-social-link__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/zen-brands.png"></span><span class="b-social-link__text"></span></a>
            </div>
        </div>
        <div class="b-footer__content">
            <div class="b-footer__app-buttons">
                <div class="b-app-list"><a rel="nofollow" class="b-app-list__button"
                                           href="<?=$settings["PROPERTIES"]["LINK_APPSTORE"]["VALUE"]?>" target="_blank"><img
                                class="b-app-list__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/btn-app-store.svg" alt="Загрузите в App Store"
                                title="" /></a><a rel="nofollow" class="b-app-list__button" href="<?=$settings["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"]?>" target="_blank"><img
                                class="b-app-list__img lazy" data-src="<?=SITE_TEMPLATE_PATH?>/img/btn-google-play.svg" alt="Доступно в Google Play"
                                title="" /></a>
                </div>
            </div>
            <div class="b-footer__social">
                <div class="footer-phone-wrapper"><a class="footer-phone phone-btn" data-position="footer" href="tel:84951059797">8 495 105 97 97</a></div>
            </div>

        </div>
    </div>
</footer>
<script>
<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse')===false):?>
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
        background-image: url(/local/templates/spiritfit-v3/img/open-lines-background.png);
        /*background-color: black;*/
    }
    .bx-livechat-head{
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);;
    }
    .bx-livechat-bright-header .bx-livechat-title {
        color: white!important;
        font-weight: 700;
        font-size: 18px;
        font-family: 'Gotham Pro';
    }
    .bx-livechat-textarea-resize-handle{
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);;
    }
    .bx-im-textarea{
        padding:20px 0 0 0;
    }
    .b24-widget-button-inner-item{
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);;
        background-color: transparent;
    }
    .b24-widget-button-position-bottom-right{
        bottom:37px!important;
    }
    .b24-widget-button-pulse{
        border-color: #8428DD!important;
    }
    .b24-widget-button-inner-mask{
        background: #8428DD!important;
    }
    button.b24-form-btn{
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);;
    }
    .bx-imopenlines-form-result-container.bx-imopenlines-form-success {
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);!important;
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
        background-image: linear-gradient(135deg, #E23834 3.26%, #8428DD 98.07%);;
    }
    a.b24-widget-button-social-item.b24-widget-button-openline_livechat {
        background-color: #8428DD!important;
    }
    a.b24-widget-button-social-item.b24-widget-button-callback {
        background-color: #8428DD!important;
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
<?endif;?>
<?php endif?>
</body>
<?
$inHead = $APPLICATION->GetViewContent('inhead');
if( empty($inHead) ) {
    $APPLICATION->AddViewContent('inhead', 'https://spiritfit.ru/images/logo_white.svg');
}
?>
</html>
