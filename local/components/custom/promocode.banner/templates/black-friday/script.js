$(document).ready(function(){
    if (window.sessionStorage.getItem("bn_promocode")!=="1"){
        setTimeout(function(){
            $(".promocode-banner__container")
                .fadeIn(300)
                .css("display", "flex");

            $('body').css('overflow', 'hidden');
            dataLayerSend('bannerCatchDiscount', 'shown', bannerPromocodePage);
            window.sessionStorage.setItem("bn_promocode", "1");
        },bannerTime);


        $('.promocode-banner__closer').on(clickHandler, function(){
            $(".promocode-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCloseButton', bannerPromocodePage);
        });
    }
});