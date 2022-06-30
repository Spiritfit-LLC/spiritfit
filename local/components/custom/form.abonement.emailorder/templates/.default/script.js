$(document).ready(function(){
    var widgetOptions={
        publicId: w_publicID,
        description: w_description,
        amount: w_amount,
        currency: w_currency,
        accountId: w_userID,
        invoiceId: w_invoiceID,
        email: w_email,
        requireEmail:w_emailRequire,
        skin: "mini",
        data: w_data,
    };

    function payCloudWidget() {
        var widget = new cp.CloudPayments();
        widget.pay('charge',
            widgetOptions,
            {
                onSuccess: function (options) {
                    $('.subscription__label-prices-block').hide(300);
                    form.unbind();
                    form.submit(function(){
                        window.location.href='/abonement/';
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
    };

    var form = $('form.get-abonement.order');
    form.unbind();
    form.submit(function(e){
        e.preventDefault();
        payCloudWidget();
    })
})