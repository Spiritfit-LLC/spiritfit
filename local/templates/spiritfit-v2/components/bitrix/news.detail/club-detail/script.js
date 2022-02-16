$(document).ready(function () {
    $('.flip_button').click(function () {
        var block = $(this).parents('.club__team-slide-inner').is(".club__team-slide-front");
        if (block) {
            $(this).parents('.club__team-slide-inner').find('.flip_button').hide();
        }else{
            $(this).parents('.club__team-flip_box').find('.flip_button').show();
        }
        $(this).parents('.club__team-slide').children('.club__team-flip_box').toggleClass('flipped');
        return false;
    });
	
	$('.reviews-slider').on('init', function(event, slick) {
		slick.$slideTrack.find(".slick-current").next().next().addClass("last-inshow");
	});
	$(".reviews-slider").slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 500,
		adaptiveHeight: false,
		draggable: true,
		prevArrow: '<div type="button" class="b-cards-slider__arrow b-cards-slider__arrow--left slick-prev"></div>',
		nextArrow: '<div type="button" class="b-cards-slider__arrow b-cards-slider__arrow--right slick-next"></div>',
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [{
			breakpoint: 1260,
			settings: {
				arrows: true,
				slidesToScroll: 1,
				slidesToShow: 2,
				autoplay: false,
			}
		},
		{
			breakpoint: 768,
			settings: {
				arrows: true,
				slidesToScroll: 1,
				slidesToShow: 1,
				autoplay: false,
			}
		}]
	});
	$('.reviews-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		slick.$slideTrack.find(".reviews-slider-item").removeClass("last-inshow");
		slick.$slideTrack.find(".reviews-slider-item[data-slick-index='"+nextSlide+"']").next().next().addClass("last-inshow");
	});
	$('.reviews-slider').on('reInit', function(event, slick) {
		slick.$slideTrack.find(".slick-current").next().next().addClass("last-inshow");
	});
});