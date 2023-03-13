$(document).ready(function(){
    var $form = $("#personal-promisepayment__form");
    var $modal = $form.closest(".popup-modal__container");

    $form.submit(function(e){
        e.preventDefault();

        //Выключаем кнопку
        $form.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $form.find('.escapingBallG-animation').addClass('active');
        $form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        var postData = new FormData(this);

        BX.ajax.runComponentAction(personalPromisePaymentComponent, 'doPromisePayment', {
            mode:'class',
            method:'POST',
            data:postData
        }).then(function (r){
            console.log(r);

            //Выключаем колабашечку и включаем кнопку
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            $form.find('input[type="submit"]').removeAttr('disabled');

            window.location.reload();
        }, function(r){
            console.log(r);

            //Выключаем колабашечку и включаем кнопку
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            $form.find('input[type="submit"]').removeAttr('disabled');


            var error_id=0;
            r.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=r.errors[error_id].message;
            var code=r.errors[error_id].code;

            $modal.find(".popup__modal-info.error-text").html(message);
            $modal.find(".popup__modal-content").hide();
            $modal.find(".popup__modal-error").show();
        });
    })
});