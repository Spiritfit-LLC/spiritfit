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
<div class="blog-wrapper">
	<? if($arParams["DISPLAY_TOP_PAGER"]) { ?>
		<?=$arResult["NAV_STRING"]?>
	<? } ?>
	<div class="blog-items <?=(count($arResult["LEFT_ITEMS"]) > 0 || !empty($arParams["BANNER"])) ? "two" : "one" ?>">
		<div class="blog-items-col">
			<? if(!empty($arResult["ITEMS"][0])) {
				?>
				<div class="blog-top blog-item">
					<a <?=!empty($arResult["ITEMS"][0]["SECTION"]) ? 'href="' . $arResult["ITEMS"][0]["LINK"] . '"' : "" ?>>
						<div class="blog-item-banner">
							<img src="<?=$arResult["ITEMS"][0]["PICTURE"]["BIG"]?>" alt="<?=strip_tags($arResult["ITEMS"][0]["NAME"])?>" title="<?=strip_tags($arResult["ITEMS"][0]["NAME"])?>">
							<? if(!empty($arResult["ITEMS"][0]["SECTION"])) {
								?><div class="blog-item-section"><?=$arResult["ITEMS"][0]["SECTION"]["NAME"]?></div><?
							} ?>
							<div class="blog-item-date">
								<?=$arResult["ITEMS"][0]["DATE"]?>
								<div class="blog-item-name"><?=$arResult["ITEMS"][0]["NAME"]?></div>
							</div>
						</div>
					</a>
				</div><?
			} ?>
			<? if( count($arResult["ITEMS"]) > 1 ) {
				?><div class="blog-second"><?
				foreach($arResult["ITEMS"] as $key => $arItem) {
					if( $key == 0 ) continue;
					?>
					<a class="blog-item" <?=!empty($arItem["SECTION"]) ? 'href="' . $arItem["LINK"] . '"' : "" ?>>
						<div class="blog-item-banner">
							<img src="<?=$arItem["PICTURE"]["MEDIUM"]?>" alt="<?=strip_tags($arItem["NAME"])?>" title="<?=strip_tags($arItem["NAME"])?>">
							<? if(!empty($arItem["SECTION"])) {
								?><div class="blog-item-section"><?=$arItem["SECTION"]["NAME"]?></div><?
							} ?>
							<div class="blog-item-date">
								<?=$arItem["DATE"]?>
								<div class="blog-item-name mobile"><?=$arItem["NAME"]?></div>
							</div>
						</div>
						<div class="blog-item-name"><?=$arItem["NAME"]?></div>
						<? if(!empty($arItem["PREVIEW_TEXT"])) {
							?><div class="blog-item-description"><?=$arItem["PREVIEW_TEXT"]?></div><?
						} ?>
					</a>
					<?
				}
				?></div><?
			} ?>
			<? if($arParams["DISPLAY_BOTTOM_PAGER"]) { ?>
				<?=$arResult["NAV_STRING"]?>
			<? } ?>
		</div>
		<? if(count($arResult["LEFT_ITEMS"]) > 0 || !empty($arParams["BANNER"])) {
			?>
			<div class="blog-items-col">
				<? if(!empty($arParams["BANNER"])) { ?><a class="blog-banner" href="<?=$arParams["BANNER"]["LINK"]?>"><img src="<?=$arParams["BANNER"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>"></a><? } ?>
				<? foreach($arResult["LEFT_ITEMS"] as $key => $arItem) {
					?>
						<a class="blog-item blog-left" <?=!empty($arItem["SECTION"]) ? 'href="' . $arItem["LINK"] . '"' : "" ?>>
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
