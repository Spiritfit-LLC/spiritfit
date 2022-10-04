$(document).ready(function(){
    if ($('.personal-section[data-code="trialworkout_zapis"]').length>0){
        var tw_form=$('.personal-section[data-code="trialworkout_zapis"]').children('form');
    }
    else if($('.personal-section[data-code="change_tw"]').length>0){
        tw_form=$('.personal-section[data-code="change_tw"]').children('form');
    }
    tw_form.unbind();

    $('a[href="#tw_cancel"]').click(function(e){
        e.preventDefault();

        var accept = confirm("Внимание, тренировка будет отменена. Обратить действие и записаться на новую тренировку будет невозможно!");
        if (!accept){
            return;
        }

        var postData={
            "action":"cancel"
        }

        BX.ajax.runComponentAction("custom:personal.trialworkout", 'setSlot', {
            mode: 'class',
            data: postData,
            method:'POST',
        }).then(function(response){
            var res_data=response.data;
            if (res_data['reload']===true){
                if (res_data.section!==undefined){
                    window.location.search='?SECTION='+res_data.section;
                }
                else{
                    window.location = window.location.pathname;
                }
            }

        })
    });

    $('a[href="#tw_accept"]').click(function(e){
        e.preventDefault();

        var postData={
            "tw_action":"accept"
        }

        BX.ajax.runComponentAction("custom:personal.trialworkout", 'setSlot', {
            mode: 'class',
            data: postData,
            method:'POST',
        }).then(function(response){
            var res_data=response.data;
            if (res_data['reload']===true){
                if (res_data.section!==undefined){
                    window.location.search='?SECTION='+res_data.section;
                }
                else{
                    window.location = window.location.pathname;
                }
            }

        })
    });
})