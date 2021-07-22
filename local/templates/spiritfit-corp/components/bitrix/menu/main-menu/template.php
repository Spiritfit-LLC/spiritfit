<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if (!empty($arResult)):?>
	<ul class="b-top-menu__menu">
		<? foreach($arResult as $arItem):
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
				continue; ?>
				<li class="b-top-menu__item">
					<a href="<?=$arItem["LINK"]?>" class="b-top-menu__link"><?= $arItem["TEXT"] ?></a>
                </li>
		<?endforeach?>
	</ul>
<?endif?>