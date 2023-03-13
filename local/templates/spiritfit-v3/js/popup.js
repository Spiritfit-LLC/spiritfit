$(document).ready(function(){
    $(".clue-detail-show").click(function(e){
        var $modal = $(this).closest(".popup__modal");
        var $this = $(this);
        var $info_text = $modal.find(".popup__modal-info-text");

        if ($this.hasClass("showed")){
            $this.text("подробнее");
            $info_text
                .scrollTop(0)
                .css({
                    "max-height":"20px",
                    "text-overflow": "ellipsis",
                    "overflow":"hidden",
                    "white-space": "nowrap"
                });

            $(".popup__modal-main.hidden")
                .removeClass("hidden")
                .show();
        }
        else{
            BX.ajax.runComponentAction("personal.custom:personal", "getClue", {
                mode: 'class',
                data: {
                    clue_code:$(this).data("code")
                },
                method:'POST',
            }).then(function (response){
                console.log(response);

                $this.text("закрыть");
                $(".popup__modal-main:visible")
                    .addClass("hidden")
                    .hide();

                $info_text
                    .html(response.data)
                    .css({
                        "max-height":"55vh",
                        "overflow":"auto",
                        "white-space": "unset",
                        "text-overflow": "unset",
                    });

            });
        }


        $(this).toggleClass("showed");
    });
})