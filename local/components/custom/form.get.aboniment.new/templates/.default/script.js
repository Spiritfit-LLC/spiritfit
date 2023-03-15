function o(){
    $(window).innerWidth() < 1260 ? $(".subscription__aside").next().is($(".services-block")) || ($(".subscription__aside").insertAfter(".subscription__label-prices-block"), $(".subscription__ready").insertAfter(".subscription__title:eq(0)")) : $(".subscription__aside").next().is($(".services-block")) && ($(".subscription__aside").insertAfter(".subscription__main"), $(".subscription__ready").insertAfter(".subscription__common"))
}

$(document).ready(function (){
    dataLayerSend('UX', 'openMembershipRegPage', params.datalayerLabel);


    [].forEach.call( document.querySelectorAll('[type="tel"]'), function(input) {
        $(input).inputmask({
            'mask': '+7 (999) 999-99-99',
        });
    });

    $('#sms-code-field').inputmask(
        {
            mask: '9 9 9 9 9',
            placeholder: "*",
        }
    );



    //Константы
    var form=$("#get-abonement-form");
    var club_selector=$("#club-field");
    var promocode_btn=$('#promocode-apply-btn');


    function CheckVisible(el){
        if (!$(el).is(':visible'))
        {
            $(el).show(300);
        }
    }

    //ВЫБОР КЛУБА
    club_selector.on("select2:select", function(){
        var postData={
            'club_id': $(this).val(),
        };

        BX.ajax.runComponentAction(params.componentName, 'getClub', {
            mode: 'class',
            data: postData,
            method:'POST',
            signedParameters:params.signedParameters
        }).then(function (response) {
            o();

            if (response.data.result===false){
                return;
            }

            var services=response.data.SERVICES;
            var prices=response.data.PRICE;

            if (services!=null){
                $('.services-block').html(services);
                CheckVisible($('.services-block'));
            }
            else{
                $('.services-block').hide(300);
            }

            if (prices!=null){
                $('.subscription__label-prices-block').html(prices);
                CheckVisible($('.subscription__label-prices-block'));
            }
            else{
                $('.subscription__label-prices-block').hide(300);
            }

            if (response.data.BASE_PRICE===null){
                $('.subscription__total-value-old').hide(300)
            }
            else{
                $('.subscription__total-value-old').show(300)
            }

            $('.old-price').text(response.data.BASE_PRICE)
            $('.current_price').text(response.data.CURRENT_PRICE)

            CheckVisible($('.subscribtion__bottom-block'));
        }, function (response) {
            //Сообщение об ошибке
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            ShowMessage(message);
        });
    });

    //ПРОМОКОД
    $("#promocode-field").on("input keyup paste", function(){
        if ($(this).val().length===0){
            promocode_btn.fadeOut(300)
        }
        else{
            promocode_btn.fadeIn(300)
        }
    });


    promocode_btn.click(function(){
        form.find('.escapingBallG-animation').addClass('active');
        form.find('input[type="submit"]').prop("disabled", "disabled");
        form.find('input[type="submit"]').css({
            'opacity':0,
        });

        BX.ajax.runComponentAction(params.componentName, 'applyPromocode', {
            mode: 'class',
            data: new FormData(form.get(0)),
            method:'POST',
            signedParameters:params.signedParameters
        }).then(function(response){
            o();

            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').removeAttr('disabled');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });

            $("#promocode-field").prop("disabled", "disabled");
            promocode_btn.fadeOut(300, function(){
                $(this).remove();
            });

            response.data.DISCOUNTS.forEach(function(el){
                $(`.subscription__label-item[data-month="${el['number']}"]`).find('.price-value').text(el['price']);
            });
            $('.old-price').text(response.data.BASEPRICE);
            $('.current_price').text(response.data.CURRENT_PRICE);

            ShowMessage(response.data.MESSAGE);

        }, function(response){

            form.find('.escapingBallG-animation').removeClass('active');
            form.find('input[type="submit"]').removeAttr('disabled');
            form.find('input[type="submit"]').css({
                'opacity':1,
            });

            //Сообщение об ошибке
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });
            var message=response.errors[error_id].message;
            ShowMessage(message);
        });
    });

    form.submit(submitForm)
    $("#resend-btn").click(function(){
        params.action="getOrder";
        form.submit();
    });
});

function ShowMessage(message){
    $("#modal__text").html(message);
    $('#ajax-message__container').css("display", "flex").fadeIn(300);
}

var widgetOptions=null;
function payCloudWidget(){
    if (widgetOptions!==null){
        var widget = new cp.CloudPayments();
        widget.pay('charge',
            widgetOptions,
            {
                onSuccess: function (options) {
                    params.action='done';
                    $(".subscribtion__bottom-block").hide(300);
                    BX.ajax.runComponentAction(params.componentName, params.action, {
                        mode: 'class',
                        method:'GET',
                        signedParameters:params.signedParameters
                    }).then(function(response){
                        console.log(response);

                        $(".subscription__main").html(response.data);
                    });

                },
                onFail: function (reason, options) { // fail
                    $("#get-abonement-btn").val("Оплатить");
                },
                onComplete: function (paymentResult, options) {

                }
            }
        )
    }
}

