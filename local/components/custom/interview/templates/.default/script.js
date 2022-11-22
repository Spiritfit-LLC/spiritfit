$(document).ready(function(){
    $('<div class="b-interview__progress">\n' +
        '    <div class="b-interview__progress-success"></div>\n' +
        '</div>').appendTo('header');


    $(".b-question__answer-item.stars-item").mouseenter(function(){
        $(this).addClass("active").prevAll("").addClass("active");
    }).mouseleave(function(){
        $(".b-question__answer-item.stars-item").not(".selected").removeClass("active");
    }).click(function(){
        $(this).addClass("selected");
        $(this).nextAll(".selected").removeClass("selected").removeClass("active");
        $(this).prevAll().addClass("selected");
    });

    $(".star-item__text").click(function(){
        $(this).closest(".b-question__answer-item").find("input").click();
    });
    $("input[type=\"radio\"]").click(function(){
        var $question=$(this).closest(".b-interview__question");
        $question.find(".b-question__go-next").find(".button").removeClass("disabled");
        $question.find(".b-question__answer-item.select").removeClass("select");
        $(this).closest(".b-question__answer-item").addClass("select");
    });
    $("input.b-checkbox__input").on("change", function(){
        var $question=$(this).closest(".b-interview__question");
        if ($question.find("input.b-checkbox__input:checked").length>0){
            $question.find(".b-question__go-next").find(".button").removeClass("disabled");
        }
        else{
            $question.find(".b-question__go-next").find(".button").addClass("disabled");
        }
    });
    $("input[type=\"text\"]").keyup(function(){
        var $question=$(this).closest(".b-interview__question");
        if ($(this).val()!==undefined && $(this).val()!=='' ){
            $question.find(".b-question__go-next").find(".button").removeClass("disabled");
        }
        else{
            $question.find(".b-question__go-next").find(".button").addClass("disabled");
        }
    });

    $("input.radio-item, input.stars-item, input.checkbox-item").on("change", function(){
        var data_required=$(this).data("required");
        $(`[data-required-from-id="${data_required}"]`).addClass("dependent").removeClass("required").hide(200);
        $(`[data-required-from-id="${data_required}"]`).filter(`[data-required-from-val="${$(this).val()}"]`).removeClass("dependent").addClass("required");
    });

    var $form=$('.interview-form-box').find('form');
    $form.unbind();
    $form.submit(function(e){
        e.preventDefault();
        var componentName=$(this).data("componentname");
        var postData=new FormData(this);
        var action="interview";

        BX.ajax.runComponentAction(componentName, action, {
            mode: 'class',
            method:'POST',
            data:postData,
            signedParameters:params.signedParameters
        }).then(function(res){
            console.log(res);
            setTimeout(function(){
                $form.hide(300, function(){
                    var $target=$(res.data);
                    $target.appendTo(".interview-form-box");
                    $(".interview__promocode").fadeIn().addClass("animate__tada");
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $target.offset().top
                    }, 1000);
                });
            }, 500);
        },function(res){
            console.log(res)
        });
    });
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

var go_next_question=function(btn=null){
    if (btn!==null && $(btn).hasClass("disabled")){
        return;
    }
    else if(btn!==null){
        $(btn).closest('.b-interview__question').addClass("answered");
    }
    var target=$('.b-interview__question').not(".showing").not(".dependent").first();
    target.removeClass("is-hide");
    $([document.documentElement, document.body]).animate({
        scrollTop: target.offset().top
    }, 1000);
    target.addClass("showing");

    try{
        var progress=$('.b-interview__question.answered').not(".dependent").length/$('.b-interview__question').length;
        $(".b-interview__progress-success").css("width", progress*100+"%");
    }
    catch (e){ }

    if (btn!==null && $(btn).hasClass("disabled")){
        $(btn).fadeOut(300);
    }
}

var submit_form=function(btn){
    if (btn!==null && $(btn).hasClass("disabled")){
        return;
    }
    $(".b-interview__progress-success").css("width", "100%");
    var $form=$('.interview-form-box').find('form');
    $form.submit();
}