
<div class="subscription__stage">
    <div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Регистрация</div>
    <div class="subscription__stage-item subscription__stage-item--done" data-stage="2">2. Оформление</div>
    <div class="subscription__stage-item subscription__stage-item--done" data-stage="3">3. Оплата</div>
</div>
<div class="subscription__ready" style="display: block;">
    <div class="subscription__title">Абонемент готов</div>
    <div class="subscription__desc"><?=$arResult["ELEMENT"]["~DETAIL_TEXT"] ?></div>
    <div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
        <div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
    </div>
</div>

