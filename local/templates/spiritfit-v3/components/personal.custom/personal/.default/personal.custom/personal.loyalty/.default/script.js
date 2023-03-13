$(document).ready(function(){
    var offset=10;
    if ($(window).width()<=425){
        offset=5;
    }
    else if ($(window).width()<=768){
        offset=10;
    }
    else if($(window).width()<=1024){
        offset=7;
    }
    else if ($(window).width()<=1440){
        offset=8;
    }

    var current_visit_el_index=0;

    var $container=$(".pl-item__all-visits-container");

    function scrollDaysCount(index){
        var element=$container.find(`.pl-visits-count-container[data-index="${index}"]`);
        $($container).stop().animate({scrollLeft:element.offset().left + $container.scrollLeft() - $container.offset().left}, 500);
    }

    if ($container.length>0){
        setTimeout(function(){
            scrollDaysCount(current_visit_el_index);
        },500)
    }

    $('.pl-item__visits-controller').on("click", function(){
        if ($(this).hasClass('left')){
            var ind=current_visit_el_index-offset;
            if (ind<0){
                ind=0;
            }
        }
        else if ($(this).hasClass('right')){
            ind=current_visit_el_index+offset;
            if (ind>$container.find('.pl-visits-count-container').length-offset){
                ind=$container.find('.pl-visits-count-container').length-offset;
            }
        }
        scrollDaysCount(current_visit_el_index=ind);
    });


    $("#loyaltyhistory_btn").click(function(){
        $(this).hide();
        $(".pl-item__lh-loading").fadeIn(300);


        BX.ajax.runComponentAction(personalLoyaltyComponent, "history", {
            mode:"class",
            method:'GET',
        }).then(function(response){
            if (response.data.result===false){
                $(".pl-item__lh-message").html(response.data.message);
            }

            if (response.data.result===true){
                response.data.data.forEach(function(el){
                    var $tr=$('<tr></tr>');
                    $tr.append(`<td class="col-date">${el.date}</td>`);
                    $tr.append(`<td class="col-event">${el.basis}</td>`);

                    if (el.count<0){
                        var action="Списание";
                        var className="red";
                        el.count=Math.abs(el.count);
                    }
                    else{
                        action="Начисление";
                        className="purple";
                    }
                    $tr.append(`<td class="col-action ${className}">${action}</td>`);
                    $tr.append(`<td class="count ${className}">${el.count}</td>`);

                    $("#pl-loyaltyhistory__table").find('tbody').append($tr);

                    var $mobile=$('<div class="pl-lh__row"></div>');
                    $mobile.append('<div class="pl-lh__field">' +
                        '<div class="pl-lh__field-placeholder">дата</div>' +
                        `<div class="pl-lh__field-value">${el.date}</div>` +
                        '</div>');
                    $mobile.append('<div class="pl-lh__field">' +
                        '<div class="pl-lh__field-placeholder">Событие</div>' +
                        `<div class="pl-lh__field-value">${el.basis}</div>` +
                        '</div>');
                    $mobile.append('<div class="pl-lh__field">' +
                        '<div class="pl-lh__field-placeholder">Действие</div>' +
                        `<div class="pl-lh__field-value bold ${className}">${action}</div>` +
                        '</div>');
                    $mobile.append('<div class="pl-lh__field">' +
                        '<div class="pl-lh__field-placeholder">Баллы</div>' +
                        `<div class="pl-lh__field-value bold ${className}">${el.count}</div>` +
                        '</div>');

                    $(".pl-loyaltyhistory__mobile").append($mobile);

                });

                $(".pl-item__lh-message").hide();
                $(".pl-item__lh-table").fadeIn(300);

            }
        }, function(response){
            $(".pl-item__lh-message").html("Не удалось загрузить данные.");
        });
    });


    tippy(".promocode-item", {
        content: (reference)=>{
            var str = '<div class="pl-present__clue">' +
                `<div class="pl-present__info">Промокод для друга на сумму&nbsp;<span class="red">${$(reference).data("sum")}</span>&nbsp;₽</div>` +
                `<div class="pl-present__info">Действует до: ${$(reference).data("date")}</div>` +
                '<div class="pl-present__action-info">Скопируйте промокод нажав на него.</div>'+
                '</div>';

            return str;
        },
        allowHTML:true,
        placement: 'top',
        arrow: false,
        interactive: true,
        duration:[300, 1000],
    });


    tippy(".pl-bonuses-btn.block", {
        content: (reference)=>{
            return `<div class="pl-present__clue">${$(reference).data("message")}</div>`;
        },
        allowHTML:true,
        placement: 'top',
        arrow: false,
        interactive: true,
        duration:[300, 1000],
        trigger: "click"
    });

    var action=null;
    $(".pl-bonuses-btn.unblock").click(function(e){
        action=$(this).data("action");

        switch (action){
            case "charge":
                $('#pl-charge')
                    .fadeIn(300)
                    .css("display", "flex");
                break;
            case "present":
                $('#pl-present')
                    .fadeIn(300)
                    .css("display", "flex");
                break;
        }
    });

    function submit(e){
        e.preventDefault();

        var $form=$(this);

        //Включаем, забираем, выключаем
        var disabled = $(this).find(':input:disabled');
        disabled.removeAttr('disabled');
        var postData=new FormData(this);
        disabled.attr('disabled','disabled');

        //Скрываем ошибки
        $('.field-error').fadeOut(300);

        //Выключаем кнопку
        $form.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $form.find('.escapingBallG-animation').addClass('active');
        $form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        var $modal=$form.closest(".popup__modal");

        $modal.find(".popup__modal-info.error-text").html('');
        $modal.find(".popup__modal-content").show();
        $modal.find(".popup__modal-error").hide();

        BX.ajax.runComponentAction(personalLoyaltyComponent, action, {
            mode: 'class',
            data: postData,
            method:'POST'
        }).then(function(response){
            console.log(response)

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
                    $form.find(`#${el.field_name}`)
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

            if (result.reload===true){
                window.location.reload();
            }

        }, function(response){
            console.log(response)

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

            $modal.find(".popup__modal-info.error-text").html(message);
            $modal.find(".popup__modal-content").hide();
            $modal.find(".popup__modal-error").show();
        })
    }

    $("#pl-charge__form").submit(submit);
    $("#pl-present__form").submit(submit);


    var $pl_reg_modal=$("#pl-reg");
    $("#personal-loyalty-reg-btn").click(function(e){
        e.preventDefault();

        //Выключаем кнопку
        $pl_reg_modal.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $pl_reg_modal.find('.escapingBallG-animation').addClass('active');
        $pl_reg_modal.find('input[type="button"]').css({
            'opacity':0,
            'z-index':1
        });

        $pl_reg_modal.find(".popup__modal-info.error-text").html('');
        $pl_reg_modal.find(".popup__modal-content").show();
        $pl_reg_modal.find(".popup__modal-error").hide();

        BX.ajax.runComponentAction(personalLoyaltyComponent, 'regLoyalty', {
            mode:'class',
            method:'POST'
        }).then(function(r){
            //Выключаем колабашечку и включаем кнопку
            $pl_reg_modal.find('.escapingBallG-animation').removeClass('active');
            $pl_reg_modal.find('input[type="button"]').css({
                'opacity':1,
            });

            window.location.reload();
        }, function(r){
            //Выключаем колабашечку и включаем кнопку
            $pl_reg_modal.find('.escapingBallG-animation').removeClass('active');
            $pl_reg_modal.find('input[type="submit"]').css({
                'opacity':1,
            });
            $pl_reg_modal.find('input[type="submit"]').removeAttr('disabled');

            var error_id=0;
            r.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=r.errors[error_id].message;
            var code=r.errors[error_id].code;

            $pl_reg_modal.find(".popup__modal-info.error-text").html(message);
            $pl_reg_modal.find(".popup__modal-content").hide();
            $pl_reg_modal.find(".popup__modal-error").show();
        })
    })
});


var copyPromocode=function(promocode){
    var date = new Date;
    date.setDate(date.getDate() + 1);
    document.cookie = "bn_promocode="+promocode+"; path=/; expires=" + date.toUTCString();
    var $tmp = $("<textarea>");
    $("body").append($tmp);
    $tmp.val(promocode).select();
    document.execCommand("copy");
    $tmp.remove();
    alert("Промокод скопирован!");
}