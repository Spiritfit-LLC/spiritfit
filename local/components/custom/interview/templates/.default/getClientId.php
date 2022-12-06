<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", $arResult["TITLE"]);
?>

<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters(), 'componentName'=>$this->getComponent()->GetName()])?>;
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
    .form-message {
        font-size: 14px;
        line-height: 16px;
        margin-top: 10px;
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
            <div class="form-message"></div>
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

            var $form=$(this);
            var $step=$form.find('input[name="step"]');
            var $phone=$form.find('input[name="client-phone"]');
            var $code=$form.find("input[name=\"client-code\"]");
            var $message=$form.find(".form-message");

            $message
                .removeClass("message")
                .removeClass("error")
                .fadeOut();

            var postData={
                step:$step.val(),
                phone:$phone.val()
            }

            if ($step.val()==2){
                postData['code']=$code.val();
            }

            BX.ajax.runComponentAction(params.componentName, 'auth', {
                mode:'class',
                method:'POST',
                data:postData,
            }).then(function (response){

                if (response.data.reload!==undefined && response.data.reload===true){
                    window.location.href=window.location.pathname;
                    return;
                }

                if (response.data.next_step!==undefined){
                    $step.val(response.data.next_step);
                }
                if (response.data.code!==undefined && response.data.code===true){
                    $('.sms-code').show(300);
                }
                else{
                    $('.sms-code').hide(300);
                }

                if (response.data.message!==undefined){
                    $message
                        .html(response.data.message)
                        .addClass("message")
                        .fadeIn();
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

                $message
                    .html(message)
                    .addClass("error")
                    .fadeIn();

                if (code===4){
                    $step.val(1);
                    $('.sms-code').hide(300);
                    $code.val('');
                }
            });

        });
    });

</script>