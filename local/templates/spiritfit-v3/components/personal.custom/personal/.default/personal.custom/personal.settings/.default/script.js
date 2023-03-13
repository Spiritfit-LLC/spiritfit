$(document).ready(function(){
    $('[data-toggle="datepicker"]').each(function(){
        $(this).inputmask({
            'mask': '99.99.9999',
        });

        var minDate=$(this).data('min');
        var maxDate=$(this).data('max');

        var minAge=$(this).data('minage');
        var maxAge=$(this).data('maxage');

        if (typeof minAge !== typeof undefined && maxAge !== false) {
            var today = new Date();
            maxDate = today.setFullYear(today.getFullYear()-minAge);
            minDate=today.setFullYear(today.getFullYear()-maxAge);
        }

        function setMinMax(dateattr){
            if (dateattr==='today'){
                return new Date();
            }
            else if (dateattr==='week'){
                var date = new Date();
                date.setDate(testDate.getDate() + 7);
                return date;
            }
            else if (dateattr==='month'){
                date = new Date();
                date.setMonth(date.getMonth() + 1);
                return date;
            }
            else if (dateattr==='year'){
                date = new Date();
                date.setFullYear(date.getFullYear() + 1);
                return date;
            }
            else{
                return dateattr;
            }
        }

        $(this).datepicker({
            language:'ru-RU',
            format: 'dd.mm.yyyy',
            autoHide:true,
            startDate: setMinMax(minDate),
            endDate: setMinMax(maxDate),
        }).change(function(){
            const [day, month, year] = $(this).val().split('.');
            const date = new Date(+year, +month - 1, +day)
            var today=new Date();

            if (date>=today){
                $(this).datepicker('setDate', new Date(maxDate));
                return;
            }

            var diff=Math.floor((new Date() - date)/ (1000*3600*24*365));

            console.log(diff)
            if (diff<14){
                $(this).datepicker('setDate', new Date(maxDate));
            }

            if (diff<18){
                $(".personal-input__form-row.parental_consent").show(300, function(){
                    var $preview_item=$('.personal-media_preview__item.exist');
                    $preview_item.height($preview_item.width());
                });

            }
            else{
                $(".personal-input__form-row.parental_consent").hide(300);
            }
        });

        var $preview_item=$('.personal-media_preview__item.exist');
        $preview_item.height($preview_item.width());
    });

    $("#personal-settings__form").submit(function(e){
        e.preventDefault();

        $(".personal-settings__form-message").html("");
        $(this).find(".field-error").html("");

        var post_data=new FormData(this);
        BX.ajax.runComponentAction(personalSettingsComponent, 'save', {
            mode:'class',
            data:post_data,
            method:'POST',
        }).then(function(response){
            if (response.data.error_fields!==undefined){
                response.data.error_fields.forEach(function(el){
                    var $this=$(`#${el.field}`);
                    if ($this.next('.field-error').length > 0) {
                        $this.next('.field-error').text(el.message)
                    } else {
                        $this.after(`<span class="field-error">${el.message}</span>`);
                    }
                });
            }

            if (response.data.message!==undefined){
                $(".personal-settings__form-message").html(response.data.message);
            }
        }, function (response){
            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            $(".personal-settings__form-message").html(message);
        });
    });
});

var pass_show=function(t, input){
    $(`#${input}`).attr('type', 'text');
    $(t).removeClass("active");

    $(t)
        .next(".pass-hide")
        .addClass("active");

}

var pass_hide=function(t, input){
    $(`#${input}`).attr('type', 'password');
    $(t).removeClass("active");

    $(t)
        .prev(".pass-show")
        .addClass("active");
}

var personal_file_input=function(files, preview_block_id){
    const file = files[0];
    if (!file) return;

    var file_type=file.type;
    var $preview=$(`#${preview_block_id}_media-preview`);
    var $dropzone=$(`#${preview_block_id}_dropzone`);

    $preview.find(".field-error").remove();

    if (!file_type.includes("image")){
        $('<span className="field-error">Не удается обработать документ. Загрузите изображение.</span>').appendTo($preview);
        return;
    }


    $(`#${preview_block_id}_preview_item`).css("background-image",`url('${URL.createObjectURL(file)}')`);

    $dropzone.hide();
    $preview.show();

    var $preview_item=$(`#${preview_block_id}_preview_item`);
    $preview_item.width('calc(100% - 0px)');
    var preview_width=$preview_item.width();
    $preview_item.height(preview_width);
}

var reset_file_input=function(id){
    $(`#${id}_preview_item`)
        .css("background-image",`none`);

    $(`#${id}_media-preview`).hide();

    $(`#${id}`).val('');
    $(`#${id}_dropzone`)
        .show()
        .css("display", "flex");
}