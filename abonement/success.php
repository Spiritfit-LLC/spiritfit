<?php

define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
define('SITE_TEMPLATE_ID', 'spiritfit-v3');

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>

<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH.'/css/service.css'?>"/>
<style>
    .order-success.text-center {
        font-family: "Gotham Pro", sans-serif;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
</style>
<div class="content-center">
    <div class="order-success text-center">
        Оплата прошла успешно. Можете закрыть это окно.
    </div>
</div>

