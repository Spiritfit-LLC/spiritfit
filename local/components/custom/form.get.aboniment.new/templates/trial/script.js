$(document).ready(function(){

    var strSend=clubName+"/"+strAbonement;

    function o(){
        $(window).innerWidth() < 1260 ? $(".subscription__aside").next().is($(".services-block")) || ($(".subscription__aside").insertAfter(".subscription__label-prices-block"), $(".subscription__ready").insertAfter(".subscription__title:eq(0)")) : $(".subscription__aside").next().is($(".services-block")) && ($(".subscription__aside").insertAfter(".subscription__main"), $(".subscription__ready").insertAfter(".subscription__common"))
    }


    $(".input--checkbox").styler();
    [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
        $(input).inputmask({
            'mask': '+7 (999) 999-99-99',
        });
    });


    dataLayerSend('UX', 'openMembershipRegPage', strSend);

    //МОДАЛЬНЫЕ ОКНА
    var message_modal_content=$('#message-modal').get(0);
    var message_modal=new ModalWindow('', message_modal_content, AnimationsTypes['fadeIn'], false, false);

    function ShowMessage(message, clickable=true){
        $('.message-modal__content').html(message)

        $('.overlay').unbind();
        if (clickable===true){
            $('.overlay').click(function(){
                message_modal.close();
            })
        }

        message_modal.show();
    }

    //Константы
    var form=$(".get-abonement")
    var clubSelector=form.find(".get-abonement-club");
    var sub_code=form.find('input[name="SUB_CODE"]').val()

    function CheckVisible(el){
        if (!$(el).is(':visible'))
        {
            $(el).show(300);
        }
    }
    function CheckFormBeforeSubmit(f){
        var success=true;
        $(f).find('input').each(function(){
            if($(this).prop('required')){
                if (!$(this).val()) {
                    success=false;
                    return;
                }
            }
        });
        return success;
    }

    //Обновление клуба
    function updateClub(e){
        var CLUB_ID=$(this).val()
        var postData={
            'CLUB_ID': CLUB_ID,
            'SUB_CODE': sub_code
        };

        BX.ajax.runComponentAction(componentName, 'getClub', {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function (response) {
            o();
            var result_data=response['data'];

            if (result_data['CLUB_NAME']!==undefined){
                strSend=result_data['CLUB_NAME']+"/"+strAbonement;
            }

            if (result_data['result']===false){
                return;
            }

            var services=result_data['SERVICES'];
            var prices=result_data['PRICE'];


            if (services!=null){
                $('.services-block').html(services);
                CheckVisible($('.services-block'));
            }
            else{
                $('.services-block').hide(300);
            }

            if (prices!=null){
                $('.subscription__label-prices-block').html(prices);
                CheckVisible($('.subscription__label-prices-block'));
            }
            else{
                $('.subscription__label-prices-block').hide(300);
            }

            if (result_data['BASE_PRICE']===null){
                $('.subscription__total-value-old').hide(300)
            }
            else{
                $('.subscription__total-value-old').show(300)
            }


            $('.old-price').text(result_data['BASE_PRICE'])
            $('.current_price').text(result_data['CURRENT_PRICE'])

            CheckVisible($('.subscribtion__bottom-block'));


        }, function (response) {
            //Сообщение об ошибке
            var message=response.errors[0].message;
            ShowMessage(message);
        });
    }
    $(clubSelector).change(updateClub)
    $(clubSelector).trigger('change');


    function SubmitForm(e){
        e.preventDefault();



        var form=$(this);
        var action=form.find('input[name="ACTION"]').val();

        if ($(form).find('[data-upmetric="setTypeClient"]').length > 0){
            var setClientData= {
                'phone':$(form).find('[data-upmetric="phone"]').val(),
                'email':$(form).find('[data-upmetric="email"]').val(),
                'setTypeClient':$(form).find('[data-upmetric="setTypeClient"]').val()
            };
            $(form).find('[data-upmetric="setTypeClient"]').remove();
            sendToUpMetrika(setClientData);
        }

        if (!CheckFormBeforeSubmit()){
            ShowMessage('Заполните обязательные поля');
            return false;
        }

        var disabled = $(this).find(':input:disabled').removeAttr('disabled');
        var postData=new FormData(this);
        disabled.attr('disabled','disabled');

        //Блокируем кнопку
        form.find('input[type="submit"]').attr('disabled','disabled');

        form.find('input.input').attr('disabled','disabled');
        form.find('select.input').attr('disabled','disabled');

        //Старт Анимация
        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        BX.ajax.runComponentAction(componentName, action, {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(responce){
            // console.log(responce)
            o();

            //Разблокируем кнопку
            form.find('input[type="submit"]').removeAttr('disabled')

            //Конец Анимация
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });

            var res_data=responce['data'];

            /////////////////СТАНДАРТ///////////////////
            if (res_data['next-action']!==undefined){
                form.find('input[name="ACTION"]').val(res_data['next-action']);
            }
            if (res_data['btn']!==undefined){
                $('input.get-abonement-agree').val(res_data['btn']);
            }
            if (res_data['url']!==undefined){
                setTimeout(function(){
                    window.open(res_data['url'], '_blank');
                }, 3000)
            }
            if (res_data['reload']===true){
                window.location = window.location.pathname;
            }
            if (res_data['form-step']!==undefined){
                form.find('input[name="STEP"]').val(res_data['form-step'])
            }
            if (res_data['elements']!==undefined){
                for (const [key, value] of Object.entries(res_data['elements'])) {
                    if (value===null){
                        $(key).remove();
                        continue;
                    }
                    document.querySelector(key).outerHTML=value;
                }
            }
            if (res_data['CURRENT_PRICE']!==undefined){
                $('.current_price').text(res_data['CURRENT_PRICE']);
            }
            if (res_data['step']!==undefined){
                $(`.subscription__stage-item[data-step="${res_data['step']}"]`).addClass('subscription__stage-item--done');
            }

            /////////////////СТАНДАРТ///////////////////


            if (action==='getTrial'){
                dataLayerSend('UX', 'openSmsCodePage', strSend);
                if( strAbonement == "Домашние тренировки" ) {
                    dataLayerSend('UX', 'sendContactFormHomeWorkout', strSend);
                } else {
                    dataLayerSend('conversion', 'sendContactForm', strSend);
                }

                form.find('.form-checkboxes').hide(300);

                if (res_data["href"]!==undefined){
                    window.location.href=res_data["href"];
                }
                else{
                    form.find('input#smscode-input').prop('required', 'required').removeAttr('disabled');

                    form.find('.subscription__code-new').show(300);

                    $('input#smscode-input').inputmask(
                        {
                            mask: '9 9 9 9 9',
                            placeholder: "*",
                        }
                    );

                    $('a[href="#resend"]').unbind();
                    $('a[href="#resend"]').click(function(e){
                        e.preventDefault();
                        form.find('input[name="ACTION"]').val(action);
                        form.submit();
                    });

                    $('input.get-abonement-agree').val('Подтвердить')
                }
            }
            else if(action==='checkCodeTrial'){
                if (res_data["href"]!==undefined){
                    window.location.href=res_data["href"];
                }
                else{
                    dataLayerSend('UX', 'openMembershipReadyPage', strSend);
                }
            }
        }, function(response){

            form.find('input[type="submit"]').removeAttr('disabled');
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
            var code=response.errors[error_id].code;

            ShowMessage(message);

            if (code===7 || code===20){
                form.find('input.input').removeAttr('disabled');
                form.find('select.input').removeAttr('disabled');
            }

            if (action==='checkCodeTrial'){
                if (code===7 || code===20){
                    $('a[href="#resend"]').unbind();
                    form.find('input[name="ACTION"]').val('getTrial');
                    form.find('.subscription__code-new').hide(300);
                    form.find('input#smscode-input').removeAttr('required').val('');
                    $('.subscription__promo').show(300);
                    form.find('.form-checkboxes').show(300);
                    $('input.get-abonement-agree').val('Купить');
                }
                if (code===6){
                    form.find('input#smscode-input').removeAttr('disabled').val('');
                }
            }

        });
    }

    form.unbind()
    form.on('submit', SubmitForm);
})