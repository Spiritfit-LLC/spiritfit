$(document).ready(function(){

    var freezingform=$('input[value="getfreezing"]').closest('form');
    freezingform.unbind()
    freezingform.submit(function(e){
        e.preventDefault();

        if ($(this).find('input[name="ACTION"]').val()==='go-to'){
            window.open($('input[name="target_link"]').val(), '_blank');
            return false;
        }

        var disabled = $(this).find(':input:disabled').removeAttr('disabled');
        var postData=new FormData(this);
        disabled.attr('disabled','disabled');

        var form=$(this);
        form.find('.form-submit-result-text').html('').removeClass('active');

        form.find('input[type="submit"]').attr('disabled','disabled');

        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        form.find('select').attr('disabled','disabled');

        BX.ajax.runComponentAction(componentName, postData.get('ACTION'), {
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
            if (res_data['next_action']!==undefined){
                $(form).find('input[name="ACTION"]').val(res_data['next_action']);
            }

            if (postData.get('ACTION')==='getfreezing'){
                form.find('input[type="submit"]').val('подтвердить');

                var select_str='<div class="personal-section-form__item select-item" id="freezingdays" style="display:none"><select name="freezing" required>'
                var descriptions_block='<div class="descriptions-block">';

                var i=0;
                res_data['freezings'].forEach(function(el){
                    if (i===0){
                        select_str+=`<option value="${el.id}" selected>${el.name}</option>`;
                        descriptions_block+=`<div class="freezing-description" data-id="${el.id}">${el.description}`+
                            `<div class="freezing-price">Цена: ${el.prices[0].price} руб.</div>`+
                            `</div>`;
                    }
                    else{
                        select_str+=`<option value="${el.id}">${el.name}</option>`;
                        descriptions_block+=`<div class="freezing-description" style="display: none" data-id="${el.id}">${el.description}`+
                            `<div class="freezing-price">Цена: ${el.prices[0].price} руб.</div>`+
                            `</div>`;
                    }
                    i+=1;
                });
                select_str+='</select></div>'
                descriptions_block+='</div>';

                var select_freezing_days=$(select_str);
                select_freezing_days.insertBefore(form.find('input[type="submit"]'));

                var descriptions=$(descriptions_block);
                descriptions.insertAfter(select_freezing_days);

                select_freezing_days.find('select').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
                select_freezing_days.show(300);

                select_freezing_days.find('select').change(function(e){
                    $(`.freezing-description:not([data-id="${$(this).val()}"])`).hide(300);
                    $(`.freezing-description[data-id="${$(this).val()}"]`).show(300);
                });
            }
            else if (postData.get('ACTION')==='postfreezing'){
                form.find('input[type="submit"]').val('Получить счет');
                form.find('input[name="ACTION"]').val('go-to');

                form.find('.descriptions-block').text('Ваша услуга готова! Пожалуйста, оплатите счет.')

                form.append(`<input type="hidden" value="${res_data.invoice.formUrl}" name="target_link">`);
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
            form.find('.form-submit-result-text').html(message).addClass('active');
        })
    });

    if ($('a[href="#freefreezing"]').length>0){
        const instance = tippy(document.querySelector('a[href="#freefreezing"]'));
        instance.setProps({
            interactive: true,
            trigger: 'manual',
            allowHTML: true,
            maxWidth:700,
            appendTo: () => document.querySelector('body'),
            onHide:()=>{
                $('.tippy-background').removeClass('active');
                $('.freefreezing-form').unbind();
                freezingform.find('input[type="submit"]').fadeIn(300);
            },
            onMount: ()=>{
                $('select[name="freezing"]').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%',
                    dropdownParent: $('.freefreezing-form')
                });
                $('.tippy-background').addClass('active');
            },
            onShown:(instance)=>{
                freezingform.find('input[type="submit"]').fadeOut(300);

                $('.freefreezing-form').unbind();
                $('.freefreezing-form').submit(function(e){
                    e.preventDefault();

                    var disabled = $(this).find(':input:disabled').removeAttr('disabled');
                    var postData=new FormData(this);
                    disabled.attr('disabled','disabled');

                    var form=$(this);
                    form.find('.form-submit-result-text').html('').removeClass('active');

                    form.find('input[type="submit"]').attr('disabled','disabled');

                    form.find('.escapingBallG-animation').addClass('active');
                    form.find('input[type="submit"]').css({
                        'opacity':0,
                        'z-index':1
                    });

                    BX.ajax.runComponentAction(componentName, postData.get('ACTION'), {
                        mode: 'class',
                        data: postData,
                        method:'POST'
                    }).then(function(responce){

                        form.find('.escapingBallG-animation').removeClass('active');
                        form.find('input[type="submit"]').css({
                            'opacity':1,
                        });

                        form.find('input[type="submit"]').removeAttr('disabled');
                        var res_data=responce['data'];
                        if (res_data['reload']===true){
                            window.location = window.location.pathname;
                        }
                    }, function (responce){
                        form.find('.escapingBallG-animation').removeClass('active');
                        form.find('input[type="submit"]').css({
                            'opacity':1,
                        });
                        form.find('input[type="submit"]').removeAttr('disabled');
                        var error_id=0;
                        responce.errors.forEach(function(err, index){
                            if (err.code!==0){
                                error_id=index
                                return false;
                            }
                        });
                        var message=responce.errors[error_id].message;
                        form.find('.form-submit-result-text').html(message).addClass('active');
                    })

                    return false;
                });
            }
        });

        $('a[href="#freefreezing"]').click(function(e){
            e.preventDefault();
            var loading_content='<div class="escapingBallG-animation active tippy-form loader">' +
                '<div id="escapingBall_1" class="escapingBallG"></div>' +
                '</div>';

            instance.setContent(loading_content);
            instance.show();

            var form=$(this).closest('form');

            BX.ajax.runComponentAction(componentName, 'getfreefreezing', {
                mode: 'class',
                method:'POST'
            }).then(function(response){

                var modal_content='<form class="freefreezing-form tooltip-form">' +
                    '<div class="tooltip-form-title">Использовать бесплатную заморозку</div>' +
                    '<input type="hidden" name="ACTION" value="freefreezingpost">' +
                    '<div class="tooltip-form-body-text">Укажите количество бесплтаных дней, которыми вы хотите воспользоваться</div>' +
                    '<div class="personal-section-form__item select-item"><select name="freezing" required>';

                var freezings=response.data.freezings;
                freezings.forEach(function(el){
                    modal_content+=`<option value="${el}">${el} дней</option>`
                })
                modal_content+='</select></div>' +
                    '<input type="submit" class="tooltip-form-submit" value="подтвердить">' +
                    '<div class="escapingBallG-animation tippy-form">' +
                    '<div id="escapingBall_1" class="escapingBallG"></div>' +
                    '</div>' +
                    '<span class="form-submit-result-text"></span>' +
                    '</form>'
                instance.hide()
                instance.setContent(modal_content);
                instance.show();
            })
        });
    }

});