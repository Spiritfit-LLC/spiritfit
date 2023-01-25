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
                    <div class="b-twoside-card utp-main__item">
                        <div class="b-twoside-card__inner">
                            <div class="b-twoside-card__content">
                                <div class="utp-item__image" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"></div>
                                <div class="utp-item__text">
                                    <div class="utp-item__title"><?=$arItem["NAME"]?></div>
                                    <button class="button utp">Подробнее</button>
                                </div>
                            </div>
                            <div class="b-twoside-card__hidden-content">
                                <div class="utp-item__text-detail">
                                    <?=htmlspecialcharsback($arItem["PREVIEW_TEXT"])?>
                                </div>
                                <?if ($arItem["PROPERTIES"]["UTP_LINK"]["VALUE"]):?>
                                    <?if (!empty($arItem["PROPERTIES"]["UTP_LINK"]["DESCRIPTION"])){
                                        $btn_text=$arItem["PROPERTIES"]["UTP_LINK"]["DESCRIPTION"];
                                    }
                                    else{
                                        $btn_text="Подробнее";
                                    }?>
                                    <a href="<?=$arItem["PROPERTIES"]["UTP_LINK"]["VALUE"]?>" class="button"><?=$btn_text?></a>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif;?>
