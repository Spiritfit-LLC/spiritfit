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
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>

<?/*<title><?= strip_tags($arResult["~NAME"]) ?></title>*/?>

<?php if ($arResult["PROPERTIES"]["PATH_TO"]["VALUE"]): ?>
	<div class="popup popup-path_to">
		<div class="popup__bg"></div>
		<div class="popup__window">
			<div class="popup__close">
				<div></div>
				<div></div>
			</div>
			<div class="popup__heading">Как добраться</div>
			<div class="popup__desc">
				<p><?=$arResult["PROPERTIES"]["PATH_TO"]["~VALUE"]["TEXT"]?></p>
			</div>
		</div>
	</div>
<?php endif ?>

<?
if($arResult["PREVIEW_PICTURE"]["SRC"]) {
	$ogImage = $arResult["PREVIEW_PICTURE"]["SRC"];
} else {
	$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
}

?>
<script type="text/javascript" src="https://profilepxl.ru/s.js?id=e42e8131-3e93-41a7-a220-aab564eb5a2d"></script>
<div id="seo-div" hidden="true"
	data-title="<?=$arResult['SEO']['ELEMENT_META_TITLE']?>"
	data-description="<?=$arResult['SEO']['ELEMENT_META_DESCRIPTION']?>"
	data-keywords="<?=$arResult['SEO']['ELEMENT_META_KEYWORDS']?>"
	data-image="<?=$ogImage?>"></div>
<input type="hidden" id="clubNumber" value="<?=$arResult['PROPERTIES']['NUMBER']['VALUE']?>"/>

<div class="club">
	<div class="block__detail-breadcrumb club__top">
    	<? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "custom",
            array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => "s1"
            )
        ); ?>
    </div>
	<?/*<div class="club__top">Клуб</div>*/?>
	<div class="club__head">
		<div>
			<h1 class="club__heading"><?= $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] ?></h1>
			<div class="club__subheading">Скоро открытие</div>
		</div>
		<div class="club__head-wrap-mobile">
			<div class="club__head-wrap">
				<a class="btn" href="#js-pjax-clubs">Оставить заявку</a>
				<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]): ?>
					<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]): ?>
						<? $cord = explode(',', $arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]); ?>
						<a class="btn" href="https://www.google.com/maps/dir/?api=1&destination=<?= $cord[0] ?>,<?= $cord[1] ?>" target="_blank">Как добраться</a>
					<? endif; ?>
				<? endif; ?>
			</div>
			<div class="club__tel call_phone_1"><a class="club__tel-link" onclick="dataLayerSend('UX', 'clickCallButton', '')" href="tel:<?= $arResult["PROPERTIES"]["PHONE"]["VALUE"] ?>"><?= $arResult["PROPERTIES"]["PHONE"]["VALUE"] ?></a></div>
		</div>
	</div>

	<div class="club__desc">
		<div class="club__desc-inner"><?= $arResult["PREVIEW_TEXT"] ?></div>
			<div>
				<a class="btn" href="#js-pjax-clubs">Оставить заявку</a>
				<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]): ?>
					<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]): ?>
						<? $cord = explode(',', $arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]); ?>
						<a class="btn btn--margin" href="https://www.google.com/maps/dir/?api=1&destination=<?= $cord[0] ?>,<?= $cord[1] ?>" target="_blank">Как добраться</a>
					<? endif; ?>
				<? endif; ?>
			</div>
	</div>


	<? if ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"]): ?>
		<h2 class="club__subheading">
			Фотогалерея
			<? if ($arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]): ?>
				<a class="btn" href="<?= $arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"] ?>" target="_blank">Виртуальный тур 360&deg;</a>
			<? endif; ?>
		</h2>
		<div class="club__slider js-club__slider-popup">
			<? foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"] as $key => $photo): ?>
				<div class="club__slider-item" data-src='<?= $photo ?>'>
					<div class="club__slider-item-inner">
					<div class="club__slider-item-text"><?= $arResult["PROPERTIES"]["PHOTO_GALLERY"]["DESCRIPTION"][$key] ?></div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	<? if ($arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]): ?>
		<a class="btn" href="<?=$arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]?>" target="_blank">Виртуальный тур 360°</a>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["SHOW_FORM"]["VALUE"]): ?>
		<div id="js-pjax-clubs">
		<?
			$APPLICATION->IncludeComponent(
				"custom:form.request",
				"clubs",
				array(
					"AJAX_MODE" => "N",
					"WEB_FORM_ID" => "5",
					"NUMBER" => $arResult["PROPERTIES"]["NUMBER"]["VALUE"],
					"TEXT_FORM" => $arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"],
				),
				false
			);
		?>
	<!-- Вывод ошибки в popup -->
	</div>
	<? endif; ?>


