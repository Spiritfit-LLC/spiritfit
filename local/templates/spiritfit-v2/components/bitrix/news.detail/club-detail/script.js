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

	var message_modal_content=$('.club-video-container').get(0);
	if (message_modal_content!==undefined){
		var message_modal=new ModalWindow($('a[href="#show-club-btn"]').data('title'), message_modal_content, AnimationsTypes['fadeIn'], false, true);



		$('a[href="#show-club-btn"]').click(function(e){
			e.preventDefault();
			if ($('.club-video-container').hasClass('loaded')){
				message_modal.show();
			}
			else{
				$('.escapingBallG-animation').addClass('active');
				$(this).css('opacity', 0);
				$.ajax({
					url: '/local/ajax/videoplayer.php',
					type: 'GET',
					data: ({
						VIDEOFILE:$(this).data('src'),
						POSTER:$(this).data('poster')
					}),
					success: function(data) {
						$('.escapingBallG-animation').removeClass('active');
						$('a[href="#show-club-btn"]').css('opacity', 1);
						$('.club-video-container').html(data);
						message_modal.show();

						$('.club-video-container').addClass('loaded');
					},
					error: function(data) {
						$('.escapingBallG-animation').removeClass('active');
						$('a[href="#show-club-btn"]').css('opacity', 1);
						alert('Возникла ошибка')
					},
				});
			}

		});

		message_modal.set_on_Close(function(){
			$('.club-video-container').find('video').trigger('pause');
		});
	}


	//КАК ДОБРАТЬСЯ
	$('.b-map__route-tab-link').on(clickHandler,function(){
		$('.b-map__route-tab-link.active').removeClass('active');
		$(this).addClass('active');

		var id=$(this).data('routetypeid');

		$('.b-map__route-tabitem-desc.active').removeClass("active");
		$(`.b-map__route-tabitem-desc[data-routetypeid="${id}"]`).addClass('active');
	});

	//UTP
	if ($(window).width()>=1280){
		if ($('.club-utp').length>0){

			if ($('.b-screen__slider').length>0){
				$('.b-screen__slider').addClass('slider-club-utp');
				$('.club-utp').appendTo('.b-screen__content');
			}
			else{
				$('.club-utp').appendTo('.b-screen');
				$('.club-utp').css({
					'z-index': '1000',
					'margin-left':'auto'
				})
			}
			setTimeout(function(){
				$('.club-utp').css({
					'opacity':1,
				});
				$('.club-utp__item').each(function(index, el){
					setTimeout(function(){
						$(el).css({
							'opacity':1
						});
					}, index*500)
				})
			}, 2000)
		}
	}





});