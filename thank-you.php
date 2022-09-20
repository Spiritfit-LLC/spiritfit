<?php
define('HIDE_SLIDER', true);
define('BREADCRUMB_H1_ABSOLUTE', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
<style>
    .thank-you-message {
        margin: 80px auto;
        display: block;
        width: max-content;
        text-transform: uppercase;
        font-size: 40px;
        color: #ff7628;
        font-weight: 500;
    }
    .b-screen:after{
        content:none;
    }
    @media screen and (max-width: 768px){
        .thank-you-message{
            margin: 0px auto;
            width: unset;
        }
    }
</style>
<div class="content-center">
    <div class="thank-you-message">
        Спасибо за ваш ответ!
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");