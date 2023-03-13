$(document).ready(function(){
    //ОПЛАТА
    var $bonuses_modal=$("#popup-bonuses__container");
    var widgetOptions=null;
    var $switch=null;

    function pay(update=true){
        if (!widgetOptions || $bonuses_modal.hasClass("laoding")){
            return false;
        }
        else{
            var widget = new cp.CloudPayments();
            widget.pay('charge',
                widgetOptions,
                {
                    onSuccess: function (options) {
                        $switch.prop("checked", "checked");
                        UpdatePersonalInfo(update);
                    },
                    onFail: function (reason, options) { // fail

                    },
                    onComplete: function (paymentResult, options) {
                        widgetOptions=null;
                    }
                }
            )
        }
    }
    $bonuses_modal.find(".modal__closer").click(function(){
        $("#bonuses-slider").get(0).noUiSlider.destroy();
        pay();
    });

    //RECALC
    $("#recalc-form").submit(function (e){
        e.preventDefault();

        var $bonuses_form = $(this);
        //Выключаем кнопку
        $bonuses_form.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $bonuses_form.find('.escapingBallG-animation').addClass('active');
        $bonuses_form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        var postData = new FormData(this);
        $bonuses_modal.addClass("laoding");


        $bonuses_modal.find(".popup__modal-info.error-text").html('');
        $bonuses_modal.find(".popup__modal-content").show();
        $bonuses_modal.find(".popup__modal-error").hide();

        BX.ajax.runComponentAction(personalServicesComponent, 'recalc', {
            mode:'class',
            method:'POST',
            data:postData
        }).then(function(r){
            console.log(r);

            //Выключаем колабашечку и включаем кнопку
            $bonuses_modal.find('.escapingBallG-animation').removeClass('active');
            $bonuses_modal.find('input[type="submit"]').css({
                'opacity':1,
            });
            $bonuses_modal.find('input[type="submit"]').removeAttr('disabled');

            widgetOptions.amount = r.data.amount;
            widgetOptions.data = r.data.jsonData;

            $("#bonuses-slider").get(0).noUiSlider.destroy();
            $bonuses_modal.removeClass("laoding");
            $bonuses_modal.fadeOut(300);

            pay();

        }, function(response){
            console.log(r)

            //Выключаем колабашечку и включаем кнопку
            $bonuses_modal.find('.escapingBallG-animation').removeClass('active');
            $bonuses_modal.find('input[type="submit"]').css({
                'opacity':1,
            });
            $bonuses_modal.find('input[type="submit"]').removeAttr('disabled');


            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $bonuses_modal.find(".popup__modal-info.error-text").html(message);
            $bonuses_modal.find(".popup__modal-content").hide();
            $bonuses_modal.find(".popup__modal-error").show();
        });
    })


    var $modal_subs=$("#ps-modal");
    
    var $modal_service=$("#ps-modal-service");

    function getSubscriptionInfo(e){
        e.preventDefault();

        $modal_subs.find(".popup__modal-info.error-text").html("");
        $modal_subs.find(".popup__modal-content").show(200);
        $modal_subs.find(".popup__modal-error").hide(200);

        $switch=$(this);

        BX.ajax.runComponentAction(personalServicesComponent, 'getSubInfo', {
            mode:'class',
            method:'POST',
            data:{
                id:$(this).data("id"),
                type:$(this).data("type")
            }
        }).then(function(response){
            var message = response.data.message;
            if (response.data.btn !== undefined){
                $modal_subs
                    .find('input[type="submit"]')
                    .val(response.data.btn);
            }

            var $modal_info = $modal_subs.find(".popup__modal-info");
            $modal_info.html(message);

            if (response.data.text1 !==undefined){
                $('<div class="popup__modal-info__sub"></div>')
                    .html(response.data.text1)
                    .appendTo($modal_info);
            }
            if (response.data.list1 !==undefined){
                var $cause_list = $('<div class="popup__modal-causelist"></div>');
                response.data.list1.forEach(function(el){
                    $cause_list.append(`<div class="personal-input__radio-input"><input class="personal-input__input-value input-radio-btn" name="termination_id", type="radio" value="${el.id}" id="cause_item_${el.id}">`+
                    `<label for="cause_item_${el.id}">${el.name}</label></div>`)
                    // $cause_list.append('<label class="modal-radio">' +
                    //     `<input type="radio" class="modal-radio__input" name="termination_id" id="cause_item_${el.id}" value="${el.id}">` +
                    //     el.name +
                    //     '</label>');
                });
                $cause_list.appendTo($modal_info);
            }

            $modal_subs.fadeIn(300).css("display", "flex");

        }, function (response){

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $modal_subs.find(".popup__modal-info.error-text").html(message);
            $modal_subs.find(".popup__modal-content").hide(200);
            $modal_subs.find(".popup__modal-error").show(200, function(){
                $modal_subs.fadeIn(300).css("display", "flex");
            });
        })
    }
    function editSubscription(e){
        e.preventDefault();

        //Выключаем кнопку
        $modal_subs.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $modal_subs.find('.escapingBallG-animation').addClass('active');
        $modal_subs.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });


        var causelist=[];
        $('input[name="termination_id"]:checked').each(function(){
            causelist.push($(this).val());
        })

        BX.ajax.runComponentAction(personalServicesComponent, 'editSub', {
            mode:'class',
            method:'POST',
            data:{
                id:$switch.data("id"),
                type:$switch.data("type"),
                causelist: causelist
            }
        }).then(function (response){
            console.log(response)

            //Выключаем колабашечку и включаем кнопку
            $modal_subs.find('.escapingBallG-animation').removeClass('active');
            $modal_subs.find('input[type="submit"]').css({
                'opacity':1,
            });
            $modal_subs.find('input[type="submit"]').removeAttr('disabled');



            var data = response.data;
            if (data.result === "enable"){
                $switch.prop("checked", "checked");
                $modal_subs.fadeOut(300);
            }
            else if (data.result === "disable"){
                $switch.prop("checked", false);
                $modal_subs.fadeOut(300);
            }
            else if (data.result === "order"){
                $modal_subs.fadeOut(300);
                widgetOptions=response.data.model;
                $("#invoice-id-input").val(response.data.model.invoiceId)
                if (response.data.bonuses!==null){
                    var bonuses=parseInt(response.data.bonuses);
                    $("#bonuses-count").text(bonuses);
                    noUiSlider.create($("#bonuses-slider").get(0),{
                        start: 0,
                        step: 1,
                        direction: "ltr",
                        range: {
                            'min': 0,
                            'max': bonuses
                        },
                        connect: "lower",
                        keyboardSupport: true,
                        keyboardDefaultStep: 5,
                        pips: {
                            mode: 'count',
                            values: 10,
                            density: 4,
                        }
                    });

                    $("#bonuses-slider").get(0).noUiSlider.on('update', function(value) {
                        var fullprice=response.data.fullprice;
                        $(".current_price").text(fullprice - value);
                        $("#bonus-field").val(value);
                        $(".bonuses-sale").text(value);
                    });
                    $bonuses_modal.fadeIn(300).css("display", "flex");
                }
                else{
                    pay();
                }
            }
        }, function (response){
            console.log(response)

            //Выключаем колабашечку и включаем кнопку
            $modal_subs.find('.escapingBallG-animation').removeClass('active');
            $modal_subs.find('input[type="submit"]').css({
                'opacity':1,
            });
            $modal_subs.find('input[type="submit"]').removeAttr('disabled');


            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $modal_subs.find(".popup__modal-info.error-text").html(message);
            $modal_subs.find(".popup__modal-content").hide(200);
            $modal_subs.find(".popup__modal-error").show(200);
        });
    }

    $(".personal-service__switch-checkbox.subscription").click(getSubscriptionInfo);
    $modal_subs.find('input[type="submit"]').click(editSubscription);


    $(".personal-service__switch-checkbox.service").click(function(e){
        e.preventDefault();

        $switch=$(this);

        $modal_service.find(".popup__modal-info.error-text").html("");
        $modal_service.find(".popup__modal-content").show(200);
        $modal_service.find(".popup__modal-error").hide(200);

        var postData = {
            type: $(this).data("type"),
            v: 3,
            template_folder: personaServiceTemplateFolder,
        }

        BX.ajax.runComponentAction(personalServicesComponent, 'getServiceForm', {
            mode:'class',
            method:"POST",
            data: postData
        }).then(function(response){
            $modal_service.find(".popup__modal-content").html(response.data);
            var $select=$("#service-select");
            $select.select2(
                {
                    width:"100%",
                    placeholder:"",
                    minimumResultsForSearch: 10,
                    dropdownParent:$select.parent(),
                    closeOnSelect: true
                }
            );

            $select.on("select2:select", function(){
                var id = $(this).val();
                $(".ps-service__item:visible").hide();
                $(`.ps-service__item[data-id="${id}"]`).show();
            });

            $modal_service.fadeIn(300).css("display", "flex");

            $("#ps-service__form").submit(editService);

        }, function (response){
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $modal_service.find(".popup__modal-info.error-text").html(message);
            $modal_service.find(".popup__modal-content").hide(200);
            $modal_service.find(".popup__modal-error").show(200, function(){
                $modal_service.fadeIn(300).css("display", "flex");
            });
        });
    });

    function editService(e){
        e.preventDefault();

        //Выключаем кнопку
        $modal_service.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $modal_service.find('.escapingBallG-animation').addClass('active');
        $modal_service.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        var postData = new FormData(this);

        BX.ajax.runComponentAction(personalServicesComponent, 'editService', {
            mode:'class',
            data:postData,
            method: 'POST'
        }).then(function(r){
            console.log(r);

            //Выключаем колабашечку и включаем кнопку
            $modal_service.find('.escapingBallG-animation').removeClass('active');
            $modal_service.find('input[type="submit"]').css({
                'opacity':1,
            });
            $modal_service.find('input[type="submit"]').removeAttr('disabled');

            if (r.data.result === "enable"){
                $switch.prop("checked", "checked");
                $modal_service.fadeOut(300);
            }
            else if (r.data.result === "disable"){
                $switch.prop("checked", false);
                $modal_service.fadeOut(300);
            }
            else if (r.data.result === "order"){
                $modal_service.fadeOut(300);
                widgetOptions=r.data.model;
                $("#invoice-id-input").val(r.data.model.invoiceId)
                if (r.data.bonuses!==null){
                    var bonuses=parseInt(r.data.bonuses);
                    $("#bonuses-count").text(bonuses);
                    noUiSlider.create($("#bonuses-slider").get(0),{
                        start: 0,
                        step: 1,
                        direction: "ltr",
                        range: {
                            'min': 0,
                            'max': bonuses
                        },
                        connect: "lower",
                        keyboardSupport: true,
                        keyboardDefaultStep: 5,
                        pips: {
                            mode: 'count',
                            values: 10,
                            density: 4,
                        }
                    });

                    $("#bonuses-slider").get(0).noUiSlider.on('update', function(value) {
                        var fullprice=r.data.fullprice;
                        $(".current_price").text(fullprice - value);
                        $("#bonus-field").val(value);
                        $(".bonuses-sale").text(value);
                    });
                    $bonuses_modal.fadeIn(300).css("display", "flex");
                }
                else{
                    pay(true);
                }
            }
        }, function(response){
            console.log(response);

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            $modal_service.find(".popup__modal-info.error-text").html(message);
            $modal_service.find(".popup__modal-content").hide(200);
            $modal_service.find(".popup__modal-error").show(200, function(){
                $modal_service.fadeIn(300).css("display", "flex");
            });
        })
    }

});