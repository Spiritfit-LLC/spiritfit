function payCloudWidget() {
    var form = $('form.get-abonement.order');

    var widget = new cp.CloudPayments();
    widget.pay('charge',
        params.model,
        {
            onSuccess: function (options) {
                $('.subscription__label-prices-block').hide(300);
                form.unbind();
                form.submit(function(e){
                    e.preventDefault();

                    window.location.href='/';
                })
                form.find('input[type="submit"]').val('Закрыть')
                $('.subscription__desc').text(succes_pay_desc);
            },
            onFail: function (reason, options) { // fail

            },
            onComplete: function (paymentResult, options) {

            }
        }
    )
}

function ShowMessage(message){
    $("#modal__text").html(message);
    $('#ajax-message__container').css("display", "flex").fadeIn(300);
}

function recalcForm(e){
    e.preventDefault();

    var postData=new FormData(this);
    var $form=$(this);

    BX.ajax.runComponentAction(params.componentName, "orderRecalc", {
        mode:"class",
        data:postData,
        method:'POST',
        signedParameters:params.signedParameters
    }).then(function(response){
        console.log(response)

        console.log(response.data.jsonData)

        params.model.amount=parseInt(response.data.amount);
        params.model.data=response.data.jsonData;

        $(".current_price").text(params.model.amount);

        $('#popup-bonuses__container').fadeOut(300);
        payCloudWidget();

        $form.unbind();
        $form.submit(function(e){
            e.preventDefault();

            payCloudWidget();
        })


    }, function(response){
        console.log(response)

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
}

function payForm(e){
    e.preventDefault();
    var form=$(this)

    if (params.bonuses!==null){
        var bonuses=parseInt(params.bonuses);
        $("#bonuses-count").text(bonuses);
        $("#popup-bonuses__container").find(".current-price").text(params.model.fullprice)

        noUiSlider.create($("#bonuses-slider").get(0),{
            start: params.model.fullprice-params.model.amount, //Это начальная отметка, она должна быть в приделах максимальной и минимальной величин(Указав через запятую еще одно число вы добавите еще 1 ползунок)
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
            var fullprice=params.model.fullprice;
            $(".current_price").text(fullprice - value);
            $("#bonus-field").val(value);
            $(".bonuses-sale").text(value);
        });

        $("#popup-bonuses__container")
            .css("display", "flex")
            .fadeIn(300);

        window.sessionStorage.setItem("bonuses", "1");

        form.unbind();
        form.submit(recalcForm)
    }
    else{
        payCloudWidget();
        window.sessionStorage.setItem("bonuses", "0");
    }
}

function closeBonuses(){
    $('#popup-bonuses__container').fadeOut(300);
    payCloudWidget();

    var form = $('form.get-abonement.order');
    form.unbind();
    form.submit(function(e){
        e.preventDefault();
        if (window.sessionStorage.getItem("bonuses")==="1"){
            $('#popup-bonuses__container').fadeIn(300);
            $(this).unbind();
            $(this).submit(recalcForm);

            $(".current_price").text(params.model.fullprice - $("#bonus-field").val());
        }
        else{
            payCloudWidget();
        }
    });
    $(".current_price").text(params.model.amount);

}


$(document).ready(function(){
    var form = $('form.get-abonement.order');
    form.unbind();
    form.submit(payForm);
})

//TODO
//Нужно написать инфу об уже измененой цене баллами
//Если клиент решил оплатить все бонусами, то:
//1. Спрашиваем, действительно ли хочет оплатить вес бюонусами,
//2. если да, то не открываем оплату и виджет, а отправляем запрос payorder POST {"invoiceID"=>$this->arParams["INVOICE_ID"], "bonusessum"=>(int)$bonuses}
//
//
//
//