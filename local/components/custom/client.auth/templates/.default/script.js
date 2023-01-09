var authAction=function(){
    BX.ajax.runComponentAction(params.componentName, 'auth', {
        mode:'class',
        signedParameters:params.signedParameters,
    }).then(function(response){

        if (response.data.result){
            $("#user-message").html(response.data.message)

            setTimeout(function(){
                window.location.href='/';
            }, 3000);

        }
    }, function(response){

        var error_id=0;
        response.errors.forEach(function(err, index){
            if (err.code!==0){
                error_id=index
                return false;
            }
        });
        var message=response.errors[error_id].message;
        var code=response.errors[error_id].code;

        $("#user-message").html(message)
    });
}