<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<script>
    $('<?=$arParams["TRIGGER"]?>').on('<?=$arParams["TRIGGER_TYPE"]?>', function(){
        var $modal = $('#modal_<?=$arParams["AJAX_OPTION_ADDITIONAL"]?>');
        if ($modal.hasClass("loaded")){
            $modal.find(".popup__modal-info.error-text").html('');
            $modal.find(".popup__modal-content").show();
            $modal.find(".popup__modal-error").hide();

            $modal
                .fadeIn(300)
                .css("display", "flex");
        }
        else{
            var data={
                v: '3',
                signed: <?=CUtil::PhpToJSObject($arResult["SIGNED_PARAMETERS"])?>
            }
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            urlParams.forEach(function(val, key){
                if (!data.hasOwnProperty(key)){
                    data[key]=val;
                }
            });

            $.ajax({
                url:<?=CUtil::PhpToJSObject($this->getComponent()->getPath()."/class.php")?>,
                method:'POST',
                data:data,
                async:true,
                success:function(response){
                    let processed = BX.processHTML(response);

                    $modal
                        .find(".popup__modal-content")
                        .html(processed.HTML);


                    BX.ajax.processScripts(processed.SCRIPT);

                    $("#modal_<?=$arParams["AJAX_OPTION_ADDITIONAL"]?>")
                        .fadeIn(300)
                        .css("display", "flex");

                    $('#modal_<?=$arParams["AJAX_OPTION_ADDITIONAL"]?>').addClass("loaded");

                },
                error: function(response){
                    $modal.find(".popup__modal-info.error-text").html('');
                    $modal.find(".popup__modal-content").hide(200);
                    $modal.find(".popup__modal-error").show(200, function(){
                        $modal.fadeIn(300).css("display", "flex");
                    });
                }
            });
        }
    })
</script>

<div class="popup-modal__container" id="modal_<?=$arParams["AJAX_OPTION_ADDITIONAL"]?>">
    <div class="popup__modal">
        <div class="modal__closer" onclick="$('#modal_<?=$arParams["AJAX_OPTION_ADDITIONAL"]?>').fadeOut(300)">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
        </div>
        <div class="popup__modal-content"></div>
        <div class="popup__modal-error" style="display: none">
            <div class="popup__modal-title">
                <div class="popup__modal-title-icon">
                    <img src="<?=SITE_TEMPLATE_PATH . '/img/icons/warning-mark.svg'?>">
                </div>
                <span>Что-то не так</span>
            </div>
            <div class="popup__modal-info error-text">
                Мы скоро все исправим!
            </div>
        </div>
    </div>
</div>