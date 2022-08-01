<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if (!empty($arResult)):?>

<?php $isClient=PersonalUtils::IsClient();?>

	<ul class="b-top-menu__menu">
        <?if ($USER->IsAuthorized()):?>
        <li>
            <a href="/personal/" class="b-top-menu__link is-hide-desktop"
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

        <?if (!$isClient):?>
        <li class="b-top-menu__item is-hide-mobile">
            <a class="b-top-menu__abonement-btn" href="/abonement/"
               data-layer="true"
               data-layercategory="UX"
               data-layeraction="clickBuyAbonementButton"
               data-layerlabel="header">купить абонемент</a>
        </li>
        <?endif;?>
	</ul>
<?endif?>