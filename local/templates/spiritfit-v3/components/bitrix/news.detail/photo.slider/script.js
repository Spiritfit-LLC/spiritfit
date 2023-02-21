$(document).ready(function (){
    var $slider=$(`#slick_${slider_slick_id}`);

    $slider.slick({
        dots: true,
        infinite: false,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay:false,
        arrows:false,
        slidesToShow:1,
        slidesToScroll:1,
        adaptiveHeight: true
    });

    $(".photo-slider__arrow").click(function (){
        if ($(this).hasClass("left")){
            $slider.slick("slickPrev");
        }
        else if ($(this).hasClass("right")){
            $slider.slick("slickNext");
        }
    })

})