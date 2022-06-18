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
                    <div class="blog-head-items">
					<? if( !empty($arResult["PICTURE_SRC"]) ) { ?>
						<div class="blog-detail-picture">
							<img src="<?=$arResult["PICTURE_SRC"]?>" alt="<?=strip_tags($arResult["NAME"])?>" title="<?=strip_tags($arResult["NAME"])?>">
							<? if(!empty($arResult["SECTION_NAMES"])) {
								?><div class="blog-detail-section items">
									<? foreach($arResult["SECTION_NAMES"] as $name) {
										?><div class="item"><?=$name?></div><?
									} ?>
								</div><?
							} ?>
						</div>
					<? } ?>
                    </div>
                    <div class="blog-detail-text">
                        <div><?=$arResult['~DETAIL_TEXT']?></div>

                        <div class="show-sections-titles">СОДЕРЖАНИЕ</div>
                        <?foreach ($arResult['TXT'] as $TXT):?>
                            <div class="text-section" data-id="<?=$TXT['ID']?>">
                                <div class="text-section__title"><?=$TXT['TITLE']?></div>
                                <div class="text-section__text"><?=$TXT['TEXT']?></div>
                            </div>
                        <?endforeach;?>
                    </div>
				</div>
                <div class="blog-items-col is-hide-mobile">
                    <ul class="text-section-titles">
                        <?foreach ($arResult['TXT'] as $TXT):?>
                            <li class="text-section__head-title" data-id="<?=$TXT['ID']?>"><?=$TXT['TITLE']?></li>
                        <?endforeach;?>
                    </ul>
                </div>

			</div>
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
                                        ?><div class="blog-item-section items">
                                        <? foreach($arItem["SECTION"] as $name) {
                                            ?><div class="item"><?=$name?></div><?
                                        } ?>
                                        </div><?
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