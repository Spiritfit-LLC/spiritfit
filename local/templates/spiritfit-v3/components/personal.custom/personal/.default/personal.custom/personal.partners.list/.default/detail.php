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
$this->setFrameMode(false);
?>

<div class="ppd__header">
    <div class="ppd__header-img" style="background-image: url('<?=CFile::GetPath($arResult["DETAIL_PICTURE"])?>')"></div>
    <div class="ppd__header-content">
        <div class="ppd__header-title"><?=$arResult["TITLE"]?></div>
        <div class="ppd__header-call2action"><?=$arResult["CALL2ACTION"]?></div>
        <?if ($arResult["ACTIVE_TO"]):?>
            <div class="pp-item__date detail">
                <div class="pp-item-date__icon">
                    <?=file_get_contents(__DIR__.'/images/time-icon.svg')?>
                </div>
                <span>Воспользуйтесь до <?=FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_TO"]))?></span>
            </div>
        <?endif;?>

        <?if ($arResult["TYPE"]=="code"):?>
            <div class="ppd__header--btn">
                <button type="button" class="button" onclick="copy_partner_promocode('<?=$arResult["PROMOCODE"]?>')"><?=$arResult["PROMOCODE"]?></button>
            </div>
        <?elseif ($arResult["TYPE"]=="qr"):?>
            <div class="ppd__header--btn">
                <a class="button" href="<?=$arResult["QR_LINK"]?>" target="_blank">Открыть QR-код</a>
            </div>
        <?elseif ($arResult["TYPE"]=="link"):?>
            <div class="ppd__header--btn">
                <a class="button" href="<?=$arResult["BTN"]["href"]?>"><?=$arResult["BTN"]["value"]?></a>
            </div>
        <?elseif($arResult["TYPE"]=="trigger"):?>
            <div class="ppd__header--btn">
                <button class="button" onclick="$('#<?=$arResult["BTN"]["trigger"]?>').click(); $('#partner-detail-popup').fadeOut(300)"><?=$arResult["BTN"]["value"]?></button>
            </div>
        <?endif?>
    </div>
</div>
<div class="ppd__main">
    <?=htmlspecialcharsback($arResult["DETAIL_TEXT"])?>
</div>
