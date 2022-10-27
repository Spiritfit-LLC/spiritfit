$( document ).ready(function() {
    $(".form-request-new__field").addClass("is-not-empty");

    var videoOpen = false;
    $(".b-treners__item").unbind();
    $(".b-treners__item").click(function () {
        if( videoOpen ) {
            videoOpen = false;
        } else {
            $(this).toggleClass("is-open");
        }
    });

    $(".b-twoside-card__video.has-video").click(function () {
        /*$.fancybox.open( atob($(this).data("source")) );*/
        if( $(this).find("video").length == 0 ) {
            $(this).html( atob($(this).data("source")) );
        }
        videoOpen = true;
    });

    $(".autoplay-video").click(function() {
        let video = $(this).find("video");
        if( $(video)[0].paused ) {
            $(video)[0].play();
            $(this).find("svg").hide();
        } else {
            $(video)[0].pause();
            $(this).find("svg").show();
        }
    });

    $(".moveto").unbind();
    $(".moveto").click(function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(".b-abonements").eq(0).offset().top
        }, 1000);
    });

    function setTrenerInit() {
        $(".setTrener").unbind();
        $(".setTrener").click(function (e) {
            e.preventDefault();

            var id = $(this).data("id");
            $(".v3-abonement .get-abonement").data("leaderid", id);

            $(this).parents(".b-treners__wrapper").find(".b-treners__item").removeClass("selected");

            var item = $(this).parents(".b-treners__item");
            $(item).addClass("selected");

            $('html, body').animate({
                scrollTop: $(".b-abonements").eq(0).offset().top
            }, 1000);
        });
    }
    if ($(window).width() < 768) {
        var trenersSlider = $(".b-treners__wrapper");
        trenersSlider.slick({
            dots: false,
            arrows: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: false,
            variableWidth: true,
            touchThreshold: 50,
            prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
            nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
            responsive: [{
                breakpoint: 1770,
                settings: {
                    slidesToShow: 3,
                    dots: false
                }
            }, {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    dots: false
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                    arrows: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                    dots: true,
                    adaptiveHeight: true
                }
            }, {
                breakpoint: 456,
                settings: {
                    slidesToShow: 1,
                    arrows: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                    dots: true
                }
            }]
        });
    } else {
        setTrenerInit();
    }

    $('.partners-wrapper').each(function () {
        var context = $(this);
        var navPlace = $(context).find(".b-cards-slider__slider-nav").eq(0);
        var slider = $(context).find(".partners-slider").eq(0);

        slider.on('init', function(event, slick) {
            $(".v3-abonement .b-twoside-card").unbind();
            $(".v3-abonement .b-twoside-card").click(function() {
                $(this).toggleClass("is-open");
            });
        });

        slider.slick({
            dots: false,
            arrows: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: false,
            variableWidth: true,
            touchThreshold: 50,
            prevArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--left"><div class="b-cards-slider__arrow b-cards-slider__arrow--left"></div></div>',
            nextArrow: '<div class="b-cards-slider__arrow-wrapper b-cards-slider__arrow-wrapper--right"><div class="b-cards-slider__arrow b-cards-slider__arrow--right"></div></div>',
            responsive: [{
                breakpoint: 1276,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    dots: false
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                    arrows: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                    dots: true
                }
            }, {
                breakpoint: 456,
                settings: {
                    slidesToShow: 1,
                    arrows: true,
                    slidesToScroll: 1,
                    variableWidth: false,
                    dots: true
                }
            }]
        });

        if ($(window).width()<=639){
            $(".partners").find(".b-twoside-card__content").each(function(){
                var partner_card_width=$(this).width();
                $(this).height(partner_card_width);
            });
        }
    });
});

