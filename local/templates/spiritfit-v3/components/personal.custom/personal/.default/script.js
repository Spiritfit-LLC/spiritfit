$(document).ready(function(){
    $('input[type="tel"]').each(function(){
        $(this).inputmask({
            'mask': '(999) 999-99-99',
            placeholder: "_",
        });
    });

    tippy('.personal-field-clue', {
        content: '<div class="clue-loader__container" style="color:white"><span class="loader-circle"></span></div>',
        allowHTML:true,
        placement: 'top',
        arrow: false,
        interactive: true,
        duration:[300, 1000],
        onShow(instance) {
            var clue_code=$(instance.reference).data("clue");

            BX.ajax.runComponentAction(personalComponentName, "getClue", {
                mode: 'class',
                data: {
                    clue_code:clue_code
                },
                method:'POST',
            }).then(function (response){
                if (response.data!==false){
                    instance.setContent(response.data);
                }
                else{
                    instance.setContent("Не удалось загрузить данные.")
                }
            }, function(response){
                instance.setContent("Не удалось загрузить данные.")
            });
        },
        onCreate(instance) {
            instance._isFetching = false;
            instance._data = null;
            instance._error = null;
        },
    });


    $("#update").click(UpdatePersonalInfo);



    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const pds = urlParams.get('pds');

    if (pds!==undefined){
        setTimeout(function(){
            $("#"+pds).click();
        }, 1000);

    }
});

function UpdatePersonalInfo(reload = true){
    BX.ajax.runComponentAction(personalComponentName, "update", {
        mode: 'class',
        method:'GET',
    }).then(function(){
        if (reload){
            location.reload();
        }
    });
}