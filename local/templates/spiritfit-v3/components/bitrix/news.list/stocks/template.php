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

$this->addExternalCss(SITE_TEMPLATE_PATH . '/css/slick.css');
$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");
?>

<div class="content-center">
    <div class="stocks__main">
        <?foreach ($arResult["ITEMS"] as $ITEM):?>
            <div class="stock__item">
                <div class="stock__item-bg lazy-img-bg" data-src="<?=$ITEM["PREVIEW_PICTURE"]["SRC"]?>">
                    <?if (!empty($ITEM["PROPERTIES"]["BANNER_TITLE"]["VALUE"])):?>
                        <div class="stock__item-subtitle"><?=$ITEM["PROPERTIES"]["BANNER_TITLE"]["VALUE"]?></div>
                    <?endif;?>
                </div>
                <div class="stock__item-content">
                    <div class="stock__item-title">
                        <?=htmlspecialcharsback($ITEM["NAME"])?>
                    </div>
                    <div class="stock__item-text">
                        <?=htmlspecialcharsback($ITEM["PREVIEW_TEXT"])?>
                    </div>
                    <div class="stock__item-btn">
                        <a href="<?=$ITEM["DETAIL_PAGE_URL"]?>" class="button">Подробнее</a>
                    </div>
                </div>
            </div>
        <?endforeach?>
    </div>
</div>
