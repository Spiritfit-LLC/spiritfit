$( document ).ready(function() {
    let priseSlider = $('.prise-slider:not(.disabled)');
    priseSlider.slick({
        dots: false,
        arrows: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        adaptiveHeight: true,
        touchThreshold: 50,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3
            }
        }, {
            breakpoint: 768,
            settings: {
                slidesToShow: 2,
            }
        }, {
            breakpoint: 640,
            settings: {
                slidesToShow: 1,
                arrows: true,
                slidesToScroll: 1,
                dots: true,
            }
        }]
    });
});