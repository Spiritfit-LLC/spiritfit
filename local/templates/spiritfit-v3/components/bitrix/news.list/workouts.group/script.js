$(document).ready(function (){
    var lazyBackgrounds = [].slice.call(document.querySelectorAll(".workout-group-twoside-card__content"));

    if ("IntersectionObserver" in window) {
        let lazyBackgroundObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var $target=$(entry.target);
                    $target.css("background-image", 'url('+$target.data("src")+')');
                    lazyBackgroundObserver.unobserve(entry.target);
                }
            });
        });

        lazyBackgrounds.forEach(function(lazyBackground) {
            lazyBackgroundObserver.observe(lazyBackground);
        });
    }


    $(".workout-group-slider__slider").slick({
        infinite: false,
        autoplay: false,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        slidesToShow: 4,
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
                breakpoint: 1358,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 868,
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


    var $card = $('.workout-group-slider__item');
    $card.click(function(){
        $(this).toggleClass('is-open');
    });
})