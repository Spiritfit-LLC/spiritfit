jQuery(function($) {
    $( document ).ready(function() {
		$(".input--checkbox").styler();
    	
    	$(".input--name").unbind();
    	$(".input--name").on("input", function() {
        	this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
    	});
    	$(".get-contact [type=tel]").inputmask({
			'mask': '+7 (999) 999-99-99',
			onBeforeMask: function (value) {
				if (value[0] == '8') {
					var processedValue = value.replace('8', "");
				}
				return processedValue;
			}
		});

    	MyContactsApp.Forms.init(".get-contact");
	});
	var MyContactsApp = MyContactsApp || {};
	MyContactsApp.Forms = {
		init: function(selector) {
			var actionInput = $(selector).find("input[name=ACTION]");
			var submitButton = $(selector).find("input[type=submit]");
			var resendButton = $(selector).find(".get-contact-resend");
			var formElement = $(selector);

			if( step === 1 || step === 3 ) {
				$(submitButton).unbind("click");
				$(submitButton).click( function(e) {
					e.preventDefault();

					$(actionInput).val("SEND_SMS");
					$(formElement).submit();
				});
			}
			if( step === 2 ) {
				$(submitButton).unbind("click");
				$(submitButton).click( function(e) {
					e.preventDefault();
					
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
				MyContactsApp.Forms.clearTextValues( $(selector) );
			}

			$( selector ).unbind();
			$( selector ).submit(function(e) {
			    e.preventDefault();

				var inputs = ["input[type=text]", "input[type=password]", "input[type=hidden]", "input[type=checkbox]", "select", "textarea"];
				var hasErrors = false;
				for (var i = 0; i < inputs.length; i++) {
				    $(formElement).find( inputs[i] ).each(function( index ) {
					    var name = $(this).attr("name");
					    var value = $(this).val();
						
						if(typeof $(this).attr("required") != "undefined" && $(this).attr("type") != "checkbox") {
							if(typeof value != "undefined" && value == "") {
								hasErrors = true;
							}
						}
						if(typeof $(this).attr("required") != "undefined" && $(this).attr("type") == "checkbox" && !$(this).is(":checked")) {
							hasErrors = true;
						}
				    });
				}
				if( hasErrors && step != 2 ) {
					$(formElement).find(".form-error-modal").remove();
					$(formElement).append('<div class="popup popup--call form-error-modal" style="display: block;"><div class="popup__bg"></div><div class="popup__window"><div class="popup__close"><div></div><div></div></div><div class="popup__success">Для продолжения заполните все обязательные поля</div></div></div>');
					return false;
				}

				$(this).find("input[type=submit]").attr('disabled', true);
				$(this).find("input[type=submit]").attr("value", "Обработка...");

				var formObject = $(this);
				MyContactsApp.Forms.sendMultipartPost($(this));
				
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
					var id = $(cForm).parents(".company-message").eq(0).attr("id");
					var wrapper = ("#"+id);
					$(wrapper).html( $(data).find( "#"+id ).html() );

					step = parseInt( $(data).find( "#"+id ).data("step") );

					$("#" + id + " select").select2({
                		minimumResultsForSearch: Infinity
            		});
            		$("#"+id+" .input--checkbox").styler();

            		$("#"+id+" .input--name").on("input", function() {
						this.value = this.value.replace(/[^А-Яа-яA-Za-z]/gi, "");
					});

					$("#"+id+" [type=tel]").inputmask({
						'mask': '+7 (999) 999-99-99',
						onBeforeMask: function (value) {
							if (value[0] == '8') {
								var processedValue = value.replace('8', "");
							}
							return processedValue;
						}
					});

					if( prevStep === 1 && step === 2 ) {
						$('html, body').animate({
        					scrollTop: $(wrapper).find("form").eq(0).offset().top
    					}, 500);
					}

					if( prevStep === 2 && step === 3 ) {
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
					
					MyContactsApp.Forms.init(".get-contact");
					
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
	}
});