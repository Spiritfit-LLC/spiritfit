$(document).ready(function(){
    function showTraining(){
        if ($('.b-timetable__day-item.active').find('.b-timetable__training-item.filter').length > 0) {
            if ($('.b-timetable__day-item.active').find('.b-timetable__training-item.active').length===0){
                $('.b-timetable__day-item.active').find('.b-timetable__training-item.filter:first').addClass('active');
            }
            $('.filter-not-found').hide();
            $('.b-timetable__body').show();
            $('.get-trialworkout.is-hide-desktop').removeClass('is-hide');

            var parent=$('.b-timetable__training-item.active').closest('.b-timetable__day-times');
            var element=$('.b-timetable__training-item.active');
            $(parent).stop().animate({scrollTop:$(element).offset().top + $(parent).scrollTop() - $(parent).offset().top}, 500);
            var uid=$(element).data('id');
            $('.training-item__block.active').removeClass('active');
            $(`.training-item__block[data-id="${uid}"]`).addClass('active');

        }
        else{
            $('.b-timetable__body').hide();
            $('.get-trialworkout.is-hide-desktop').addClass('is-hide');
            $('.filter-not-found').show();
        }
    }

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
                $('.b-timetable__day-item.active').find('.b-timetable__training-item:visible').first().addClass('active');
            }
            showTraining();



            if ($(window).width()<=1024){
                var parent=$('.b-timetable__header');
                var element=$('.b-timetable__column.active');
                $(parent).stop().animate({scrollLeft:$(element).offset().left + $(parent).scrollLeft() - $(parent).offset().left}, 500);
            }
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

        // var message_modal=null;

        if ($(window).width()<=1024){
            var parent=$('.b-timetable__header');
            var element=$('.b-timetable__column.active');
            $(parent).stop().animate({scrollLeft:$(element).offset().left + $(parent).scrollLeft() - $(parent).offset().left}, 500);
        }

        // $('a[href="#modal-trial"]').on(clickHandler, function(e){
        //     e.preventDefault();
        //
        //     $('.timetable-overlay').addClass('active');
        //     var form_id=$(this).data('form-id');
        //
        //     $.ajax({
        //         url:'/local/ajax/getform.php',
        //         method:'POST',
        //         data:{
        //             WEB_FORM_ID:form_id,
        //             ABONEMENT_CODE:"pb",
        //             AJAX_ACTION:"getTrial",
        //             FORM_TYPE:"3"
        //         },
        //         success:function(result){
        //             $('.timetable-overlay').removeClass('active');
        //
        //             if (message_modal instanceof ModalWindow){
        //                 message_modal.destroy();
        //             }
        //
        //             var modal_content=$('<div class="trial-workout-form__modal" style="display: none"></div>').insertAfter($('#timetable .content-center'));
        //             modal_content.html(result);
        //             message_modal=new ModalWindow('ЗАПИСЬ НА ПРОБНУЮ ТРЕНИРОВКУ', modal_content.get(0), AnimationsTypes['fadeIn'], true, true, 'white-style');
        //
        //             $('.trial-workout-form__modal').show();
        //             message_modal.show();
        //         },
        //         error:function(data){
        //             console.log(data)
        //         }
        //     })
        // });
    }

    init();

    $('.b-timetable__switch').on("select2:select",function(){
        $('.timetable-overlay').addClass('active');

        var club_num=$(this).val();
        BX.ajax.runComponentAction(scheduleComponentName, 'getSchedule', {
            mode: 'class',
            data: {
                'club':club_num
            },
            method:'POST'
        }).then(
            function(response){

                $('.timetable-overlay').removeClass('active');

                var buffer=$(response.data.schedule);
                $('section#timetable').find('.b-timetable__content-wrap').html(buffer.find('.b-timetable__content-wrap').html());
                init();

                $('select.filter-coach__switch').empty();
                $('select.filter-direction__switch').empty();

                var newOption = new Option('Любой тренер', 'all', true, true);
                $('select.filter-coach__switch').append(newOption);
                for (el of Object.values(response.data.coaches)){
                    newOption = new Option(el.firstname+' '+el.secondname, el.id, false, false);
                    $('select.filter-coach__switch').append(newOption);
                }

                newOption = new Option('Все направления', 'all', true, true);
                $('select.filter-direction__switch').append(newOption);
                for (el of Object.values(response.data.directions)){
                    newOption = new Option(el.name, el.id, false, false);
                    $('select.filter-direction__switch').append(newOption);
                }

                $('select.filter-coach__switch').val('all').trigger('change');
                $('select.filter-direction__switch').val('all').trigger('change');
            }
        );
    });

    //===============Фильтры===============
    //Показать скрыть
    $('.show-filters').on(clickHandler, function(){
        if ($(this).hasClass('active')){
            $(this).html('Показать фильтры <span class="btn-arrow"></span>');
            $(this).removeClass('active');
            $('.b-timetable__filter').hide(200);
        }
        else{
            $(this).html('Скрыть фильтры <span class="btn-arrow up"></span>');
            $(this).addClass('active');
            $('.b-timetable__filter').show(200).css('display', 'flex');
        }
    })
    //Время
    $('input[name="b-timetable__time-filter"]').on("click", showFiltered);
    $('select.filter-coach__switch').on('select2:select', showFiltered);
    $('select.filter-direction__switch').on('select2:select', showFiltered);
    $('select.filter-exercise__switch').on('select2:select', showFiltered);

    //Функция отображения отфильтрованных элементов
    function showFiltered() {
        $('.b-timetable__training-item').removeClass('filter');
        var $times = $('input[name="b-timetable__time-filter"]:checked');

        if ($times.length > 0) {
            $times.each(function () {
                $(`.b-timetable__training-item[data-filter-time="${$(this).val()}"]`).addClass('filter');
            });
        } else {
            $('.b-timetable__training-item').addClass('filter');
        }

        var coach = $('select.filter-coach__switch').val();
        if (coach !== 'all') {
            $('.b-timetable__training-item.filter').not(`[data-filter-coach="${coach}"]`).removeClass('filter');
        }
        var direction = $('select.filter-direction__switch').val();
        if (direction !== 'all') {
            $('.b-timetable__training-item.filter').not(`[data-filter-direction="${direction}"]`).removeClass('filter');
        }

        var exercise=$('select.filter-exercise__switch').val();
        if (exercise !== 'all') {
            $('.b-timetable__training-item.filter').not(`[data-filter-exercise="${exercise}"]`).removeClass('filter');
        }

        $('.b-timetable__training-item').not('.filter').hide(200);
        $('.b-timetable__training-item.filter').show(200);


        $('.b-timetable__training-item.active').removeClass('active');
        $('.b-timetable__day-item.active').find('.b-timetable__training-item.filter:first').addClass('active');

        setTimeout(showTraining, 200)
    }

    //Сброс фильтров
    $('.b-timetable__filter-clear').on(clickHandler, function(){
        $('input[name="b-timetable__time-filter"]').prop('checked', false);
        $('select.filter-coach__switch').val('all').trigger('change');
        $('select.filter-direction__switch').val('all').trigger('change');

        showFiltered();
    })
})