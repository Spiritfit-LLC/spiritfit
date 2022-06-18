$(document).ready(function(){
    //Временное решение. Светлана тема по умолчанию
    if (!$("body").hasClass("white")){
        $(".theme-selector input[type=checkbox]").trigger('click');
    }


    function SetActiveSection(){
        var pageTop = $(window).scrollTop()+115;
        var pageBottom = pageTop + $(window).height()-115;

        $('.text-section').each(function(i, el){
            var elementTop = $(el).offset().top;
            var elementBottom = elementTop + $(el).height();

            if(((elementTop <= pageBottom) && (elementBottom >= pageTop))){
                if ((pageTop < elementTop) && (pageBottom > elementBottom)){
                    $('.text-section__head-title').removeClass('active');
                    var id=$(el).data('id');
                    $(`.text-section__head-title[data-id="${id}"]`).addClass('active');

                    return false;
                }
                $('.text-section__head-title').removeClass('active');
                var id=$(el).data('id');
                $(`.text-section__head-title[data-id="${id}"]`).addClass('active');

                return false;
            }
        });
    }

    SetActiveSection();

    $(window).scroll(function(){
        var user_scrolling=$('.blog-detail-text').attr('user-scrolling');
        if (!user_scrolling || !!(user_scrolling)){
            SetActiveSection();
        }
    })

    $('.text-section__head-title').click(function(){
        var id=$(this).data('id');
        var dest = $(`.text-section[data-id="${id}"]`)[0];

        if(dest !== undefined && dest !== '') {
            $('.blog-detail-text').attr('user-scrolling', true);
            $('html, body').animate({
                    scrollTop: $(dest).offset().top-115
                }, {
                    duration: 500,
                    easing: "swing",
                    complete: function () {
                        $('.text-section__head-title').removeClass('active');
                        $(`.text-section__head-title[data-id="${id}"]`).addClass('active');
                        $('.blog-detail-text').attr('user-scrolling', false);
                    }
                },
            );
        }
    });


    if ($(window).width()<=1200){
        $('<div>', {
            'class': 'mobile-text-section-titles',
            'style':'display:none',
            html:'<div class="closer-btn"><img src="/local/templates/spiritfit-v2/img/icons/closer-white.png"></div>'
        })
            .append($('.text-section-titles'))
            .prependTo($('.blog-detail-text'));

        $('.show-sections-titles').click(function(){
            $('.mobile-text-section-titles').fadeIn(300);
        })

        $('.mobile-text-section-titles .closer-btn').click(function(){
            $('.mobile-text-section-titles').fadeOut(300);
        })

        $('.text-section__head-title').click(function(){
            $('.mobile-text-section-titles').fadeOut(300);
        });
        // $('.text-section-titles').hide(300);
        //
        // $('.show-hide-titles').click(function(){
        //     if ($(this).hasClass('active')){
        //         $('.text-section-titles').hide(300);
        //         $(this).removeClass('active');
        //     }
        //     else{
        //         $('.text-section-titles').show(300);
        //         $(this).addClass('active');
        //     }
        // })
    }
});