function closeBonuses(){
    $('#popup-bonuses__container').fadeOut(300);
    payCloudWidget();
    $("#get-abonement-form").unbind();
    $("#get-abonement-form").submit(function(e){
        e.preventDefault();
        if (window.sessionStorage.getItem("bonuses")==="1"){
            $('#popup-bonuses__container').fadeIn(300);
            $(this).unbind();
            $(this).submit(submitForm);
            $(".current_price").text(widgetOptions.amount - $("#bonus-field").val());
        }
        else{
            payCloudWidget();
        }
    });
    $(".current_price").text(widgetOptions.amount);
}

function close_legal(){
    $("#legalinfo-popup").fadeOut(300);
    params.step='LEGALINFO';
    $("#legalinfo-field").removeAttr("required");
}

function submitForm(e){
    e.preventDefault();
    var form=$("#get-abonement-form");
    var popup=$("#legalinfo-popup")

    if (params.step==='LEGALINFO'){
        popup.fadeIn(300);
        $("#legalinfo-field").prop("required", "required");
        params.step='';
        return;
    }

    popup.fadeOut(300);

    form.find('input[disabled]')
        .addClass("disabled")
        .removeAttr("disabled");

    form.find('.escapingBallG-animation').addClass('active');
    form.find('input[type="submit"]').prop("disabled", "disabled");
    form.find('input[type="submit"]').css({
        'opacity':0,
    });


    BX.ajax.runComponentAction(params.componentName, params.action, {
        mode: 'class',
        data: new FormData(form.get(0)),
        method:'POST',
        signedParameters:params.signedParameters
    }).then(function(response){
        o();

        form.find('.escapingBallG-animation').removeClass('active');
        form.find('input[type="submit"]').removeAttr('disabled');
        form.find('input[type="submit"]').css({
            'opacity':1,
        });

        params.action=response.data.ajax;

        form.find('input.disabled')
            .removeClass("disabled")
            .prop("disabled", "disabled");

        if (response.data.dataLayer!==undefined){
            var category='UX';
            if (response.data.dataLayer.eCategory!==undefined){
                category=response.data.dataLayer.eCategory;
            }
            try{
                dataLayerSend(category, response.data.dataLayer.eAction, response.data.dataLayer.eLabel)
            }
            catch (e) {
                console.log(e);
            }
        }

        if (response.data.action !==undefined){
            switch (response.data.action){
                case "sms":
                    $("#form-inputs-1").hide(300);
                    $("#form-inputs-2").show(300);
                    $(".sms-code-sent__tel").text($("#phone-field").val())
                    $("#get-abonement-btn").val("Подтвердить");
                    break;
                case "bonuses":
                    $("#form-inputs-1").show(300);
                    $("#form-inputs-2").hide(300);
                    form.find("input.input").prop("disabled", "disabled");
                    form.find("select").prop("disabled", "disabled");

                    $(".current_price").text(response.data.model.amount);



                    widgetOptions=response.data.model;
                    $("#invoice-id-input").val(response.data.model.invoiceId)
                    if (response.data.bonuses!==null){
                        var bonuses=parseInt(response.data.bonuses);
                        $("#bonuses-count").text(bonuses);

                        noUiSlider.create($("#bonuses-slider").get(0),{
                            start: 0, //Это начальная отметка, она должна быть в приделах максимальной и минимальной величин(Указав через запятую еще одно число вы добавите еще 1 ползунок)
                            step: 1, // Шаг
                            direction: "ltr", // Определяет, в какую сторону будет ездить ползунок "ltr","rtl"
                            range: { // Массив с диапазоном чисел
                                'min': 0, //Минимальное
                                'max': bonuses //Максимальное
                            },
                            connect: "lower", // Позволяет записывать данные ползунка туда, куда нам нужно!
                            keyboardSupport: true,
                            keyboardDefaultStep: 5,
                            pips: {
                                mode: 'count',
                                values: 10,
                                density: 4,
                            }
                        });

                        $("#bonuses-slider").get(0).noUiSlider.on('update', function(value) {
                            var amount=widgetOptions.amount;
                            $(".current_price").text(amount - value);
                            $("#bonus-field").val(value);
                            $(".bonuses-sale").text(value);
                        });



                        $("#popup-bonuses__container")
                            .css("display", "flex")
                            .fadeIn(300);

                        window.sessionStorage.setItem("bonuses", "1");
                    }
                    else{
                        payCloudWidget();
                        window.sessionStorage.setItem("bonuses", "0");
                    }
                    break;
                case "recalc":
                    widgetOptions.amount=parseInt(response.data.amount);
                    widgetOptions.data=response.data.jsonData;

                    $(".current_price").text(widgetOptions.amount);

                    $('#popup-bonuses__container').fadeOut(300);
                    payCloudWidget();
                    break;
            }
        }

        if (response.data.step !==undefined){
            params.step=response.data.step;
        }

        if (response.data.signedParams!==undefined){
            params.signedParameters=response.data.signedParams;
        }


    }, function(response){

        form.find('.escapingBallG-animation').removeClass('active');
        form.find('input[type="submit"]').removeAttr('disabled');
        form.find('input[type="submit"]').css({
            'opacity':1,
        });

        //Сообщение об ошибке
        var error_id=0;
        response.errors.forEach(function(err, index){
            if (err.code!==0){
                error_id=index
                return false;
            }
        });
        var message=response.errors[error_id].message;
        if (error_id===1){
            $("#form-inputs-1").show(300);
            $("#form-inputs-2").hide(300);
        }
        else if (error_id===2){
            $("#form-inputs-1").hide(300);
            $("#form-inputs-2").show(300);
        }
        ShowMessage(message);
    });
}