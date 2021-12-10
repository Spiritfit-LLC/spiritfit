jQuery(function($) {
    $( document ).ready(function() {
		$(".to-contact-form").click(function(e) {
			e.preventDefault();
			$('html, body').animate({
                scrollTop: $("#js-pjax-clubs").offset().top
            }, 500);
			
			$(this).parents(".corp-abonement-wrapper").addClass("disabled");
			
			var button = $(this);
			setTimeout(function() {
				$(button).parents(".corp-abonement-wrapper").removeClass("disabled");
			}, 500);
		});
	});
});
