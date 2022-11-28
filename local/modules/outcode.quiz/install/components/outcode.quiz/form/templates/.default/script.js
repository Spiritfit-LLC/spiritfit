jQuery(function($) {
    $(document).ready(function() {
        $('.show-more').unbind();
        $('.show-more').click(function(e) {
            e.preventDefault();
            let wrapper = $(this).parents('.results-table');
            $(wrapper).find('.results-table__row').removeClass('hidden');
            $(this).hide();
        });
        $('.show-more').each(function() {
            let wrapper = $(this).parents('.results-table');
            if( $(wrapper).find('.results-table__row.hidden').length == 0 ) $(this).hide();
        });

        $('.question-form').unbind()
        $('.question-form').submit(function(e) {
            e.preventDefault();

            var form = new FormData( $(this)[0] );
            var formObj = $(this);

            $(formObj).find('.question-form__error').text('');

            BX.ajax.runComponentAction(quizComponentName, 'sendAnswer', {
                mode: 'class',
                data: form,
                method:'POST'
            }).then(function (response) {
                if( response.data.error != '' ) {
                    $(formObj).find('.question-form__error').text(response.data.error);
                } else {
                    if( response.data.result.ID == 0 ) {
                        $(formObj).html('<div class="success">'+quizComponentExistMsg+'</div>');
                    } else {
                        $(formObj).html('<div class="success">'+quizComponentSuccessMsg+'</div>');
                    }

                    BX.ajax.runComponentAction("custom:personal", 'quiz', {
                        mode: 'class',
                        data: {
                            'type':22
                        },
                        method:'POST'
                    });
                }
            }, function (response) {
                $(formObj).find('.question-form__error').text(response.errors[0].message);
            });

            return false;
        });


        //МАСКА ДЛЯ ТЕЛЕФОНА
        [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
            $(input).inputmask({
                'mask': '+7 (999) 999-99-99',
            });
        });

        $("#auth-lite-form, #reg-lite-form").unbind();
        $("#auth-lite-form, #reg-lite-form").submit(function(e){
            e.preventDefault();

            var disabled = $(this).find(':input:disabled');
            disabled.removeAttr('disabled');
            var postData=new FormData(this);
            disabled.attr('disabled','disabled');

            $('.field-error').fadeOut(300);

            function createCodeEl(form, step){
                if ($(form).find('.reg_code').length<=0){
                    var elStr='<div style="display: none; margin-top: 20px; position: relative" class="reg_code-block lk-auth-lite__form-item">' +
                        '<span class="lk-auth-lite__form-item-placeholder">Введите код</span>' +
                        '<input class="lk-auth-lite__form-item-value reg_code" name="reg_code" required type="text">' +
                        '<div class="resend-icon">' +
                        '<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="60px" height="60px"><path d="M 15 3 C 12.031398 3 9.3028202 4.0834384 7.2070312 5.875 A 1.0001 1.0001 0 1 0 8.5058594 7.3945312 C 10.25407 5.9000929 12.516602 5 15 5 C 20.19656 5 24.450989 8.9379267 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.437925 7.8516588 21.277839 3 15 3 z M 4 10 L 0 16 L 3.0507812 16 C 3.562075 22.148341 8.7221607 27 15 27 C 17.968602 27 20.69718 25.916562 22.792969 24.125 A 1.0001 1.0001 0 1 0 21.494141 22.605469 C 19.74593 24.099907 17.483398 25 15 25 C 9.80344 25 5.5490109 21.062074 5.0488281 16 L 8 16 L 4 10 z"/></svg>' +
                        '</div>' +
                        '</div>';
                    $(form).find('input[type="tel"]').closest(".lk-auth-lite__form-item").after(elStr);
                    $(form).find('input[type="tel"]').closest(".lk-auth-lite__form-item").next().show(300);

                    //МАСКА ДЛЯ КОДА
                    $('input.reg_code').inputmask(
                        {
                            mask: '9 9 9 9 9',
                            placeholder: "*",
                        }
                    );

                    $('.resend-icon').unbind()
                    $('.resend-icon').click(function(){
                        $(form).find('input[name="FORM_STEP"]').val(step);
                        $(form).submit();
                    })
                }
            }

            var form=$(this);
            form.find('.form-submit-result-text').html('').removeClass('active');

            form.find('input[type="submit"]').attr('disabled','disabled');
            form.find('.escapingBallG-animation').addClass('active');
            form.find('input[type="submit"]').css({
                'opacity':0,
                'z-index':1
            });
            BX.ajax.runComponentAction("custom:personal", postData.get('ACTION'), {
                mode: 'class',
                data: postData,
                method:'POST'
            }).then(function(response){

                form.find('.escapingBallG-animation').removeClass('active');
                form.find('input[type="submit"]').css({
                    'opacity':1,
                });

                form.find('input[type="submit"]').removeAttr('disabled');
                var res_data=response['data'];

                if (res_data['message']!==undefined){
                    form.find('.form-submit-result-text').html(res_data['message']).addClass('active');
                }
                if (res_data['next_step']!==undefined){
                    $(form).find('input[name="FORM_STEP"]').val(res_data['next_step']);
                }
                if (res_data['next_action']!==undefined){
                    $(form).find('input[name="ACTION"]').val(res_data['next_action']);
                }
                if (res_data['reg-code']===true){
                    createCodeEl(form, 1);
                }

                if (res_data.field_messages !== undefined){
                    $('.field-message').remove();
                    res_data.field_messages.forEach(function(el){
                        $(`<span class="field-message" style="display: none">${el.message}</span>`).insertAfter(form.find(`input[name="${el.field_name}"]`));
                    });

                    $('.field-message').fadeIn(300);
                }

                if (postData.get('ACTION')==='login' && res_data['next_step']===3){
                    form.find('.auth-password').show(300)
                    form.find('.auth-password input').prop('required', true)
                }

                if (res_data['reload']===true){
                    setTimeout(function(){
                        window.location = window.location.pathname;
                    }, 500);
                }


            }, function(response){


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
                if (code===7){
                    form.find('input[name="FORM_STEP"]').val(postData.get('FORM_STEP').replace('2', '1'));
                    form.find('input[type="submit"]').val('отправить');
                    form.find('.reg_code-block').hide(300);
                    form.find('.reg_code-block').remove();
                    disabled.removeAttr('disabled');
                }
            })
        })


        $(".lk-auth-lite__type-item").click(function(){
            if ($(this).hasClass("active")){
                return;
            }
            $(".lk-auth-lite__type-item.active").removeClass("active");
            $(".lk-auth-lite__form.active").removeClass("active");
            $(this).addClass("active");

            if ($(this).hasClass("reg")){
                $(".lk-auth-lite__form.reg").addClass("active");
            }
            else{
                $(".lk-auth-lite__form.auth").addClass("active");
            }
        })
    });
});