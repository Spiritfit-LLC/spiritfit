$(document).ready(function(){
    $('button.personalcard-savebtn').click(function(){
        BX.ajax.runComponentAction(componentName, 'getVCard', {
            mode:'class',
            method:'POST',
            data:{
                'USER-ID':UserID
            }
        }).then(function(response){
            var file = new Blob([response.data.file_content]);
            var link = document.createElement('a'),
                filename = response.data.filename;

            link.href = URL.createObjectURL(file);
            link.download = filename;
            link.click();
        });
    });
});