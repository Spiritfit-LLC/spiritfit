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
    <section class="blog-cards">
        <div class="content-center">
            <div class="blog-cards__items">
                <?foreach ($arResult["ITEMS"] as $item):?>
                    <div class="blog-card__item">
                        <a href="<?=$item["DETAIL_PAGE_URL"]?>">
                            <div class="blog-card__img lazy-img-bg" data-src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>">
                                <div class="blog-card__tag"><?=Utils::GetIBlockSectionIDBySID($item["IBLOCK_SECTION_ID"], "NAME")?></div>
                            </div>
                            <div class="blog-card__content"><?=$item["NAME"]?></div>
                        </a>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif;?>