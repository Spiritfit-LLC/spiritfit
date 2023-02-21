$(document).ready(function(){
    var $card = $('.event-schedule__item');
    $card.click(function(){
        $(this).toggleClass('is-open');
    });
});