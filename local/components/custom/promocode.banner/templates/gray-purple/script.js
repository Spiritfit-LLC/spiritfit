$(document).ready(function(){
    if (!getCookie("bn_promocode")){
        setTimeout(function(){
            $(".promocode-gp-banner__container")
                .fadeIn(300)
                .css("display", "flex");

            $('body').css('overflow', 'hidden');
            dataLayerSend('bannerCatchDiscount', 'shown', bannerPromocodePage);
            window.sessionStorage.setItem("bn_promocode", "1");
        },bannerTime);


        $('.promocode-gp-banner__closer').on(clickHandler, function(){
            $(".promocode-gp-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCloseButton', bannerPromocodePage);
        });

        $('button.promocode-gp-btn').on(clickHandler, function(){
            var date = new Date;
            date.setDate(date.getDate() + 1);
            document.cookie = "bn_promocode="+bannerPromocode+"; path=/; expires=" + date.toUTCString();
            var $tmp = $("<textarea>");
            $("body").append($tmp);
            $tmp.val(bannerPromocode).select();
            document.execCommand("copy");
            $tmp.remove();
            alert("Промокод скопирован!");
            $(".promocode-gp-banner__container").fadeOut(300);
            $('body').css('overflow', 'auto');
            dataLayerSend('bannerCatchDiscount', 'clickCopyPromocodeButton', bannerPromocodePage);
        });
    }
});