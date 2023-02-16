$(document).ready(function(){
    $(".blog-cards__items").slick({
        infinite: false,
        autoplay: false,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: true,
        touchThreshold: 50,
        // prevArrow:false,
        // nextArrow:false,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        adaptiveHeight: false,
        responsive: [
            {
                breakpoint: 1440,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 456,
                settings: {
                    variableWidth:false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });

    function setHeight(selector){
        var height=0;
        $(selector).each(function(){
            if ($(this).height()>height){
                height=$(this).height();
            }
        }).height(height);
    }

    setHeight(".blog-card__content");

});