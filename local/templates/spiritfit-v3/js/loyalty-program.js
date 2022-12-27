$(document).ready(function(){
    $('.level-item__image').each(function(){
        var $this=$(this);
        var width=$this.width();

        var height=width/1.84;
        $this.height(height);
    });

    if ($(window).width()>768){
        var height=0;
        $(".loyalty-сondition__item").each(function (){
            if ($(this).height()>height){
                height=$(this).height();
            }
        }).height(height);
    }

})

var open_accrodion=function(el){
    $(el).toggleClass("active");

    $(el).find(".b-accordion__content").slideToggle(300);
}