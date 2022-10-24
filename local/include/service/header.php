<div class="content-center">
    <div class="page-hiden-slider__header">
        <div class="page-desc-short">
            <div class="page-desc-short__text">
                <?=htmlspecialcharsback($arParams["HEAD_DESC"])?>
            </div>
            <?if (!empty($arParams["BUTTON"])):?>
            <div class="page-desc-short__btn">
                <a class="page-desc__request-btn button-outline" href="<?=$arParams["BUTTON_LINK"]?>"><?=$arParams["BUTTON"]?></a>
            </div>
            <?endif;?>
        </div>
        <div class="page-desc-banner <?if (defined('H1_BIG_COLORFUL')) echo "big-colorful"?>">
            <div class="owl-carousel">
                <?foreach ($arParams["HEAD_IMAGES"] as $IMG):?>
                    <div class="owl-slide normal-size" style="background-image: url('<?=CFile::GetPath($IMG)?>')"></div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</div>
