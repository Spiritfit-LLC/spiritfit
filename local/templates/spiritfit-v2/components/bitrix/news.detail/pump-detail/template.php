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
$app = $arResult["PROPERTIES"]["MOBILE"]["VALUE_XML_ID"] == "YES" ? true : false;
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<?/*<title><?= strip_tags($arResult["~NAME"]) ?></title>*/?>
<h1 class="application__heading"><?=$arResult["NAME"]?></h1>
<div class="application__desc club__desc">
<div class="application__desc-inner club__desc-inner">
<?=$arResult["PREVIEW_TEXT"]?>
</div>
</div>

<? if ($arResult["PROPERTIES"]["PARTICIPATE"]["VALUE"]): ?>
    <div class="product__subheading product__subheading-pump">Как принять участие:</div>
    <div class="product__list product__list-pump">
            <div class="product__list-col product__list-col-pump">
                <? foreach ($arResult["PROPERTIES"]["PARTICIPATE"]["VALUE"] as $key => $arItem): ?>
                    <div class="product__list-item">
                        <div class="product__list-item-num"><?= $key + 1 ?>.</div>
                        <div class="product__list-item-text"><?= $arItem ?></div>
                    </div>
                <? endforeach; ?>
            </div>
    </div>
<? endif; ?>

<div class="application__subheading"><?=$arResult["PROPERTIES"]["TITLE_PRIZES"]["VALUE"]?></div>
<div class="application__possibilities">
    <? foreach ($arResult["PROPERTIES"]["PRIZES"]["ITEMS"] as $key => $arItem): ?>
        <div data-temp="2:pump-detail" class="application__possibilities-slide application__possibilities-slide--traning">
            <div class="application__possibilities-slide-wrap flip_box">
                <div class="application__possibilities-slide-inner <?/*application__possibilities-slide-inner--front*/?>">
                    <div class="application__possibilities-info">
                        <div class="application__possibilities-info-num"><?= $key + 1 ?></div>
                        <div class="application__possibilities-info-title"><?= $arItem["~NAME"] ?></div>
                        <div class="application__possibilities-slide-pic">
                            <img class="application__possibilities-slide-pic-img"
                                src="<?= $arItem["PICTURE"]["src"] ?>" alt="<?= $arItem["~NAME"] ?>">
                         </div>
                        <div class="application__possibilities-info-desc"><?= $arItem["PREVIEW_TEXT"] ?></div>
                    </div>
                   
                </div>
                <?/*div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                    <div class="application__possibilities-info">
                        <div class="application__possibilities-info-num"><?= $key + 1 ?></div>
                        <div class="application__possibilities-info-title"><?= $arItem["~NAME"] ?></div>
                        <div class="application__possibilities-info-desc"><?= $arItem["PREVIEW_TEXT"] ?></div>
                    </div>
                </div*/?>
            </div>
            <?/*div class="r_wrap">
                <div class="b_round"></div>
                <div class="s_round">
                    <div class="s_arrow"></div>
                </div>
            </div*/?>
        </div>
    <? endforeach; ?>
</div>
<div id="js-pjax-clubs">
    <?php
    $APPLICATION->IncludeComponent(
        "custom:form.request",
        "clubs-pump",
        array(
            "AJAX_MODE" => "N",
            "WEB_FORM_ID" => "5",
            "NUMBER" => $arResult["PROPERTIES"]["NUMBER"]["VALUE"],
            "TEXT_FORM" => $arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"],
        ),
        false
    );
    ?>

</div>
    <!-- Вывод ошибки в popup -->


<div class="video video_full" style="background-image: url('<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>')"></div>
<div class="club__links">
    <a class="club__links-item" href="<?=$arResult["PROPERTIES"]["URL_BUTTON"]["VALUE"]?>">
        <div class="club__links-item-text"><?=$arResult["PROPERTIES"]["TITLE_BUTTON"]["VALUE"]?></div>
    </a>
</div>