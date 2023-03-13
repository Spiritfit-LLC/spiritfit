$(document).ready(function(){
    //МАСКА ДЛЯ ТЕЛЕФОНА
    $("#auth-container").find('[type="tel"]').each(function(){
        $(this).inputmask({
            'mask': '(999) 999-99-99',
            placeholder: "_",
        });
    });

    var $container=$("#auth-container");
    var $modal=$(".popup-modal__personal");

    $modal.find(".closer").click(function (){
        $container.fadeOut(300);
    });

    $(`[href="${enter_btn}"]`).click(function(e){
        e.preventDefault();

        $modal.removeClass("active");
        $("#AUTH_MODAL")
            .css("z-index", 11)
            .addClass("active")
            .removeClass("scale")
            .fadeIn(300);

        $container
            .fadeIn(300)
            .css("display", "flex");
    });

    $(".get-form-btn").click(function(e){
        e.preventDefault();

        var $current_modal=$(this).closest(".popup-modal__personal");
        var next_modal_id=$(this).attr("href");
        var $next_modal=$(next_modal_id);

        $current_modal.fadeOut(300, function(){
            $(this).removeClass("active");
        })
            .css("z-index", 10)
            .addClass("scale");

        $next_modal
            .css("z-index", 11)
            .addClass("scale")
            .addClass("active")
            .fadeIn(1, function(){
                $(this).removeClass("scale")
            });
    });

    //Поле с СМС
    function createCodeEl(form, step){
        if ($(form).find('.personal_form__input.reg_code').length<=0){
            var elStr='<div class="personal_form__input reg_code" style="display: none">'+
                    '<div class="personal_form__input-placeholder">Введите код</div>'+
                    '<div class="personal_form__input-container">'+
                        '<input class="personal_form__input-value reg_code" type="text" name="reg_code" required>'+
                    '</div>'+
                    '<a class="gradient-text resend" href="#resend">Запросить код повторно</a>'
                '</div>';

            var $phone_input=$(form).find('input[type="tel"]').closest(".personal_form__input");

            $phone_input.after(elStr);
            $phone_input.next().show(300);

            //МАСКА ДЛЯ КОДА
            $('input.reg_code').inputmask(
                {
                    mask: '9 9 9 9 9',
                    placeholder: "✱",
                }
            );

            $('a[href="#resend"]').unbind()
            $('a[href="#resend"]').click(function(){
                $(form).find('input[name="FORM_STEP"]').val(step);
                $(form).submit();
            })
        }
    }

    //Отправка формы
    $(".popup-personal-form").submit(function(e){
        e.preventDefault();

        //Включаем, забираем, выключаем
        var disabled = $(this).find(':input:disabled');
        disabled.removeAttr('disabled');
        var postData=new FormData(this);
        disabled.attr('disabled','disabled');

        //Скрываем ошибки
        $('.field-error').fadeOut(300);

        //Скрываем текст ответа
        var $form=$(this);
        $form.find('.form-submit-result-text')
            .html('')
            .removeClass('active');

        //Выключаем кнопку
        $form.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $form.find('.escapingBallG-animation').addClass('active');
        $form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        BX.ajax.runComponentAction(personal_component, $form.data("action"), {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            //Выключаем колабашечку и включаем кнопку
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            $form.find('input[type="submit"]').removeAttr('disabled');

            var result=response.data;

            if (result.result===false){
                $('.field-error').remove();
                result.errors.forEach(function(el){
                    $form.find(`#${el.form_name}`)
                        .closest(".personal_form__input-container")
                        .after(`<span class="field-error" style="display: none">${el.message}</span>`);
                });

                $('.field-error').fadeIn(300);
                return;
            }

            if (result.dataLayer!==undefined){
                try{
                    dataLayerSend('UX', result.dataLayer.eAction, result.dataLayer.eLabel)
                }
                catch (e) {
                    console.log(e);
                }
            }

            if (result.message!==undefined){
                $form.find('.form-submit-result-text')
                    .html(result.message)
                    .addClass('active');
            }
            if (result.next_step!==undefined){
                $form.find('input[name="FORM_STEP"]').val(result.next_step);
            }
            if (result.next_action!==undefined){
                $form.data('action', result.next_action);
            }
            if (result.reg_code===true){
                createCodeEl($form, 1);
                $form.find('input[type="submit"]').val('Подтвердить');
            }
            if (result.field_messages !== undefined){
                $('.field-message').remove();
                result.field_messages.forEach(function(el){
                    $form.find(`#${el.form_name}`)
                        .closest(".personal_form__input-container")
                        .after(`<span class="field-message" style="display: none">${el.message}</span>`);
                });

                $('.field-message').fadeIn(300);
            }

            if ($form.data('action')==='login' && result.next_step === 3){
                $form.find('.personal_form__input.password-item').show(300)
                $form.find('.personal_form__input.password-item').prop('required', true)
            }

            if (result.href!==undefined){
                window.location.href = result.href;
            }

        }, function(response){
            //Выключаем колабашечку и включаем кнопку
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            $form.find('input[type="submit"]').removeAttr('disabled');

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;
            $form.find('.form-submit-result-text')
                .html(message)
                .addClass('active');

            if (code===0){
                $form.find('input[name="FORM_STEP"]').val(1);
                $form.find('input[type="submit"]').val('Войти');
                $form.find('.personal_form__input.reg_code')
                    .hide(300, function(){
                        $(this).remove();
                    });
                disabled.removeAttr('disabled');
            }
        })

    })
});

var pass_show=function(t, input){
    $(`#${input}`).attr('type', 'text');
    $(t).removeClass("active");

    $(t)
        .next(".pass-hide")
        .addClass("active");

}

var pass_hide=function(t, input){
    $(`#${input}`).attr('type', 'password');
    $(t).removeClass("active");

    $(t)
        .prev(".pass-show")
        .addClass("active");
}
