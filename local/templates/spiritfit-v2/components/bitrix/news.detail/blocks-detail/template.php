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

$text = $arResult['PROPERTIES']['BLOCK_TEXT']['VALUE'];
$video = $arResult['PROPERTIES']['BLOCK_VIDEO_YOUTUBE']['VALUE'];
$videoPreview = $arResult['PROPERTIES']['BLOCK_PREVIEW']['VALUE'];
$videoText = $arResult['PROPERTIES']['BLOCK_TEXT_VIDEO']['VALUE'];
$photos = $arResult['PROPERTIES']['BLOCK_PHOTO']['VALUE'];
?>

<? if(!empty($photos)){ ?>
	<section class="b-image-block b-image-block_simple-mobile">
		<div class="content-center">
			<div class="b-image-block__content">
				<div class="b-image-block__slider-nav"></div>
				<div class="b-image-block__img-holder b-image-block__img-holder_slider">
					<?foreach ($photos as $itemPhoto) {
						$itemPhotoSrc = CFile::getPath($itemPhoto);
						
						$imageType1 = CFile::ResizeImageGet($itemPhoto, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
						$imageType2 = CFile::ResizeImageGet($itemPhoto, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
						$imageType3 = CFile::ResizeImageGet($itemPhoto, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);
						?>
						<div class="b-image-block__slide">
							<img class="b-image-block__slide-img" src="<?=$imageType1["src"]?>" srcset="<?=$imageType3["src"]?> 400w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w" alt="" role="presentation" />
						</div>
						<?
					} ?>
				</div>
				<div class="b-image-block__text-content text-center">
					<div class="b-image-block__text-content-inner">
						<div class="b-image-block__text">
							<? if(!empty($text)){ ?>
								<? foreach ($text as $itemText) { ?>
									<div class="b-image-block__text-item"><?=$itemText['TEXT']?></div>
								<? } ?>
							<? } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<? } ?>

<? if(!empty($arResult['PREVIEW_TEXT'])) {?>
	<div class="b-text-col">
		<div class="content-center">
			<div class="b-text-col__content content-area">
				<?=$arResult['PREVIEW_TEXT']?>
			</div>
		</div>
	</div>
<? } ?>

<? if(!empty($video)){?>
	<section class="b-image-plate-block b-image-plate-block_simple-mobile">
		<div class="content-center">
			<div class="b-image-plate-block__content">
				<? 
				$imageType1 = ""; $imageType2 = ""; $imageType3 = "";
				if(!empty($videoPreview)){
					
					$imageType1 = CFile::ResizeImageGet($videoPreview, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL);
					$imageType2 = CFile::ResizeImageGet($videoPreview, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL);
					$imageType3 = CFile::ResizeImageGet($videoPreview, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL);
					
					$videoPreview = CFile::getPath($videoPreview);
				}?>

				<a class="b-image-block__slide play-btn-overlay" href="<?=$video?>" data-fancybox="">
					<img class="b-image-block__slide-img" src="<?=$imageType1["src"]?>" srcset="<?=$imageType3["src"]?> 400w, <?=$imageType2["src"]?> 800w, <?=$imageType1["src"]?> 1280w"  alt="" role="presentation" />
				</a>
					
				<div class="b-image-plate-block__text-content text-center">
					<div class="b-image-plate-block__text-content-inner">
						<div class="b-image-plate-block__text">
							<div class="b-image-plate-block__text-item">
								<?=$videoText['TEXT']?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<? } ?>

<? if(!empty($arResult['DETAIL_TEXT'])){ ?>
	<div class="b-text-col">
		<div class="content-center">
			<div class="b-text-col__content content-area">
				<?=$arResult['DETAIL_TEXT']?>
			</div>
		</div>
	</div>
<? } ?>