jQuery(function($) {
    $(document).ready(function() {
        $('.select-prize').unbind();
        $('.select-prize').click(function(e) {
            e.preventDefault();

            let id = parseInt($(this).data('id'));
            let cid = $(this).data('cid');

            BX.ajax.runComponentAction(quizPrizeComponentName, 'selectPrize', {
                mode: 'class',
                data: {elementId: id, componentId: cid},
                method:'POST'
            }).then(function (response) {
                if( response.data ) {
                    $('.select-prize-wrapper').addClass('success');
                    $('.select-prize-wrapper').text(quizPrizeComponentSuccessMsg);
                } else {
                    $('.select-prize-wrapper').text(quizPrizeComponentExistMsg);
                }
            }, function (response) {
                $('.select-prize__error').text(response.errors[0].message);
            });
        });
    });
});