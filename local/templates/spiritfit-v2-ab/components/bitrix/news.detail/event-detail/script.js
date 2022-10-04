$(document).ready(function(){
    function resizeText(){
        try {
            $('.event-schedule__item-title').each(function(index, el){
                var current_width = el.scrollWidth;
                var real_width = el.clientWidth;

                if (current_width > real_width) {
                    if ($(window).width()>1770){
                        $(el).css("font-size", '30px');
                    }
                    else if ($(window).width()>1728){
                        $(el).css("font-size", '28px');
                    }
                    else if ($(window).width()>1660){
                        $(el).css("font-size", '26px');
                    }
                    else if ($(window).width()<847){
                        $(el).css("font-size", '26px');
                    }

                    if ($(window).width()<748){
                        $(el).css("font-size", '24px');
                    }
                }
            })
        }
        catch (e) {
            console.log(e)
        }
    }
    resizeText();
    $(window).resize(resizeText)
})