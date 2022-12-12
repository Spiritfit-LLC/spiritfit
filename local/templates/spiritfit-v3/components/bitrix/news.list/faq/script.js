var open_accrodion=function(el){
    $(el).toggleClass("active");

    $(el).find(".b-accordion__content").slideToggle(300);
}

var set_section=function(el, section_id){
    if ($(el).hasClass("active")){
        return;
    }

    $(".b-faq__tab.active")
        .hide(300)
        .removeClass("active");

    $(`.b-faq__tab[data-section="${section_id}"]`)
        .show(300, function(){
            if ($(window).width()>1200){
                var offset=$(this).offset().top - 200
            }
            else{
                offset=$(this).offset().top - 150
            }
            $('html, body').animate({
                scrollTop: offset
            }, 300);
        })
        .addClass("active");


    $(".b-faq__tab-link.active").removeClass("active");
    $(el).addClass("active");


}

$(document).ready(function(){
    $(".b-faq__tab.active")
        .show(300);
})