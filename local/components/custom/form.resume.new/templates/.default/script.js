$(document).ready(function(){
    //ФОКУСИРОВКА НА ЭЛЕМЕНТЕ
    $('.form-request-new__input').on("focus", function(){
        $(this).closest(".form-request-new__item").addClass("is-focused");
    });

    $('.form-request-new__input').on("blur", function(){
        if ($(this).val().length===0){
            $(this).closest(".form-request-new__item").removeClass("is-focused");
            $(this).closest(".form-request-new__item").addClass("is-empty");
        }
    });

    $('.form-request-new__input').on("keyup", function(){
        if ($(this).val().length===0){
            $(this).closest(".form-request-new__item").addClass("is-empty");
            $(this).closest(".form-request-new__item").removeClass("is-not-empty");
        }
        else{
            $(this).closest(".form-request-new__item").removeClass("is-empty");
            $(this).closest(".form-request-new__item").addClass("is-not-empty");
        }
    });


    $('input[data-type="price"]').inputmask({
        'mask': "(999){+|1}.00 руб.",
    })
})

var add_file=function(el){
    $('#'+el.id+'_trigger').text(el.files[0].name)
}

var submit_resume=function(el){
    var postData=new FormData(el);
    var componentname=$(el).data('componentname');
    var action="send";


    BX.ajax.runComponentAction(componentname, action, {
        mode: 'class',
        method:'POST',
        data:postData,
        signedParameters:params.signedParameters
    }).then(function(res){
        $(el).fadeOut();
        $(".result-message").addClass("success").text(res.data.message)
    },function(res){
        $(".result-message").text(res.errors[0].message)
        $(".result-message").removeClass("success");
    });

}