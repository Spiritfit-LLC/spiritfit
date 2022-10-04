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
?>

<?
$sectionItems = [];
$sectionName = false;
$leftButtonText = 'Предыдущее видео';
$rightButtonText = 'Следующее видео';
$sectionDescription = false;
if ($_REQUEST['theme'] && $_REQUEST['theme'] != "all" && !empty($arResult["CHOOSEN_SECTION"][$_REQUEST['theme']])) {
    $sectionItems = $arResult["CHOOSEN_SECTION"][$_REQUEST['theme']]["ITEMS"];
    $sectionName = $arResult["CHOOSEN_SECTION"][$_REQUEST['theme']]["NAME"];
    $sectionDescription = $arResult["CHOOSEN_SECTION"][$_REQUEST['theme']]["DESCRIPTION"];
} else {
    $sectionItems = $arResult["ITEMS"];
    $sectionName = $arResult["MAIN_SECTION"]["NAME"];
    $sectionDescription = $arResult["MAIN_SECTION"]["DESCRIPTION"];
}

if ($arResult['BUTTON_TEXT_LEFT']) {
    $leftButtonText = $arResult['BUTTON_TEXT_LEFT'];
} 

if ($arResult['BUTTON_TEXT_RIGHT']) {
    $rightButtonText = $arResult['BUTTON_TEXT_RIGHT'];
}
?>

<div class="grid fixed">
	<div class="grid__inner">
		<div class="grid__aside">
			<div class="grid-element grid-element--aside-big grid-element--select-videos">
				<h1 class="grid-element__head grid-element__head--paid-video"><?= $sectionName ?></h1>
				<div class="grid-element__desc"><?= $sectionDescription ?></div>
				<div class="subscription__aside-form-row subscription__aside-form-row--select subscription__aside-form-row--select-videos">
                    <select class="input input--light input--long custom--select-video js-pjax-select-videos" data-placeholder="Выберите направление">
                        <option value=""></option>
                        <?if ($_REQUEST['theme']) {?>
                            <option value="all" <?if($_REQUEST['theme'] == "all") {?> selected<?}?>><?=$arResult["MAIN_SECTION"]["UF_DIRECTION_NAME"]?></option>
                        <?}?>
                        <? foreach ($arResult["SECTIONS"] as $direction) {?>
                            <option value="<?= $direction['ID'] ?>" <?if($direction['ID'] == $_REQUEST['theme']) {?> selected<?}?> ><?= $direction['NAME'] ?></option>
                        <? } ?>
                    </select>
                </div>
			</div>
        </div>
        <? $ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']); ?>
        <div id="seo-div" hidden="true"
            data-title="<?= $arResult['SEO']['SECTION_META_TITLE'] ?>" 
            data-description="<?= $arResult['SEO']['SECTION_META_DESCRIPTION'] ?>" 
            data-keywords="<?= $arResult['SEO']['SECTION_META_KEYWORDS'] ?>"
            data-image="<?= $ogImage ?>"></div>
		<div class="grid__main">
			<? foreach ($sectionItems as $arItem) { 
                if ($arItem["PREVIEW_AVAILABLE_VIDEO_SRC"] && $arItem["PREVIEW_CLOSED_VIDEO_SRC"] && $arItem["LINK_VIDEO"]["VALUE"] && $arItem["~PREVIEW_TEXT"] && $arItem['VIDEO_NUMBER']['VALUE']) { 
            ?>
				<?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
                <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="grid-element grid-element--club grid-element--paid-video <?= (!$arItem["FORBIDDEN_VIDEO"]["VALUE"] && !isset($_COOKIE['video_token'])) ?  "js-forbidden-video" : "js-video" ?>">
                    <?if ($arItem["FORBIDDEN_VIDEO"]["VALUE_ENUM"] == "Y" || isset($_COOKIE['video_token'])) {?>
                        <div class="grid-element__bg" style="background-image: url(<?= $arItem["PREVIEW_AVAILABLE_VIDEO_SRC"] ?>)"></div>
                    <?} else {?>
                        <div class="grid-element__bg" style="background-image: url(<?= $arItem["PREVIEW_CLOSED_VIDEO_SRC"] ?>)"></div>
                    <?}?>
                    <div class="grid-element__type"><?= $arItem['VIDEO_NUMBER']['VALUE'] ?></div>
                    <div class="grid-element__heading"><?= $arItem["~NAME"] ?></div>
                    <? if ($arItem["FORBIDDEN_VIDEO"]["VALUE_ENUM"] == "Y" || isset($_COOKIE['video_token'])) { ?>
                        <div class="popup popup--paid-video">
                            <div class="popup__bg"></div>
                            <div class="popup__window">
                                <div class="popup__close">
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="paid-video-popup" itemscope itemtype="http://schema.org/VideoObject">
                                    <meta itemprop="name" content="<?=$arItem["NAME"]?>" />
                                    <meta itemprop="description" content="<?=$arItem["PREVIEW_TEXT"]?>" />
                                    <meta itemprop="uploadDate" content="<?=(new \Bitrix\Main\Type\DateTime($arItem['TIMESTAMP_X']))->format('Y-m-d');?>">
                                    <meta itemprop="duration" content="<?=$arItem['TIME_VIDEO']['VALUE']?>">
                                    <meta itemprop="isFamilyFriendly" content="true">
                                    <div class="paid-video-popup__content-video" itemtype="http://schema.org/ImageObject" itemprop="thumbnail">
                                        <meta itemprop="thumbnailUrl" content="https://spiritfit.ru<?= $arItem["PREVIEW_AVAILABLE_VIDEO_SRC"] ?>">
                                        <div class="paid-video-popup__wrapper-video">
                                            <video class="paid-video-popup__video" controls controlsList="nodownload" disablePictureInPicture playsinline src="">
                                                <? /* <source src="video.webm" type="video/webm"> */ ?>
                                                <?$codedVideoId = md5($arItem["ID"]);?>
                                                <source src="<?="/media/videoAvailability.php?videoId=".$codedVideoId?>" type="video/mp4">
                                                    
                                            </video>
                                        </div>
                                        <p class="paid-video-popup__title"><?= $arItem["~NAME"] ?></p>
                                        <div class="paid-video-popup__description ps__child--consume">
                                            <?= $arItem["~PREVIEW_TEXT"] ?>
                                        </div>
                                    </div>
                                    <div class="paid-video-popup__wrapper-buttons">
                                        <button class="btn paid-video-popup__button paid-video-popup__button--prev js-paid-video-button" type="button" data-direction="prev"><?= $leftButtonText ?></button>
                                        <button class="btn paid-video-popup__button paid-video-popup__button--next js-paid-video-button" type="button" data-direction="next"><?= $rightButtonText ?></button>
                                    </div>
                                    <meta itemprop="url" content="<?="/media/videoAvailability.php?videoId=".$codedVideoId?>" />
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
                <? } ?>
            <? } ?>
		</div>
	</div>
</div>