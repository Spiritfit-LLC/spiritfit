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
    $('input[name="not-virtual"]').on("click", showFiltered);


    //Выбор фильтра
    $('.filter-switch').on("select2:selecting select2:unselecting", function(e){
        var lastSelectedItem=e.params.args.data.id;
        var selectionItems=$(this).val();
        if (selectionItems.includes("all") && lastSelectedItem!=="all"){
            selectionItems = selectionItems.filter(function(item) {
                return item !== "all"
            });
            $(this).val(selectionItems).trigger("change");
        }
        else if (selectionItems.includes("all") && lastSelectedItem==="all"){
            $(this).val(null);
            $(this).val(null).trigger("change");
        }
        else if (lastSelectedItem==="all" && !selectionItems.includes("all")){
            e.preventDefault();
            $(this).val(null).trigger("change");
            $(this).select2("close");
        }
    });
    $(".filter-switch").on('change', showFiltered);

    $(".filter-switch").on("select2:select select2:unselect", function(e){
        var selectionItems=$(this).val();
        var select_container=$(this).data('select2').$container;
        var select_selecttion=$(select_container).find(".select2-selection__rendered");

        let countPlus = selectionItems.length-1;
        $(select_selecttion).find(".plus-selection").remove();
        if(countPlus>0) {
            $(select_selecttion)
                .append($(`<span class="plus-selection">+ ${countPlus}</span>`))
        }
    })

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

        var loadLevels=$(".filter-loadlevel__switch").select2("val");
        var musculeGroups=$(".filter-musculegroups__switch").select2("val");
        var iwants=$(".filter-iwant__switch").select2("val");
        $('.b-timetable__training-item.filter').each(function(){
            var hide=true;
            if (loadLevels.length===0){
                hide=false;
            }
            loadLevels.forEach((loadlevel)=>{
                if ($(this).data("filter-loadlevel").includes(loadlevel)){
                    hide=false;
                    return false;
                }
            });
            if (!hide){
                hide = musculeGroups.length !== 0;
                musculeGroups.forEach((musculegroup)=>{
                    if ($(this).data("filter-musculegroup").includes(musculegroup)){
                        hide=false;
                        return false;
                    }
                });
            }
            if (!hide){
                hide = iwants.length !== 0;
                iwants.forEach((iwant)=>{
                    if ($(this).data("filter-iwant").includes(iwant)){
                        hide=false;
                        return false;
                    }
                });
            }
            if (hide){
                $(this).removeClass("filter");
            }
        });

        if ($('input[name="not-virtual"]:checked').length>0){
            $('.b-timetable__training-item.filter').not('[data-filter-virtual="false"]').removeClass("filter");
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
        $('input[name="not-virtual"]').prop('checked', false);
        $('.filter-switch').val(null).trigger("change");
    })


    $('.filter-switch').on('select2:opening select2:closing', function( event ) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });

    if ($(window).width()<=768){
        // var new_checkbox_block=$('<div class="b-timetable__filter-block"></div>').appendTo(".b-timetable__filter");
        $(".b-timetable__filter-checkbox").insertBefore(".b-timetable__filter-clear")
    }
})