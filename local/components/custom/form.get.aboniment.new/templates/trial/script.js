function o(){
    $(window).innerWidth() < 1260 ? $(".subscription__aside").next().is($(".services-block")) || ($(".subscription__aside").insertAfter(".subscription__label-prices-block"), $(".subscription__ready").insertAfter(".subscription__title:eq(0)")) : $(".subscription__aside").next().is($(".services-block")) && ($(".subscription__aside").insertAfter(".subscription__main"), $(".subscription__ready").insertAfter(".subscription__common"))
}

$(document).ready(function (){
    dataLayerSend('UX', 'openMembershipRegPage', params.datalayerLabel);

    o();

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

    form.submit(submitForm)
    $("#resend-btn").click(function(){
        params.action="getTrial";
        form.submit();
    });
});

function ShowMessage(message){
    $("#modal__text").html(message);
    $('#ajax-message__container').css("display", "flex").fadeIn(300);
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

        form.find('.escapingBallG-animation').removeClass('active');
        form.find('input[type="submit"]').removeAttr('disabled');
        form.find('input[type="submit"]').css({
            'opacity':1,
        });

        params.action=response.data.ajax;

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
                case "href":
                    window.location.href=response.data.href;
                    break;
            }
        }

        if (response.data.step !==undefined){
            params.step=response.data.step;
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
