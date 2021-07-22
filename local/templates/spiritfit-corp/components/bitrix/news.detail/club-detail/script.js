$(document).ready(function () {
    $('.flip_button').click(function () {
        var block = $(this).parents('.club__team-slide-inner').is(".club__team-slide-front");
        if (block) {
            $(this).parents('.club__team-slide-inner').find('.flip_button').hide();
        }else{
            $(this).parents('.club__team-flip_box').find('.flip_button').show();
        }
        $(this).parents('.club__team-slide').children('.club__team-flip_box').toggleClass('flipped');
        return false;
    });
});