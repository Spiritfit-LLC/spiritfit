$(document).ready(function(){
//    AUTOSCROLL
    $('html, body').animate({scrollTop: 0},500);


    //анимация
    // $('.personal-onpage').animate({
    //     'left':`-120px`
    // }, 500, "swing")

//    НАСТРЙОКИ ДЛЯ ДАТЫ
    $('[data-toggle="datepicker"]').each(function(){
        $(this).inputmask({
            'mask': '99.99.9999',
        });

        var minDate=$(this).data('min');
        var maxDate=$(this).data('max');

        var minAge=$(this).data('minage');
        var maxAge=$(this).data('maxage');

        if (typeof minAge !== typeof undefined && maxAge !== false) {
            var today = new Date();
            maxDate = today.setFullYear(today.getFullYear()-minAge);
            minDate=today.setFullYear(today.getFullYear()-maxAge);
        }

        $(this).datepicker({
            language:'ru-RU',
            format: 'dd.mm.yyyy',
            autoHide:true,
            startDate: minDate,
            endDate: maxDate,
        })
    });

    //МАСКА ДЛЯ ТЕЛЕФОНА
    [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
        $(input).inputmask({
            'mask': '+7 (999) 999-99-99',
        });
    });





    //ПОКАЗ РАЗДЕЛОВ
    $('.personal-section').hide(300).removeClass('active');
    $(`.personal-section[data-id="${$('.personal-profile__tab-item.active').data('id')}"`).show(300).addClass('active');
    function showSection(){
        var data_id=$(this).data('id');
        if (data_id===$('.personal-section.active').data('id')){
            return;
        }
        $('.personal-profile__tab-item').removeClass('active');
        $(this).addClass('active');

        $('.personal-section').hide(300).removeClass('active');
        $(`.personal-section[data-id="${data_id}"`).show(300).addClass('active');

        if (window.outerWidth<=768){
            $('.show-all-section-icon').removeClass('active');
            $('.personal-profile__tab-item:not(.active)').hide(300);
        }
    }

    $('.personal-profile__tab-item').click(showSection);


    //ПОКАЗАТЬ/СКРЫТЬ ПАРОЛЬ
    $('.show-password-icon').click(function (){
        if ($(this).find('div.active').hasClass('pass-view')){
            $(this).parent().find('input[type="password"]').attr('type', 'text');
            $(this).find('div.pass-view').removeClass('active');
            $(this).find('div.pass-hide').addClass('active');
        }
        else{
            $(this).parent().find('input[type="text"]').attr('type', 'password');
            $(this).find('div.pass-hide').removeClass('active');
            $(this).find('div.pass-view').addClass('active');
        }
    });

    //ВЫХОД
    $('.profile-exit-btn').unbind();
    $('.profile-exit-btn').click(function(){
        var url=ajax;
        $.ajax({
            url:url,
            method:'POST',
            data: {
                'ACTION': 'EXIT'
            },
            cache: false,
            success:function(res){
                if (res['result']===true){
                    window.location.reload();
                }
            }
        })
    });

    //CHECKBOX
    $('.checkbox-input').unbind();
    $('.checkbox-input').click(function(){
        if($(this).is(':checked')){
            $(this).val(1);
        }
        else{
            $(this).val(0);
        }
    })

    //Послать форму стандартная
    $('.personal-section-form').unbind();
    $('.personal-section-form').on('submit', function (e){
        e.preventDefault();

        var url=$(this).attr('action');

        var disabled = $(this).find(':input:disabled').removeAttr('disabled');
        var postData=$(this).serializeArray();
        disabled.attr('disabled','disabled');

        var form=$(this);
        form.find('.form-submit-result-text').html('').removeClass('active');

        form.find('input[type="submit"]').attr('disabled','disabled');

        function createCodeEl(form, action){
            if ($(form).find('.reg_code').length<=0){
                var elStr='<div style="display: none; margin-top: 20px;" class="reg_code-block">' +
                    '<span class="personal-section-form__item-placeholder">Подтверждение</span>' +
                    '<input class="personal-section-form__item-value reg_code" name="reg_code" required style="border-bottom: 1px solid #fc612042; background: transparent ">' +
                    '<div class="resend-icon">' +
                    '<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="60px" height="60px"><path d="M 15 3 C 12.031398 3 9.3028202 4.0834384 7.2070312 5.875 A 1.0001 1.0001 0 1 0 8.5058594 7.3945312 C 10.25407 5.9000929 12.516602 5 15 5 C 20.19656 5 24.450989 8.9379267 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.437925 7.8516588 21.277839 3 15 3 z M 4 10 L 0 16 L 3.0507812 16 C 3.562075 22.148341 8.7221607 27 15 27 C 17.968602 27 20.69718 25.916562 22.792969 24.125 A 1.0001 1.0001 0 1 0 21.494141 22.605469 C 19.74593 24.099907 17.483398 25 15 25 C 9.80344 25 5.5490109 21.062074 5.0488281 16 L 8 16 L 4 10 z"/></svg>' +
                    '</div>' +
                    '</div>';
                $(form).find('input[type="tel"]').after(elStr);
                $(form).find('input[type="tel"]').next().show(300);

                //МАСКА ДЛЯ КОДА
                $('input.reg_code').inputmask(
                    {
                        'mask': '9 9 9 9 9',
                    }
                );

                $('.resend-icon').unbind()
                $('.resend-icon').click(function(){
                    $(form).find('input[name="ACTION"]').val(action);
                    $(form).submit();
                })
            }

        }

        $.ajax({
            url:url,
            method:'POST',
            data:postData,
            cache:false,
            success:function (res){
                console.log(res);
                form.find('input[type="submit"]').removeAttr('disabled');
                var ACTION=form.find('input[name="ACTION"]').val();
                if (res['result']===true){
                    if (ACTION==='UPDATE_USER'){
                        form.find('.form-submit-result-text').html('Ваши данные обновлены').addClass('active');
                        if (res['type']==='is_correct'){
                            window.location.reload();
                        }

                        form.find('input[data-code]').each(function(){
                            $('.personal-profile__user-head-items').find(`[data-code="${$(this).data('code')}"]`).text($(this).val());
                        })
                    }
                    else if(ACTION==='UPDATE_1C'){
                        var data=res['data'];
                        for (const [key, value] of Object.entries(data)) {
                            form.find(`input[data-code=${key}]`).val(value);
                        }
                        form.find('.form-submit-result-text').html('Ваши данные обновлены').addClass('active');
                    }
                    else if(ACTION==='LOGIN_1'){
                        var user_type=res['type'];
                        if (user_type==='site'){
                            form.find('.auth-password').show(300)
                            form.find('.auth-password input').prop('required', true)
                            form.find('input[name="ACTION"]').val(ACTION.replace('1', '3'))
                        }
                        else if (user_type==='1c'){
                            form.find('input[name="ACTION"]').val(ACTION.replace('1', '2'));
                            form.find('input:not([type="submit"]):not(.reg_code)').prop( "disabled", true );
                            form.find('input[type="submit"]').val('подтвердить');

                            createCodeEl(form, ACTION);
                        }
                        else if (user_type==='site2'){
                            form.find('input[name="ACTION"]').val('FORGOT_2');
                            form.find('input:not([type="submit"]):not(.reg_code)').prop( "disabled", true );
                            form.find('input[type="submit"]').val('подтвердить');

                            createCodeEl(form, ACTION);
                        }
                    }
                    else if(ACTION==='LOGIN_3' || ACTION==='LOGIN_2'){
                        window.location.reload();
                    }
                    else if (ACTION==='REG_1'){
                        form.find('input[name="ACTION"]').val(ACTION.replace('1', '2'));
                        form.find('input:not([type="submit"]):not(.reg_code)').prop( "disabled", true );
                        form.find('input[type="submit"]').val('подтвердить');

                        createCodeEl(form, ACTION);
                    }
                    else if (ACTION==='FORGOT_1'){
                        user_type=res['type'];
                        if (user_type==='site'){
                            form.find('input[name="ACTION"]').val('FORGOT_2');
                            form.find('input:not([type="submit"]):not(.reg_code)').prop( "disabled", true );
                            form.find('input[type="submit"]').val('подтвердить');

                            createCodeEl(form, ACTION);
                        }
                        else if (user_type==='1c'){
                            form.find('input[name="ACTION"]').val('LOGIN_2');
                            form.find('input:not([type="submit"]):not(.reg_code)').prop( "disabled", true );
                            form.find('input[type="submit"]').val('подтвердить');

                            createCodeEl(form, ACTION);
                        }
                    }
                    else if (ACTION==='REG_2' || ACTION==='FORGOT_2'){
                        window.location.href=personal_page_url;
                    }
                }
                else{
                    form.find('.form-submit-result-text').html(res['message']).addClass('active');
                    if (res['errorCode']===7){
                        form.find('input[name="ACTION"]').val(ACTION.replace('2', '1'));
                        form.find('input[type="submit"]').val('отправить');
                        form.find('.reg_code-block').hide(300);
                        form.find('.reg_code-block').remove();
                        disabled.removeAttr('disabled');
                    }
                }
            },
            error:function(err){
                console.log(err);
            }
        })
    })

    //Моб Версия. Показать все разделы
    $('.show-all-section-icon').unbind()
    $('.show-all-section-icon').click(function(){
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $('.personal-profile__tab-item:not(.active)').hide(300);
        }
        else{
            $(this).addClass('active');
            $('.personal-profile__tab-item').show(300);
        }
    })


    //Изменить фото
    $('.personal-profile__user-refresh-photo svg').click(function(){
        function changeProfilePhoto(e){
            var target=e.target;
            var url=$(target.form).attr('action');
            var postData=$(target.form).serializeArray();

            var form_data = new FormData();
            var file = target.files[0];
            form_data.append('new-photo-file', file);
            for(var i=0; i<postData.length; i++){
                form_data.append(postData[i].name, postData[i].value);
            }
            $('.personal-profile__user-result').html('обработка...')
            $.ajax({
                url:url,
                method:'POST',
                data:form_data,
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success:function(res){
                    console.log(res);
                    var success=res['result'];
                    var message=res['message'];
                    if (success){
                        window.location.reload();
                    }
                    else{
                        $('.personal-profile__user-result').html(message)
                    }
                }
            });

        }
        $('.personal-profile__user-refresh-photo-file-input').on('change', changeProfilePhoto);
        $('.personal-profile__user-refresh-photo-file-input').trigger('click');

    })


    //Подсказки
    tippy.createSingleton(tippy('.clue-icon', {
        content: (reference) =>
        {
            return $(reference).find('.clue-text').text();
        },
        theme: 'light',
    }), {
        allowHTML: true,
        delay: 500, // ms
        placement: 'top',
        arrow: false,
        animation: 'fade',
        theme: 'material',
        interactive: true,
    })

    //Временное решение
    tippy('.spisat-btn', {
        content: 'Списание бонусов возможно спустя месяц после регистрации.',
        theme: 'light',
        trigger: 'click',
    });
    tippy('.podarok-btn', {
        content: 'Можно подарить бонусы другу, мы отправим вам промокод на скидку = сумме бонусов, по промокоду можно купить любой абонемент. Бонусы нельзя подарить действующему члену клуба.',
        theme: 'light',
        trigger: 'click',
        arrow: false,
    })
    //Временное решение

});