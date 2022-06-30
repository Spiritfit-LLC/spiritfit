$(document).ready(function(){

    // функция скачивания изображения
    function download_img(url){
        var link = document.createElement('a');
        link.target = "_blank";
        link.download = "img.jpg";
        link.href = url;
        link.click();
    }

    const instance = tippy(document.querySelector('a[href="#get-card-qr"]'));
    instance.setProps({
        interactive: true,
        trigger: 'manual',
        allowHTML: true,
        maxWidth:700,
        appendTo: () => document.querySelector('body'),
        onHide:()=>{
            $('.tippy-background').removeClass('active');
            $('.card-qr-code-form').unbind();
        },
        onMount: ()=>{
            $('.tippy-background').addClass('active');
        },
        onShown:(instance)=>{
            $('.card-qr-code-form').unbind();
            $('.card-qr-code-form').submit(function(e){
                e.preventDefault();
                var url=$('img.qr-code-img').attr('src');
                download_img(url);
            });
        }
    });


    $('a[href="#get-card-qr"]').click(function(e){
        e.preventDefault();

        var loading_content='<div class="escapingBallG-animation active tippy-form loader">' +
            '<div id="escapingBall_1" class="escapingBallG"></div>' +
            '</div>';

        instance.setContent(loading_content);
        instance.show();



        var user_id=$(this).data('value');
        var page_url=window.location.protocol+'//'+window.location.hostname

        $.ajax({
            url:'/local/ajax/get-qr.php',
            data:{
                data:page_url+'/personalcard/?ID='+user_id,
                logo:true
            },
            method:'POST',
            success:function(response){

                var html_content='<form class="card-qr-code-form tooltip-form">' +
                    '<div class="tooltip-form-title">Используйте данный QR-Code в качестве вашей визитки</div>' +
                    '<div class="tooltip-form-body-text">' +
                    `<img class="qr-code-img" src="${response['url']}">` +
                    '</div>' +
                    '<input type="submit" class="tooltip-form-submit" value="скачать">' +
                    '<span class="form-submit-result-text"></span>' +
                    '</form>';

                instance.hide()
                instance.setContent(html_content);
                instance.show();
            }
        })
    });
});