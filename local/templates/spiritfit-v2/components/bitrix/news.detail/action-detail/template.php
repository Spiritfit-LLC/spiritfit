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
$APPLICATION->SetTitle($arResult["~NAME"]);
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<?/*<title><?= strip_tags($arResult["~NAME"]) ?></title>*/?>

<div class="action">
    <a href="" class="action__close js-pjax-link" onclick="window.history.back();">
        <div></div>
        <div></div>
    </a>
    
    <div class="action__image" style="background-image: url('<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>')">
        <div class="action__image action__image--mobile" style="background-image: url('<?= $arResult["PREVIEW_PICTURE"]["SRC"] ?>')">
            <div class="action__image-date"><?= $arResult["DATE"] ?></div>
        </div>
    </div>

    <?
    if($arResult["PREVIEW_PICTURE"]["SRC"]) {
		$ogImage = $arResult["PREVIEW_PICTURE"]["SRC"];
    } else { 
		$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
    }
	?>
	<div id="seo-div" hidden="true"
	 	 data-title="<?=$arResult['SEO']['ELEMENT_META_TITLE']?>" 
		 data-description="<?=$arResult['SEO']['ELEMENT_META_DESCRIPTION']?>" 
         data-keywords="<?=$arResult['SEO']['ELEMENT_META_KEYWORDS']?>"
         data-image="<?=$ogImage?>"></div>

    <div class="action__info">
        <div class="action__info-text">
        <div class="block__detail-breadcrumb">
        <? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "custom",
            array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => "s1"
            )
        ); ?>
        </div>
            <h1 class="action__info-title"><?= $arResult["~NAME"] ?></h1>
            <div class="action__info-detail">
                <? if ($arResult["DETAIL_TEXT"]):
                    echo $arResult["DETAIL_TEXT"];
                else: 
                    echo $arResult["PREVIEW_TEXT"];
                endif; ?>
            </div>
        </div>

        <? if ($arResult["PROPERTIES"]["LINK_ACTIONS"]["VALUE_XML_ID"] == "MOBILE_LINK"): ?>
            <div class="action__info-cta">
                <div class="action__info-cta-text">Скачать приложение:</div>
                    <a class="action__info-cta-btn btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_APPSTORE"]["VALUE"] ?>" target="_blank">
                        <img src="<?= SITE_TEMPLATE_PATH . '/img/appstore.png' ?>" alt="appstore logo">
                    </a>
                    <a class="action__info-cta-btn btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"] ?>" target="_blank">
                        <img src="<?= SITE_TEMPLATE_PATH . '/img/googleplay.png' ?>" alt="google play logo">
                    </a>
            </div>
        <? endif; ?>

        <? if ($arResult["PROPERTIES"]["LINK_ACTIONS"]["VALUE_XML_ID"] == "PAY_SUBSCRIPTION"): ?>
            <div class="action__info-cta action__info-cta--links">
            <?if ($arResult['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE'] && $arResult['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']) {?>
                <a class="action__info-cta-link js-pjax-link" href="<?=$arResult['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE']?>">
                    <div class="action__info-cta-link-text"><?=$arResult['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']?></div>
                </a>
            <?}?>
            <?if ($arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE'] && $arResult['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']) {?>
                <a class="action__info-cta-link js-pjax-link" href="<?=$arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']?>">
                    <div class="action__info-cta-link-text"><?=$arResult['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']?></div>
                </a>
            <?}?>
            </div>
        <? endif; ?>
    </div>
</div>
<?
if (!$_SERVER['HTTP_X_PJAX']) {
	$APPLICATION->AddViewContent('inhead', $ogImage);
}
?>