$(document).ready(function(){
    function setHeight(selector){
        var height=0;
        $(selector).each(function(){
            if ($(this).height()>height){
                height=$(this).height();
            }
        }).height(height);
    }

    if ($(window).width()>691){
        setHeight(".stock__item-content");
    }
});