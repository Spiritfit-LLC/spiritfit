var offset=6;
var current_visit_el_index=0;
var HighLightColors=[
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

let d = new Date();
let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d);
let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
var choose_date=`${ye}.${mo}.${da}`;

var choose_tooltip=null;
var choose_clue_tooltip = null;


function scroll_days_container(index){
    var parent=$('.tw-dates__days-container');
    var element=parent.find(`.day-item[data-index="${index}"]`);
    $(parent).stop().animate({scrollLeft:element.offset().left + parent.scrollLeft() - parent.offset().left}, 500);


    var month=parent.find(`.day-item[data-index="${index}"]`).data('month');
    if (month!==parent.find(`.day-item[data-index="${index+offset}"]`).data('month') && parent.find(`.day-item[data-index="${index+offset}"]`).length>0){
        month+='-'+parent.find(`.day-item[data-index="${index+offset}"]`).data('month');
    }
    $('.tw-dates__date-month').text(month)
}

function highlight_workout(){
    var tw_timetable_times=$('.tw-timetable__section-times-container');
    tw_timetable_times.each(function(i, el){
        var position=1;
        if ($(window).width()>768){
            var lenght=4;
        }
        else{
            lenght=4;
        }
        $(el).find('.tw-timetable__section-timeitem').each(function(j, el2){
            $(el2).data('row-position', position);
            if (position===lenght){
                position=1;
                return;
            }
            position++;
        })
    });

    var loop_workouts=[];
    tw_timetable_times.each(function(index, times_section){
        $(times_section).find(".tw-timetable__section-timeitem.busy").each(function(i, busy) {
            if (!loop_workouts.includes($(busy).data("workout-id"))) {
                $(busy).addClass("highlighted");
                loop_workouts.push($(busy).data("workout-id"));
            }
        });
    });

    loop_workouts.forEach(function(workout_id, index){
        $(`.busy[data-workout-id="${workout_id}"`).css({
            outline: `6px outset ${HighLightColors[index]}`,
            "outline-offset":"-4px",
        })
            .not(".highlighted").each(function(){
            $(this).hide();
        });
    });

}

var date_controller=function(direction){
    if (direction==='left'){
        var ind=current_visit_el_index-offset;
        if (ind<0){
            ind=0;
        }
    }
    else if (direction==='right'){
        ind=current_visit_el_index+offset;
        if (ind>$('.tw-dates__days-container').find('.day-item').length-offset){
            ind=$('.tw-dates__days-container').find('.day-item').length-offset;
        }
    }
    scroll_days_container(current_visit_el_index=ind);
}

var get_timetable=function(date){
    var overlay=$('.LK_COACHTRAININGS').find('.loading-overlay');
    overlay.addClass("active");

    BX.ajax.runComponentAction(tw_timetable_component, 'getSlots', {
        mode: 'class',
        method:'POST',
        data:{
            date:date
        },
    }).then(function(response){
        // console.log(response);
        overlay.removeClass("active");
        $('.tw-days-timetable__container').html(response.data);

        prepare_busy_list();
        highlight_workout();
    }, function(response){
        // console.log(response);
        overlay.removeClass("active");

        response.errors.forEach(function(err, index){
            if (err.code!==0){
                error_id=index
                return false;
            }
        });
        var message=response.errors[error_id].message;
        var code=response.errors[error_id].code;

        $(".tw-request-error__container")
            .css("display", "flex")
            .fadeIn(300)
            .html(message);
    });
}

var set_active_date=function(el, date){
    $(".day-item").removeClass("active");
    $(el).addClass("active");

    choose_date=date;
}

