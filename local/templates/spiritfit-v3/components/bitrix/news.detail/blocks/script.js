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
    $('.b-image-block').each(function (index, elem) {
        var $context = $(this);
        var $imgHolder = $('.b-image-block__img-holder', $context);
        var $slides = $('.b-image-block__slide', $context);
        var $navPlace = $('.b-image-block__slider-nav', $context);
        var $textSlider = $('.b-image-block__text', $context);
        let $sliderFirstImg = elem.querySelector('.b-image-block__slide:first-child img.b-image-block__slide-img');
        function sliderInit() {
            $imgHolder.slick({
                lazyLoad: 'ondemand',
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
                        arrows: true,
                        dots: false
                    }
                }]
            });
            $textSlider.slick({
                arrows: true,
                dots: false,
                prevArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--left"></div>',
                nextArrow: '<div class="b-image-plate-block__arrow b-image-plate-block__arrow--right"></div>',
                fade: true,
                asNavFor: $imgHolder,
                focusOnSelect: true,
                adaptiveHeight: true,
                swipeToSlide: true,
                touchThreshold: 20,
                infinite: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        adaptiveHeight: true,
                        dots: false
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


    $('.b-image-plate-block').each(function (index , elem) {
        var $context = $(this);
        var $imgHolder = $('.b-image-plate-block__img-holder', $context);
        var $slides = $('.b-image-plate-block__slide', $context);
        var $navPlace = $('.b-image-plate-block__slider-nav', $context);
        var $textSlider = $('.b-image-plate-block__text', $context);
        let $sliderFirstImg = elem.querySelector('.b-image-plate-block__slide:first-child img.b-image-plate-block__slide-img');
        function sliderInit(){
            $imgHolder.slick({
                lazyLoad: 'ondemand',
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

})