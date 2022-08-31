$(document).ready(function(){
    $('a[href="#promisedpayment-appeal"]').click(function(e){
        e.preventDefault();

        var answr=confirm();
        if (!answr)
            return;

        BX.ajax.runComponentAction(componentName, 'promisedPayment', {
            mode: 'class',
            data: {
                'action':'appeal'
            },
            method:'POST',
        }).then(function(response){
            // console.log(response)

            if (response.data['reload']===true){
                setTimeout(function(){
                    if (response.data.section!==undefined){
                        window.location.search='?SECTION='+response.data.section;
                    }
                    else{
                        window.location.reload();
                    }
                }, 500);

            }
        }, function(response){
            console.log(response)
        })
    });

    $('a[href="#promisedpayment"]').click(function(e){
        e.preventDefault();

        var answr=confirm();
        if (!answr)
            return;

        BX.ajax.runComponentAction(componentName, 'promisedPayment', {
            mode: 'class',
            data: {
                'action':'promisedpayment'
            },
            method:'POST',
        }).then(function(response){
            // console.log(response)

            if (response.data['reload']===true){
                setTimeout(function(){
                    if (response.data.section!==undefined){
                        window.location.search='?SECTION='+response.data.section;
                    }
                    else{
                        window.location.reload();
                    }
                }, 500);

            }

        }, function(response){
            console.log(response)
        })
    });
});