<? if ($arResult["ABONEMENTS"] ): ?>
	<h2 class="club__subheading">Абонемент</h2>
	<div class="club__team">
		<? foreach ($arResult["ABONEMENTS"] as $abonement): ?>
			<?
			$minPrice = '0';
			$abonemetPrices =[];
			foreach ($abonement["PRICES"] as $key => $price) {
				$abonemetPrices[$key] = $price['PRICE'];
			}
			$minFromAbonementPrices = min($abonemetPrices);
			if ($minFromAbonementPrices && $abonement["SALE"] && ($minFromAbonementPrices > $abonement["SALE"])) {
				$minPrice = $abonement["SALE"];
			}elseif($minFromAbonementPrices){
				$minPrice = $minFromAbonementPrices;
			}
			?>

			<div class="club__team-slide js-parent-slide">
				<div class="club__team-flip_box flip_box">
					<div class="club__team-slide-inner club__team-slide-front club__team-slide-front--subscription" data-src ='<?=CFile::GetPath($abonement['PREVIEW_PICTURE']);?>'>
						<div class="club__team-name club__team-name--bolder"><?= $abonement["~NAME"] ?></div>
						<div class="club__team-position club__team-position--free">
							<? if ($abonement["PROPERTIES"]["PRICE"]["VALUE"] && $abonement["PROPERTIES"]["PRICE"]["VALUE"][0]["PRICE"] != " " && $minPrice !== '0'): ?>
								<?= $minPrice ?> ₽
							<? else: ?>
							<?= $abonement["PROPERTIES"]["PRICE_SIGN"]["VALUE"] ?>
							<? endif; ?>
						</div>
					</div>
					<div class="club__team-slide-inner club__team-slide-back club__team-slide-back--subscription" >
						<div class="club__team-quote club__team-quote--subscription club__team-quote--subscription-gift"><?=$abonement['~PREVIEW_TEXT']?></div>
						<div class="club__team-wrapper-gift">
							<?
							$count = 0;
							foreach ($abonement['PROPERTIES']['FOR_PRESENT']['VALUE'] as $present) {?>
								<? if ($present['LIST'] == $arResult['ID'] && $count < 3){?>
									<p class="club__team-gift">
										<?='+ '.$present['PRICE']?>
									</p>
								<? 	$count++;
									} ?>
							<? } ?>
						</div>
						<?if (strlen($abonement["BASE_PRICE"]["PRICE"]) > 0){?>
							<div class="club__team-price club__team-price--gift">
								<? foreach ($abonement["PRICES"] as $key => $price):?>
									<div class="club__team-price-item">
										<div class="club__team-price-mounth"><?= $price["SIGN"] ?></div>
										<div class="club__team-price-wrap">
											<?if ($key == 0 && $abonement["SALE"]) {?>
												<span class="club__team-price-unit club__team-price-unit--old"><?= $price["PRICE"] ?>₽</span>
												<span class="club__team-price-unit club__team-price-unit--new"><?=$abonement["SALE"]?>₽</span>
											<?}elseif($key == 1 && $abonement["SALE_TWO_MONTH"]){?>
												<span class="club__team-price-unit club__team-price-unit--new"><?= $abonement["SALE_TWO_MONTH"] ?>₽</span>
											<?}else{?>
												<? if ($price["PRICE"]  && $price["PRICE"] != " "): ?>
													<span class="club__team-price-unit club__team-price-unit--new"><?= $price["PRICE"] ?>₽</span>
												<? endif; ?>
											<?}?>
											<? if ($minPrice == '0'){?>
												<?= $abonement["PROPERTIES"]["PRICE_SIGN"]["VALUE"]; ?>
											<?}?>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						<?}?>
						<? if ($abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]): ?>
							<p class="club__team-duration"><?= $abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></p>
						<? endif; ?>
						<a href="<?=$abonement['DETAIL_PAGE_URL']?>" class="btn btn--subscription js-pjax-link">Выбрать</a>
					</div>
				</div>
				<div class="r_wrap">
					<div class="b_round"></div>
					<div class="s_round">
						<div class="s_arrow"></div>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<? endif; ?>


	<? if ($arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"]): ?>
		<div class="video video_full" style="background-image: url('<?= $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] ?>')"></div>
	<? endif; ?>

	<div class="club__links">
		<?if ($arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE'] && $arResult['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']) {?>
			<?
				$checkLink = Utils::checkLink($arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']);
			?>
			<a class="club__links-item" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']?>">
				<div class="club__links-item-text" ><?=$arResult['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']?></div>
			</a>
		<?}?>
	</div>

</div>
<?
if (!$_SERVER['HTTP_X_PJAX']) {
	$APPLICATION->AddViewContent('inhead', $ogImage);
}
?>

<div class="popup popup--success popup--success-club js-club-popup-success" style="display: none;">
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close js-club-close-popup">
            <div></div>
            <div></div>
        </div>
        <div class="popup__success">Ваша заявка принята, консультанты клуба перезвонят Вам для согласования времени посещения.</div>
    </div>
</div>
