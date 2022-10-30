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
            <?=empty($arResult["ITEMS"]) ? GetMessage("BLOG_EMPTY") : "" ?>
            <? if(!empty($arResult["ITEMS"][0])) {
                ?>
                <div class="blog-top blog-item">
                <a <?=!empty($arResult["ITEMS"][0]["SECTION"]) ? 'href="' . $arResult["ITEMS"][0]["LINK"] . '"' : "" ?>>
                    <div class="blog-item-banner">
                        <img src="<?=$arResult["ITEMS"][0]["PICTURE"]["BIG"]?>" alt="<?=strip_tags($arResult["ITEMS"][0]["NAME"])?>" title="<?=strip_tags($arResult["ITEMS"][0]["NAME"])?>">
                        <? if(!empty($arResult["ITEMS"][0]["SECTION"])) {
                            ?>
                            <div class="blog-item-section items">
                            <? foreach($arResult["ITEMS"][0]["SECTION"] as $name) {
                                ?><div class="item"><?=$name?></div><?
                            } ?>
                            </div><?
                        } ?>
                        <div class="blog-item-date">
                            <?=$arResult["ITEMS"][0]["DATE"]?>
                            <?if (!empty($arResult["ITEMS"][0]['RATING'])):?>
                            <div class="blog-item-raiting">Рейтинг: <?=$arResult["ITEMS"][0]['RATING']?>
                                <svg width="14" height="14" viewBox="0 0 32 32" style="margin: 0 5px; fill: #fc6120;">
                                    <path d="M31.547 12a.848.848 0 00-.677-.577l-9.427-1.376-4.224-8.532a.847.847 0 00-1.516 0l-4.218 8.534-9.427 1.355a.847.847 0 00-.467 1.467l6.823 6.664-1.612 9.375a.847.847 0 001.23.893l8.428-4.434 8.432 4.432a.847.847 0 001.229-.894l-1.615-9.373 6.822-6.665a.845.845 0 00.214-.869z" />
                                </svg>
                            </div>
                            <?endif;?>
                            <div  class="blog-item-shows">
                                Просмотры: <?=$arResult["ITEMS"][0]["SHOWING_COUNT"]?>
                            </div>
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
                                ?><div class="blog-item-section items">
                                <? foreach($arItem["SECTION"] as $name) {
                                    ?><div class="item"><?=$name?></div><?
                                } ?>
                                <?=$arItem["SECTION"]["NAME"]?>

                                </div><?
                            } ?>
                            <div class="blog-item-date">
                                <?=$arItem["DATE"]?>
                                <?if (!empty($arItem['RATING'])):?>
                                <div class="blog-item-raiting">Рейтинг: <?=$arItem['RATING']?>
                                    <svg width="14" height="14" viewBox="0 0 32 32" style="margin: 0 5px; fill: #fc6120;">
                                        <path d="M31.547 12a.848.848 0 00-.677-.577l-9.427-1.376-4.224-8.532a.847.847 0 00-1.516 0l-4.218 8.534-9.427 1.355a.847.847 0 00-.467 1.467l6.823 6.664-1.612 9.375a.847.847 0 001.23.893l8.428-4.434 8.432 4.432a.847.847 0 001.229-.894l-1.615-9.373 6.822-6.665a.845.845 0 00.214-.869z" />
                                    </svg>
                                </div>
                                <?endif;?>
                                <div  class="blog-item-shows">
                                    Просмотры: <?=$arItem["SHOWING_COUNT"]?>
                                </div>
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
