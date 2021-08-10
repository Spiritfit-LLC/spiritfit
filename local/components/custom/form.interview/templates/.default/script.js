$( document ).ready(function() {
	$(document).on('pjax:success', function(data, status, xhr, options) {
		initFormInterview();
	});
	function checkStep1() {
		if( $("input[name=form_text_115]").val() !== "" && typeof $("input[name=form_radio_sex]:checked").val() !== "undefined"
			&& typeof $("input[name=form_radio_age]:checked").val() !== "undefined" && typeof $("input[name=form_radio_knowledge]:checked").val() !== "undefined"
			&& typeof $("input[name=form_radio_visit]:checked").val() !== "undefined" && typeof $("input[name=form_radio_goals]:checked").val() !== "undefined"
			&& typeof $("input[name=form_radio_payment]:checked").val() !== "undefined" ) {
			return true;
		}
		return false;
	}
	function checkStep2() {
		if( $("input[name=form_text_141]").val() !== "" && $("input[name=form_text_161]").val() !== "" && $("input[name=form_text_162]").val() !== ""
			&& $("input[name=form_text_145]").val() !== "" && $("input[name=form_text_163]").val() !== "" && $("input[name=form_text_164]").val() !== ""
			&& $("input[name=form_text_149]").val() !== "" && $("input[name=form_text_165]").val() !== "" && $("input[name=form_text_166]").val() !== ""
			&& $("input[name=form_text_153]").val() !== "" && $("input[name=form_text_167]").val() !== "" && $("input[name=form_text_168]").val() !== ""
			&& $("input[name=form_text_157]").val() !== "" && $("input[name=form_text_169]").val() !== "" && $("input[name=form_text_170]").val() !== "" 
			&& $("input[name=form_text_171]").val() !== "" && $("input[name=form_text_172]").val() !== "" && $("input[name=form_text_173]").val() !== ""
			&& $("input[name=form_text_174]").val() !== "" && $("input[name=form_text_175]").val() !== "" && $("input[name=form_text_176]").val() !== ""
			&& $("input[name=form_text_178]").val() !== "" && $("input[name=form_text_179]").val() !== "" && $("input[name=form_text_180]").val() !== ""
			&& $("input[name=form_text_181]").val() !== "" && $("input[name=form_text_183]").val() !== "" && $("input[name=form_text_184]").val() !== ""
			&& $("input[name=form_text_185]").val() !== "" && $("input[name=form_text_186]").val() !== "" && $("input[name=form_text_194]").val() !== ""
			&& $("input[name=form_text_195]").val() !== "" && $("input[name=form_text_196]").val() !== "" && $("input[name=form_text_212]").val() !== ""
			/*&& typeof $("input[name=form_text_211]:checked").val() !== "undefined" && typeof $("input[name=form_text_209]:checked").val() !== "undefined" && typeof $("input[name=form_text_210]:checked").val() !== "undefined"*/
			&& ($("input[name=form_checkbox_188]").is(':checked') || $("input[name=form_checkbox_189]").is(':checked') || $("input[name=form_checkbox_190]").is(':checked')
				|| $("input[name=form_checkbox_191]").is(':checked') || $("input[name=form_checkbox_192]").is(':checked') || $("input[name=form_text_213]").val() !== "")
			&& ($("input[name=form_checkbox_198]").is(':checked') || $("input[name=form_checkbox_199]").is(':checked') || $("input[name=form_checkbox_200]").is(':checked')
				|| $("input[name=form_checkbox_201]").is(':checked') || $("input[name=form_checkbox_202]").is(':checked') || $("input[name=form_checkbox_203]").is(':checked')
			|| $("input[name=form_text_206]").val() !== "")) {
			return true;
		}
		return false;
	}
	function checkStep() {
		if( checkStep1() ) {
			$(".go-back").removeClass("disabled");
		} else {
			$(".go-back").addClass("disabled");
		}
		if( checkStep2() ) {
			$(".form-interview input[type=submit]").show();
		} else {
			$(".form-interview input[type=submit]").hide();
		}
	}
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
			$(this).closest('.quality__form-rating').siblings('input').change();

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
			if( $(this).hasClass("disabled") ) {
				return;
			}
			$(".form__error-text").text("");
			if( $(".go-back").hasClass("last") ) {
				$(".go-back").removeClass("last");
				$(".form-interview .step-1").show();
				$(".form-interview .step-2").hide();
				$(this).text("Далее");
			} else {
				$(".go-back").addClass("last");
				$(".form-interview .step-1").hide();
				$(".form-interview .step-2").show();
				
				$('html, body').animate({
        			scrollTop: $("#form_interview").offset().top
    			}, 1000);

				$(this).text("Назад");
			}
		});
		
		$("select").change(function() {
			checkStep();
		});
		$("input").change(function() {
			checkStep();
		});
		$("input[name=form_text_213]").keyup(function() {
			checkStep();
		});
		$("input[name=form_text_206]").keyup(function() {
			checkStep();
		});
		$("input[name=form_text_212]").keyup(function() {
			checkStep();
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
		$(".form-interview input[name=form_text_139]").keyup(function() {
			var value = parseInt($(this).val());
			if( value < 0 ) {
				$(this).val(0);
			}
			if( value > 10 ) {
				$(this).val(10);
			}
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