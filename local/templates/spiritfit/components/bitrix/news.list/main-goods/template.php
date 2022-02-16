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

<div class="grid__main">
	<div class="grid__main-title">Продукты</div>
	<?foreach($arResult["ITEMS"] as $key => $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<a class="grid-element js-pjax-link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="grid-element__type"><?= $arItem["PROPERTIES"]["NUMBER"]["VALUE"] ?></div>
			<div class="grid-element__icon"><img class="grid-element__icon-image" src="<?= $arItem["PROPERTIES"]["ICON_MAIN"]["RESIZE"]["src"] ?>" alt="<?= $arItem["~NAME"] ?>"></div>
			<div class="grid-element__heading"><?= $arItem["~NAME"] ?></div>
		</a>
	<?endforeach;?>
</div>