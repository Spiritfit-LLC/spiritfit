$(document).ready(function(){
    $('.b-faq__tab-link').click(function(){
        dataLayerSend('UX', 'clickFAQButtons', $(this).text());
    });
})