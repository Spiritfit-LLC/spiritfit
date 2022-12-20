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

$text = $arResult['PROPERTIES']['BLOCK_TEXT']['VALUE'];
$video = $arResult['PROPERTIES']['BLOCK_VIDEO_YOUTUBE']['VALUE'];
$videoPreview = $arResult['PROPERTIES']['BLOCK_PREVIEW']['VALUE'];
$photos = $arResult['PROPERTIES']['BLOCK_PHOTO']['VALUE'];
$link = $arResult['PROPERTIES']['BLOCK_LINK']['VALUE'];
$linkTitle = $arResult['PROPERTIES']['BLOCK_TITLE_LINK']['VALUE'];
$view = $arResult['PROPERTIES']['BLOCK_VIEW']['VALUE_XML_ID'];

$arResult['BROWSER'] = getBrowserInformation();

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
<? if( !empty($arParams['BLOCK_TITLE']) ) { ?>
    <div class="content-center">
        <div class="b-section__title" <?=(!empty($arParams["BLOCK_ID"])) ? 'id="'.$arParams["BLOCK_ID"].'"' : '' ?>>
            <h2><?=$arParams['BLOCK_TITLE']?></h2>
        </div>
    </div>
<? } ?>
<section class="<?=$class?> <?=($slider ? $class.'_simple-mobile' : '')?> <?if(empty($arParams['BLOCK_TITLE'])) echo "section-margin-top"?>">
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
                        <img class="<?=$class?>__img" data-lazy="<?=$imageType1['src']?>"  alt="<?=$videoPreview['DESCRIPTION']?>" loading="lazy"/>
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
                                <img class="<?=$class?>__slide-img" data-lazy="<?=$imageType1['src']?>" alt="<?=$videoPreview['DESCRIPTION']?>" role="presentation" loading="lazy" />
                            </a>
                        <? }else{ ?>
                            <a class="<?=$class?>__img-holder play-btn-overlay" href="<?=$video?>" data-fancybox="">
                                <img class="<?=$class?>__img" data-lazy="<?=$imageType1['src']?>"  alt="<?=$videoPreview['DESCRIPTION']?>" loading="lazy" /></a>
                        <? } ?>
                    <? } ?>

                    <? if(!empty($photos)){
                        if($slider){
                            foreach ($photos as $itemPhoto) {
                                $itemPhotoData = CFile::GetFileArray($itemPhoto);
                                if( (!empty($arResult['BROWSER']['NAME']) && $arResult['BROWSER']['NAME'] !== "Safari") || empty($arResult['BROWSER']['NAME']) ) {
                                    $imageType1 = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 1280, 800, true);
                                    $imageType2 = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 800, 500, true);
                                    $imageType3 = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 450, 281, true);
                                } else {
                                    $imageType1['WEBP_SRC'] = CFile::ResizeImageGet($itemPhoto, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                                    -									$imageType2['WEBP_SRC'] = CFile::ResizeImageGet($itemPhoto, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                                    -									$imageType3['WEBP_SRC'] = CFile::ResizeImageGet($itemPhoto, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                                }
                                ?>
                                <div class="<?=$class?>__slide">
                                    <img class="<?=$class?>__slide-img" data-lazy="<?=$imageType1['WEBP_SRC']?>" alt="<?=$itemPhotoData['DESCRIPTION']?>" role="presentation" />
                                </div>
                                <?
                            }
                        } else {
                            $itemPhotoData = CFile::GetFileArray($photos[0]);
                            if( (!empty($arResult['BROWSER']['NAME']) && $arResult['BROWSER']['NAME'] !== "Safari") || empty($arResult['BROWSER']['NAME']) ) {
                                $imageType1 = \ImageConverter\Picture::getResizeWebp($pitemPhotoData, 1280, 800, true);
                                $imageType2 = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 800, 500, true);
                                $imageType3 = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 450, 281, true);
                            } else {
                                $imageType1['WEBP_SRC'] = CFile::ResizeImageGet($photos[0], array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                                -								$imageType2['WEBP_SRC'] = CFile::ResizeImageGet($photos[0], array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                                -								$imageType3['WEBP_SRC'] = CFile::ResizeImageGet($photos[0], array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
                            }

                            ?>
                            <img class="b-image-block__img" data-lazy="<?=$imageType1['WEBP_SRC']?>"/>
                        <? } ?>
                    <? } ?>
                </div>
            <? } ?>
            <div class="<?=$class?>__text-content text-center">
                <div class="<?=$class?>__text-content-inner">
                    <h2><a href="<?=(!empty($linkTitle) ? $linkTitle : $link)?>"><?=$arResult['NAME']?></a></h2>
                    <div class="<?=$class?>__text">
                        <? if(!empty($text)){ ?>
                            <? foreach ($text as $itemText) { ?>
                                <div class="<?=$class?>__text-item"><?=$itemText['TEXT']?></div>
                            <? } ?>
                        <? } ?>
                    </div>
                    <? if($showApp){ ?>
                        <div class="<?=$class?>__app-list">
                            <div class="b-app-list"><a rel="nofollow" class="b-app-list__button"
                                                       href="<?=$settings["PROPERTIES"]["LINK_APPSTORE"]["VALUE"]?>"
                                                       target="_blank"><img class="b-app-list__img" src="<?=SITE_TEMPLATE_PATH?>/img/btn-app-store-black.svg"
                                                                            alt="Загрузите в App Store" title="" /></a><a rel="nofollow" class="b-app-list__button"
                                                                                                                          href="<?=$settings["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"]?>" target="_blank"><img class="b-app-list__img"
                                                                                                                                                                                                               src="<?=SITE_TEMPLATE_PATH?>/img/btn-google-play-black.svg" alt="Доступно в Google Play"
                                                                                                                                                                                                               title="" /></a>
                            </div>
                        </div>
                    <? } ?>
                    <? if($btnAvailable){ ?>
                        <a class="<?=$class?>__btn <?=$classBtn?>" href="<?=$link?>"><?=$btnText?></a>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
</section>