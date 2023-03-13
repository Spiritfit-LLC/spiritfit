$(document).ready(function(){

    $(".personal-partners__list").slick({
        infinite: false,
        autoplay: false,
        dots: false,
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: false,
        touchThreshold: 50,
        // prevArrow:false,
        // nextArrow:false,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        adaptiveHeight: false,
        responsive: [
            {
                breakpoint: 1336,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 1
                }
            },
        ]
    });

    $(".personal-pfd__list").slick({
        infinite: false,
        autoplay: false,
        dots: false,
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: false,
        touchThreshold: 50,
        // prevArrow:false,
        // nextArrow:false,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        adaptiveHeight: false,
        responsive: [
            {
                breakpoint: 1336,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 1
                }
            },
        ]
    });

    function setHeight(selector, add_height=0){
        var height=0;
        $(selector).each(function(){
            if ($(this).height()>height){
                height=$(this).height();
            }
        }).height(height + add_height);
    }

    setHeight(".pp-item__preview-text");
    setHeight(".pp-item__preview-call2action");
    setHeight(".pp-item__footer");




    var $modal=$("#partner-detail-popup");


    $(".pp-item__btn").click(function(){
        var postData={
            id:$(this).data("id"),
            type:$(this).data("type"),
            v: '3',
            template_folder: personalPartnerTemplatePath
        }

        BX.ajax.runComponentAction(personalPartnersComponent, 'getDetail', {
            mode: 'class',
            method: 'POST',
            data:postData
        }).then(function(response){
            $modal.find(".personal-partner-detail__content").html(response.data);

            $modal.fadeIn(300).css("display", "flex");

            console.log(response)
        }, function (response){
            console.log(response)
        });
    });
});

var copy_partner_promocode=function(promocode){
    var date = new Date;
    date.setDate(date.getDate() + 1);
    document.cookie = "bn_promocode="+promocode+"; path=/; expires=" + date.toUTCString();
    var $tmp = $("<textarea>");
    $("body").append($tmp);
    $tmp.val(promocode).select();
    document.execCommand("copy");
    $tmp.remove();
    alert("Промокод скопирован!");
}