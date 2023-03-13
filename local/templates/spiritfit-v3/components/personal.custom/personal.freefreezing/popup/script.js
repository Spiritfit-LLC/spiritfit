$(document).ready(function(){
    var $select=$("#pff-count-select");
    $select.select2(
        {
            width:"100%",
            placeholder:"",
            dropdownParent:$select.parent(),
            language:{
                noResults:function(){
                    return"Ничего не найдено"
                }
            },
            closeOnSelect: true,
        }
    );

    $("#personal-freefreezing__form").submit(function(e){
        e.preventDefault();

        var $form=$(this);

        //Выключаем кнопку
        $form.find('input[type="submit"]').attr('disabled','disabled');

        //Активируем колабашечку
        $form.find('.escapingBallG-animation').addClass('active');
        $form.find('input[type="submit"]').css({
            'opacity':0,
            'z-index':1
        });

        var postData = new FormData(this);

        var $modal=$form.closest(".popup__modal");

        BX.ajax.runComponentAction(personalFreeFreezingComponent, 'doFreeFreezing', {
            mode:'class',
            method:'POST',
            data:postData
        }).then(function(r){
            console.log(r);

            //Выключаем колабашечку и включаем кнопку
            $form.find('.escapingBallG-animation').removeClass('active');
            $form.find('input[type="submit"]').css({
                'opacity':1,
            });
            $form.find('input[type="submit"]').removeAttr('disabled');

            window.location.reload();

        }, function (r){
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
})