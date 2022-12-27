$(document).ready(function(){
    $(".reviews-slider__container").slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        variableWidth: true,
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
                breakpoint: 1358,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 956,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 592,
                settings: {
                    slidesToShow: 1,
                    variableWidth:false,
                    arrows: false,
                }
            }
        ]
    });


    var lazyBackgrounds = [].slice.call(document.querySelectorAll(".review__user-img.lazyload"));

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
})


var show_review=function(id){
    var review=$(`.review__item[data-id="${id}"]`);
    var review_clone=review.clone();


    review_clone
        .removeClass('slick-slide')
        .removeClass('slick-current')
        .removeClass('slick-cloned')
        .removeClass('slick-active');



    $(".review-detail__popup")
        .find('.review-detail-content')
        .html('')
        .append(review_clone.get(0));
    $(".review-detail__popup-container").fadeIn(300);
}

var close_review=function(){
    $(".review-detail__popup")
        .find('.review-detail-content')
        .html('');
    $(".review-detail__popup-container").fadeOut(300);
}