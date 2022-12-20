$(document).ready(function(){
    if (!window.sessionStorage.getItem("bn_promocode")){
        setTimeout(function(){
            $(".promocode-gp-banner__container")
                .fadeIn(300)
                .css("display", "flex");

            $('body').css('overflow', 'hidden');
            dataLayerSend('bannerCatchDiscount', 'shown', bannerPromocodePage);
            window.sessionStorage.setItem("bn_promocode", "1");
        },bannerTime);


        $('.promocode-gp-banner__container').on(clickHandler, function(e){
            $(".promocode-gp-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCloseButton', bannerPromocodePage);
        });
    }
});