<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
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
?>
<div class="content-center">
	<div class="blog">
		<div class="blog-wrapper detail">
			<div class="blog-items <?=(count($arResult["LEFT_ITEMS"]) > 0 || !empty($arParams["BANNER"])) ? "two" : "one" ?>">
				<div class="blog-items-col">
					<div class="blog-detail-date"><?=$arResult["DATE"]?></div>
					<? if( !empty($arResult["PICTURE_SRC"]) ) { ?>
						<div class="blog-detail-picture">
							<img src="<?=$arResult["PICTURE_SRC"]?>" alt="<?=strip_tags($arResult["NAME"])?>" title="<?=strip_tags($arResult["NAME"])?>">
							<? if(!empty($arResult["SECTION_NAME"])) {
								?><div class="blog-detail-section"><?=$arResult["SECTION_NAME"]?></div><?
							} ?>
						</div>
					<? } ?>
					<div class="blog-detail-text"><?=$arResult["~DETAIL_TEXT"]?></div>
					<? if( !empty($arResult["ADDITIONAL_ITEMS"]) ) { ?>
						<div class="blog-additional-wrapper">
							<div class="blog-additional-title"><?=GetMessage("BLOG_ADDITIONAL_TITLE")?></div>
							<div class="blog-additional">
								<?
									foreach($arResult["ADDITIONAL_ITEMS"] as $key => $arItem) {
										?>
										<a class="blog-item" href="<?=$arItem["LINK"]?>">
											<div class="blog-item-banner">
												<img src="<?=$arItem["PICTURE"]["MEDIUM"]?>" alt="<?=strip_tags($arItem["NAME"])?>" title="<?=strip_tags($arItem["NAME"])?>">
												<? if(!empty($arItem["SECTION"])) {
													?><div class="blog-item-section"><?=$arItem["SECTION"]["NAME"]?></div><?
												} ?>
												<div class="blog-item-name"><?=$arItem["NAME"]?></div>
											</div>
										</a>
										<?
									}
								?>
							</div>
						</div>
					<? } ?>
				</div>
				<? if(count($arResult["LEFT_ITEMS"]) > 0 || !empty($arParams["BANNER"])) {
					?>
					<div class="blog-items-col">
						<? if(!empty($arParams["BANNER"])) { ?><a class="blog-banner" href="<?=$arParams["BANNER"]["LINK"]?>"><img src="<?=$arParams["BANNER"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>"></a><? } ?>
						<? foreach($arResult["LEFT_ITEMS"] as $key => $arItem) {
							?>
								<a class="blog-item blog-left" href="<?=$arItem["LINK"]?>">
									<div class="cell">
										<img src="<?=$arItem["PICTURE"]["SMALL"]?>" alt="<?=strip_tags($arItem["NAME"])?>" title="<?=strip_tags($arItem["NAME"])?>">
									</div>
									<div class="cell">
										<? if(!empty($arItem["SECTION"])) {
											?><div class="blog-item-section"><?=$arItem["SECTION"]["NAME"]?></div><?
										} ?>
										<div class="blog-item-name"><?=$arItem["NAME"]?></div>
									</div>
								</a>
							<?
						} ?>
					</div>
					<?
				} ?>
			</div>
			<? if(!empty($arParams["BANNER"])) { ?>
				<div class="blog-banner__mobile">
					<a class="blog-banner" href="<?=$arParams["BANNER"]["LINK"]?>">
						<img src="<?=$arParams["BANNER"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>">
					</a>
				</div>
			<? } ?>
		</div>
	</div>
</div>