var choose_slots=function(el){
    var choose_start=$(".tw-timetable__section-timeitem.start");
    if (choose_start.length===0){
        $(el)
            .addClass("choose")
            .addClass("start")
            .addClass("end");
        choose_start=$(el);

        if (choose_start.hasClass("free")){
            choose_start.addClass("only-free");
        }

        choose_clue_tooltip = tippy(choose_start.get(0), {
            content:'<div class="tooltip-clue">Вы выбрали начала диапазона. Выберите конец диапазона.</div>',
            allowHTML: true,
            delay: 100, // ms
            arrow: true,
            animation: 'fade',
            interactive: false,
            trigger: 'manual',
            placement: 'top'
        });
        choose_clue_tooltip.show();
    }
    else{
        $(".tw-timetable__section-timeitem").not(".busy").unbind();
        var start_index=choose_start.data("index");
        var end_index=$(".tw-timetable__section-timeitem.end").data("index");

        if (start_index>end_index){
            $(".tw-timetable__section-timeitem.end").removeClass("end").addClass("start");
            choose_start.removeClass("start").addClass("end");
        }
        var tooltip_options={
            content: (reference) => {
                var obj='<div class="workout-choose-tooltip">';
                if (!$(reference).hasClass("only-free"))
                    obj+='<div class="workout-choose-btn" onclick="choose_action(\'free\')">Добавить в расписание</div>';
                else
                    obj+='<div class="workout-choose-btn" onclick="choose_action(\'delete\')">Удалить из расписания</div>';

                if (!$(reference).hasClass("has-busy"))
                    obj+='<div class="workout-choose-btn" onclick="choose_action(\'busy-show\')">Добавить тренировку</div>';

                obj+='<div class="workout-choose-btn" onclick="choose_action(\'close\')">Отменить выбор</div>' +
                    '</div>';
                return obj;
            },
            theme: 'coach.training',
            allowHTML: true,
            delay: 100, // ms
            arrow: false,
            animation: 'fade',
            interactive: true,
            trigger: 'manual',
            placement: $(window).width()>768?'right':'top'
        }
        choose_tooltip=tippy($(".tw-timetable__section-timeitem.choose").last().get(0), tooltip_options);
        choose_tooltip.show();

        choose_clue_tooltip.hide();
        choose_clue_tooltip.destroy();
        return;
    }

    $(".tw-timetable__section-timeitem").not(".busy").unbind();
    $(".tw-timetable__section-timeitem")
        .not(".busy")
        .mouseenter(function(){
            $(".tw-timetable__section-timeitem")
                .removeClass("has-busy")
                .removeClass("only-free")
                .removeClass("choose")
                .removeClass("end");

            $(this).addClass("choose").addClass("end");

            var start_index=choose_start.data("index");
            var end_index=$(this).data("index");

            if (start_index>end_index){
                var buff=end_index;
                end_index=start_index;
                start_index=buff;
            }

            var only_free=choose_start.hasClass("free");
            for (var i=start_index; i<=end_index; i++){
                var current=$(`.tw-timetable__section-timeitem[data-index="${i}"]`);
                if (current.hasClass("busy")){
                    $(this).addClass("has-busy");
                    choose_start.addClass("has-busy");
                    continue;
                }

                if (!current.hasClass("free")){
                    only_free=false;
                }
                current.addClass("choose");

                $(`.tw-timetable__section-timeitem[data-index="${i}"]`).not(".busy").addClass("choose");
            }

            if (only_free){
                $(this).addClass("only-free");
                choose_start.addClass("only-free");
            }
        });
}

var choose_action=function(action){
    if (action==="close"){
        $(".tw-timetable__section-timeitem")
            .removeClass("start")
            .removeClass("end")
            .removeClass("choose");

    }
    else if (action==="free" || action==="delete"){
        var choose_start=$(".tw-timetable__section-timeitem.start");
        var choose_end=$(".tw-timetable__section-timeitem.end");

        var overlay=$('.LK_COACHTRAININGS').find('.loading-overlay');
        overlay.addClass("active");

        BX.ajax.runComponentAction(tw_timetable_component, 'setSlots', {
            mode: 'class',
            method:'POST',
            data:{
                "date":choose_date,
                "type":action,
                "start":choose_start.data("time"),
                "finish":choose_end.data("time")
            },
        }).then(function(response){
            // console.log(response);
            overlay.removeClass("active");

            $('.tw-days-timetable__container').html(response.data);

            prepare_busy_list();
            highlight_workout();
        }, function(response){
            // console.log(response);
            overlay.removeClass("active");

            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $(".tw-request-error__container")
                .css("display", "flex")
                .fadeIn(300)
                .html(message);

            get_timetable(choose_date)
        });
    }
    else if (action==="busy-show"){
        $("#tw-add__client-name").val('');
        $(".tw-add-workout__container").fadeIn(300).css("display", "flex");
    }
    choose_tooltip.destroy();
}

var add_workout=function(form){
    $(".field-error").remove();

    var choose_start=$(".tw-timetable__section-timeitem.start");
    var choose_end=$(".tw-timetable__section-timeitem.end");

    var client_name=$("#tw-add__client-name");
    if (client_name.val()===undefined || client_name.val().length<2){
        client_name.after(`<span class="field-error" style="display: none">Проверьте корректность введенных данных</span>`);
        $(".field-error").fadeIn(300);
        return;
    }

    var overlay=$('.LK_COACHTRAININGS').find('.loading-overlay');
    overlay.addClass("active");

    BX.ajax.runComponentAction(tw_timetable_component, 'setSlots', {
        mode: 'class',
        method:'POST',
        data:{
            "date":choose_date,
            "type":"busy",
            "start":choose_start.data("time"),
            "finish":choose_end.data("time"),
            "clientName":$("#tw-add__client-name").val()
        },
    }).then(function(response){
        // console.log(response);
        overlay.removeClass("active");

        $('.tw-days-timetable__container').html(response.data);

        prepare_busy_list();
        highlight_workout();

        $(".tw-add-workout__container").fadeOut(300);
    }, function(response){
        // console.log(response);
        overlay.removeClass("active");

        $(".tw-add-workout__container").fadeOut(300);

        response.errors.forEach(function(err, index){
            if (err.code!==0){
                error_id=index
                return false;
            }
        });
        var message=response.errors[error_id].message;
        var code=response.errors[error_id].code;

        $(".tw-request-error__container")
            .css("display", "flex")
            .fadeIn(300)
            .html(message);

        get_timetable(choose_date)
    });
}

