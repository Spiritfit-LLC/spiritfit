<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="promocode-banner__container">
    <div class="promocode-banner" style="background-image: url('<?=SITE_TEMPLATE_PATH.'/img/banner-black-friday-background.png'?>')">
        <div class="promocode-banner__closer">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/cross_footer_icon.svg')?>
        </div>
        <div class="promocode-banner__content">
            <div class="promocode-content__header">
                <div class="promocode-content__logo">
                    <img src="<?=SITE_TEMPLATE_PATH.'/img/logo-cube-black.png'?>" class="cube-logo-spiritfit">
                </div>
            </div>
        </div>
        <div class="promocode-content__discount">
            <div class="promocode-to-text">до</div>
            <div class="promocode-discount-text"><?=$arResult["BANNER_DISCOUNT"]?></div>
        </div>
        <div class="promocode-content__date">
            11.11 - 14.11
        </div>
    </div>
</div>
<script>
    var bannerTime=<?=CUtil::PhpToJSObject($arResult['BANNER_TIME'])?>;
    var bannerPromocodePage=<?=empty($arResult["PAGE"])?CUtil::PhpToJSObject(""):CUtil::PhpToJSObject($arResult["PAGE"])?>
</script>