$(document).ready(function(){
    //ФОКУСИРОВКА НА ЭЛЕМЕНТЕ
    $('.form-request-new__input').on("focus", function(){
        $(this).closest(".form-request-new__field").addClass("is-focused");
    });

    $('.form-request-new__input').on("blur", function(){
        if ($(this).val().length===0){
            $(this).closest(".form-request-new__field").removeClass("is-focused");
            $(this).closest(".form-request-new__field").addClass("is-empty");
        }
    });

    $('.form-request-new__input').on("keyup", function(){
        if ($(this).val().length===0){
            $(this).closest(".form-request-new__field").addClass("is-empty");
            $(this).closest(".form-request-new__field").removeClass("is-not-empty");
        }
        else{
            $(this).closest(".form-request-new__field").removeClass("is-empty");
            $(this).closest(".form-request-new__field").addClass("is-not-empty");
        }
    });
    var $messageModal=$(".message-modal");

    function ShowMessage(message){
        $messageModal.find(".message-body").html(message);
        $messageModal.addClass("active").fadeIn(300).css({"position":"fixed", "display":"flex"});
    }

    $messageModal.click(function(e){
        if (e.target===this){
            $messageModal.removeClass("active").fadeOut(300);
            $messageModal.find(".message-body").html('');
        }
    })

    //Отправка данных
    $('.form-request-new').unbind();
    $('.form-request-new').submit(function(e){
        e.preventDefault();

        var submitFlag=true;

        $('.form-request-new__input').each(function(index, el){
            if ($(el).val().length===0 && $(el).prop('required')){
                $(el).closest(".form-request-new__field").removeClass("is-focused");
                $(el).closest(".form-request-new__field").addClass("is-empty");

                submitFlag=false;
            }
        })

        if (!submitFlag){
            return false;
        }

        var $form=$(this)
        var disabled = $form.find(':input:disabled');
        disabled.removeAttr('disabled');
        $form.find('select').removeAttr('disabled');

        var postData=new FormData(this);
        disabled.attr('disabled','disabled');

        //Блокируем кнопку
        $form.find('input[type="submit"]').attr('disabled','disabled');

        $form.find('input').attr('disabled','disabled');
        $form.find('select').attr('disabled','disabled');

        //Старт Анимация
        $form.find('.escapingBallG-animation').addClass('active');
        $form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });


        var componentname=$form.data('componentname');
        var action=$form.data('action');
        postData.set('signed_params', $form.data('signed'))

        BX.ajax.runComponentAction(componentname, action, {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            // console.log(response)

            //Конец Анимация
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            //Разблокируем кнопку
            $form.find('input[type="submit"]').removeAttr('disabled');

            var data=response.data;

            //STANDART
            if (data.elements!==undefined){
                for (const [key, value] of Object.entries(data.elements)) {
                    if (Array.isArray(value)){
                        value.forEach(function(el){
                            if (el==="hide"){
                                $form.find(key).hide(300);
                            }
                            else if(el==="show"){
                                $form.find(key).show(300);
                            }
                            else if (el.style!==undefined){
                                $form.find(key).css(el.style);
                            }
                            else{
                                $form.find(key).get(0).outerHTML=el;
                            }
                        })
                    }
                    else{
                        if (value==="hide"){
                            $form.find(key).hide(300);
                        }
                        else if(value==="show"){
                            $form.find(key).show(300);
                        }
                        else if (value.style!==undefined){
                            $form.find(key).css(value.style);
                        }
                        else{
                            $form.find(key).get(0).outerHTML=value;
                        }

                    }

                }
            }
            if (data.btn!==undefined){
                $form.find('input[type="submit"]').val(data.btn)
            }
            if (data['next-action']!==undefined){
                $form.data('action', data['next-action']);
            }
            if (data.message!==undefined){
                ShowMessage(data.message);
            }
            if (data['enable-inputs']===true){
                var disabled = $form.find(':input:disabled');
                disabled.removeAttr('disabled');
                $form.find('select').removeAttr('disabled');
            }
            if (data["clear-inputs"]===true){
                $form.find('input.form-request-new__input').val('');
            }

            //STANDART
            if (data['next-action']==="code"){
                $form.find(".form-request-new__footer").css("justify-content", "center");
                $form.find('.form-request-new__input.code-input').prop("required", "required").removeAttr("disabled");
                $form.find('.form-request-new__input.code-input').inputmask(
                    {
                        mask: '9 9 9 9 9',
                        placeholder: "*",
                    }
                );

                $form.find('a[href="#resend"]').unbind();
                $form.find('a[href="#resend"]').click(function(e){
                    e.preventDefault();
                    $form.find('.form-request-new__input.code-input').removeAttr("required").val('');
                    $form.data('action', action);
                    $form.submit();
                });
            }
            if (data['next-action']==="reg"){
                $form.find(".form-request-new__footer").css("justify-content", "space-between");
            }


        }, function(response){
            // console.log(response)

            //Конец Анимация
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            //Разблокируем кнопку
            $form.find('input[type="submit"]').removeAttr('disabled')
            var disabled = $form.find(':input:disabled');
            disabled.removeAttr('disabled');
            $form.find('select').removeAttr('disabled');

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
        })
    });
})