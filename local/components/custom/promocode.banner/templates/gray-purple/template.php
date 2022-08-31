<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="promocode-gp-banner__container">
    <div class="promocode-gp-banner" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/banner-background-gray.png'?>')">
        <div class="promocode-gp-banner__closer">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
        </div>
        <div class="promocode-gp-banner__content">
            <div class="promocode-gp-content__header">
                <div class="promocode-gp-content__logo" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/logo-purple-cursive.png'?>')"></div>
            </div>
            <div class="promocode-gp-content__body">
                <div class="promocode-gp-content__text">
                    Ловите<br>промокод на скидку
                </div>
                <div class="promocode-gp-content__code"><?=$arResult["PROMOCODE"]?></div>
                <div class="promocode-gp-content__discount">
                    <div class="promocode-gp-content__discount-value"><?=$arResult["BANNER_DISCOUNT"]?></div>
                    <div class="promocode-gp-content__discount-shadow"><?=$arResult["BANNER_DISCOUNT"]?></div>
                </div>
                <div class="promocode-gp-content__text2">на любой абонемент</div>
                <button class="promocode-gp-btn">Скопировать промокод</button>
            </div>
        </div>
    </div>
</div>
<script>
    var bannerTime=<?=CUtil::PhpToJSObject($arResult['BANNER_TIME'])?>;
    var bannerPromocode=<?=CUtil::PhpToJSObject($arResult['PROMOCODE'])?>;
    var bannerPromocodePage=<?=empty($arResult["PAGE"])?CUtil::PhpToJSObject(""):CUtil::PhpToJSObject($arResult["PAGE"])?>
</script>
