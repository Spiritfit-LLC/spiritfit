$(document).ready(function(){

    //МАСКА ДЛЯ ТЕЛЕФОНА
    [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
        $(input).inputmask({
            'mask': '+7 (999) 999-99-99',
        });
    });

    //МАСКА ДЛЯ КОДА
    $('input.reg_code').inputmask(
        {
            'mask': '9 9 9 9 9',
        }
    );
    // var codeInput=$('.personal-reg-form-code-block').find('input[name="reg_code"]');
    // $(codeInput).inputmask({
    //     'mask': '9 9 9 9 9',
    // });


    $('.go-auth').click(function(){
        var parent=$(this).parent()
        var X_COORD=parent.height();
        parent.animate({
            'left':`-=${X_COORD}px`
        }, 300, function(){
            $('.personal-auth.-modal').addClass('active')
        })
    });

    $('.show-profile').click(function(){
        var parent=$(this).parent()
        var X_COORD=parent.height();
        parent.animate({
            'left':`-=${X_COORD}px`
        }, 300, function(){
            $('.personal-profile.-modal').addClass('active')
        })
    })


    //ЧИТАЕМ ПАРАМЕТРЫ ЗАПРОСА
    var params = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    if (Object.keys(params).includes('profile')){
        //console.log(params['profile'], params['profile']==='Y')
        if (params['profile']==='Y'){
            $('.show-profile').trigger('click');
        }
    }

    $('.-modal-closer').click(function(){
        $('.-modal').removeClass('active');
        $('.error-message-text').html('');

        var X_COORD=$('.personal-onpage').height();
        $('.personal-onpage').animate({
            'left':`+=${X_COORD}px`
        })
    });

    $('span.auth-link').click(function(){
        $('.-modal').removeClass('active');

        $(`.${$(this).data('action')}`).addClass('active');
    })

    function setError(form, errorText){
        $(form).find('.error-message-text').html(errorText);
        $(form).find('.error-message-text').closest('.-modal').css({
            'height':`+=${$(form).find('.error-message-text').height()}`
        })
    }

    function clearError(form){
        $(form).find('.error-message-text').html('');
        $(form).find('.error-message-text').closest('.-modal').removeAttr('style');
    }

    //Запрос СМС
    function sendSMS(form){
        var url=$(form).attr('action');
        var postData=$(form).serializeArray();

        clearError(form);

        $(form).find('input').prop( "readonly", true );
        $(form).find('option').prop( "readonly", true );

        //console.log(postData);
        $.ajax({
            url:url,
            method:'POST',
            data:postData,
            success:function(res){
                //console.log(res)
                var success=res['result'];
                var message=res['message'];
                var errorCode=res['errorCode'];
                if (success){
                    $(form).find('input[name="STEP"]').val(2);
                    $(form).find('input').prop( "readonly", true );
                    $(form).find('option').prop( "readonly", true );

                    $(form).find('.code-block').find('input').prop("readonly", false);
                    $(form).find('input[type="submit"]').prop("readonly", false).val("Подтвердить");

                    $(form).find('.code-block').addClass('active');
                    $(form).find('.reg_code').prop('readonly', false);
                    $(form).find('.reg_code').val('')
                }
                else{
                    $(form).find('input[name="STEP"]').val(1);

                    setError(form,message);

                    $(form).find('input').prop( "readonly", false );
                    $(form).find('option').prop( "readonly", false );
                }
            },
            error:function(){
                setError(form, 'Не удалось связаться с сервером.');

                $(form).find('input').prop( "readonly", false );
                $(form).find('option').prop( "readonly", false );
            }
        })
    }
    //Проверка СМС
    function checkSMS(form){
        var url=$(form).attr('action');
        var postData=$(form).serializeArray();

        clearError(form);

        $(form).find('input').prop( "readonly", true );
        $(form).find('option').prop( "readonly", true );

        $.ajax({
            url:url,
            method:'POST',
            data:postData,
            success:function(res){
                //console.log(res);

                var success=res['result'];
                var message=res['message'];
                var errorCode=res['errorCode'];

                if (success){
                    window.location.href='/personal/';
                }
                else{
                    setError(form, message);

                    $(form).find('.reg_code').val('')
                    if (errorCode===7){
                        $(form).find('input[name="STEP"]').val(1);
                        $(form).find('input').prop( "readonly", false );
                        $(form).find('option').prop( "readonly", false );
                        $(form).find('input[type="submit"]').prop("readonly", false).val("отправить");
                        $(form).find('.code-block').removeClass('active');
                    }
                    else{
                        $(form).find('.reg_code').prop('readonly', false);
                        $(form).find('input[type="submit"]').prop("readonly", false);
                    }
                }
            },
            error:function(){
                setError(form, 'Не удалось связаться с сервером.');

                $(form).find('input').prop( "readonly", false );
                $(form).find('option').prop( "readonly", false );
            }
        })
    }


    //АВТОРИЗАЦИЯ
    $('form.personal-auth-form').on('submit', function (e){
        e.preventDefault();
        clearError(this);

        var url=$(this).attr('action');
        var postData=$(this).serializeArray();
        // //console.log(postData);

        $.ajax({
            url:url,
            method:'POST',
            data:postData,
            success: function(res){
                if (res['result']!==true){
                    setError($('.personal-auth-form'), res['message']);
                }
                else{
                    window.location.reload()
                }
            },
            error:function(){
                setError($('.personal-auth-form'), 'Не удалось связаться с сервером.');
            }
        })

        return false;
    });

    //РЕГИСТРАЦИЯ
    $('form.personal-reg-form').on('submit', function(e){
        e.preventDefault();

        $(this).find('input').prop( "readonly", false );
        $(this).find('option').prop( "readonly", false );

        if (parseInt($(this).find('input[name="STEP"]').val())===1){
            sendSMS(this);

            $(this).find('a.refreshcode').unbind();
            $(this).find('a.refreshcode').click(function(){
                $($('form.personal-reg-form')).find('input[name="STEP"]').val(1);
                sendSMS($('form.personal-reg-form'));
            });
        }
        else{
            checkSMS(this);
        }
        return false;
    });

    //Восстановление пароля
    $('form.personal-getpass-form').on('submit', function(e){
        e.preventDefault();

        $(this).find('input').prop( "readonly", false );
        $(this).find('option').prop( "readonly", false );

        if (parseInt($(this).find('input[name="STEP"]').val())===1){
            sendSMS(this);

            $(this).find('a.refreshcode').unbind();
            $(this).find('a.refreshcode').click(function(){
                $($('form.personal-getpass-form')).find('input[name="STEP"]').val(1);
                sendSMS($('form.personal-getpass-form'));
            });
        }
        else{
            checkSMS(this);
        }
        return false;
    })

    //ВЫХОД из профиля или обновление профиля
    $('form.personal-profile-form').on('submit', function(e){
        e.preventDefault();

        var url=$(this).attr('action');
        var postData=$(this).serializeArray();
        $.ajax({
            url:url,
            method:'POST',
            data:postData,
            success:function(res){
                var success=res['result'];
                var message=res['message'];
                if (success){
                    const url = new URL(document.location);
                    const searchParams = url.searchParams;
                    searchParams.delete("change"); // удалить параметр
                    window.history.pushState({}, '', url.toString());
                    window.location.reload()
                }
                else{
                    setError($('.personal-profile-form'), message);
                }
            },
            error:function(){
                setError($('.personal-profile-form'), 'Не удалось связаться с сервером');
            }
        });
        return false;
    });

    //Зависимость заполнения поля
    function requiredforminput(e){
        var input=$(e.target);
        var fromInput=$(`input[data-required_from="${input.data('required_id')}"]`);

        if (input.data('required_from') !==undefined){
            if (input.val().length===0 && fromInput.val().length===0){
                input.closest('form').find('input[type="submit"]').prop('disabled', false);
            }
            else if (input.val().length>0 && fromInput.val().length>0){
                input.closest('form').find('input[type="submit"]').prop('disabled', false);
            }
            else{
                input.closest('form').find('input[type="submit"]').prop('disabled', true);
            }
        }
    }
    $('input[data-required_from]').on('input', requiredforminput);
    $('input[data-required_from]').on("focus", requiredforminput);
    $('input[data-required_from]').on("blur", requiredforminput);
    $('input[data-required_from]').on("keydown", requiredforminput)



    //Обновление инфы из 1С
    $('.update-head-items').click(function(e){
        e.preventDefault();

        if ($(this).attr('disabled')!=="disabled"){
            $(this).text('Обработка...');
            $(this).attr('disabled', "disabled");

            var postData= {
                'ACTION': 'UPDATE_1C',
            };
            var url=$(this).closest('form').attr('action');

            $.ajax({
                url:url,
                data:postData,
                method:'POST',
                success:function(res){
                    for (const [key, value] of Object.entries(res['data'])) {
                        $(`[data-code=${key}]`).text(value);
                    }
                    $('.update-head-items').text('Обновить')
                }
            })
        }
    });


    //
    //
    // function closeChangePhotoModal(){
    //     $('.modal-overlay').removeClass('active');
    //     $('.personal-change-profile-photo').removeClass('active');
    // }
    // $('.personal-change-profile-photo-closer').click(closeChangePhotoModal);
    // $('.modal-overlay').click(closeChangePhotoModal);
    //
    //
    // $('#choose-new-profile-photo').click(function(){
    //     $('.personal-change-profile-photo-file-input').trigger('click');
    // })
    // $('#new-profile-photo-filename').click(function(){
    //     $('.personal-change-profile-photo-file-input').trigger('click');
    // })

    // $('.personal-change-profile-photo-choose').on('submit', function(){
    //     e.preventDefault();
    //
    //     $(this).find('input').prop( "readonly", false );
    //     $(this).find('option').prop( "readonly", false );
    //
    //     var url=$(this).attr('action');
    //     var postData=$(this).serializeArray();
    // });
    //
    // $('.personal-change-profile-photo-file-input').change(function(e){
    //     var file = e.target.files[0];
    //     $('#new-profile-photo-filename').val(file.name);
    //
    //     $('.personal-change-profile-photo-preloader').addClass('active');
    //
    //     var form_data = new FormData();
    //     form_data.append('personal-photo', file);
    //     form_data.append('action', 'new_profile_photo');
    //     form_data.append('old-photo-id', $('input[name="old-photo-id"]').val());
    //     form_data.append('user-id', $('input[name="user-id"]').val());
    //
    //     var url=$(this).data('action');
    //     //console.log(url);
    //     $.ajax({
    //         'url':url,
    //         'data':form_data,
    //         'processData': false,
    //         'contentType': false,
    //         'enctype': 'multipart/form-data',
    //         'method':'POST',
    //         'type':'POST',
    //         success:function(responce){
    //             var res=JSON.parse(responce)
    //             if (res['result']!==true){
    //                 // //console.log(res);
    //                 $('.personal-change-profile-photo .error-message-text').html(res['message']);
    //                 $('.personal-change-profile-photo-preloader').removeClass('active');
    //             }
    //             else{
    //                 if (!Object.keys(params).includes('profile') && !Object.keys(params).includes('change')) {
    //                     var url = new URL(window.location.href);
    //                     url.searchParams.append('profile', 'Y');
    //                     window.location.href=url.href;
    //                 }
    //                 else{
    //                     window.location.reload();
    //                 }
    //             }
    //         }
    //     });
    // });

})