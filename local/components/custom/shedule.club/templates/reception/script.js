$(document).ready(function(){
    function init(){
        $('.b-timetable-item').on(clickHandler, function(e){
            if (e.target.className==="b-timetable-item__close"){
                $(this).find('.b-timetable-item__hidden-content').removeClass('active');
            }
            else{
                $('.b-timetable-item__hidden-content').removeClass('active');
                $(this).find('.b-timetable-item__hidden-content').addClass('active');
            }
        });
    }

    init()

    $('.b-timetable__switch').on("select2:select",function(){
        $('.timetable-overlay').addClass('active');

        var club_num=$(this).val();
        BX.ajax.runComponentAction(scheduleComponentName, 'getSchedule', {
            mode: 'class',
            data: {
                'club':club_num,
                'page':'reception'
            },
            method:'POST'
        }).then(
            function(response){

                $('.timetable-overlay').removeClass('active');

                var buffer=$(response.data);
                $('section#timetable').find('.b-timetable__content-wrap').html(buffer.find('.b-timetable__content-wrap').html());
                init();
            });
    })

});