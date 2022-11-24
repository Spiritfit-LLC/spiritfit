$(document).ready(function(){

    // if ()
    $("a[href=\"#quiz\"]").click(function(){
        $(".promocode-banner__container")
            .fadeIn(300)
            .css("display", "flex");

        $('body').css('overflow', 'hidden');
        dataLayerSend('bannerCatchDiscount', 'shown', bannerPromocodePage);
    });

    $('.promocode-banner__closer').on(clickHandler, function(){
        $(".promocode-banner__container").fadeOut(300);
        $('body').css('overflow', 'auto');
        dataLayerSend('bannerCatchDiscount', 'clickCloseButton', bannerPromocodePage);
    });
});