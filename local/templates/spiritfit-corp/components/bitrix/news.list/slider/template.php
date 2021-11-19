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
$this->setFrameMode(true);?>

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

		if(empty($files['DESCRIPTION'][$k])){
			$generalFileID = $itemFile;
		}
	}

	if(!empty($currentFileID)){
		$dbFile = CFile::GetByID($currentFileID);
		$src = CFile::GetPath($currentFileID);	
	}else{
		$dbFile = CFile::GetByID($generalFileID);
		$src = CFile::GetPath($generalFileID);	
	}

	if($arFile = $dbFile->Fetch()){
		if(strpos($arFile['CONTENT_TYPE'], 'video') !== false){
			$video = true;
		}
		if(strpos($arFile['CONTENT_TYPE'], 'image') !== false){
			$image = true;
		}
	}
}

?>

<section class="b-screen <?=($page != '/' ? 'b-screen_with-page-heading' : 'no-bottom-margin')?>">
	<div class="b-screen__bg-holder">
		<? if($video){ ?>
			<video class="b-screen__bg-video" muted="true" poster="<?=SITE_TEMPLATE_PATH?>/img/screen-video-placeholder.jpg" loop autoplay playsinline src="<?=$src?>" type="video/mp4">
			</video>
		<? }elseif($image){ ?>
			<img src="<?=$src?>" alt="">
		<? }else{ ?>
			<video class="b-screen__bg-video" muted="true" poster="<?=SITE_TEMPLATE_PATH?>/img/screen-video-placeholder.jpg" loop autoplay playsinline src="<?=SITE_TEMPLATE_PATH?>/video/spirit-screen.mp4" type="video/mp4">
			</video>
		<? } ?>
	</div>
	<? if(!empty($arResult["ITEMS"])){  ?>
		<div class="content-center">
			<div class="b-screen__content">
				<button class="b-screen__sound-btn"></button>
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
									<div class="b-info-slider__suptitle">Акция</div>
									<div class="b-info-slider__title"><?=$arItem["NAME"]?></div>
									<div class="b-info-slider__text"><?=$arItem["~PREVIEW_TEXT"]?></div>
									<a class="b-info-slider__btn button-outline" href="<?=(!empty($btnLink) ? $btnLink : '#')?>"><?=(!empty($btnText) ? $btnText : 'Заказать')?></a>
								</div>
							<?endforeach;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<? } ?>
</section>