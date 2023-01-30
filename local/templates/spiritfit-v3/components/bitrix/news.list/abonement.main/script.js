$(document).ready(function(){
    $(".b-cards-slider__slider").slick({
        infinite: false,
        autoplay: false,
        dots: false,
        arrows: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: true,
        touchThreshold: 50,
        prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
        nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
        responsive: [
            {
                breakpoint: 1358,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 956,
                settings: {
                    slidesToShow: 1,
                    variableWidth: false,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 552,
                settings: {
                    centerMode: true,
                    centerPadding: '10px',
                    slidesToShow: 1
                }
            }
            // {
            //     breakpoint: 484,
            //     settings: {
            //         slidesToShow: 1,
            //         // arrows:false,
            //         variableWidth: false,
            //     }
            // }
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


    if ($(window).width()>768){
        setHeight(".slider-abonement__item-include-list");
        setHeight(".slider-abonement__item-title");
        setHeight(".slider-abonement__item-price");
        setHeight(".slider-abonement__item-sale", $(".abonement-sale-date").height());
    }

    var section_id=$(".slider-section__item.active").data("section-id");
    if (section_id!==undefined){
        $(".b-cards-slider__item.abonement").each(function (){
            var sections=$(this).data("sections");

            if (sections.includes(section_id.toString())){
                $(this).addClass("filter");
            }
        });

        $(".b-cards-slider__slider").slick('slickFilter', '.filter');
    }

});

var select_section=function(el, section_id){
    if ($(el).hasClass("active")){
        return;
    }
    $('.slider-section__item.active').removeClass("active");
    $(el).addClass("active");

    $(".b-cards-slider__slider").slick('slickUnfilter');

    $(".b-cards-slider__item.abonement").each(function (){
        var sections=$(this).data("sections");

        $(this).removeClass("filter")

        if (sections.includes(section_id)){
            $(this).addClass("filter");
        }
    });

    $(".b-cards-slider__slider").slick('slickFilter', '.filter');
}