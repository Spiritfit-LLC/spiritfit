$(document).ready(function(){
    function init(){
        function showSchedule(){
            var uid=$('.b-timetable__column.active').data('id');
            $('.b-timetable__day-item.active').removeClass('active');
            $(`.b-timetable__day-item[data-id="${uid}"]`).addClass('active');


            $('.b-timetable__training-item.active').removeClass('active');
            if ($('.b-timetable__day-item.active').find('.b-timetable__training-item.current-item').length>0){
                $('.b-timetable__day-item.active').find('.b-timetable__training-item.current-item').addClass('active');
            }
            else{
                $('.b-timetable__day-item.active').find('.b-timetable__training-item').first().addClass('active');
            }
            showTraining();

            if ($(window).width()<=1024){
                var parent=$('.b-timetable__header');
                var element=$('.b-timetable__column.active');
                $(parent).stop().animate({scrollLeft:$(element).offset().left + $(parent).scrollLeft() - $(parent).offset().left}, 500);
            }
        }
        function showTraining(){
            var parent=$('.b-timetable__training-item.active').closest('.b-timetable__day-times');
            var element=$('.b-timetable__training-item.active');
            $(parent).stop().animate({scrollTop:$(element).offset().top + $(parent).scrollTop() - $(parent).offset().top}, 500);
            var uid=$(element).data('id');
            $('.training-item__block.active').removeClass('active');
            $(`.training-item__block[data-id="${uid}"]`).addClass('active');
        }

        $('.b-timetable__column').click(function(){
            if (!$(this).hasClass('active')){
                $('.b-timetable__column.active').removeClass('active');
                $(this).addClass('active');
                showSchedule();
            }
        });

        $('.b-timetable__training-item').click(function (){
            if (!$(this).hasClass('active')){
                $('.b-timetable__training-item.active').removeClass('active');
                $(this).addClass('active');
                showTraining();
            }
        });

        showSchedule();

        if ($(window).width()<=1024){
            var parent=$('.b-timetable__header');
            var element=$('.b-timetable__column.active');
            $(parent).stop().animate({scrollLeft:$(element).offset().left + $(parent).scrollLeft() - $(parent).offset().left}, 500);
        }
    }

    init();

    $('.b-timetable__switch').on("select2:select",function(){
        $('.timetable-overlay').addClass('active');

        var club_num=$(this).val();
        BX.ajax.runComponentAction(componentName, 'getSchedule', {
            mode: 'class',
            data: {
                'club':club_num
            },
            method:'POST'
        }).then(
            function(response){
                console.log(response)

                $('.timetable-overlay').removeClass('active');

                var buffer=$(response.data);
                $('section#timetable').find('.b-timetable__content-wrap').html(buffer.find('.b-timetable__content-wrap').html());
                init();
            },
            function(response){
                console.log(response)
            });
    })
})