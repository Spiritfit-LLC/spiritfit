<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div class="promocode-banner__container">
    <div class="promocode-banner" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/cube-background_fitsummer.png'?>')">
        <div class="promocode-banner__closer">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
        </div>
        <div class="promocode-banner__content">
            <div class="promocode-content__header">
                <div class="promocode-content__logo">
                    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/cube-logo_gradient.svg')?>
                </div>
                <div class="promocode-content__text" style="position: absolute;bottom: 50%;margin-bottom: -65px;">
                    Ловите<br>промокод<br>на скидку!
                </div>
            </div>
            <div class="promocode-content__body">
                <div class="promocode-body-container">
                    <div class="promocode-content__text">скидка</div>
                    <div class="promocode-content__discount"><?=$arResult["BANNER_DISCOUNT"]?></div>
                    <div class="promocode-content__text">на любой абоменемент</div>
                    <hr>
                    <div class="promocode-content__present">+ подарки при покупке онлайн</div>
                    <button class="promocode-btn">Скопировать промокод</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var bannerTime=<?=CUtil::PhpToJSObject($arResult['BANNER_TIME'])?>;
    var bannerPromocode=<?=CUtil::PhpToJSObject($arResult['PROMOCODE'])?>;
</script>
