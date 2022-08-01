$(document).ready(function(){
    if ($('.personal-section[data-code="trialworkout_zapis"]').length>0){
        var tw_form=$('.personal-section[data-code="trialworkout_zapis"]').children('form');
    }
    else if($('.personal-section[data-code="change_tw"]').length>0){
        tw_form=$('.personal-section[data-code="change_tw"]').children('form');
    }

    $('<input type="hidden" name="TYPE" value="API">').prependTo(tw_form);

    var message_modal=null;
    var table_content=null;

    tw_form.unbind();
    tw_form.submit(function(e){
        e.preventDefault();

        var postData=$(this).serialize();
        var form=$(this);
        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });
        $('a[href="#cancel_tw"]').closest('.personal-section-form__item').fadeOut();

        $.ajax({
            url: '/local/ajax/personal.tw.php',
            type: 'POST',
            data: postData,
            success: function(data) {
                form.find('.escapingBallG-animation').removeClass('active');
                form.find('input[type="submit"]').css({
                    'opacity':1,
                });
                $('a[href="#cancel_tw"]').closest('.personal-section-form__item').fadeIn();

                if (message_modal instanceof ModalWindow){
                    message_modal.destroy();
                }
                if (table_content!==null){
                    table_content.remove();
                }

                table_content=$('<div class="tw_timetable" style="display: none"></div>').insertAfter(tw_form);
                table_content.html(data)
                var type=table_content.find('.LK_TRIALWORKOUT').data('type');

                if (type==='modal'){
                    message_modal=new ModalWindow($('.tw_timetable').find('.LK_TRIALWORKOUT').data('header'), table_content.get(0), AnimationsTypes['fadeIn'], false, true);
                    message_modal.show();

                    $('a[href="#choose_my_time"]').click(function(e){
                        e.preventDefault();



                        var formdata=new FormData($('form.tw_form').get(0));
                        var postData2={
                            "clubid":formdata.get('clubid'),
                            "date":formdata.get('date'),
                            "timetable":$('form.tw_form').data("additional-timetable"),
                            "TYPE":"TIMETABLE",
                            "tw_action":formdata.get("tw_action")
                        };

                        $.ajax({
                            url: '/local/ajax/personal.tw.php',
                            type: 'POST',
                            data: postData2,
                            success:function(response){
                                if (table_content!==null){
                                    table_content.remove();
                                }
                                table_content=$('<div class="tw_timetable" style="display: none"></div>').insertAfter(tw_form);
                                table_content.html(response)

                                message_modal.close();
                                message_modal.destroy();

                                table_content.show();
                            },
                            error: function(response) {
                                alert('Возникла ошибка');
                            },
                        });
                    })
                }
                else if (type==='form'){

                }
                table_content.show();
            },
            error: function(data) {
                form.find('.escapingBallG-animation').removeClass('active');
                form.find('input[type="submit"]').css({
                    'opacity':1,
                });
                alert('Возникла ошибка');
            },
        });
    });


    $('a[href="#cancel_tw"]').closest('.personal-section-form__item').insertAfter(tw_form.find('input[type="submit"]'));

    $('a[href="#cancel_tw"]').click(function(e){
        e.preventDefault();

        var accept = confirm("Внимание, тренировка будет отменена. Обратить действие и записаться на новую тренировку будет невозможно!");
        if (!accept){
            return;
        }

        var postData={
            "tw_action":"cancel"
        }

        var form=tw_form;
        form.find('.form-submit-result-text').html('').removeClass('active');

        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        BX.ajax.runComponentAction("custom:personal.trialworkout", "setSlot", {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            console.log(response)
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });
            var res_data=response['data'];
            if (res_data['reload']===true){
                window.location.reload();
                return;
            }

        }, function(response){
            console.log(response)
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            form.find('.form-submit-result-text').html(message).addClass('active');
        });
    });

    $('a[href="#tw_accept"]').click(function(e){
        e.preventDefault();

        var postData={
            "tw_action":"accept"
        }

        var form=tw_form;
        form.find('.form-submit-result-text').html('').removeClass('active');

        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        BX.ajax.runComponentAction("custom:personal.trialworkout", "setSlot", {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            console.log(response)
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });
            var res_data=response['data'];
            if (res_data['reload']===true){
                window.location.reload();
                return;
            }

        }, function(response){
            console.log(response)
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            form.find('.form-submit-result-text').html(message).addClass('active');
        });
    });
})