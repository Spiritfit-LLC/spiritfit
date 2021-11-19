<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$settings = Utils::getInfo();
?>
<?if (!empty($arResult)):?>
	<nav class="header__nav">
		<div class="header__nav-wrap header__nav-wrap--link">
		<? foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
				continue; ?>
			<?if($arItem["SELECTED"] && !$arItem["PARAMS"]["class"]):?>
				<a href="<?=$arItem["LINK"]?>" class="header__nav-link header__nav-link--active js-pjax-link <?= $arItem["PARAMS"]["class"] ?>"><?= $arItem["TEXT"] ?></a>
			<?elseif ($arItem["PARAMS"]["tel"] == true):?>
				<a href="tel:<?=$arItem["LINK"]?>"  class="header__nav-link"><?=$arItem["TEXT"]?></a>
			<?elseif ($arItem["PARAMS"]["class"] !== 'header__nav-link--enroll'):?>
				<a href="<?=$arItem["LINK"]?>" class="header__nav-link js-pjax-link <?= $arItem["PARAMS"]["class"] ?>" ><?= $arItem["TEXT"] ?></a>
			<?endif?>
			<?endforeach?>
		</div>
		<? foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
				continue; ?>
				<?if($arItem["PARAMS"]["class"] == 'header__nav-link--enroll'):?>
				<div class="header__nav-wrap">
					<a href="<?=$arItem["LINK"]?>" class="header__nav-link js-pjax-link header__nav-link--enroll"><?= $arItem["TEXT"] ?></a>
				</div>
				<?endif?>
		<?endforeach?>
		<div class="header__nav-footer">
			<a class="header__nav-footer-inst" href="<?= $settings["PROPERTIES"]["LINK_INSTAGRAM"]["VALUE"] ?>">
				<div class="header__nav-footer-inst-icon"></div>
				<div class="header__nav-footer-inst-text">instagram</div>
			</a>
			<div class="header__nav-footer-btn btn btn--gray">Обратная связь</div>
		</div>
	</nav>
<?endif?>