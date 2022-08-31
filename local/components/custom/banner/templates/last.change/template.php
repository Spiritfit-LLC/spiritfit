<div id="<?=$arResult["BANNER_ID"]?>" class="is-hide last-change-banner" data-componentname="<?=$arResult['COMPONENT_NAME']?>">
    <?if (!empty($arResult["BACKGROUND"])):?>
        <div class="last-change__background" style="background-image: <?=$arResult["BACKGROUND"]?>"></div>
    <?endif;?>
    <div class="last-change-banner__content">
        <div class="banner__header-text">Нашли дешевле?</div>
        <div class="banner__body-text">Оставьте заявку,<br>нам есть что предложить</div>
        <form>
            <input name="type" value="<?=$arResult["FORM_TYPE"]?>" type="hidden"/>
            <div class="last-change__input-block">
                <span class="last-change__placeholder">Телефон</span>
                <input name="phone" class="last-change__input" type="tel" required>
            </div>
            <input type="submit" value="Отправить" class="last-change__submit">
            <div class="subscription__aside-form-row last-change__checkbox">
                <label class="input-label">
                    <input class="input input--checkbox"
                           type="checkbox"
                           name="privacy[]"
                           value="1"
                           required>
                    <div class="input-label__text">Я даю свое согласие на обработку персональных данных и принимаю условия
                        <a href="https://spiritfit.ru/upload/form/%D0%9F%D0%BE%D0%BB%D0%B8%D1%82%D0%B8%D0%BA%D0%B0_%D0%BA%D0%BE%D0%BD%D1%84%D0%B8%D0%B4%D0%B5%D0%BD%D1%86%D0%B8%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8.pdf">Пользовательского соглашения</a></div>
                </label>
            </div>
            <span class="error-message"></span>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('div#<?=$arResult["BANNER_ID"]?>').find('[type="tel"]').inputmask({
            'mask': '+7 (999) 999-99-99',
        });

        var banner_modal=new ModalWindow('', $('div#<?=$arResult["BANNER_ID"]?>').get(0), AnimationsTypes["fadeIn"], true, false, 'last-change');
        $(banner_modal.content).removeClass('is-hide');
        $(document).mouseleave(function(e){
            if (getCookie('last_change')===undefined && e.clientY < 10){
                banner_modal.show();
                var date = new Date;
                date.setDate(date.getDate() + 1);
                document.cookie = "last_change=1; path=/; expires=" + date.toUTCString();
            }
        });

        $('#<?=$arResult["BANNER_ID"]?>').find('form').submit(function(e){
            e.preventDefault();

            var $form=$(this);
            var postData=new FormData(this);

            $form.find('.error-message').html('');

            BX.ajax.runComponentAction($('div#<?=$arResult["BANNER_ID"]?>').data('componentname'), 'request', {
                mode: 'class',
                data: postData,
                method:'POST'
            }).then(function (response) {
                banner_modal.close();
            }, function (response) {
                var error_id=0;
                response.errors.forEach(function(err, index){
                    if (err.code!==0){
                        error_id=index
                        return false;
                    }
                });
                var message=response.errors[error_id].message;
                var code=response.errors[error_id].code;
                $form.find('.error-message').html(message);
            });
        })
    });
</script>
