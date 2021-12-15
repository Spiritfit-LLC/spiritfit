jQuery(function($) {
    $( document ).ready(function() {
		$(".input--checkbox").styler();
    	$(".input--num").on("input", function() {
        	this.value = this.value.replace(/[^0-9]/gi, "");
    	});
    	$(".input--name").on("input", function() {
        	this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
    	});
		MyAbonementApp.Forms.init(".get-abonement");
		
		if( step === 1 ) {
			dataLayerSend('UX', 'openMembershipRegPage', strSend);
		}
	});
	var MyAbonementApp = MyAbonementApp || {};
	MyAbonementApp.Forms = {
		init: function(selector) {
			
			var clubSelector = $(selector).find(".get-abonement-club");
			var clubInput = $(selector).find("input[name=CLUB_ID]");
			var actionInput = $(selector).find("input[name=ACTION]");
			var agreeButton = $(selector).find(".get-abonement-agree");
			var agreeDialog = $(selector).find(".popup--legal-information");
			var promoButton = $(selector).find(".get-abonement-promo");
			var submitButton = $(selector).find("input[type=submit]");
			var resendButton = $(selector).find(".get-abonement-resend");
			var stepThreeAgreement = $(selector).find(".agreement3");
			var paymentButton = $(selector).find(".get-abonement-pay");
			var formElement = $(selector);
			
			$( clubSelector ).unbind();
			$( clubSelector ).change(function() {
				$(clubInput).val($(this).val());
				$(actionInput).val("REFREH");
				$(formElement).submit();
			});
			
			$( promoButton ).unbind();
			$( promoButton ).click( function(e) {
				e.preventDefault();
				$(actionInput).val("COUPON");
				$(formElement).submit();
			});
			
			if( step === 1 ) {
				$( agreeButton ).unbind();
				$( agreeButton ).click( function(e) {
					e.preventDefault();
					
					var inputs = ["input[type=text]", "input[type=password]", "input[type=hidden]", "input[type=radio]:checked", "input[type=checkbox]:checked", "select", "textarea"];
					var hasErrors = false;
					for (var i = 0; i < inputs.length; i++) {
				    	$(formElement).find( inputs[i] ).each(function( index ) {
					    	var name = $(this).attr("name");
					    	var value = $(this).val();
							
							if(typeof $(this).attr("required") != "undefined") {
								if(typeof value != "undefined" && value == "") {
									hasErrors = true;
								}
							}
				    	});
					}
					if( hasErrors ) {
						$(formElement).find(".form-error-modal").remove();
						$(formElement).append('<div class="popup popup--call form-error-modal" style="display: block;"><div class="popup__bg"></div><div class="popup__window"><div class="popup__close"><div></div><div></div></div><div class="popup__success">Для продолжения заполните все обязательные поля</div></div></div>');
						return false;
					}
					
					$(actionInput).val("SEND_SMS");
					
					$(agreeDialog).fadeIn(300);
					
					let scrollbarLegalInformation = new PerfectScrollbar(".popup__legal-information", {
						suppressScrollX: true,
						minScrollbarLength: 100
					});
					
					if (scrollbarLegalInformation.containerHeight !== scrollbarLegalInformation.contentHeight) {
						$('body').css('overflow', 'hidden');
					}
					
					$('.popup__close, .popup__bg').unbind();
					$('.popup__close, .popup__bg').on('click', function() {
						$('body').css('overflow', '');
						scrollbarLegalInformation.destroy();
					});
					
					$(submitButton).unbind("click");
					$(submitButton).click( function() {
						$(this).attr('disabled', true);
						$(this).attr("value", "Обработка...");
						$('body').css('overflow', '');
						scrollbarLegalInformation.destroy();
						$(formElement).submit();
					});
					
					$('input[name="form_checkbox_legal-information"]').unbind();
					$('input[name="form_checkbox_legal-information"]').change(function() {
						if( $(this).is(':checked') ) {
							$(submitButton).removeAttr('disabled');
						} else {
							$(submitButton).attr('disabled', true);
						}
					});
					
					$(window).on('resize', function() {
            			scrollbarLegalInformation.update();
        			});
				});
			}
			if( step === 2 ) {
				$(submitButton).unbind("click");
				$(submitButton).click( function(e) {
					e.preventDefault();
					
					reachGo( $(this).closest('form') );
					
					$(actionInput).val("CHECK_SMS");
					$(this).attr('disabled', true);
					$(this).attr("value", "Обработка...");
					$(formElement).submit();
				});
				$( resendButton ).unbind();
				$( resendButton ).click( function(e) {
					e.preventDefault();
					$(actionInput).val("RESEND_SMS");
					$(formElement).submit();
				});
			}
			if( step === 3 ) {
				$( stepThreeAgreement ).unbind();
				$( stepThreeAgreement ).change(function() {
					if( !$(stepThreeAgreement).is(':checked') ) {
						$(this).click();
					}
				});
				$(paymentButton).unbind();
				$(paymentButton).click( function(e) {
					e.preventDefault();
					
					reachGo( $(this).closest('form') );
        			reachGoOnline();
					
					var url = $(this).attr('href'); 
					window.open(url, '_blank');
					
					$(formElement).submit();
				});
			}
			
			$( selector ).unbind();
			$( selector ).submit(function(e) {
			    e.preventDefault();
				
				var formObject = $(this);
				MyAbonementApp.Forms.sendMultipartPost($(this));
				
				return false;
			});
		},
		sendMultipartPost: function( cForm ) {
			var form = new FormData($( cForm )[0]);
			$.ajax({
                type: "POST", 
                contentType: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                url: $(cForm).attr("action"),  
                data: form,
                success: function( data )  
                {
					var prevStep = step;
					var id = $(cForm).parents(".subscription").eq(0).attr("id");
					var wrapper = ("#"+id);
					$(wrapper).html( $(data).find( "#"+id ).html() );
					
					step = parseInt( $(data).find( "#"+id ).data("step") );
					strSend = $(data).find( "#"+id ).data("strsend");
					strAbonement = $(data).find( "#"+id ).data("abonementname");
					
					$('.get-abonement-club').select2({
                		minimumResultsForSearch: Infinity
            		});
					
					$("#"+id+" .input--checkbox").styler();
					$("#"+id+" .input--num").on("input", function() {
						this.value = this.value.replace(/[^0-9]/gi, "");
					});
					$("#"+id+" .input--name").on("input", function() {
						this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
					});
					
					$("#"+id+" .input--tel").inputmask({
						'mask': '+7 (999) 999-99-99',
						onBeforeMask: function (value) {
							if (value[0] == '8') {
								var processedValue = value.replace('8', "");
							}
							return processedValue;
						}
					});
					
					if( prevStep === 1 && step === 2 ) {
						dataLayerSend('UX', 'openSmsCodePage', strSend);
						if( strAbonement == "Домашние тренировки" ) {
							dataLayerSend('UX', 'sendContactFormHomeWorkout', strSend);
						} else {
							dataLayerSend('conversion', 'sendContactForm', strSend);
						}
						
						$('html, body').animate({
        					scrollTop: $(wrapper).find("form").eq(0).offset().top
    					}, 500);
					}
					
					if( prevStep === 2 && step === 3 ) {
						dataLayerSend('UX', 'openMembershipReadyPage', strSend);
						
						$('html, body').animate({
        					scrollTop: $(wrapper).find("form").eq(0).offset().top
    					}, 500);
					}
					
					if( step === 2 ) {
						$("#"+id+" .input--num").on("keyup", function (evt) {
							if( evt.target.value > 1 ) {
								this.value = evt.target.value.slice(0, 1);
							}
							if( evt.target.value !== '' ) {
								$(this).next('.input--num').focus();
							}
            				if( evt.key === 'Backspace' ) {
                				$(this).prev('.input--num').focus();
            				}
							
							var code = "";
        					$(this).closest('form').find(".input--num").each(function() {
            					code += $(this).val();
        					});
							$(wrapper).find("input[name=NUM]").val(code);
        				});
					}
					
					MyAbonementApp.Forms.init(".get-abonement");
					
					return false;
                },
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus + ": " + errorThrown);
				}
            });
		},
		clearTextValues: function(formObject) {
			var inputs = ["input[type=text]", "textarea", "select"];
			for (var i = 0; i < inputs.length; i++) {
				$(formObject).find( inputs[i] ).each(function( index ) {
					$(this).val('');
				});
			}
			if( $(formObject).find(".options li").length > 0 ) {
				$(formObject).find(".options li").eq(0).click();
			}
		}
	};
});