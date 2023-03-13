$(document).ready(function(){
    var $select=$("#pt-club-select");
    $select.select2(
        {
            width:"100%",
            placeholder:"",
            dropdownParent:$select.parent(),
            language:{
                noResults:function(){
                    return"Ничего не найдено"
                }
            },
            closeOnSelect: true,
        }
    );

    $(".pt-action-btn").click(function(){
        var $content=$(this).closest(".personal-training__content");
        var action=$(this).data("event");

        switch (action){
            case "edit":
                $content.find(".personal-training__exist").hide(0, function(){
                    $content.find(".pt__form").show();
                });
                break;
            case "go-back":
                $content.find(".pt__form").hide(0, function(){
                    $content.find(".personal-training__exist").show();
                });
                break;
        }
    });

    if ($(window).width()>590){
        var offset=8;
    }
    else if ($(window).width()>484){
        offset=7;
    }
    else{
        offset=6;
    }

    var current_visit_el_index=0;
    var $container=$('.pt__dates-container');


    function scrollDaysCount(index, animate_time=500){
        var element=$container.find(`.datetime-item.day[data-index="${index}"]`);
        $($container).stop().animate({scrollLeft:element.offset().left + $container.scrollLeft() - $container.offset().left}, animate_time);


        var month=$container.find(`.datetime-item.day[data-index="${index}"]`).data('month');
        if (month!==$container.find(`.datetime-item.day[data-index="${index+offset}"]`).data('month') && $container.find(`.datetime-item.day[data-index="${index+offset}"]`).length>0){
            month+='-'+$container.find(`.datetime-item.day[data-index="${index+offset}"]`).data('month');
        }
        $('.pt__date-month').text(month)
    }



    $('.pt-dates__controller').on("click", function(){
        if ($(this).hasClass('left')){
            var ind=current_visit_el_index-offset;
            if (ind<0){
                ind=0;
            }
        }
        else if ($(this).hasClass('right')){
            ind=current_visit_el_index+offset;
            if (ind>$container.find('.datetime-item.day').length-offset){
                ind=$container.find('.datetime-item.day').length-offset;
            }
        }
        scrollDaysCount(current_visit_el_index=ind);
    });


    function getTimeTable(){
        var $form=$("#pt__form");

        var $load_container=$form
            .find(".pt-load-container");

        $load_container.css("height", $load_container.height());

        $form
            .find(".personal-training__timetable")
            .fadeOut(300, function(){
                $form.find(".pt-ajax__loader").fadeIn(300).css("display", "flex");
            });


        var postData = new FormData($form.get(0));
        postData.set('template', personalTrainingTemplateName);
        postData.set('v', '3');

        BX.ajax.runComponentAction(personalTrainingComponent, 'getTimetable', {
            mode: 'class',
            data: postData,
            method:'POST',
        }).then(function(response){
            setTimeout(function(){
                $form.find(".pt-ajax__loader").fadeOut(300, function(){
                    $load_container.removeAttr("style");
                    $form
                        .find(".personal-training__timetable")
                        .html(response.data);
                    $(".pt_datetime-radiobtn.time").change(function(){
                        $form.find('input[type="submit"]').removeAttr("disabled");
                    });

                    $form
                        .find(".personal-training__timetable")
                        .fadeIn(300);
                });


            }, 300);

        }, function(response){
            $form.find('input[type="submit"]').removeAttr('disabled');

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            var $modal=$form.closest(".popup__modal");


            $modal.find(".popup__modal-info.error-text").html(message);
            $modal.find(".popup__modal-content").hide(200);
            $modal.find(".popup__modal-error").show(200);
        });
    }

    setTimeout(function(){
        scrollDaysCount(current_visit_el_index);
        getTimeTable();
    },500)

    $(".pt_datetime-radiobtn.day").change(function(){
        scrollDaysCount(current_visit_el_index, 0);
        getTimeTable();
    });
    $(".radio-pt-type").change(function(){
        getTimeTable();
    });
    $select.change(function(){
        getTimeTable();
    });

    $("#pt__form").submit(function(e){
        e.preventDefault();

        var $form=$(this);

        $form.find('input[type="submit"]').prop('disabled', 'disabled');

        var postData = new FormData($form.get(0));
        BX.ajax.runComponentAction(personalTrainingComponent, 'setSlot', {
            mode: 'class',
            data: postData,
            method:'POST',
        }).then(function(response){
            if (response.data.dataLayer!==undefined){
                var category='UX';
                if (response.data.dataLayer.eCategory!==undefined){
                    category=response.data.dataLayer.eCategory;
                }
                try{
                    dataLayerSend(category, response.data.dataLayer.eAction, response.data.dataLayer.eLabel)
                }
                catch (e) {
                    console.log(e);
                }
            }

            if (response.data.reload===true){
                if (window.location.search!==''){
                    var search = window.location.search + '&pds=training';
                }
                else{
                    search = '?pds=training';
                }
                window.location.search = search;
            }
        }, function(response){
            $form.find('input[type="submit"]').removeAttr('disabled');

            var error_id=0;
            response.errors.forEach(function(err, index){
                if (err.code!==0){
                    error_id=index
                    return false;
                }
            });

            var message=response.errors[error_id].message;
            var code=response.errors[error_id].code;

            var $modal=$form.closest(".popup__modal");


            $modal.find(".popup__modal-info.error-text").html(message);
            $modal.find(".popup__modal-content").hide(200);
            $modal.find(".popup__modal-error").show(200);
        })
    });
})