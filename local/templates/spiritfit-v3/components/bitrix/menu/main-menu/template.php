<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if (!empty($arResult)):?>

<?php $isClient=PersonalUtils::IsClient();?>

	<ul class="b-top-menu__menu">
        <?if ($USER->IsAuthorized()):?>
            <li class="b-top-menu__item">
                <a href="/personal/" class="b-top-menu__link hidden-desktop"
                   data-layer="true"
                   data-layercategory="UX"
                   data-layeraction="clickLKbutton">Профиль</a>
            </li>
        <?endif;?>
		<? foreach($arResult as $arItem):
            if (!$isClient && $arItem["LINK"]=="/abonement/"){
                continue;
            }
			if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				continue; ?>
				<li class="b-top-menu__item">
					<a href="<?=$arItem["LINK"]?>" class="b-top-menu__link"><?= $arItem["TEXT"] ?></a>
                </li>
		<?endforeach?>


	</ul>

<?endif?>