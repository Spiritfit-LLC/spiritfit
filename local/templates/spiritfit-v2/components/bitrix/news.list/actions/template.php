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
$this->setFrameMode(true);?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="action" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="action__image" style="background-image: url('<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>')">
			<div class="action__image action__image--mobile" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>')">
				<div class="action__image-date"><?= $arItem["DATE"] ?></div>
			</div>
		</div>
		<div class="action__info">
		<div class="action__info-text">
			<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="action__info-title js-pjax-link"><?= $arItem["~NAME"] ?></a>
			<div class="action__info-detail"><?= $arItem["~PREVIEW_TEXT"] ?></div>
		</div>

		<? if ($arItem["PROPERTIES"]["LINK_ACTIONS"]["VALUE_XML_ID"] == "MOBILE_LINK"): ?>
			<div class="action__info-cta">
				<div class="action__info-cta-text">Скачать приложение:</div>
					<a class="action__info-cta-btn btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_APPSTORE"]["VALUE"] ?>">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/appstore.png' ?>" alt="appstore logo">
					</a>
					<a class="action__info-cta-btn btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"] ?>">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/googleplay.png' ?>" alt="google play logo">
					</a>
			</div>
		<? endif; ?>

		<? if ($arItem["PROPERTIES"]["LINK_ACTIONS"]["VALUE_XML_ID"] == "PAY_SUBSCRIPTION"): ?>
			<div class="action__info-cta action__info-cta--links">
				<?if ($arItem['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE'] && $arItem['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']) {?>
					<?
						$checkLink = Utils::checkLink($arItem['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE']);
					?>
					<a class="action__info-cta-link <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arItem['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE']?>">
						<div class="action__info-cta-link-text"><?=$arItem['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']?></div>
					</a>
				<?}?>
				<?if ($arItem['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE'] && $arItem['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']) {?>
					<?
						$checkLink = Utils::checkLink($arItem['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']);
					?>
					<a class="action__info-cta-link <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arItem['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']?>">
						<div class="action__info-cta-link-text"><?=$arItem['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']?></div>
					</a>
				<?}?>
			</div>
		<? endif; ?>
		</div>
	</div>
<?endforeach;?>