var show_busy=function(el){
    moment.locale('ru');
    $("#tw-busy-workout__title").text($(el).data("workout-type"));

    var start_time=$(`.tw-timetable__section-timeitem[data-workout-id="${$(el).data("workout-id")}"]`)
        .first()
        .data("time");

    var finish_time=$(`.tw-timetable__section-timeitem[data-workout-id="${$(el).data("workout-id")}"]`)
        .last()
        .data("time");
    finish_time=moment(finish_time, "HH:mm").add(1, 'hours').format('HH:mm');
    $("#tw-busy-time").text(`${start_time} - ${finish_time} (${moment(finish_time, "HH:mm").diff(moment(start_time, "HH:mm"), 'minutes')} минут) `);
    var date=moment(choose_date, "YYYY.MM.DD").format("dddd, MMMM DD");
    $("#tw-busy-date").text(date);
    if ($(el).data("client")===''){
        $("#tw-busy-client").closest(".tw-busy-info__field").hide();
    }
    else{
        $("#tw-busy-client").text($(el).data("client"));
        $("#tw-busy-client").closest(".tw-busy-info__field").show();
    }

    $(".tw-cancel").unbind();
    if (Boolean($(el).data("changeble"))){
        $(".tw-cancel").show();
        $(".tw-cancel").click(function(){
            cancel_workout($(el).data("workout-id"))
        });
    }
    else{
        $(".tw-cancel").hide();
    }

    $(".tw-busy__container").fadeIn(300).css("display", "flex");


}
var cancel_workout=function(workout_id){
    var answer=confirm("Вы действительно хотите удалить тренировку?");
    if (!answer){
        return;
    }
    var overlay=$('.LK_COACHTRAININGS').find('.loading-overlay');
    overlay.addClass("active");

    BX.ajax.runComponentAction(tw_timetable_component, 'cancelSlot', {
        mode: 'class',
        method:'POST',
        data:{
            "date":choose_date,
            "workout_id":workout_id
        },
    }).then(function(response){
        // console.log(response);
        overlay.removeClass("active");

        $('.tw-days-timetable__container').html(response.data);
        prepare_busy_list();
        highlight_workout();
        $(".tw-busy__container").fadeOut(300);
    }, function(response){
        // console.log(response);
        overlay.removeClass("active");
        $(".tw-busy__container").fadeOut(300);

        response.errors.forEach(function(err, index){
            if (err.code!==0){
                error_id=index
                return false;
            }
        });
        var message=response.errors[error_id].message;
        var code=response.errors[error_id].code;

        $(".tw-request-error__container")
            .css("display", "flex")
            .fadeIn(300)
            .html(message);

        get_timetable(choose_date)
    });
}

$(document).ready(function (){
    setTimeout(()=>{
        scroll_days_container(current_visit_el_index);
    }, 500);

    get_timetable(choose_date);
});

// var show_slots=function(type, element){
//     $(".tw-view-controller").hide();
//     $(".tw-timetable__list").hide();
//     $(".tw-timetable__slots").show();
//
//     if (type==="all"){
//         $(".tw-timetable__section-timeitem").not(".busy").show();
//         $(".tw-timetable__section-timeitem.highlighted").show();
//     }
//     else if (type==="free"){
//         $(".tw-timetable__section-timeitem").hide();
//         $(".tw-timetable__section-timeitem.free").show();
//     }
//     else if (type==="busy"){
//         $(".tw-timetable__section-timeitem").hide();
//         $(".tw-timetable__section-timeitem.highlighted").show();
//
//         $(".tw-view-controller").show().css("display", "flex");
//         $(".tw-view-control").removeClass("active");
//         $(".tw-view-control.table").addClass("active");
//     }
//
//
//     $(".tw-timetable__section").show();
//     $(".tw-timetable__section-times-container")
//         .each(function(index, container){
//             if ($(container).find(":visible").length===0){
//                 $(container).closest(".tw-timetable__section").hide();
//             }
//     });
//
//     $(".tw-slots-control").removeClass("active");
//     $(element).addClass("active");
// }

// var set_view=function(type, element){
//     if (type==="table"){
//         $(".tw-timetable__list").hide();
//         $(".tw-timetable__slots").show();
//     }
//     else if (type==="list"){
//         $(".tw-timetable__list").show();
//         $(".tw-timetable__slots").hide();
//     }
//
//     $(".tw-view-control").removeClass("active");
//     $(element).addClass("active");
// }

var prepare_busy_list=function(){
    $(".tw-busy-list__item").each(function(){
        moment.locale('ru');
        var section_item=$(this);

        var start_time=$(`.tw-timetable__section-timeitem[data-workout-id="${$(section_item).data("workout-id")}"]`)
            .first()
            .data("time");

        var finish_time=$(`.tw-timetable__section-timeitem[data-workout-id="${$(section_item).data("workout-id")}"]`)
            .last()
            .data("time");

        finish_time=moment(finish_time, "HH:mm").add(1, 'hours').format('HH:mm');
        section_item.find(".tw-busy-list-field__val.time").text(`${start_time} - ${finish_time} (${moment(finish_time, "HH:mm").diff(moment(start_time, "HH:mm"), 'minutes')} минут)`)
    });
}