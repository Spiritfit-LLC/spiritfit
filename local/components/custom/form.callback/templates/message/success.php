<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--success" style="display: block;">
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__success">Сообщение отправлено</div>
    </div>
</div>
<script>$('.popup.popup--message').fadeOut(300, function() {
            $('.popup.popup--message').remove();
        });</script>
<? if($_REQUEST['form_text_22'] == 'Корпоративный фитнес'){ ?>
	<script>dataLayerSend('conversion', 'sendFormWriteUs', 'corpoFitness')</script>
<? } ?>