$(document).ready(function (){
    var $card = $('.b-twoside-card.b-treners__item');
    $card.click(function(){
        $(this).toggleClass('is-open');
    });

    $(".b-treners__wrapper").slick({
        infinite: true,
        autoplay: false,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        slidesToShow: 4,
        slidesToScroll: 1,
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

    var lazyBackgrounds = [].slice.call(document.querySelectorAll(".b-twoside-card__image.lazy-bg"));

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

    var club_id=$(".trainer-block__club-control.active").data("club");
    if (club_id!==undefined){
        $(".b-twoside-card.b-treners__item").each(function (){
            if ($(this).data("club")===club_id){
                $(this).addClass("filter");
            }
        });
        $(".b-treners__wrapper").slick('slickFilter', '.filter');
    }

    $('.trainer-block__club').jScroll({
        type: 'h',
    });
})

var select_club=function(el, club_id){
    if ($(el).hasClass("active")){
        return;
    }
    $('.trainer-block__club-control.active').removeClass("active");
    $(el).addClass("active");

    var parent=$('.trainer-block__club');
    var element=$(el);
    $(parent).stop().animate({scrollLeft:element.offset().left + parent.scrollLeft() - parent.offset().left-(parent.width()/2-element.width()/2)}, 1500);

    $(".b-treners__wrapper").slick('slickUnfilter');

    $(".b-twoside-card.b-treners__item").each(function (){
        $(this).removeClass("filter")
        if ($(this).data("club")===club_id){
            $(this).addClass("filter");
        }
    });
    $(".b-treners__wrapper").slick('slickFilter', '.filter');
}