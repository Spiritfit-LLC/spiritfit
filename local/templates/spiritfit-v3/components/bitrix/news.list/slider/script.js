$(document).ready(function(){
    function resizeSliderTitle(){
        try {
            $('.b-info-slider__item').each(function(index, el){
                var current_width = $(el).find('.b-info-slider__title').get(0).scrollWidth;
                var real_width = $(el).find('.b-info-slider__title').get(0).clientWidth;

                if (current_width > real_width) {
                    var curr_text = $(el).find('.b-info-slider__title').text().toLowerCase();
                    if (curr_text.split('spirit.').length>1){
                        var new_text = curr_text.split('spirit.')[0] + 'spirit. ' + curr_text.split('spirit.')[1]
                        $(el).find('.b-info-slider__title').text(new_text)
                    }
                    else{
                        $(el).find('.b-info-slider__title').css("font-size", '28px');
                    }
                }
            })
        }
        catch (e) {
            console.log(e)
        }
    }
    resizeSliderTitle();


    //Слайдер акций
    $('.b-info-slider').each(function () {
        var $context = $(this);
        var $navPlace = $('.b-info-slider__nav', $context);
        var $slider = $('.b-info-slider__slider', $context);
        $('.b-info-slider__slider').on('init', function (e, slick) {
            $(".b-info-slider__btn").click(function() {
                dataLayerSendCorp('UX', 'clickButtonFindOutCost', '');
            });
        });
        $slider.slick({
            dots: true,
            appendDots: $navPlace,
            arrows: true,
            prevArrow: '<div class="b-info-slider__arrow b-info-slider__arrow--left"></div>',
            nextArrow: '<div class="b-info-slider__arrow b-info-slider__arrow--right"></div>',
            fade: true,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 5000,
        });
    });
})