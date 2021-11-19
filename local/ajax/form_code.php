
    <form class="subscription__aside-form" name="" action="/abonement/ezhemesyachnyy-abonement-po-podpiske/?ELEMENT_CODE=ezhemesyachnyy-abonement-po-podpiske" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="result_not_add" value="1" />
        <input type="hidden" name="form_text_44" id="form_text_44" value="<?=htmlspecialchars($_REQUEST['form_text_44'])?>" />
        <input type="hidden" name="form_text_45" id="form_text_45" value="<?=htmlspecialchars($_REQUEST['form_text_45'])?>" />
        <input type="hidden" name="form_text_46" id="form_text_46" value="<?=htmlspecialchars($_REQUEST['form_text_46'])?>" />
        <input type="hidden" name="form_text_47" id="form_text_47" value="<?=htmlspecialchars($_REQUEST['form_text_47'])?>" />
        <input type="hidden" name="form_text_48" id="form_text_48" value="<?=htmlspecialchars($_REQUEST['form_text_48'])?>" />
        <input type="hidden" name="form_text_49" id="form_text_49" value="<?=htmlspecialchars($_REQUEST['form_text_49'])?>" />
        <input type="hidden" name="step" value="2" />
        <input type="hidden" name="sub_id" value="<?=htmlspecialchars($_REQUEST['sub_id'])?>" />
        <input type="hidden" name="two_month" value="<?=htmlspecialchars($_REQUEST['two_month'])?>" />
        <input type="hidden" name="form_text_5" value="<?=htmlspecialchars($_REQUEST['form_text_5'])?>" />
        <input type="hidden" name="form_text_6" value="<?=htmlspecialchars($_REQUEST['form_text_6'])?>" />
        <input type="hidden" name="form_text_7" value="<?=htmlspecialchars($_REQUEST['form_text_7'])?>" />
        <input type="hidden" name="form_text_8" value="<?=htmlspecialchars($_REQUEST['form_text_8'])?>" />
        <input type="hidden" name="form_email_9" value="<?=htmlspecialchars($_REQUEST['form_email_9'])?>" />
        
        <input type="hidden" name="promo" value="" />
        <input type="hidden" name="form_checkbox_personal" value="Array" />
        <input type="hidden" name="form_checkbox_rules" value="Array" />
        <input type="hidden" name="form_checkbox_privacy" value="Array" />
        <input type="hidden" name="form_hidden_10" value="<?=htmlspecialchars($_REQUEST['form_hidden_10'])?>" />
        <input type="hidden" name="form_checkbox_legal-information" value="on" />
        <div class="subscription__sent">
            <div class="subscription__sent-text">Код отправлен на номер</div>
            <div class="subscription__sent-tel"><?=htmlspecialchars($_REQUEST['form_text_8'])?></div>
        </div>
        <div class="subscription__aside-form-row subscription__aside-form-row--code">
            <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[0]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required" />
            <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[1]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required" />
            <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[2]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required" />
            <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[3]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required" />
            <input class="input input--num input--light" type="text" maxlength="1" inputmode="numeric" name="num[4]" placeholder="0" min="0" max="9" pattern="[0-9]" required="required" />
        </div>
        <a class="subscription__code" href="#">Получить код повторно</a>
        <div class="subscription__bottom">
            <div class="subscription__total">
                <div class="subscription__total-text">Итого к оплате</div>
                <div class="subscription__total-value">
                    <div class="subscription__total-value-old"><span><?=$_REQUEST['old_price']?> ₽</span></div>
                    <?=$_REQUEST['form_hidden_10']?> ₽
                </div>
                <div class="subscription__total-subtext">Предложение действует до <?=date('d.m.Y', time() + 950400)?></div>
            </div>
            <div class="subscription__total-btn subscription__total-btn--form btn btn--white js-check-code" data-stage="2">Купить</div>
        </div>
    </form>