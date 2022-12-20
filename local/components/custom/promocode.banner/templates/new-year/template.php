<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="promocode-gp-banner__container">
    <div class="promocode-gp-banner">
        <img src="<?=DEFAULT_PATH.'/img/banner-background-new-year.png'?>" loading="lazy" style="max-height: 100%;">
        <div class="promocode-gp-banner__closer">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].DEFAULT_PATH.'/img/icons/cross_footer_icon.svg')?>
        </div>
    </div>
</div>
<script>
    var bannerTime=<?=CUtil::PhpToJSObject($arResult['BANNER_TIME'])?>;
    var bannerPromocodePage=<?=empty($arResult["PAGE"])?CUtil::PhpToJSObject(""):CUtil::PhpToJSObject($arResult["PAGE"])?>
</script>
