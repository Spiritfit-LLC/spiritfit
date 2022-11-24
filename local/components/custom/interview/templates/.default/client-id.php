<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", $arResult["TITLE"]);
?>

<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
</script>
<style>
    .interview-get-client-id__container {
        width: 100%;
        height: calc(100vh - 143px - 133px - 110px);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .interview-client-id__content {
        width: 600px;
        background: white;
        padding: 30px;
        border-radius: 10px;
    }
    input.button.client-id-form-submit {
        width: 100%;
        margin-top: 30px;
    }
    .question-item {
        margin: 50px 0 0;
    }
    #get-client-id h3.b-question__title {
        font-size: 30px;
        margin-bottom: 20px;
    }
    .client-id__error {
        margin: 10px 0;
        font-size: 16px;
        color: red;
    }
    @media screen and (max-width: 768px) {
        .interview-get-client-id__container {
            height: max-content;
        }
    }
</style>

<div class="interview-get-client-id__container">
    <div class="interview-client-id__content">
        <form id="get-client-id">
            <input type="hidden" value="1" name="step">
            <div class="question-item">
                <h3 class="b-question__title">Введите номер телефона</h3>
                <div class="b-question__answers text-item_question">
                    <div class="b-question__answer-item text-item">
                        <input type="text"
                               name="client-phone"
                               class="text-item" required/>
                    </div>
                </div>
            </div>
            <div class="question-item sms-code is-hide">
                <h3 class="b-question__title">КОД ИЗ СМС</h3>
                <div class="b-question__answers text-item_question">
                    <div class="b-question__answer-item text-item">
                        <input type="text"
                               name="client-code"
                               class="text-item"/>
                    </div>
                </div>
            </div>
            <div class="client-id__error">

            </div>
            <input type="submit" class="button client-id-form-submit" value="Авторизация">
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("input[name=\"client-phone\"]").inputmask({
            mask:"+7 (999) 999-99-99"
        });
        $("input[name=\"client-code\"]").inputmask({
            mask:"9 9 9 9 9",
            placeholder: '*'
        });

        $("#get-client-id").submit(function(e){
            e.preventDefault();

            var form=$(this);
            var step=form.find('input[name="step"]');
            var phone=form.find('input[name="client-phone"]').val();
            var code=form.find("input[name=\"client-code\"]").val();

            form.find('.client-id__error').text('');

            BX.ajax.runComponentAction('<?=$this->getComponent()->getName()?>', 'auth', {
                mode: 'class',
                method:'POST',
                data:{
                    step:step.val(),
                    phone:phone,
                    code:code
                },
                signedParameters:params.signedParameters
            }).then(function(response){

                if (step.val()==1){
                    form.find(".sms-code").show(300);
                    $("input[name=\"client-code\"]").prop('required', 'required');
                    step.val(2);
                    $(".client-id-form-submit").val("Подтвердить");
                }
                else if (step.val()==2){
                    window.location.reload();
                }

            }, function(response){
                var error_id=0;
                response.errors.forEach(function(err, index){
                    if (err.code!==0){
                        error_id=index
                        return false;
                    }
                });
                var message=response.errors[error_id].message;
                var code=response.errors[error_id].code;
                form.find('.client-id__error').text(message);

                if (code==2){
                    form.find(".sms-code").hide(300);
                    $("input[name=\"client-code\"]").removeAttr('required');
                    $("input[name=\"client-code\"]").val('');
                    step.val(1);
                    $(".client-id-form-submit").val("Авторизация");
                }
            })
        })
    });

</script>