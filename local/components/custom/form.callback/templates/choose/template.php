<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--choose" <? if ($_REQUEST["web_form_submit"]): ?>style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__heading">Выберите действие</div>
        <div class="popup__btns-holder">
            <div class="popup__btns-holder_btn btn btn--orange js-popup-call">Заявка на звонок</div>
            <div class="popup__btns-holder_btn btn btn--orange js-popup-message">Отправить сообщение</div>
        </div>
    </div>
</div>