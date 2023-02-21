<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");

global $settings;

$video = $arResult['PROPERTIES']['BLOCK_VIDEO_YOUTUBE']['VALUE'];
$videoPreview = $arResult['PROPERTIES']['BLOCK_PREVIEW']['VALUE'];
$view = $arResult['PROPERTIES']['BLOCK_VIEW']['VALUE_XML_ID'];
$link = $arResult['PROPERTIES']['BLOCK_LINK']['VALUE'];
$linkTitle = $arResult['PROPERTIES']['BLOCK_TITLE_LINK']['VALUE'];

$btnAvailable = ($arResult['PROPERTIES']['BLOCK_BTN_AVAILABLE']['VALUE'] == 'Да' ? true : false);
if($btnAvailable) $btnText = (!empty($arResult['PROPERTIES']['BLOCK_BTN_TEXT']['VALUE']) ? $arResult['PROPERTIES']['BLOCK_BTN_TEXT']['VALUE'] : 'Подробнее');

switch ($view) {
    case 'v1':
        $slider = false;
        $showApp = false;
        $reverse = true;
        $class = 'b-image-plate-block';
        $hideWrapperImg = true;
        $classBtn = 'button';
        break;

    case 'v2':
        $slider = true;
        $showApp = true;
        $reverse = false;
        $class = 'b-image-plate-block';
        $hideWrapperImg = false;
        $classBtn = 'button-outline';
        break;

    case 'v3':
        $slider = false;
        $showApp = false;
        $reverse = false;
        $class = 'b-image-block';
        $hideWrapperImg = false;
        $classBtn = 'button-outline';
        break;

    case 'v4':
        $slider = true;
        $showApp = false;
        $reverse = true;
        $class = 'b-image-block';
        $hideWrapperImg = false;
        $classBtn = 'button-outline';
        break;

    case 'v5':
        $slider = true;
        $showApp = false;
        $reverse = false;
        $class = 'b-image-plate-block';
        $hideWrapperImg = false;
        $classBtn = 'button-outline';
        break;
}
?>
<? if( !empty($arResult['TITLE']) && $arParams["HIDE_TITLE"]==false) { ?>
    <div class="content-center">
        <div class="b-section__title" <?=(!empty($arParams["BLOCK_ID"])) ? 'id="'.$arParams["BLOCK_ID"].'"' : '' ?>>
            <h2><?=$arResult['TITLE']?></h2>
        </div>
    </div>
<? } ?>
<section <?=(!empty($arParams["BLOCK_ID"]) && empty($arResult['TITLE'])) ? 'id="'.$arParams["BLOCK_ID"].'"' : '' ?> class="<?=$class?> <?=($slider ? $class.'_simple-mobile' : '')?> abonements-block-slider">
    <div class="content-center">
        <div class="<?=$class?>__content <?=($reverse == 'Y' ? $class.'__content_reverse' : '')?>">
            <? if($slider){ ?>
                <div class="<?=$class?>__slider-nav"></div>
            <? } ?>

            <? if($hideWrapperImg){
                if(!empty($video)){
                    if(!empty($videoPreview)){
                        $imageType1 = CFile::ResizeImageGet($videoPreview, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
                        $imageType2 = CFile::ResizeImageGet($videoPreview, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
                        $imageType3 = CFile::ResizeImageGet($videoPreview, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);

                        $videoPreview = CFile::GetFileArray($videoPreview);
                    } ?>
                    <a class="<?=$class?>__img-holder play-btn-overlay" href="<?=$video?>" data-fancybox="">
                        <img class="<?=$class?>__img" src="<?=$imageType1['src']?>" srcset="<?=$imageType3["src"]?> 450w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w" alt="<?=$videoPreview['DESCRIPTION']?>" loading="lazy"/>
                    </a>
                <? } ?>
            <? }else{ ?>
                <div class="<?=$class?>__img-holder <?=($slider ? $class.'__img-holder_slider' : '')?>">
                    <? if(!empty($video)){
                        if(!empty($videoPreview)){
                            $imageType1 = CFile::ResizeImageGet($videoPreview, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
                            $imageType2 = CFile::ResizeImageGet($videoPreview, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
                            $imageType3 = CFile::ResizeImageGet($videoPreview, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);

                            $videoPreview = CFile::GetFileArray($videoPreview);
                        }

                        if($slider){ ?>
                            <a class="<?=$class?>__slide play-btn-overlay" href="<?=$video?>" data-fancybox="">
                                <img class="<?=$class?>__slide-img" src="<?=$imageType1['src']?>" srcset="<?=$imageType3["src"]?> 450w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w" alt="<?=$videoPreview['DESCRIPTION']?>" role="presentation" loading="lazy" />
                            </a>
                        <? }else{ ?>
                            <a class="<?=$class?>__img-holder play-btn-overlay" href="<?=$video?>" data-fancybox="">
                                <img class="<?=$class?>__img" src="<?=$imageType1['src']?>" srcset="<?=$imageType3["src"]?> 450w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w" alt="<?=$videoPreview['DESCRIPTION']?>" loading="lazy" /></a>
                        <? } ?>
                    <? } ?>
                    <? if($slider) { ?>
                        <? foreach($arResult["SLIDER_PHOTOS"] as $block) { ?>
                            <?
                            $imageType1 = (!empty($block["PHOTO"]["1280"])) ? $block["PHOTO"]["1280"] : "";
                            $imageType2 = (!empty($block["PHOTO"]["800"])) ? $block["PHOTO"]["800"] : "";
                            $imageType3 = (!empty($block["PHOTO"]["450"])) ? $block["PHOTO"]["450"] : "";
                            ?>
                            <div class="<?=$class?>__slide">
                                <img class="<?=$class?>__slide-img mobile-src-check" src="<?=$imageType1?>" alt="<?=$itemPhotoData['DESCRIPTION']?>" data-src1="<?=$imageType2?>" data-src2="<?=$imageType3?>" role="presentation" />
                            </div>
                        <? } ?>
                    <? } else if(!empty($arResult["SLIDER_PHOTOS"][0])) { ?>
                        <?
                        $imageType1 = (!empty($arResult["SLIDER_PHOTOS"][0]["PHOTO"]["1280"])) ? $arResult["SLIDER_PHOTOS"][0]["PHOTO"]["1280"] : "";
                        $imageType2 = (!empty($arResult["SLIDER_PHOTOS"][0]["PHOTO"]["800"])) ? $arResult["SLIDER_PHOTOS"][0]["PHOTO"]["800"] : "";
                        $imageType3 = (!empty($arResult["SLIDER_PHOTOS"][0]["PHOTO"]["450"])) ? $arResult["SLIDER_PHOTOS"][0]["PHOTO"]["450"] : "";
                        ?>
                        <img class="b-image-block__img" src="<?=$imageType1?>" srcset="<?=$imageType3?> 400w, <?=$imageType2?> 800w, <?=$imageType1?> 1280w" alt="<?=$arResult["SLIDER_PHOTOS"][0]["PHOTO_DESCRIPTION"]?>"/>
                    <? } ?>
                </div>
            <? } ?>
            <div class="<?=$class?>__text-content text-center">
                <div class="<?=$class?>__text-content-inner">
                    <div class="<?=$class?>__text">
                        <? foreach($arResult["SLIDER_PHOTOS"] as $block) { ?>
                            <div class="<?=$class?>__text-item">
                                <h2 class="slide-text-title"><?=$block["TITLE"]?></h2>
                                <div class="slide-text-content">
                                    <?=$block["TEXT"]?>
                                </div>
                                <? if( !empty($block["LINK"]["URL"]) ) { ?>
                                    <div class="slide-img-text__bottom">
                                        <a class="<?=$class?>__btn <?=$classBtn?>" href="<?=$block["LINK"]["URL"]?>" <?=($block["LINK"]["IS_BLANK"]) ? "target=\"_blank\"" : "" ?>><?=$block["LINK"]["TITLE"]?></a>
                                    </div>
                                <? } ?>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>