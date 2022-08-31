$(document).ready(function(){
    $('body').append('<div class="tippy-background"></div>');


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
                        // console.log(responce)
                        form.find('.escapingBallG-animation').removeClass('active');
                        form.find('input[type="submit"]').css({
                            'opacity':1,
                        });

                        form.find('input[type="submit"]').removeAttr('disabled');
                        var res_data=responce['data'];
                        if (res_data.result===false){
                            $('.field-error').remove();
                            res_data.errors.forEach(function(el){
                                $(`input[name="${el.form_name}"]`).after(`<span class="field-error" style="display: none">${el.message}</span>`);
                            });
                            if (res_data.section!==undefined){
                                $(`.personal-profile__tab-item[data-id="${res_data.section}"]`).click();
                            }
                            $('.field-error').fadeIn(300);
                            instance.unmount();
                            return;
                        }
                        if (res_data['reload']===true){
                            window.location = window.location.pathname;
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
                            window.location = window.location.pathname;
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

    //График посещений
    if ($('.personal-section__visits_count').length>0){
        var values = $.map($('.visits-count__occupancy'), function(el, index) { return parseInt($(el).data('count'));});
        var max = Math.max.apply(null, values);
        $('.visits-count__occupancy').each(function(index, el){
            $(el).css('height', parseInt($(el).data('count'))/max*100+'%');
        });

        if ($(window).width()<=768){
            var offset=4;
        }
        else{
            offset=5;
        }
        var current_visit_el_index=$('.visits-count-container').length-offset;
        if (current_visit_el_index<0){
            current_visit_el_index=0;
        }

        function scrollVisitsCount(index){
            var parent=$('.personal-section__visits_count');
            var element=$(`.visits-count-container[data-index="${index}"]`);
            $(parent).stop().animate({scrollLeft:element.offset().left + parent.scrollLeft() - parent.offset().left}, 500);

        }
        setTimeout(function(){
            scrollVisitsCount(current_visit_el_index);
        },500)

        $('.visits-count__controller').on(clickHandler, function(){
            if ($(this).hasClass('left')){
                var ind=current_visit_el_index-offset;
                if (ind<0){
                    ind=0;
                }
            }
            else if ($(this).hasClass('right')){
                ind=current_visit_el_index+offset;
                if (ind>$('.visits-count-container').length-offset){
                    ind=$('.visits-count-container').length-offset;
                }
            }
            scrollVisitsCount(current_visit_el_index=ind);
        });

    }

    //История баллов
    $('a[href="#getHistory"]').on(clickHandler, function(e){
        e.preventDefault();
        var $this=$(this);
        if ($this.hasClass('loaded')){
            if ($this.hasClass('active')){
                $('.loyaltyhistory').hide(200);
                $this.removeClass('active');
                $this.text('Детали накопления бонусов');
            }
            else{
                $('.loyaltyhistory').slideDown(300);
                $this.text('Закрыть');
                $this.addClass('active');
            }
        }
        else{
            BX.ajax.runComponentAction(componentName, 'getHistory', {
                mode: 'class',
                method:'GET'
            }).then(function(response){
                // console.log(response)
                //Графики
                var chart_data={
                    labels:response.data.label,
                    datasets: [
                        {
                            label: 'Баллы',
                            fill: false,
                            stepped: false,
                            data:[],
                            backgroundColor:'#fc6120',
                            borderColor:'#fc6120',
                            pointRadius: 5,
                            pointHoverRadius: 10,
                            pointHitRadius: 30,
                            pointBorderWidth: 2,
                            pointStyle: 'rectRounded'
                        }
                    ]
                };
                var basises=[];
                var differences=[];
                response.data.dataset.forEach(function(el){
                    chart_data.datasets[0].data.push({x:el.x, y:el.count})
                    basises.push(el.basis);
                    differences.push(el.diff);
                })
                const config = {
                    type: 'line',
                    data: chart_data,
                    options: {
                        plugins:{
                            legend:{
                                display:false
                            },
                            tooltip:{
                                callbacks:{
                                    beforeTitle:function(context){
                                        return context[0].label;
                                    },
                                    title:function(context){
                                        return basises[context[0].dataIndex]+': '+differences[context[0].dataIndex]+' бонусов';
                                    },
                                    label:function(context){
                                        return '';
                                    }
                                }
                            }
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        },
                        scales:{
                            xAxis: {
                                display: false //this will remove all the x-axis grid lines
                            }
                        }
                    }
                };

                const loyaltyhistoryChart = new Chart(
                    document.getElementById('loyaltyhistory-chart'),
                    config
                );

                $('.loyaltyhistory').slideDown(300);
                $this.text('Закрыть');
                $this.addClass('loaded');
                $this.addClass('active');

                //Список
                response.data.dataset.forEach(function(el){
                    var val=parseFloat(el.diff);
                    if (val<0){
                        var value_className="minus";
                    }
                    else{
                        value_className="";
                        val='+'+val;
                    }
                    if (el.basis.toLowerCase()!=="посещение" && el.basis.toLowerCase()!=="оплата списания"){
                        var basis_className="extra";
                        var basis_value='<div>'+el.basis+' <span class="loyaltyhistory-date">('+el.x+')</span></div><img class="loyalty-extra" src="/local/templates/spiritfit-v2/img/icons/extra.png"/>';
                    }
                    else{
                        basis_className="";
                        basis_value=el.basis+' <span class="loyaltyhistory-date">('+el.x+')</span>';
                    }
                    var date_value=moment(el.x, "D.M.YYY").lang("ru").format("D MMM");
                    var history_item=`<div class="lyaltyhistory-list__item"><div class="loyaltyhistory-basis"><div class="loyaltyhistory-index-column ${basis_className}">`+basis_value+`</div><div class="loyaltyhistory-date">`+date_value+`</div><div class="loyaltyhistory-diff ${value_className}">`+val+'</div></div></div>';
                    $(history_item).appendTo('.loyaltyhistory-list');
                })
            })
        }
    })
    $('.loyaltyhistory-controls__item').on(clickHandler, function(){
        $('.loyaltyhistory-controls__item.active').removeClass('active');
        $(this).addClass('active');

        $('.loyaltyhistories-block').hide();
        if ($(this).hasClass('chart')){
            $('.loyaltyhistories-block.chart').show();
        }
        else if($(this).hasClass('list')){
            $('.loyaltyhistories-block.list').show();
        }
    })
})