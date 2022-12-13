function addWheelHorizontalScrollToSlider(slider) {
    // код для горизонтальной прокрутки слайдера с помощью колеса (только горизонтальные отклонения)
    // или жеста на тачпаде ноута "два пальца и водим по горизонтали"
    let prevCall = Date.now();
    slider.on('wheel', (function(e) {
        if(e.originalEvent.deltaX !== 0) {
            e.preventDefault();
            let curCall = Date.now();
            // предотвращаем вызов чаще, чем раз в 500 мс
            if(curCall - prevCall > 500) {
                prevCall = curCall;
                if (e.originalEvent.deltaX > 0) {
                    $(this).slick('slickNext');
                } else if(e.originalEvent.deltaX < 0){
                    $(this).slick('slickPrev');
                }
            }
        }

    }));
}

// функция добавления прогрессбара к слайдеру
function addSliderProgressBar(slider, addClass = ''){
    let progressBar = document.createElement('div');
    progressBar.classList.add('slider-progressbar');
    if(addClass) {
        progressBar.classList = `slider-progressbar ${addClass}`;
    }
    $(slider).after(progressBar);
    slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        let calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;
        $(progressBar).css('background-size', calc + '% 100%').attr('aria-valuenow', calc );
    });
}


$(document).ready(function(){
    $('.b-image-plate-block').each(function (index , elem) {
        var $context = $(this);
        var $imgHolder = $('.b-image-plate-block__img-holder', $context);
        var $slides = $('.b-image-plate-block__slide', $context);
        var $navPlace = $('.b-image-plate-block__slider-nav', $context);
        var $textSlider = $('.b-image-plate-block__text', $context);
        let $sliderFirstImg = elem.querySelector('.b-image-plate-block__slide:first-child img.b-image-plate-block__slide-img');
        function sliderInit(){
            $imgHolder.slick({
                arrows: false,
                dots: true,
                prevArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--on-img b-image-plate-block__arrow--left"></div>',
                nextArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--on-img b-image-plate-block__arrow--right"></div>',
                appendDots: $navPlace,
                fade: true,
                asNavFor: $textSlider,
                focusOnSelect: true,
                adaptiveHeight: true,
                swipeToSlide: true,
                touchThreshold: 20,
                infinite: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        dots: false,
                        arrows: true
                    }
                }]
            });
            $textSlider.slick({
                arrows: true,
                dots: false,
                fade: true,
                prevArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--left"></div>',
                nextArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--right"></div>',
                asNavFor: $imgHolder,
                focusOnSelect: true,
                adaptiveHeight: true,
                swipeToSlide: true,
                touchThreshold: 20,
                infinite: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        arrows: false,
                        dots: false,
                        slidesToScroll: 1
                    }
                }]
            });
            addWheelHorizontalScrollToSlider($imgHolder);
            addSliderProgressBar($textSlider, 'slider-progressbar--bp768');
        }
        if ($slides.length > 0) {
            if ($($sliderFirstImg).height() == 0) {
                $sliderFirstImg.onload = sliderInit;
            } else {
                sliderInit();
            }
        }
    });


    var $card = $('.b-twoside-card');
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

    $('.b-map__route-tab-link').on(clickHandler,function(){
        $('.b-map__route-tab-link.active').removeClass('active');
        $(this).addClass('active');

        var id=$(this).data('routetypeid');

        $('.b-map__route-tabitem-desc.active').removeClass("active");
        $(`.b-map__route-tabitem-desc[data-routetypeid="${id}"]`).addClass('active');
    });



        $('a[href="#show-club-btn"]').click(function(e){
            e.preventDefault();
            if ($('.club-video-container').hasClass('loaded')){
                $('.club-video-container')
                    .css('display', 'flex')
                    .fadeIn(300);
            }
            else{
                $(this).css('opacity', 0);
                $.ajax({
                    url: '/local/ajax/videoplayer.php',
                    type: 'GET',
                    data: ({
                        VIDEOFILE:$(this).data('src'),
                        POSTER:$(this).data('poster')
                    }),
                    success: function(data) {
                        $('a[href="#show-club-btn"]').css('opacity', 1);
                        $('.club-video').html(data);

                        $('.club-video-container')
                            .addClass('loaded')
                            .css('display', 'flex')
                            .fadeIn(300);
                    },
                    error: function(data) {
                        $('a[href="#show-club-btn"]').css('opacity', 1);
                        alert('Возникла ошибка')
                    },
                });
            }

        });
})

var close_club_video=function(){
    $('.club-video-container').find('video').trigger('pause');
    $('.club-video-container').fadeOut(300);
}