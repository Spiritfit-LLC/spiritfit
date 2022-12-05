$(document).ready(function (){
    $(".utp-main__items").slick({
        dots: false,
        arrows: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        variableWidth: false,
        touchThreshold: 50,
        // prevArrow:false,
        // nextArrow:false,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        adaptiveHeight: false,
        // centerMode: true,
        // centerPadding: '40px',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 570,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    })
});