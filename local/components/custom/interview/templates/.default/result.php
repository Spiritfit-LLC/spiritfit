<div class="interview__result-container">
    <div class="interview__result">
        Спасибо за участие в опросе!
    </div>
    <?if (!empty($arResult["PROMOCODE"])):?>
        <span class="interview__result-promocode">Вот вам промокодик!</span>
    <?endif;?>
    <?if (!empty($arResult["PROMOCODE"])):?>
        <div class="interview__promocode is-hide" onclick="copyPromocode('<?=$arResult["PROMOCODE"]?>')"><?=$arResult["PROMOCODE"]?></div>
    <?endif;?>
</div>
