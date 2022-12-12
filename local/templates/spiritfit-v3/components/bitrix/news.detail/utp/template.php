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
?>


<section class="utp-detail">
    <div class="utp-detail__img" style="background-image: url(<?=$arResult["DETAIL_PICTURE"]["SRC"]?>)"></div>
    <div class="content-center text-content">
        <?for($i=0; $i<count($arResult["PROPERTIES"]["UTP_DESC"]["VALUE"]); $i++):?>
        <div class="utp-detail__description-item">
            <?if (!empty($arResult["PROPERTIES"]["UTP_DESC"]["DESCRIPTION"][$i])):?>
                <div class="utp-detail__description-title">
                    <h2 class="text-transform-none"><?=$arResult["PROPERTIES"]["UTP_DESC"]["DESCRIPTION"][$i]?></h2>
                </div>
            <?endif;?>
            <div class="utp-detail__description-content">
                <?=htmlspecialcharsback($arResult["PROPERTIES"]["UTP_DESC"]["VALUE"][$i]["TEXT"])?>
            </div>
        </div>
        <?endfor;?>
        <?if (!empty($arResult["PROPERTIES"]["UTP_LINK"]["VALUE"])):?>
        <div class="utp-detail__description-more">
            <a class="utp-detail__description-show-more" href="<?=$arResult["PROPERTIES"]["UTP_LINK"]["VALUE"]?>">Узнать больше <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/icon-arrow.svg')?></a>
        </div>
        <?endif;?>
    </div>
</section>