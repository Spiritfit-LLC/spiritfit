$( document ).ready(function() {
    $('.landing__abonements-wrapper').each(function () {
        var context = $(this);
        var navPlace = $(context).find(".b-cards-slider__slider-nav").eq(0);
        var slider = $(context).find(".landing__abonements-slider").eq(0);

        slider.on('init', function(event, slick) {
            $(".v3-abonement .b-twoside-card").unbind();
            $(".v3-abonement .b-twoside-card").click(function() {
                $(this).toggleClass("is-open");
            });

            $(".v3-abonement .get-abonement").unbind();
            $(".v3-abonement .get-abonement").click(function(e) {
                e.preventDefault();

                if( $(this).hasClass("trial-training-btn") ) {
                    if ($(this).data('position')){
                        var eLabel = $(this).data('position')
                    } else {
                        eLabel = window.location.href;
                    }
                    var eAction='clickTrialWorkoutButton';
                    dataLayerSend('UX', eAction, eLabel);
                }

                var location = $(this).attr("href") + "?has_leaders=1";
                var leaderId = $(this).data("leaderid");
                if( typeof leaderId !== "undefined" || leaderId !== "" ) {
                    location += "&leader_id=" + leaderId;
                }

                window.location.href = location;
            });
        });

        slider.slick({
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
    });
});