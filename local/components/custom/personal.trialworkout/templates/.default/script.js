$(document).ready(function (){


    function timePicker(FORM){
        $(FORM).unbind();
        $(FORM).submit(function(e){
            e.preventDefault();

            var postData=new FormData(this);
            postData.append("timetable", $(this).data("additional-timetable"))

            var form=$(this);
            form.find('.form-submit-result-text').html('').removeClass('active');

            BX.ajax.runComponentAction(form.data("componentname"), postData.get('ACTION'), {
                mode: 'class',
                data: postData,
                method:'POST'
            }).then(function(response){
                console.log(response)

                var res_data=response['data'];
                if (res_data['reload']===true){
                    window.location.reload();
                    return;
                }

            },function(response){
                console.log(response);

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

        const DOMElement = document.querySelector('.timepicker-ui');
        const options = {
            clockType:'24h',
            incrementMinutes:15,
            disabledTime:{
                "hours":[
                    0,1,2,3,4,5,6
                ],
                "minutes":[5,10,20,25,35,40,50,55]
            },
            cancelLabel:"Отмена",
            okLabel:"Выбрать",
            timeLabel:"Выберите время",
            switchToMinutesAfterSelectHour:true,
            appendModalSelector:'form.tw_form',
            theme:'crane-straight'
        };
        const myTimePicker = new window.tui.TimepickerUI(DOMElement, options);
        myTimePicker.create();
        myTimePicker.open();

        DOMElement.addEventListener('accept', function(event){
            $('form.tw_form').submit();
            myTimePicker.destroy();
        }, {once:true});
        DOMElement.addEventListener('cancel', function(){
            myTimePicker.destroy();
        }, {once:true});
    }

    if (pageType==="DEFAULT"){
        $('select.hour-select').unbind();
        $("select.minute-select").unbind();

        Object.keys(tw_timetable).sort().forEach(function(key, index) {
            if (index===0){
                $("select.hour-select").append(`<option value="${key}">${key}</option>`);
                Object.keys(tw_timetable[key]).sort().forEach(function(key2, index2) {
                    if (index2===0){
                        $("select.minute-select").append(`<option value="${key2}">${key2}</option>`);
                        Object.values(tw_timetable[key][key2]).forEach(function(value, index3){
                            $('select.name-select').append(`<option value=${value.id}>${value.name}</option>`)
                        });
                    }
                    else{
                        $("select.minute-select").append(`<option value="${key2}">${key2}</option>`);
                    }
                });
            }
            else{
                $("select.hour-select").append(`<option value="${key}">${key}</option>`);
            }
        });

        $('select').select2({
            minimumResultsForSearch: Infinity,
            width:'100%',
            dropdownParent: $('form.tw_form')
        });

        $('select.hour-select').change(function(){
            $("select.minute-select option").remove();
            $('select.name-select option').remove();
            var key1=$(this).val();
            Object.keys(tw_timetable[key1]).sort().forEach(function(key2, index2) {
                if (index2===0){
                    $("select.minute-select").append(`<option value="${key2}">${key2}</option>`);
                    Object.values(tw_timetable[key1][key2]).forEach(function(value, index3){
                        $('select.name-select').append(`<option value=${value.id}>${value.name}</option>`)
                    });
                }
                else{
                    $("select.minute-select").append(`<option value="${key2}">${key2}</option>`);
                }
            });
        });
        $("select.minute-select").change(function(){
            $('select.name-select option').remove();
            var key1=$("select.hour-select").val();
            var key2=$(this).val();
            Object.values(tw_timetable[key1][key2]).forEach(function(value, index3){
                $('select.name-select').append(`<option value=${value.id}>${value.name}</option>`)
            });
        });

        $('form.tw_form').unbind()
        $('form.tw_form').submit(function(e){
            e.preventDefault();

            var tw_time=$('.hour-select').find(':selected').val()+':'+$('.minute-select').find(':selected').val();
            var coach=$(`.name-select`).find(':selected').val();

            var disabled = $(this).find(':input:disabled').removeAttr('disabled');

            var formdata=new FormData(this);
            var postData={
                "tw_time":tw_time,
                "tw_coach":coach,
                "clubid":formdata.get('clubid'),
                "date":formdata.get('date'),
                "tw_action":formdata.get("tw_action")
            };
            disabled.attr('disabled','disabled');

            $('.field-error').fadeOut(300);

            var form=$(this);
            form.find('.form-submit-result-text').html('').removeClass('active');

            form.find('input[type="submit"]').attr('disabled','disabled');

            form.find('.escapingBallG-animation').addClass('active');
            form.find('input[type="submit"]').css({
                'opacity':0,
                'z-index':1
            });

            BX.ajax.runComponentAction(form.data("componentname"), formdata.get('ACTION'), {
                mode: 'class',
                data: postData,
                method:'POST'
            }).then(function(response){
                console.log(response)

                form.find('.escapingBallG-animation').removeClass('active');
                form.find('input[type="submit"]').css({
                    'opacity':1,
                });

                form.find('input[type="submit"]').removeAttr('disabled');
                var res_data=response['data'];

                if (res_data.result===false){
                    $('.field-error').remove();
                    res_data.errors.forEach(function(el){
                        form.find(`input[name="${el.form_name}"]`).after(`<span class="field-error" style="display: none">${el.message}</span>`);
                    });

                    $('.field-error').fadeIn(300);
                    return;
                }

                if (res_data['reload']===true){
                    if (res_data.section!==undefined){
                        window.location.href=window.location.href+'?SECTION='+res_data.section;
                    }
                    else{
                        window.location.reload();
                    }
                    return;
                }

            },function(response){
                console.log(response);

                form.find('.escapingBallG-animation').removeClass('active');
                form.find('input[type="submit"]').css({
                    'opacity':1,
                });

                form.find('input[type="submit"]').removeAttr('disabled');
                var error_id=0;
                response.errors.forEach(function(err, index){
                    if (err.code!==0){
                        error_id=index
                        return false;
                    }
                });
                var message=response.errors[error_id].message;
                var code=response.errors[error_id].code;
                form.find('.form-submit-result-text').html(message).addClass('active');
            });
        });
    }
    else if(pageType==="CHOOSETIME"){
        timePicker('form.tw_form');
    }
});