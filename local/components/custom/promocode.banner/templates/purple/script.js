$(document).ready(function(){
    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    if (!getCookie("bn_promocode") && window.sessionStorage.getItem("bn_promocode")!=="1"){
        setTimeout(function(){
            $(".promocode-banner__container")
                .fadeIn(300)
                .css("display", "flex");

            $('body').css('overflow', 'hidden');
            dataLayerSend('bannerCatchDiscount', 'shown', '');
            window.sessionStorage.setItem("bn_promocode", "1");

        },bannerTime);


        $('.promocode-banner__closer').on(clickHandler, function(){
            $(".promocode-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCloseButton', '');
        });

        $('button.promocode-btn').on(clickHandler, function(){
            document.cookie = "bn_promocode="+bannerPromocode+"; path=/abonement;";
            alert("Промокод скопирован!");
            $(".promocode-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCopyPromocodeButton', '');
        });
    }
});