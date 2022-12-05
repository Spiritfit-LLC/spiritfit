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

<?if (!empty($arResult["ITEMS"])):?>
<section class="utp-main">
    <div class="content-center">
        <div class="utp-main__items">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <div class="utp-main__item">
                    <div class="utp-item__image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>')"></div>
                    <div class="utp-item__text">
                        <div class="utp-item__title"><?=$arItem["NAME"]?></div>
                        <a href="/utp/<?=$arItem["CODE"]?>/" class="button utp">Подробнее</a>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</section>
<?endif;?>