$(document).ready(function(){
    $('.form-abonement__container select').select2({
        width:'100%',
        dropdownParent: $('.form-abonement__container')
    });

    //МАСКА ДЛЯ ТЕЛЕФОНА
    [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
        $(input).inputmask({
            'mask': '+7 (999) 999-99-99',
        });
    });

    var form=$(".form-abonement__container .get-abonement__popup");

    function createCodeEl(f){
        if ($(f).find('.smscode-input').length<=0){
            var elStr='<div style="display: none; margin-top: 20px;" class="form-field-item smscode-block">' +
                '<label class="form-field-item__label">Код из СМС</label>'+
                '<input class="form-field-item__input smscode-input" type="text" name="smscode" required>' +
                '<div class="resend-icon">' +
                '<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="60px" height="60px"><path d="M 15 3 C 12.031398 3 9.3028202 4.0834384 7.2070312 5.875 A 1.0001 1.0001 0 1 0 8.5058594 7.3945312 C 10.25407 5.9000929 12.516602 5 15 5 C 20.19656 5 24.450989 8.9379267 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.437925 7.8516588 21.277839 3 15 3 z M 4 10 L 0 16 L 3.0507812 16 C 3.562075 22.148341 8.7221607 27 15 27 C 17.968602 27 20.69718 25.916562 22.792969 24.125 A 1.0001 1.0001 0 1 0 21.494141 22.605469 C 19.74593 24.099907 17.483398 25 15 25 C 9.80344 25 5.5490109 21.062074 5.0488281 16 L 8 16 L 4 10 z"/></svg>' +
                '</div>' +
                '</div>';
            $(elStr).insertBefore(f.find('input[type="submit"]').closest('.form-field-item'))

            $('.smscode-block').show(300);
            // console.log(elStr,$('.smscode-block'))


            //МАСКА ДЛЯ КОДА
            $('input.smscode-input').inputmask(
                {
                    mask: '9 9 9 9 9',
                    placeholder: "*",
                }
            );
        }
        else{
            $(f).find('.smscode-input').removeAttr('disabled')
        }
    }

    form.unbind()
    form.submit(function(e){
        e.preventDefault();

        var disabled = $(this).find(':disabled').removeAttr('disabled');
        var postData=new FormData(this);
        postData.set("path", $(this).data('path'));
        disabled.attr('disabled','disabled');

        form.find('.form-submit-result-text').html('').removeClass('active');

        //Блокируем кнопку
        form.find('input[type="submit"]').attr('disabled','disabled');

        form.find('input').attr('disabled','disabled');
        form.find('select').attr('disabled','disabled');

        //Старт Анимация
        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        BX.ajax.runComponentAction(form.data('componentname'), form.data('action'), {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            // console.log(response)

            var res_data=response['data'];

            if (res_data['btn']!==undefined){
                form.find('input[type="submit"]').val(res_data['btn']);
            }

            if (res_data['elements']!==undefined){
                for (const [key, value] of Object.entries(res_data['elements'])) {
                    if (value===null){
                        form.find(key).hide(300, function() {
                            $(this).remove();
                        });
                        continue;
                    }
                    else if(value==='hide'){
                        form.find(key).hide(300);
                        continue;
                    }
                    else if(value==='disable'){
                        form.find(key).attr('disabled','disabled');
                        continue;
                    }
                    document.querySelector(key).outerHTML=value;
                }
            }

            if(res_data['sms_code']===true){
                createCodeEl(form);
                var getTrialAction=form.data('action')
                $('.smscode-block .resend-icon').unbind()
                $('.smscode-block .resend-icon').on(clickHandler, function(){
                    form.data('action', getTrialAction)
                    form.submit();
                });
            }
            if (res_data['next-action']!==undefined){
                form.data('action', res_data['next-action']);
            }

            //Разблокируем кнопку
            form.find('input[type="submit"]').removeAttr('disabled')

            //Конец Анимация
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });

        }, function(response){
            // console.log(response)

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
            if (code===7){
                form.find('input[type="submit"]').val('отправить');
                form.find('.smscode-block').hide(300);
                form.find('.smscode-block').remove();
                disabled.removeAttr('disabled');
            }
            else if (code===8){
                form.find('.smscode-input').removeAttr('disabled')
            }

            //Разблокируем кнопку
            form.find('input[type="submit"]').removeAttr('disabled')

            //Конец Анимация
            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });
        });
    })
})