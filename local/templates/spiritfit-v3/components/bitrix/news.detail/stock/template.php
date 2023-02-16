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

<section class="stock-detail">
    <div class="stock-detail__img lazy-img-bg" data-src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"></div>
    <div class="content-center">
        <?for($i=0; $i<count($arResult["PROPERTIES"]["DESCRIPTION"]["VALUE"]); $i++):?>
            <div class="stock-detail__description-item">
                <?if (!empty($arResult["PROPERTIES"]["DESCRIPTION"]["DESCRIPTION"][$i])):?>
                    <div class="stock-detail__description-title">
                        <h2 class="text-transform-none"><?=$arResult["PROPERTIES"]["DESCRIPTION"]["DESCRIPTION"][$i]?></h2>
                    </div>
                <?endif;?>
                <div class="stock-detail__description-content">
                    <?=htmlspecialcharsback($arResult["PROPERTIES"]["DESCRIPTION"]["VALUE"][$i]["TEXT"])?>
                </div>
            </div>
        <?endfor;?>
        <?if (!empty($arResult["PROPERTIES"]["UTP_LINK"]["VALUE"])):?>
            <div class="stock-detail__description-more">
                <?
                if (empty($arResult["PROPERTIES"]["UTP_LINK"]["DESCRIPTION"])){
                    $btn_name="Узнать больше";
                }
                else{
                    $btn_name=$arResult["PROPERTIES"]["UTP_LINK"]["DESCRIPTION"];
                }
                ?>
                <a class="stock-detail__description-show-more" href="<?=$arResult["PROPERTIES"]["UTP_LINK"]["VALUE"]?>"><?=$btn_name?> <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/icon-arrow.svg')?></a>
            </div>
        <?endif;?>
    </div>
</section>
<?php if ($arResult["PROPERTIES"]["FORM_SID"]["VALUE"]):?>
<section id="form" style="margin-top: 80px;">
    <?
    $APPLICATION->IncludeComponent(
        "custom:form.request.new",
        "on.page.block",
        array(
            "COMPONENT_TEMPLATE" => "on.page.block",
            "WEB_FORM_ID" => Utils::GetFormIDBySID($arResult["PROPERTIES"]["FORM_SID"]["VALUE"]),
            "WEB_FORM_FIELDS" => array(
                0 => "club",
                1 => "name",
                2 => "phone",
                3 => "email",
                4 => "personaldata",
                5 => "rules",
                6 => "privacy",
            ),
            "FORM_TYPE" =>$arResult["PROPERTIES"]["FORM_TYPE"]["VALUE"],
            "TEXT_FORM" => $arResult["PROPERTIES"]["FORM_TITLE"]["VALUE"],
        ));
    ?>
</section>
<?php endif;?>