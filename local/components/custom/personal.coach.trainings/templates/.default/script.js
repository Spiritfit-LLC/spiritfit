$(document).ready(function (){
    var tw_timetable_component=$('.LK_COACHTRAININGS').data('componentname');
    var offset=6;
    var current_visit_el_index=0;

    var errorModal=new ModalWindow("Упс... Что-то пошло не так", $(".LK_COACHTRAININGS").find(".error-modal").get(0), AnimationsTypes['fadeIn'], true, true, 'white-style');
    var addWorkoutModal=new ModalWindow('Добавить персональную тренировку', $(".LK_COACHTRAININGS").find(".add-workout__form").get(0), AnimationsTypes['fadeIn'], true, false, 'black-style')
    $(addWorkoutModal.content).find('.personal-section-form__submit').on('click', function(){
        var time_from=$('.tw-timetable__section-timeitem.choose.start').data('time');
        var time_to=$('.tw-timetable__section-timeitem.choose.end').data('time');
        var slots=[
            {
                "time_from":time_from,
                "time_to":time_to
            }
        ]
        var action="busy";
        var date=$('.tw-dates__days-container').find('.day-item.active').data('date');
        var clientName=$('input[name="add-workout__client"]').val();
        setSlots(date, slots, action, null, clientName);
        $('input[name="add-workout__client"]').val('');
        addWorkoutModal.close();
    })


    var workoutTooltipInstance=null;
    var chooseTooltipInstance=null;

    function scrollDaysCount(index){
        var parent=$('.tw-dates__days-container');
        var element=parent.find(`.day-item[data-index="${index}"]`);
        $(parent).stop().animate({scrollLeft:element.offset().left + parent.scrollLeft() - parent.offset().left}, 500);


        var month=parent.find(`.day-item[data-index="${index}"]`).data('month');
        if (month!==parent.find(`.day-item[data-index="${index+offset}"]`).data('month') && parent.find(`.day-item[data-index="${index+offset}"]`).length>0){
            month+='-'+parent.find(`.day-item[data-index="${index+offset}"]`).data('month');
        }
        $('.tw-dates__date-month').text(month)
    }
    setTimeout(function(){
        scrollDaysCount(current_visit_el_index);
    },500)


    $('.tw-dates__controller').on('click', function(){
        if ($(this).hasClass('left')){
            var ind=current_visit_el_index-offset;
            if (ind<0){
                ind=0;
            }
        }
        else if ($(this).hasClass('right')){
            ind=current_visit_el_index+offset;
            if (ind>$('.tw-dates__days-container').find('.day-item').length-offset){
                ind=$('.tw-dates__days-container').find('.day-item').length-offset;
            }
        }
        scrollDaysCount(current_visit_el_index=ind);
    });


    function hideWorkoutHighlight(workout_id){
        $(`.busy-workout-item__slots-container[data-workout-id="${workout_id}"]`).each(function(index, el){
            $(el).removeClass('active');
            if (index!==0){
                $(el).hide();
            }
            else{
                if ($(window).width()<=768){
                    var div_width='calc(calc('+23*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                    var slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                }
                else{
                    div_width='calc(calc('+19*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                    slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                }
                $(el).css({
                    'width':div_width,
                    'flex':'0 0 '+div_width,
                    'box-shadow':'inset -20px -12px 20px 3px #0000004d',
                })
                $(el).find('.tw-timetable__section-timeitem').css(
                    {
                        'width':slot_width,
                        'flex':'0 0 '+slot_width
                    }
                )
                $(el).find('.tw-timetable__section-timeitem').not('.start').hide();
            }
        });
    }

    function highlightWorkout(){
        var colors=[
            '#FFFFFF',
            '#00BFFF',
            '#98FF98',
            '#7FFF00',
            '#FFD700',
            '#FDEAA8',
            '#CD853F',
            '#000080',
            '#F5F5DC'
        ];

        var workouts=[];

        $('.tw-timetable__section-times-container').each(function(index, el){
            var workout_item={};
            $(el).find('.tw-timetable__section-timeitem.busy').not("highlighted").each(function(index, el_item){
                if (!($(el_item).data('workout-id') in workout_item)){
                    workout_item[$(el_item).data('workout-id')]={
                        'times':[$(el_item).data('time')],
                        'changeble':$(el_item).data('changeble'),
                        'client':$(el_item).data('client'),
                        'type':$(el_item).data('workout-type')
                    }
                }
                else{
                    workout_item[$(el_item).data('workout-id')].times.push($(el_item).data('time'))
                }
                if ($(window).width()>768){
                    if (5===parseInt($(el_item).data("row-position"))){
                        workouts.push(workout_item)
                        workout_item={};
                    }
                }
                else{
                    if (4===parseInt($(el_item).data("row-position"))){
                        workouts.push(workout_item)
                        workout_item={};
                    }
                }
            });
            workouts.push(workout_item)
        })

        var resultArr={}
        workouts.forEach(function(el){
            for (const [workout_id, value] of Object.entries(el)) {
                if (!(workout_id in resultArr)){
                    resultArr[workout_id]=[];
                }
                resultArr[workout_id].push(value)
            }
        });

        var workout_colors={}
        var index=0;
        var times={};
        for (const [workout_id, value] of Object.entries(resultArr)) {
            times[workout_id]={
                'minTime':moment(value[0].times[0], "HH:mm").format("HH:mm"),
                'maxTime':moment(value[0].times[0], "HH:mm").format("HH:mm"),
            }

            var div_index=0;
            value.forEach(function(el){
                if ($(window).width()<=768){
                    var div_width='calc(calc('+23*el.times.length+'%'+' + '+5*el.times.length+'px) - 5px)';
                    var slot_width='calc('+100/el.times.length+'%'+' - '+6+'px)';
                }
                else{
                    div_width='calc(calc('+19*el.times.length+'%'+' + '+5*el.times.length+'px) - 5px)';
                    slot_width='calc('+100/el.times.length+'%'+' - '+6+'px)';
                }
                if (!(workout_id in workout_colors)){
                    workout_colors[workout_id]=colors[index];
                }

                el.times.forEach(function(time){
                    if (moment(time, "HH:mm").isBefore(moment(times[workout_id].minTime, "HH:mm"))){
                        times[workout_id].minTime=moment(time, "HH:mm").format("HH:mm");
                    }
                    if (moment(time, "HH:mm").isAfter(moment(times[workout_id].minTime, "HH:mm"))){
                        times[workout_id].maxTime=moment(time, "HH:mm").format("HH:mm");
                    }
                })
                var cont_id = (Math.random() + 1).toString(36).substring(2)+workout_id;
                $(`<div class="busy-workout-item__slots-container" id="${cont_id}" data-workout-id="${workout_id}" data-index="${div_index}" data-client="${el.client}" data-changeble="${el.changeble}" data-type="${el.type}" style="width: ${div_width}; flex:0 0 ${div_width}; border-color: ${workout_colors[workout_id]}; color:${workout_colors[workout_id]}"></div>`).insertBefore(`.tw-timetable__section-timeitem[data-time="${el.times[0]}"]`);
                el.times.forEach(function(time, i){
                    $(`.tw-timetable__section-timeitem[data-time="${time}"]`).css({
                        'width':slot_width,
                        'flex':'0 0 '+slot_width
                    }).addClass('highlighted').appendTo(`#${cont_id}.busy-workout-item__slots-container`);
                    if (i===0){
                        $(`.tw-timetable__section-timeitem[data-time="${time}"]`).addClass('start')
                    }
                    else{
                        $(`.tw-timetable__section-timeitem[data-time="${time}"]`).hide();
                    }
                });
                div_index++;
            });
            index++;
        }
        for (const [workout_id, t] of Object.entries(times)) {
            $(`.busy-workout-item__slots-container[data-workout-id="${workout_id}"]`).data('time-from', t.minTime).data('time-to', t.maxTime);
            $(`.tw-timetable__list-item[data-workout-id="${workout_id}"]`).find('.tw-list__info.time').find('.tw-list__info-value').data('time-from', t.minTime).data('time-to', t.maxTime).text(t.minTime+'-'+moment(t.maxTime, "HH:mm").add(15, 'minutes').format('HH:mm'));
            $(`.tw-timetable__list-item[data-workout-id="${workout_id}"]`).find('.workout-info-list__functions-btn').on('click', function(){
                $(`.tw-timetable__list-item[data-workout-id="${workout_id}"]`).find('.workout-info-list-functions').show();
            });
            $(document).on('click', function(e){
                if (!$(e.target).hasClass('workout-info-list__functions-btn')){
                    $('.tw-timetable__list-item').find('.workout-info-list-functions').hide();
                }
            })
        }
        $(`.busy-workout-item__slots-container`).each(function(index, el){
            if (parseInt($(el).data('index'))!==0){
                $(el).hide();
            }
            else{
                $(el).addClass("workout-start");
                if ($(window).width()<=768){
                    var div_width='calc(calc('+23*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                    var slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                }
                else{
                    div_width='calc(calc('+19*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                    slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                }
                $(el).css({
                    'width':div_width,
                    'flex':'0 0 '+div_width,
                    'box-shadow':'inset -20px -12px 20px 3px #0000004d',
                })
                $(el).find('.tw-timetable__section-timeitem').css(
                    {
                        'width':slot_width,
                        'flex':'0 0 '+slot_width
                    }
                )
            }
        });

        if ($(window).width()>768) {
            $('.busy-workout-item__slots-container').on('click', function () {
                if ($(this).hasClass('active')) {
                    hideWorkoutHighlight($(this).data('workout-id'))
                } else {
                    hideWorkoutHighlight($(`.busy-workout-item__slots-container.active`).data('workout-id'))
                    $(`.busy-workout-item__slots-container[data-workout-id="${$(this).data('workout-id')}"]`).each(function (index, el) {
                        $(el).addClass('active');
                        if ($(window).width() <= 768) {
                            var div_width = 'calc(calc(' + 23 * $(el).find('.tw-timetable__section-timeitem').length + '%' + ' + ' + 5 * $(el).find('.tw-timetable__section-timeitem').length + 'px) - 5px)';
                            var slot_width = 'calc(' + 100 / $(el).find('.tw-timetable__section-timeitem').length + '%' + ' - ' + 6 + 'px)';
                        } else {
                            div_width = 'calc(calc(' + 19 * $(el).find('.tw-timetable__section-timeitem').length + '%' + ' + ' + 5 * $(el).find('.tw-timetable__section-timeitem').length + 'px) - 5px)';
                            slot_width = 'calc(' + 100 / $(el).find('.tw-timetable__section-timeitem').length + '%' + ' - ' + 6 + 'px)';
                        }
                        $(el).css({
                            'width': div_width,
                            'flex': '0 0 ' + div_width,
                            'box-shadow': 'unset',
                        }).show();
                        $(el).find('.tw-timetable__section-timeitem').css(
                            {
                                'width': slot_width,
                                'flex': '0 0 ' + slot_width
                            }
                        ).show();
                    });
                }
            });
        }

        //Подсказки о клиентах
        var tooltipoptions={
            content: (reference) =>
            {
                var obj='<div class="workout-info-tooltip" >';

                if ($(window).width()>768 && $(reference).data('changeble')===1){
                    obj+='<div class="workout-info-tooltip__functions-btn"></div>' +
                        '<div class="tooltip-functions is-hide">' +
                        '<div class="tooltip-function__item cancel">Отменить тренировку</div>';
                    obj+='</div>';
                }

                if ($(window).width()<=768){

                    obj+='<div class="workout-info-tooltip__functions-btn"></div>' +
                        '<div class="tooltip-functions is-hide">' +
                        '<div class="tooltip-function__item expand">Развернуть/свернуть</div>';
                    if ($(reference).data('changeble')===1){
                        obj+='<div class="tooltip-function__item cancel">Отменить тренировку</div>';
                    }
                    obj+='</div>';
                }
                obj+='<div class="training-info client">'+
                '<div class="training-info-placeholder">Клиент</div>'+
                `<div class="training-info-value">${$(reference).data('client')}</div>`+
                '</div>'+
                '<div class="training-info time">'+
                '<div class="training-info-placeholder">Время</div>'+
                `<div class="training-info-value">${$(reference).data('time-from')} - ${moment($(reference).data('time-to'), "HH:mm").add(15, 'minutes').format('HH:mm')}</div>`+
                '</div>'+
                '<div class="training-info type">'+
                '<div class="training-info-placeholder">Тип тренировки</div>'+
                `<div class="training-info-value">${$(reference).data('type')}</div>`+
                '</div>'+
                '</div>';

                return obj;
            },
            theme: 'light',
            allowHTML: true,
            delay: 100, // ms
            placement: 'top',
            arrow: false,
            animation: 'fade',
            interactive: true,
            onMount:(instance)=>{
                $('.training-info').css('filter', 'none')
                $('.tooltip-functions').hide(200).removeClass('active');

                $('.tw-timetable__section-timeitem').not(`[data-workout-id="${$(instance.reference).data('workout-id')}"]`).css('filter', 'blur(2px)');
                $('.busy-workout-item__slots-container').not(`[data-workout-id="${$(instance.reference).data('workout-id')}"]`).css('filter', 'blur(2px)');
                $('.workout-info-tooltip__functions-btn').on('click', function(){
                    $('.tooltip-functions').show(200).addClass('active');
                    $('.training-info').css('filter', 'blur(1px)');
                });
                $(instance.popper).on('click', function(e){
                    if (!$(e.target).hasClass('workout-info-tooltip__functions-btn')){
                        $('.training-info').css('filter', 'none')
                        $('.tooltip-functions').hide(200).removeClass('active');
                    }
                });

                if ($(window).width()<=768){
                    $('.tooltip-function__item.expand').on('click', function(){
                        if ($(instance.reference).hasClass('active')) {
                            hideWorkoutHighlight($(instance.reference).data('workout-id'));

                        } else {
                            hideWorkoutHighlight($(`.busy-workout-item__slots-container.active`).data('workout-id'));
                            $(`.busy-workout-item__slots-container[data-workout-id="${$(instance.reference).data('workout-id')}"]`).each(function (index, el) {
                                $(el).addClass('active');
                                if ($(window).width() <= 768) {
                                    var div_width = 'calc(calc(' + 23 * $(el).find('.tw-timetable__section-timeitem').length + '%' + ' + ' + 5 * $(el).find('.tw-timetable__section-timeitem').length + 'px) - 5px)';
                                    var slot_width = 'calc(' + 100 / $(el).find('.tw-timetable__section-timeitem').length + '%' + ' - ' + 6 + 'px)';
                                } else {
                                    div_width = 'calc(calc(' + 19 * $(el).find('.tw-timetable__section-timeitem').length + '%' + ' + ' + 5 * $(el).find('.tw-timetable__section-timeitem').length + 'px) - 5px)';
                                    slot_width = 'calc(' + 100 / $(el).find('.tw-timetable__section-timeitem').length + '%' + ' - ' + 6 + 'px)';
                                }
                                $(el).css({
                                    'width': div_width,
                                    'flex': '0 0 ' + div_width,
                                    'box-shadow': 'unset',
                                }).show();
                                $(el).find('.tw-timetable__section-timeitem').css(
                                    {
                                        'width': slot_width,
                                        'flex': '0 0 ' + slot_width
                                    }
                                ).show();
                            });
                        }
                        instance.hide();
                    });
                }

                $(".tooltip-function__item.cancel").on('click', function(){
                    var answer=confirm("Подтвердите отмену тренировки");
                    if (answer){
                        var time_from=$(instance.reference).data('time-from');
                        var time_to=$(instance.reference).data('time-to');

                        var slots=[
                            {
                                "time_from":time_from,
                                "time_to":time_to
                            }
                        ]
                        var action="cancel";
                        var date=$('.tw-dates__days-container').find('.day-item.active').data('date');
                        setSlots(date, slots, action, instance);
                    }
                })

            },
            onHide:(instance)=>{
                $('.tw-timetable__section-timeitem').not($(instance.reference).find('.tw-timetable__section-timeitem')).css('filter', 'none');
                $('.busy-workout-item__slots-container').not(`[data-workout-id="${$(instance.reference).data('workout-id')}"]`).css('filter', 'none');

                $(instance.popper).unbind();
                if ($(window).width()<=768){
                    $('.tooltip-function__item.expand').unbind();
                }
                $(".tooltip-function__item.cancel").unbind();
            },
            maxWidth:500,
        }
        if ($(window).width()<=768){
            tooltipoptions['trigger']='click'
        }
        workoutTooltipInstance=tippy('.busy-workout-item__slots-container', tooltipoptions);
    }

    function chooseSlots(){
        var start_index=null;
        var chooseEvent=false;
        $('.tw-timetable__section-timeitem').not('.busy').on('click', function(){
            var chooseSlotTooltip=null;
            if ($('.tw-timetable__section-timeitem.choose').length===0){
                $(this).addClass("choose").addClass("start");
                start_index=parseInt($(this).data('index'));
                chooseEvent=true;
                $('.tw-timetable__section-timeitem').removeClass('include-busy').removeClass('only-free');

                chooseSlotTooltip=tippy($('.tw-timetable__section-timeitem.choose.start').get(0), {
                    content:"Выберите конечное время",
                    theme: 'light',
                    allowHTML: true,
                    delay: 100, // ms
                    placement: 'top',
                    arrow: false,
                    animation: 'fade',
                    interactive: true,
                    trigger:'manual'
                });

                chooseSlotTooltip.show();
            }
            else{
                if (chooseSlotTooltip!==null){
                    chooseSlotTooltip.hide();
                }
                if (chooseEvent){
                    var first_index=start_index;
                    var last_index=parseInt($(this).data('index'));
                    if (last_index<first_index){
                        var buffer=first_index;
                        first_index=last_index;
                        last_index=buffer;
                    }
                    var busy_flag=false;
                    var only_free=true;
                    if (last_index!==first_index){
                        for (var i=first_index; i<=last_index; i++){
                            $(`.tw-timetable__section-timeitem[data-index="${i}"]`).not('.busy').addClass('choose');
                            if ($(`.tw-timetable__section-timeitem[data-index="${i}"]`).hasClass('busy')){
                                busy_flag=true;
                            }
                            if (!$(`.tw-timetable__section-timeitem[data-index="${i}"]`).hasClass('free')){
                                only_free=false;
                            }
                        }
                    }
                    else{
                        only_free=false;
                        busy_flag=true;
                    }
                    $('.tw-timetable__section-timeitem.choose.start').not('busy').removeClass('start');
                    $(`.tw-timetable__section-timeitem[data-index="${last_index}"]`).last().addClass('end');
                    $(`.tw-timetable__section-timeitem[data-index="${first_index}"]`).last().addClass('start');
                    if (busy_flag){
                        $('.tw-timetable__section-timeitem.choose').last().addClass('include-busy');
                    }
                    if (only_free){
                        $('.tw-timetable__section-timeitem.choose').last().addClass('only-free');
                    }
                }
                var tippyoptions={
                    content: (reference) =>
                    {
                        var obj='<div class="workout-choose-tooltip">';
                        if (!$(reference).hasClass('only-free')){
                            obj+='<div class="workout-choose-btn free">Добавить в расписание</div>';
                        }

                        if (!$(reference).hasClass('include-busy')){
                            obj+='<div class="workout-choose-btn busy">Добавить тренировку</div>';
                        }

                        obj+='<div class="workout-choose-btn cncl">Отменить выбор</div>' +
                            '</div>';
                        return obj;
                    },
                    theme: 'light',
                    allowHTML: true,
                    delay: 100, // ms
                    arrow: false,
                    animation: 'fade',
                    interactive: true,
                    trigger: 'manual',
                    onMount:(instance)=>{
                        try{
                            workoutTooltipInstance.forEach(function(el){
                                el.disable();
                            })
                        }
                        catch (e){ }

                        $('.workout-choose-btn.cncl').on('click', function(){
                            $('.tw-timetable__section-timeitem.choose').removeClass('choose').removeClass('end').removeClass('start');
                            instance.hide();
                        })
                        $('.workout-choose-btn.free').on('click', function (){
                            var time_from=$('.tw-timetable__section-timeitem.choose.start').data('time');
                            var time_to=$('.tw-timetable__section-timeitem.choose.end').data('time');
                            var slots=[];
                            if (!$(instance.reference).hasClass("include-busy")){
                                slots.push(
                                    {
                                        "time_from":time_from,
                                        "time_to":time_to
                                    }
                                );
                            }
                            else{
                                var currTime=time_from;
                                let slotItem={
                                    "time_from":time_from
                                }
                                while(moment(currTime, "HH:mm").isBefore(moment(time_to, "HH:mm").add(15, 'minutes'))){
                                    var prevTime=moment(currTime, "HH:mm").subtract(15, 'minutes').format("HH:mm");
                                    var nextTime=moment(currTime, "HH:mm").add(15, 'minutes').format("HH:mm");

                                    if ($(`.tw-timetable__section-timeitem[data-time="${currTime}"]`).hasClass("busy")){
                                        currTime=nextTime;
                                        continue;
                                    }
                                    if ($(`.tw-timetable__section-timeitem[data-time="${prevTime}"]`).hasClass("busy")){
                                        slotItem["time_from"]=currTime;
                                    }
                                    if ($(`.tw-timetable__section-timeitem[data-time="${currTime}"]`).hasClass("end") || $(`.tw-timetable__section-timeitem[data-time="${nextTime}"]`).hasClass("busy")){
                                        slotItem["time_to"]=currTime;
                                        slots.push(Object.assign({}, slotItem));
                                    }
                                    currTime=nextTime;
                                }
                            }

                            var action="free";
                            var date=$('.tw-dates__days-container').find('.day-item.active').data('date');
                            setSlots(date, slots, action, instance);
                        });

                        $('.workout-choose-btn.busy').on('click', function (){
                            addWorkoutModal.show();
                        })
                    },
                    onHide:(instance)=>{
                        try{
                            workoutTooltipInstance.forEach(function(el){
                                el.enable();
                            })
                        }
                        catch (e){ }
                        $('.workout-choose-btn.cncl').unbind();
                        $('.workout-choose-btn.free').unbind();
                    },
                    maxWidth:500,
                };
                if ($(window).width()>768){
                    tippyoptions['placement']='right'
                }
                else{
                    tippyoptions['placement']='top'
                }
                chooseTooltipInstance=tippy($(".tw-timetable__section-timeitem.choose").last().get(0), tippyoptions);
                chooseEvent=false;
                chooseTooltipInstance.show();
            }
        });

        $('.tw-timetable__section-timeitem').not('.busy').on('mouseenter', function(){
            if (chooseEvent){
                $('.tw-timetable__section-timeitem.choose').not(`[data-index="${start_index}"]`).removeClass("choose");
                var first_index=start_index;
                var last_index=parseInt($(this).data('index'));

                if (last_index===first_index){
                    return;
                }
                if (last_index<first_index){
                    var buffer=first_index;
                    first_index=last_index;
                    last_index=buffer;
                }

                for (var i=first_index; i<=last_index; i++){
                    $(`.tw-timetable__section-timeitem[data-index="${i}"]`).not('.busy').addClass('choose');
                }
            }
        });
    }

    function setPositions(){
        $('.tw-timetable__section-times-container').each(function(i, el){
            var position=1;
            if ($(window).width()>768){
                var lenght=5;
            }
            else{
                lenght=4;
            }
            $(el).find('.tw-timetable__section-timeitem').each(function(j, el2){
                // console.log(position)
                $(el2).data('row-position', position);
                if (position===lenght){
                    position=1;
                    return;
                }
                position++;
            })
        });
    }

    function getTimetable(){
        $('.LK_COACHTRAININGS').find('.loading-overlay').addClass('active');

        var postData={
            'date':$('.tw-dates__days-container').find('.day-item.active').data('date')
        }

        BX.ajax.runComponentAction(tw_timetable_component, 'getTimetable', {
            mode: 'class',
            method:'POST',
            data:postData,
        }).then(
            function(response){
                $('.LK_COACHTRAININGS').find('.loading-overlay').removeClass('active');
                $('.tw-days-timetable__container').html(response.data);

                setPositions();
                highlightWorkout();
                chooseSlots();

                // //Занятые слоты списком
                $('.trainings-list-btn').on('click', function(){
                    if ($(this).hasClass('active')){
                        $('.tw-timetable__list-item').closest(".tw-timetable__section-times-container").hide(200, function(){
                            $('.tw-timetable__list-item').hide();
                            $('.busy-workout-item__slots-container').show().each(function(index, el){
                                hideWorkoutHighlight($(el).data('workout-id'))
                            });
                        });
                        $('.tw-timetable__list-item').closest(".tw-timetable__section-times-container").show(200);
                        $(this).text("Вывести списком");
                        $(this).removeClass("active");

                    }
                    else{
                        $('.tw-timetable__list-item').show(200);
                        $('.busy-workout-item__slots-container').hide(200);
                        $(this).text("Убрать список");
                        $(this).addClass("active");

                    }

                });

                //РАДИОКНОПКА
                $('input[name="slots"]').click(function(){
                    $('.trainings-list-btn').text('Вывести списком');
                    $(`.busy-workout-item__slots-container`).removeClass('active');
                    $('.trainings-list-btn').removeClass('active');
                    $('.tw-timetable__list-item').hide();
                    $('.tw-timetable-controllers').hide(200)
                    $('.trainings-list').hide(200)
                    $('.tw-timetable__change').hide(200);
                    var radio_val=$(this).val();
                    if (radio_val==="CLIENTS"){
                        $('.tw-timetable__section-timeitem').hide();
                        $('.tw-timetable__section-timeitem.busy').show();
                        $('.busy-workout-item__slots-container').show();
                        $(`.busy-workout-item__slots-container`).each(function(index, el){
                            hideWorkoutHighlight($(el).data('workout-id'))
                        });

                        $('.tw-timetable__section').show();
                        $('.tw-timetable__section-times-container').each(function(index, el){
                            if ($(el).find(":visible").length===0){
                                $(el).closest('.tw-timetable__section').hide();
                            }
                        });
                        $('.tw-timetable-controllers').show(200)
                    }
                    else if(radio_val==="FREE"){
                        $('.tw-timetable__section-timeitem.choose').removeClass('choose').removeClass('end').removeClass('start')
                        if (chooseTooltipInstance!==null){
                            chooseTooltipInstance.hide();
                        }

                        $('.busy-workout-item__slots-container').hide();
                        $('.tw-timetable__section-timeitem').hide();
                        $('.tw-timetable__section-timeitem.free').show();
                        $('.tw-timetable__section').show();

                        $('.tw-timetable__section-times-container').each(function(index, el){
                            if ($(el).find(":visible").length===0){
                                $(el).closest('.tw-timetable__section').hide();
                            }
                        });
                    }
                    else if (radio_val==="ALL"){

                        $('.tw-timetable__section-timeitem.choose').removeClass('choose').removeClass('end').removeClass('start');

                        $('.tw-timetable__section-timeitem').show();
                        $('.tw-timetable__section').show();
                        $('.tw-timetable__change').show(200);
                        $(`.busy-workout-item__slots-container`).each(function(index, el){
                            $(el).removeClass('active');
                            if (parseInt($(el).data('index'))!==0){
                                $(el).hide();
                            }
                            else{
                                $(el).show();
                                if ($(window).width()<=768){
                                    var div_width='calc(calc('+23*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                                    var slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                                }
                                else{
                                    div_width='calc(calc('+19*$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' + '+5*$(el).find('.tw-timetable__section-timeitem.start').length+'px) - 5px)';
                                    slot_width='calc('+100/$(el).find('.tw-timetable__section-timeitem.start').length+'%'+' - '+6+'px)';
                                }
                                $(el).css({
                                    'width':div_width,
                                    'flex':'0 0 '+div_width,
                                    'box-shadow':'inset -20px -12px 20px 3px #0000004d',
                                })
                                $(el).find('.tw-timetable__section-timeitem').css(
                                    {
                                        'width':slot_width,
                                        'flex':'0 0 '+slot_width
                                    }
                                ).show();
                                $(el).find('.tw-timetable__section-timeitem').not('.start').hide();
                            }
                        });
                    }
                    scrollToTimetable();
                });

                //Отмена тренировки в списке
                $(".workout-info-list-function__item.cncl").on('click', function(){
                    var answer=confirm("Подтвердите отмену тренировки");
                    if (answer){
                        var time_from=$(this).closest(".tw-timetable__list-item").find('.tw-list__info.time').find('.tw-list__info-value').data('time-from');
                        var time_to=$(this).closest(".tw-timetable__list-item").find('.tw-list__info.time').find('.tw-list__info-value').data('time-to');

                        var slots=[
                            {
                                "time_from":time_from,
                                "time_to":time_to
                            }
                        ]
                        var action="cancel";
                        var date=$('.tw-dates__days-container').find('.day-item.active').data('date');
                        setSlots(date, slots, action);
                    }
                })


                //Формы для слотов
                // var timeitem_instance=tippy('.tw-timetable__section-timeitem:not(.busy)', {
                //     content: (reference) =>
                //     {
                //         var string=`<div class="tw-timeitem__functions" data-time="${$(reference).data('time')}">` +
                //             '<div class="tw-function__item setTraining">' +
                //             'Добавить тренировку' +
                //             '</div>';
                //
                //         if (!$(reference).hasClass('free')){
                //             string+='<div class="tw-function__item setSlot">' +
                //                 'Добавить в мое расписание' +
                //                 '</div>';
                //         }
                //         string+='</div>';
                //
                //         return string;
                //     },
                //     theme: 'light',
                //     allowHTML: true,
                //     placement: 'top-start',
                //     arrow: false,
                //     animation: 'fade',
                //     // theme: 'material',
                //     interactive: true,
                //     trigger: 'click',
                //     onMount:(instance)=>{
                //         $(instance.reference).addClass('choose');
                //
                //     },
                //     onHide:(instance)=>{
                //         $(instance.reference).removeClass('choose');
                //     },
                //     onShown:(instance)=>{
                //         $('.tw-function__item.setSlot').unbind();
                //         $('.tw-function__item.setSlot').click(function(){
                //             var postData={
                //                 'date':$('.tw-dates__days-container').find('.day-item.active').data('date'),
                //                 'time':$('.tw-timeitem__functions').data('time')
                //             }
                //
                //             BX.ajax.runComponentAction(tw_timetable_component, 'setSlot', {
                //                 mode: 'class',
                //                 method:'POST',
                //                 data:postData,
                //             }).then(function(response){
                //                 if (response.data===true){
                //                     $(instance.reference).removeClass('EMPTY').addClass('free');
                //                     instance.hide();
                //                     instance.setContent((reference) =>
                //                     {
                //                         var string=`<div class="tw-timeitem__functions" data-time="${$(reference).data('time')}">` +
                //                             '<div class="tw-function__item setTraining">' +
                //                             'Добавить тренировку' +
                //                             '</div>';
                //
                //                         if (!$(reference).hasClass('free')){
                //                             string+='<div class="tw-function__item setSlot">' +
                //                                 'Добавить в мое расписание' +
                //                                 '</div>';
                //                         }
                //
                //                         string+='</div>';
                //                         return string;
                //                     });
                //                 }
                //             }, function(response){
                //                 console.log(response)
                //             });
                //         })
                //     },
                // });

                // //Редакторировать слоты
                // var chooseSlot = function(e) {
                //     if (!$(this).hasClass('free')){
                //         $(this).addClass("free");
                //     }
                //     else{
                //         $(this).removeClass("free");
                //     }
                // };
                //
                // $(".change-slots.tw-timetable-change-btn").click(function(){
                //     if ($(this).hasClass('active')){
                //         $('.tw-timetable__section-timeitem.EMPTY.free').removeClass('free');
                //         $('.tw-timetable__section-timeitem.EMPTY').unbind('click', chooseSlot);
                //
                //         $(this).text("Изменить слоты");
                //         $('.save-slots.tw-timetable-change-btn').hide();
                //         $(this).removeClass('active');
                //         $('.tw-timetable').find('.radio-item').show(200)
                //         $('.tw-timetable__section-timeitem').show();
                //         $('.tw-timetable__section').show();
                //
                //         timeitem_instance.forEach(function(el){
                //             el.enable();
                //         })
                //         $('.tw-timetable-change-text').hide(200);
                //         $('input[name="slots"]').filter('[value=ALL]').trigger('click')
                //     }
                //     else{
                //         $('.trainings-list-btn').removeClass('active');
                //         $('.tw-timetable__list-item').hide();
                //         $('.tw-timetable-controllers').hide(200);
                //         $('.trainings-list').hide(200);
                //
                //         $(this).text("Назад");
                //         $(this).addClass('active');
                //         $('.save-slots.tw-timetable-change-btn').show();
                //         $('.tw-timetable').find('.radio-item').hide(200)
                //
                //         $('.tw-timetable__section-timeitem').hide();
                //         $('.tw-timetable__section-timeitem').not('.free').not('.busy').show();
                //         $('.tw-timetable__section').show();
                //
                //         $('.tw-timetable__section-times-container').each(function(index, el){
                //             if ($(el).find(":visible").length===0){
                //                 $(el).closest('.tw-timetable__section').hide();
                //             }
                //         });
                //         timeitem_instance.forEach(function(el){
                //             el.disable();
                //         });
                //         $('.tw-timetable__section-timeitem.EMPTY').bind('click', chooseSlot);
                //         $('.tw-timetable-change-text').show(200);
                //     }
                //     scrollToTimetable();
                // })
            },
            function(response){
                $('.LK_COACHTRAININGS').find('.loading-overlay').removeClass('active');
                var error_id=0;
                response.errors.forEach(function(err, index){
                    if (err.code!==0){
                        error_id=index
                        return false;
                    }
                });
                var message=response.errors[error_id].message;
                var code=response.errors[error_id].code;

                $(errorModal.content).find('.error-modal__message').text(message);
                errorModal.show();
            }
        );
    }
    getTimetable();


    $('.tw-dates__days-container').find('.day-item').click(function(){
        $('.tw-dates__days-container').find('.day-item.active').removeClass('active');
        $(this).addClass('active');

        getTimetable();
    });

    function scrollToTimetable(){
        $([document.documentElement, document.body]).animate({
            scrollTop: $(".tw-scrollTo").offset().top-110
        }, 500);
    }

    function setSlots(date, slots, action, tooltip=null, clientName=null){
        $('.LK_COACHTRAININGS').find('.loading-overlay').addClass('active');

        var postData={
            'date':date,
            'action':action,
            'slots':slots
        }
        if (clientName!==null){
            postData["client"]=clientName;
        }
        BX.ajax.runComponentAction(tw_timetable_component, 'setSlots', {
            mode: 'class',
            method:'POST',
            data:postData,
        }).then(function(response){
            // console.log(response)
            $('.LK_COACHTRAININGS').find('.loading-overlay').removeClass('active');

            if (tooltip!==null){
                tooltip.hide();
            }
            getTimetable();
            // if (action==="free"){
            //     $('.tw-timetable__section-timeitem.choose').addClass('free');
            // }
            // else if(action==="busy"){
            //     $('.tw-timetable__section-timeitem.choose').addClass('busy');
            //     highlightWorkout();
            // }
            //
            // $('.tw-timetable__section-timeitem.choose').removeClass('choose').removeClass("EMPTY");
        },function(response){
            // console.log(response)
            $('.LK_COACHTRAININGS').find('.loading-overlay').removeClass('active');

            if (tooltip!==null){
                tooltip.hide();
            }

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $(errorModal.content).find('.error-modal__message').text(message);
            errorModal.show();
        })
    }
});