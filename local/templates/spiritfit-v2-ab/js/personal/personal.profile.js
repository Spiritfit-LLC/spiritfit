$(document).ready(function(){
    $('a[href="#delete-my-personal"]').click(function(e){
        e.preventDefault();

        var answer=confirm("Вы подтверждаете свой запрос на удаление аккаунта и персональной информации?");
        if (answer){
            BX.ajax.runComponentAction(componentName, 'deletePersonal', {
                mode:'class',
                method:'POST',
            }).then(function(response){
                if (response.data['reload']===true){
                    setTimeout(function(){
                        if (response.data.section!==undefined){
                            window.location.search='?SECTION='+response.data.section;
                        }
                        else{
                            window.location = window.location.pathname;
                        }
                    }, 500);
                }
            }, function(response){

            });
        }
    });
})