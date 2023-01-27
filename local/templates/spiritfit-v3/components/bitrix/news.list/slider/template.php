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

?>



<?
$settings = Utils::getInfo();
$video = false;
$image = false;
$youtube = false;
$page = $APPLICATION->GetCurPage();
$currentFileID = '';
$generalFileID = '';

$files = $settings['PROPERTIES']['BANNER_FILE_BACK'];
if(!empty($files['VALUE'])){
    foreach ($files['VALUE'] as $k => $itemFile) {
        if($files['DESCRIPTION'][$k] == $page){
            $currentFileID = $itemFile;
        }
        if ($files["DESCRIPTION"][$k][0]=='m' && substr($files["DESCRIPTION"][$k], 1)==$page){
            $currentMobileFileID = $itemFile;
        }

        if(empty($files['DESCRIPTION'][$k])){
            $generalFileID = $itemFile;
        }
    }

    $imageType1 = [];
    $imageType2 = [];
    $imageType3 = [];
    $arFile = [];
    if(!empty($currentFileID)){
        $dbFile = CFile::GetByID($currentFileID);
        $arFile = $dbFile->Fetch();

        $src = CFile::GetPath($currentFileID);

        if(!empty($arFile) && strpos($arFile['CONTENT_TYPE'], 'image') !== false) {
            $imageType1 = CFile::ResizeImageGet($currentFileID, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
            $imageType2 = CFile::ResizeImageGet($currentFileID, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
            $imageType3 = CFile::ResizeImageGet($currentFileID, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);
        }
    }else{
        $dbFile = CFile::GetByID($generalFileID);
        $arFile = $dbFile->Fetch();

        $src = CFile::GetPath($generalFileID);

        if(!empty($arFile) && strpos($arFile['CONTENT_TYPE'], 'image') !== false) {
            $imageType1 = CFile::ResizeImageGet($generalFileID, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
            $imageType2 = CFile::ResizeImageGet($generalFileID, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
            $imageType3 = CFile::ResizeImageGet($generalFileID, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);
        }
    }

    if (!empty($currentMobileFileID)){
        $dbFile = CFile::GetByID($currentMobileFileID);
        $arFileMobile = $dbFile->Fetch();

        $srcMobile = CFile::GetPath($currentMobileFileID);
    }

    if(!empty($arFile)){
        if(strpos($arFile['CONTENT_TYPE'], 'video') !== false){
            $video = true;
        }
        if(strpos($arFile['CONTENT_TYPE'], 'image') !== false) {
            $image = true;
        }
    }

    if(!empty($arFileMobile)){
        if(strpos($arFileMobile['CONTENT_TYPE'], 'video') !== false){
            $videoMobile = true;
        }
        if(strpos($arFileMobile['CONTENT_TYPE'], 'image') !== false) {
            $imageMobile = true;
        }
    }
}

?>

<section class="b-screen b-screen_with-page-heading">
    <div class="b-screen__bg-holder" >
        <? if($video){ ?>
            <video class="b-screen__bg-video lazy <?if (!empty($currentMobileFileID)) echo 'is-hide-mobile visible-desktop';?>" preload="none" muted="true" poster="<?=SITE_TEMPLATE_PATH?>/img/screen-video-placeholder.webp" loop autoplay playsinline>
                <source data-src="<?=$src?>" type="video/<?=pathinfo($src, PATHINFO_EXTENSION)?>">
            </video>
        <? }elseif($image){ ?>
            <img src="<?=$imageType1["src"]?>" srcset="<?=$imageType3["src"]?> 450w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w" alt="" class="b-screen__bg-video <?if (!empty($currentMobileFileID)) echo 'is-hide-mobile';?>">
        <? }else{ ?>
            <video class="b-screen__bg-video lazy <?if (!empty($currentMobileFileID)) echo 'is-hide-mobile visible-desktop';?>" preload="none" muted="true" poster="<?=SITE_TEMPLATE_PATH?>/img/screen-video-placeholder.webp" loop autoplay playsinline>
                <source data-src="<?=SITE_TEMPLATE_PATH?>/video/spirit-screen.mp4" type="video/<?=pathinfo(SITE_TEMPLATE_PATH.'/video/spirit-screen.mp4', PATHINFO_EXTENSION)?>">
            </video>
        <? } ?>

        <?if (!empty($currentMobileFileID)):?>
            <? if($videoMobile){ ?>
                <video class="b-screen__bg-video lazy is-hide-desktop hidden-desktop" preload="none" muted="true" poster="<?=SITE_TEMPLATE_PATH?>/img/screen-video-placeholder.webp" loop autoplay playsinline>
                    <source data-src="<?=$srcMobile?>" type="video/<?=pathinfo($srcMobile, PATHINFO_EXTENSION)?>">
                </video>
            <? }elseif($imageMobile){ ?>
                <img src="<?=$srcMobile?>" alt="" class="b-screen__bg-video is-hide-desktop hidden-desktop">
            <? } ?>
        <?endif?>

    </div>
<!--    --><?php //if(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome-Lighthouse')===false):?>
    <? if(!empty($arResult["ITEMS"])){  ?>
        <div class="content-center">
            <div class="b-screen__content">
                <div class="b-screen__slider">
                    <div class="b-info-slider">
                        <div class="b-info-slider__nav"></div>
                        <div class="b-info-slider__slider">
                            <?foreach($arResult["ITEMS"] as $arItem):?>
                                <?
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                                $btnText = $arItem['PROPERTIES']['BANNER_BTN_TEXT']['VALUE'];
                                $btnLink = $arItem['PROPERTIES']['BANNER_BTN_LINK']['VALUE'];
                                $btnType = $arItem['PROPERTIES']['BANNER_BTN_TYPE']['VALUE_XML_ID'];
                                if($btnType == 'anchor'){
                                    $btnText = 'Участвовать';
                                    $btnLink = '#js-pjax-clubs';
                                }

                                $arItem["PREVIEW_TEXT"] = strip_tags($arItem["PREVIEW_TEXT"]);
                                $arItem["PREVIEW_TEXT"] = mb_strimwidth($arItem["PREVIEW_TEXT"], 0, 345, "...");
                                ?>
                                <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="b-info-slider__item">
                                    <div class="b-info-slider__suptitle"><?=(!empty($arItem['PROPERTIES']['BANNER_TITLE']['VALUE'])) ? $arItem['PROPERTIES']['BANNER_TITLE']['VALUE'] : "Акция"?></div>
                                    <div class="b-info-slider__title">
                                        <span class="b-info-slider__title__background">
                                            <span class="b-info-slider__title__text"><?=$arItem["NAME"]?></span>
                                        </span>
                                    </div>
                                    <div class="b-info-slider__text"><!--noindex--><?=$arItem["~PREVIEW_TEXT"]?><!--/noindex--></div>
                                    <?if (!empty($btnText)):?>
                                        <a class="b-info-slider__btn button" href="<?=(!empty($btnLink) ? $btnLink : '#')?>" onclick="setConversion('SliderBtnConversion');"
                                           data-layer="true"
                                           data-layercategory="UX"
                                           data-layeraction="clickLinkSliderPromo"
                                           data-layerlabel="<?=$arItem["NAME"]?>"

                                        ><?=$btnText?></a>
                                    <?endif;?>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <? } ?>
<!--    --><?//endif;?>
</section>
