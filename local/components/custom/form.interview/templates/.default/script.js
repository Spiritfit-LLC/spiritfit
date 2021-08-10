$( document ).ready(function() {
	$(document).on('pjax:success', function(data, status, xhr, options) {
		initFormInterview();
	});
	function initFormInterview() {
		
		$("select[data-for]").change(function() {
			var elem = $( $(this).attr("data-for") ).val($(this).val());
		});
		
		$(".input--checkbox").styler();
		$('.form-interview select.input--select').select2({
            minimumResultsForSearch: Infinity,
        });
		
		$('.quality__form-star').click(function() {
            var value = $(this).data('value');
            $(this).addClass('selected').siblings().removeClass('selected');
            $(this).closest('.quality__form-rating').siblings('input').val(value);

        })
        $('.quality__form-star').hover(function () {
            $(this).closest('.quality__form-rating').addClass('hovered');
            $(this).addClass('current').siblings().removeClass('current');
        }, function () {
            $(this).closest('.quality__form-rating').removeClass('hovered');
            $(this).removeClass('current');
        });
		
		$(".go-back").click( function(e) {
			e.preventDefault();
			$(".form__error-text").text("");
			if( $(".go-back").hasClass("last") ) {
				$(".go-back").removeClass("last");
				$(".form-interview input[type=submit]").hide();
				$(".form-interview .step-1").show();
				$(".form-interview .step-2").hide();
				$(this).text("Далее");
			} else {
				$(".go-back").addClass("last");
				$(".form-interview input[type=submit]").show();
				$(".form-interview .step-1").hide();
				$(".form-interview .step-2").show();
				
				$('html, body').animate({
        			scrollTop: $("#form_interview").offset().top
    			}, 1000);

				$(this).text("Назад");
			}
		});
		
		$("input[name=form_radio_goals]").change(function() {
			if( $(this).val() == 135 ) {
				$("input[name=form_text_207]").show();
			} else {
				$("input[name=form_text_207]").hide();
				$("input[name=form_text_207]").val("");
			}
		});
		$("input[name=form_radio_payment]").change(function() {
			if( $(this).val() == 138 ) {
				$("input[name=form_text_208]").show();
			} else {
				$("input[name=form_text_208]").hide();
				$("input[name=form_text_208]").val("");
			}
		});
		
		$('.form-interview input[type="tel"]').mask('+7 (999) 999-99-99', {
        	autoclear: false
    	});
		
		$(".form-interview").submit(function(e) {
			
			e.preventDefault();
			window.oldTitleDocument = document.title;
			
			$.pjax.submit(e, '#form_interview', {
				push: false,
				timeout: false,
				dataType: 'html',
				fragment: '#form_interview'
			});
		
			return false;
		});
	}
	
	initFormInterview();
});