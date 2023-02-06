$(document).ready(function (){
    var tw_timetable_component=$('.LK_TRIALWORKOUT').data('componentname');
    var offset=6;
    var current_visit_el_index=0;


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

    $('.tw-dates__controller').on(clickHandler, function(){
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




    function getTimetable(){
        $('.LK_TRIALWORKOUT').find('.loading-overlay').addClass('active');
        var postData={
            'club':$('select[name="club_num"]').val(),
            'date':$('.tw-dates__days-container').find('.day-item.active').data('date'),
            'tw_type':$('select[name="tw_type"]').val(),
        }
        if ($('input[name="tw_action"]').length>0){
            postData['action']=$('input[name="tw_action"]').val()
        }
        BX.ajax.runComponentAction(tw_timetable_component, 'getTimetable', {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(
            function(response){
                // console.log(response)
                $('.LK_TRIALWORKOUT').find('.loading-overlay').removeClass('active');
                $('.tw-days-timetable__container').html(response.data);
                $('.tw-timetable__section-timeitem').click(function(){
                    var $this=$(this);

                    // BX.ajax.runComponentAction(tw_timetable_component, 'getTrainers', {
                    //     mode: 'class',
                    //     data: {
                    //         'time':$this.text()
                    //     },
                    //     method:'POST',
                    // }).then(function(response){
                    //     // console.log(response)
                    //     if (response.data.type==="FREE"){
                    //         $('select[name="coach"]').closest('.personal-section-form__item').show();
                    //         $('select[name="coach"]').html(response.data.result).select2({
                    //             minimumResultsForSearch: Infinity,
                    //             width:'100%',
                    //             dropdownParent: $('.tw-timetable__controllers')
                    //         });
                    //     }
                    //     else if (response.data.type==="NOTFREE"){
                    //         // $('select[name="coach"]').html('').closest('.personal-section-form__item').hide();
                    //         $('select[name="coach"]').closest('.personal-section-form__item').show();
                    //
                    //         $('select[name="coach"]').html(response.data.result).select2({
                    //             minimumResultsForSearch: Infinity,
                    //             width:'100%',
                    //             dropdownParent: $('.tw-timetable__controllers')
                    //         });
                    //     }
                    //
                    // })

                    $(".tw-timetable").slideUp( "slow", function() {
                        $('.tw-timetable__choosen').text('Время: '+$this.text()).data('time', $this.text());
                        $('.tw-timetable__controllers').show(300);
                        $('.tw-timetable__show').click(function(){
                            $('.tw-timetable__controllers').hide(300, function(){
                                $(".tw-timetable").slideDown('slow');
                            });
                        });
                    });
                })

                // $('input[name="slots"]').click(function(){
                //     var radio_val=$(this).val();
                //     if (radio_val==="FREE"){
                //         $('.tw-timetable__section-timeitem.not-free').hide();
                //         $('.tw-timetable__section-times-container').each(function(index, el){
                //             if ($(el).find(":visible").length===0){
                //                 $(el).closest('.tw-timetable__section').hide();
                //             }
                //         });
                //     }
                //     else if (radio_val==="ALL"){
                //         $('.tw-timetable__section-timeitem.not-free').show();
                //         $('.tw-timetable__section').show();
                //     }
                // });

                //Подсказки
                // tippy.createSingleton(tippy('.tw-timetable__section-timeitem.not-free', {
                //     content: 'К сожалению, сейчас в это время нет свободного тренера, однако мы постараемся вам его найти!',
                //     theme: 'light',
                // }), {
                //     allowHTML: true,
                //     delay: 500, // ms
                //     placement: 'top',
                //     arrow: false,
                //     animation: 'fade',
                //     theme: 'material',
                //     interactive: true,
                // });

                $('input.trialworkout[type="submit"]').click(function(){
                    var postData={
                        'club':$('select[name="club_num"]').val(),
                        'date':$('.tw-dates__days-container').find('.day-item.active').data('date'),
                        'time':$('.tw-timetable__choosen').data('time'),
                        'tw_type':$('select[name="tw_type"]').val(),
                    }
                    // if ($('select[name="coach"]').is(":visible")){
                    //     postData['coach']=$('select[name="coach"]').val();
                    // }
                    if ($('input[name="tw_action"]').length>0){
                        postData['action']=$('input[name="tw_action"]').val();
                    }
                    BX.ajax.runComponentAction(tw_timetable_component, 'setSlot', {
                        mode: 'class',
                        data: postData,
                        method:'POST',
                    }).then(function(response){
                        var res_data=response.data;
                        if (res_data['dataLayer']!==undefined){
                            var category='UX';
                            if (res_data['dataLayer'].eCategory!==undefined){
                                category=res_data['dataLayer'].eCategory;
                            }
                            try{
                                dataLayerSend(category, res_data['dataLayer'].eAction, res_data['dataLayer'].eLabel)
                            }
                            catch (e) {
                                console.log(e);
                            }
                        }

                        if (res_data['reload']===true){
                            if (res_data.section!==undefined){
                                window.location.search='?SECTION='+res_data.section;
                            }
                            else{
                                window.location = window.location.pathname;
                            }
                            return;
                        }

                    })
                });
            });
    }
    getTimetable();

    $('select[name="club_num"]').on('select2:select', getTimetable);
    $('select[name="tw_type"]').on('select2:select', getTimetable);

    $('.tw-dates__days-container').find('.day-item').click(function(){
        $('.tw-dates__days-container').find('.day-item.active').removeClass('active');
        $(this).addClass('active');

        getTimetable();
    });

});