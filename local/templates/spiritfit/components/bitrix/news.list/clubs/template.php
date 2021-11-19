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
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>

<div class="grid fixed">
	<div class="grid__inner">
		<div class="grid__aside">
			<div class="grid-element grid-element--aside-big">
				<h1 class="grid-element__head"><?= $arResult["SECTION"]["~NAME"] ?></h1>
				<div class="grid-element__desc"><?= $arResult["SECTION"]["DESCRIPTION"] ?></div>
				<? if ($arResult["SECTION"]["UF_LINK_FAQ"]): ?>
					<a class="grid-element__link js-pjax-link" href="<?= $arResult["SECTION"]["UF_LINK_FAQ"] ?>">FAQ</a>
				<? endif; ?>
			</div>
		</div>
		<?$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);?>
		<div id="seo-div" hidden="true"
	 		  data-title="<?=$arResult['SEO']['SECTION_META_TITLE']?>"
	 		  data-description="<?=$arResult['SEO']['SECTION_META_DESCRIPTION']?>"
			  data-keywords="<?=$arResult['SEO']['SECTION_META_KEYWORDS']?>"
			  data-image="<?=$ogImage?>"></div>
		<div class="grid__main">
			<? foreach ($arResult["ITEMS"] as $arItem): ?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<? if ($arItem["PROPERTIES"]["SOON"]["VALUE"]): ?>
					<? if ($arItem["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"] == ''){ ?>
						<a class="grid-element grid-element--club js-pjax-link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<? } else { ?>
						<div class="grid-element grid-element--club" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<? } ?>
							<div class="grid-element__bg" style="background-image:url(<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>)"></div>
							<div class="grid-element__type"><?/*= $arItem["PROPERTIES"]["NUMBER"]["VALUE"]*/ ?></div>
							<div class="grid-element__heading">СКОРО<br><?= $arItem["~NAME"] ?></div>
					<? if ($arItem["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"] == ''){ ?>
						</a>
					<? } else { ?>
						</div>
					<? } ?>
				<? else: ?>
					<? if ($arItem["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"] == ''){ ?>
						<a class="grid-element grid-element--club js-pjax-link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<? } else { ?>
						<div class="grid-element grid-element--club" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<? } ?>
						<div class="grid-element__bg" style="background-image:url(<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>)"></div>
						<div class="grid-element__type"><?/*= $arItem["PROPERTIES"]["NUMBER"]["VALUE"] */?></div>
						<div class="grid-element__heading"><?= $arItem["~NAME"] ?></div>
					<? if ($arItem["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"] == ''){ ?>
						</a>
					<? } else { ?>
						</div>
					<? } ?>
				<? endif; ?>
			<? endforeach; ?>
		</div>
	</div>
</div>
