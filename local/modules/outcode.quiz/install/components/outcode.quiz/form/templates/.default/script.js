jQuery(function($) {
    $(document).ready(function() {
        $('.show-more').unbind();
        $('.show-more').click(function(e) {
            e.preventDefault();
            let wrapper = $(this).parents('.results-table');
            $(wrapper).find('.results-table__row').removeClass('hidden');
            $(this).hide();
        });
        $('.show-more').each(function() {
            let wrapper = $(this).parents('.results-table');
            if( $(wrapper).find('.results-table__row.hidden').length == 0 ) $(this).hide();
        });

        $('.question-form').unbind()
        $('.question-form').submit(function(e) {
            e.preventDefault();

            var form = new FormData( $(this)[0] );
            var formObj = $(this);

            $(formObj).find('.question-form__error').text('');

            BX.ajax.runComponentAction(quizComponentName, 'sendAnswer', {
                mode: 'class',
                data: form,
                method:'POST'
            }).then(function (response) {
                if( response.data.error != '' ) {
                    $(formObj).find('.question-form__error').text(response.data.error);
                } else {
                    if( response.data.result.ID == 0 ) {
                        $(formObj).html('<div class="success">'+quizComponentExistMsg+'</div>');
                    } else {
                        $(formObj).html('<div class="success">'+quizComponentSuccessMsg+'</div>');
                    }

                    BX.ajax.runComponentAction("custom:personal", 'quiz', {
                        mode: 'class',
                        data: {
                            'type':22
                        },
                        method:'POST'
                    });
                }
            }, function (response) {
                $(formObj).find('.question-form__error').text(response.errors[0].message);
            });

            return false;
        });
    });
});