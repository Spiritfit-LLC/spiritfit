jQuery(function($) {
    $( document ).ready(function() {
		$(".input--checkbox").styler();
    	MySubscribeApp.Forms.init(".subscribe-form");
	});
	var MySubscribeApp = MySubscribeApp || {};
	MySubscribeApp.Forms = {
		init: function(selector) {
			
			var formElement = $(selector);

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

						if( (typeof $(this).attr("required") != "undefined" || typeof $(this).data("required") != "undefined") && $(this).attr("type") == "checkbox" && !$(this).is(":checked")) {
							hasErrors = true;
						}
				    });
				}

				if( hasErrors ) {
					$(formElement).find(".form-error-modal").remove();
					$(formElement).append('<div class="popup popup--call form-error-modal" style="display: block;"><div class="popup__bg"></div><div class="popup__window"><div class="popup__close"><div></div><div></div></div><div class="popup__success">Для продолжения заполните все обязательные поля</div></div></div>');
					MySubscribeApp.Forms.initClose();
					return false;
				}

				$(this).find("input[type=submit]").attr('disabled', true);
				$(this).find("input[type=submit]").attr("value", "Обработка...");

				var formObject = $(this);
				MySubscribeApp.Forms.sendMultipartPost($(this));

				return false;
			});

			MySubscribeApp.Forms.initClose();
		},
		initClose: function() {
			$('.popup__close, .popup__bg').unbind();
			$('.popup__close, .popup__bg').on('click', function() {
				$('body').css('overflow', '');
				scrollbarLegalInformation.destroy();
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
					var id = $(cForm).parents(".subscribe-form-wrapper").eq(0).attr("id");

					var wrapper = ("#"+id);
					$(wrapper).html( $(data).find( "#"+id ).html() );

					$("#"+id+" .input--checkbox").styler();
					
					if( $(data).find( "#" + id + " .success" ).length > 0 ) {
						MySubscribeApp.Forms.clearTextValues();
					}
					MySubscribeApp.Forms.init(".subscribe-form");
					
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