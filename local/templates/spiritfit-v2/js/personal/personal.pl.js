$(document).ready(function(){
    $('body').append('<div class="tippy-background" ></div>');


    $('a[href="#present"]').click(function(e){e.preventDefault()})
    $('a[href="#spend"]').click(function(e){e.preventDefault()})

    //Формы списания баллов
    if (!$('a[href="#present"]').data('clickable')){
        tippy('a[href="#present"]', {
            content: (reference)=>{
                var string='<span class="clue-message-text">'+
                    $(reference).data('clue')+
                    '</span>';
                return string;
            },
            interactive: false,
            allowHTML: true,
            maxWidth:400,
        });
    }
    else{
        tippy('a[href="#present"]', {
            content: (reference) =>
            {
                var string='<form class="present-form tooltip-form">' +
                    '<div class="tooltip-form-title">Подарить промокод другу</div>' +
                    '<input type="hidden" name="ACTION" value="present">' +
                    '<input type="text" class="tooltip-form-input tel" name="recipient" placeholder="Номер получателя" required>' +
                    '<input type="text" class="tooltip-form-input number" name="sum" placeholder="Количество баллов" required>' +
                    '<input type="submit" class="tooltip-form-submit" value="Подарить">' +
                    '<div class="escapingBallG-animation tippy-form">' +
                    '<div id="escapingBall_1" class="escapingBallG"></div>' +
                    '</div>' +
                    '<span class="form-submit-result-text"></span>' +
                    '</form>';
                return string;
            },
            interactive: true,
            trigger: 'click',
            allowHTML: true,
            maxWidth:400,
            onMount: ()=>{
                $('.tooltip-form-input.tel').inputmask({
                    'mask': '+7 (999) 999-99-99',
                });

                $("input.number").on('input', function(e){
                    this.value = this.value.replace(/[^0-9\.]/g, '');
                });

                $('.tippy-background').addClass('active');


            },
            onShown:(instance)=>{
                $('.present-form').unbind();
                $('.present-form').submit(function(e){
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
                            window.location.reload();
                            return;
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
                        var code=responce.errors[error_id].code;
                        form.find('.form-submit-result-text').html(message).addClass('active');
                    })

                    return false;
                });
            },
            appendTo: () => document.querySelector('body'),
            onHide:()=>{
                $('.tippy-background').removeClass('active');
                $('.present-form').unbind();
            }
        });
    }

    if (!$('a[href="#spend"]').data('clickable')){
        tippy('a[href="#spend"]', {
            content: (reference)=>{
                var string='<span class="clue-message-text">'+
                    $(reference).data('clue')+
                    '</span>';
                return string;
            },
            interactive: false,
            allowHTML: true,
            maxWidth:400,
        });
    }
    else{
        tippy('a[href="#spend"]', {
            content: (reference) =>
            {
                var paymentlimit=$('input#paymentlimit').val()
                var string='<form class="spend-form tooltip-form">' +
                    '<div class="tooltip-form-title">Списать бонусы</div>' +
                    '<input type="hidden" name="ACTION" value="SPEND">' +
                    '<input type="text" class="tooltip-form-input number" name="sum" placeholder="Количество баллов" required>' +
                    '<div class="tooltip-form-body-text">Доступный лимит бонусов в счет следующего списания: <span class="bold">'+paymentlimit+'</span></div>' +
                    '<input type="submit" class="tooltip-form-submit" value="Списать">' +
                    '<div class="escapingBallG-animation tippy-form">' +
                    '<div id="escapingBall_1" class="escapingBallG"></div>' +
                    '</div>' +
                    '<span class="form-submit-result-text"></span>' +
                    '</form>';
                return string;
            },
            interactive: true,
            trigger: 'click',
            allowHTML: true,
            maxWidth:400,
            onMount: ()=>{
                $("input.number").on('input', function(e){
                    this.value = this.value.replace(/[^0-9\.]/g, '');
                });

                $('.tippy-background').addClass('active');
            },
            onShown:(instance)=>{
                $('.spend-form').unbind();
                $('.spend-form').submit(function(e){
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
                            window.location.reload();
                            return;
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
                        var code=responce.errors[error_id].code;
                        form.find('.form-submit-result-text').html(message).addClass('active');
                    })

                    return false;
                });
            },
            appendTo: () => document.querySelector('body'),
            onHide:()=>{
                $('.tippy-background').removeClass('active');
                $('.spend-form').unbind();
            }
        });
    }
})