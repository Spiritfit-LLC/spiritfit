$(document).ready(function(){
    function setHeight(selector){
        var height=0;
        $(selector).each(function(){
            if ($(this).height()>height){
                height=$(this).height();
            }
        }).height(height);
    }

    setHeight(".stock__item-content");

    $(".stocks__main").slick({
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
});