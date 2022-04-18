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


<? if (!$app): ?>
	<div class="product">
<? else: ?>
	<div class="application">
<? endif; ?>
	<? if (!$app): ?>
		<div class="product__aside">
			<? if ($arResult["PROPERTIES"]["ICON_DETAIL"]["RESIZE"]["src"]): ?>
				<img class="product__aside-icon" src="<?= $arResult["PROPERTIES"]["ICON_DETAIL"]["RESIZE"]["src"] ?>" alt="product image">
			<? endif; ?>
		</div>
		<div class="product__main">
	<? endif; ?>
	<?
		if($arResult["PROPERTIES"]["ICON_DETAIL"]["RESIZE"]["src"]) {
			$ogImage = $arResult["PROPERTIES"]["ICON_DETAIL"]["RESIZE"]["src"];
		} else {
			$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
		}
	?>

	<div id="seo-div" hidden="true"
		data-title="<?=$arResult['SEO']['ELEMENT_META_TITLE']?>"
		data-description="<?=$arResult['SEO']['ELEMENT_META_DESCRIPTION']?>"
		data-keywords="<?=$arResult['SEO']['ELEMENT_META_KEYWORDS']?>"
		data-image="<?=$ogImage?>"></div>
    <div class="block__detail-breadcrumb <?= $app ? 'application__top' : 'product__top' ?>">
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
	<div class="<?= $app ? 'application__top' : 'product__top' ?>">

			<? if ($arResult["SETTINGS"]["PROPERTIES"]["LINK_MOBILE"]["VALUE"] == $arResult["DETAIL_PAGE_URL"]): ?>
				<div class="application__top-type"> <?/*App*/?></div>
			<? else: ?>
				<div class="product__top-num"> <?//= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] ?></div>
			<? endif; ?>

			<div class="product__top-buttons">
				<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_APPSTORE"]["VALUE"] ?>" target="_blank">
					<img src="<?= SITE_TEMPLATE_PATH . "/img/appstore.png" ?>" alt="appstore logo">
				</a>
				<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"] ?>" target="_blank">
					<img src="<?=  SITE_TEMPLATE_PATH . "/img/googleplay.png" ?>" alt="google play logo">
				</a>
				<a href="/" class="product__close js-pjax-link">
					<div></div>
					<div></div>
				</a>
			</div>
	</div>

	<h1 class="<?= $app ? 'application__heading' : 'product__heading' ?>">
		<?= $arResult["~NAME"] ?>
		<? if (!$app): ?>
			<img class="product__heading-icon" src="<?= $arResult["PROPERTIES"]["ICON_MAIN"]["RESIZE"]["src"] ?>" alt="<?= $arResult["~NAME"] ?>">
		<? endif; ?>
	</h1>
	<?if ($arResult["CODE"] != "mobilnoe-prilozheniya") {?>
		<div class="application__heading-wrap">
			<a class="btn js-pjax-link" href="/schedule/">Расписание</a>
		</div>
	<? } ?>
	<?if ($app) {?>
		<div class="application__desc club__desc">
			<div class="application__desc-inner club__desc-inner">
				<?= $arResult["~PREVIEW_TEXT"] ?>
			</div>
			<?if ($arResult["CODE"] != "mobilnoe-prilozheniya") {?>
				<a class="btn js-pjax-link" href="/schedule/">Расписание</a>
			<? } ?>
		</div>
	<?}else{?>
		<div class="product__desc"><?= $arResult["~PREVIEW_TEXT"] ?></div>
	<?}?>

	<? if ($arResult["PROPERTIES"]["ENTER_SYSTEM"]["VALUE"]): ?>
		<div class="product__subheading">Что входит в систему:</div>
		<div class="product__contains">
			<? foreach ($arResult["PROPERTIES"]["ENTER_SYSTEM"]["ITEMS"] as $row): ?>
				<div class="product__contains-row">
					<? foreach ($row as $arItem): ?>
						<div class="product__contains-cell">
							<img class="product__contains-icon" src="<?= $arItem["PICTURE"]["src"] ?>" alt="contains icon 1">
							<div class="product__contains-text"><?= $arItem["~NAME"] ?></div>
						</div>
					<? endforeach; ?>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"]): ?>
		<div class="video <?= $app ? 'video--application' : 'video--product' ?>"
			style="background-image: url('<?= $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] ?>')">
			<div class="video__info">
				<div class="video__info-icon"></div>
				<div class="video__info-text">Смотреть<br>обучающее видео</div>
			</div>
		</div>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"]): ?>
		<div class="popup popup--video">
			<div class="popup__bg"></div>
			<div class="popup__window">
				<div id="player" data-id-video="<?=$arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"]?>"></div>
				<div class="popup__close">
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["HOW_USE"]["VALUE"]): ?>
		<div class="product__subheading">Как воспользоваться системой:</div>
		<div class="product__list">
			<? foreach ($arResult["PROPERTIES"]["HOW_USE"]["ITEMS"] as $column): ?>
				<div class="product__list-col">
					<? foreach ($column as $key => $arItem): ?>
						<div class="product__list-item">
							<div class="product__list-item-num"><?= $key + 1 ?>.</div>
							<div class="product__list-item-text"><?= $arItem ?></div>
						</div>
					<? endforeach; ?>
				</div>
			<? endforeach; ?>
		</div>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["REVIEWS"]["VALUE"] && !$app): ?>
		<div class="product__slider">
			<div class="product__slider-inner">
				<? foreach ($arResult["PROPERTIES"]["REVIEWS"]["ITEMS"] as $arItem): ?>
					<div class="product__slider-slide">
						<div class="product__slider-slide-inner">
							<div class="product__slider-slide-info">
								<div class="product__slider-slide-info-icon" style="background-image: url('<?= $arItem["PICTURE"]["src"] ?>')"></div>
								<div class="product__slider-slide-info-name"><?= $arItem["~NAME"] ?></div>
							</div>
							<div class="product__slider-slide-feedback"><?= $arItem["PREVIEW_TEXT"] ?></div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
		</div>
	<? endif; ?>
	<?
	// костыль для отображения слайдеров с обрезанными картинками телефонов
	$additionalSliderClass1 = "";
	$additionalSliderClass2 = "";
	$curPage = $APPLICATION->GetCurPage();
	$pageArray = array(
		"/catalog/trenirovki-br-v-prilozhenii/",
		"/catalog/mobilnoe-prilozheniya/",
	);
	if(in_array($curPage, $pageArray)) {
		$additionalSliderClass1 = "application__possibilities--img-bottom";
		$additionalSliderClass2 = "application__possibilities-slide-wrap--img-bottom";
	}
	
	?>

	<? if ($arResult["PROPERTIES"]["OPPORTUNITIES"]["VALUE"]): ?>
		<? if ($arResult["PROPERTIES"]["TITLE_OPPORTUNITIES"]["VALUE"]): ?>
			<div class="application__subheading"><?= $arResult["PROPERTIES"]["TITLE_OPPORTUNITIES"]["VALUE"] ?></div>
		<? else: ?>
			<div class="application__subheading">Возможности</div>
		<? endif; ?>
		<div class="application__possibilities <?=$additionalSliderClass1?>">
			<? foreach ($arResult["PROPERTIES"]["OPPORTUNITIES"]["ITEMS"] as $key => $arItem): ?>
				<div data-page="<?=$curPage?>" data-temp="1:goods-detail" class="application__possibilities-slide application__possibilities-slide--traning">
					<div class="application__possibilities-slide-wrap <?=$additionalSliderClass2?><?/*flip_box*/?>">
						<div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
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
						<div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
							<div class="application__possibilities-info">
								<div class="application__possibilities-info-num"><?= $key + 1 ?></div>
								<div class="application__possibilities-info-title"><?= $arItem["~NAME"] ?></div>
								<div class="application__possibilities-info-desc"><?= $arItem["PREVIEW_TEXT"] ?></div>
							</div>
						</div>
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
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["GALLERY"]["VALUE_SRC"]):?>
		<div class="club__subheading">Фотогалерея</div>
		<div class="club__slider js-club__slider-popup">
			<? foreach ($arResult["PROPERTIES"]["GALLERY"]["VALUE_SRC"] as $key => $value) :?>
				<div class="club__slider-item" data-mfp-src='<?=$value;?>'
					data-src=<?=$value;?>>
					<div class="club__slider-item-inner">
						<div class="club__slider-item-text"><?=$arResult["PROPERTIES"]["GALLERY"]["~DESCRIPTION"][$key]?></div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	<? endif;?>

	<? if ($app): ?>
		<? if ($arResult["ABONEMENTS"]): ?>
		<div class="club__subheading">Абонемент</div>
		<div class="club__team">
			<? foreach ($arResult["ABONEMENTS"] as $abonement): ?>
				<?
				$minPrice = '0';
				$abonemetPrices = [];
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
						<div class="club__team-slide-inner club__team-slide-front club__team-slide-front--subscription"
							data-src='<?=CFile::GetPath($abonement['PREVIEW_PICTURE']);?>'>
							<div class="club__team-name club__team-name--bolder"><?= $abonement["~NAME"] ?></div>
							<div class="club__team-position club__team-position--free">
								<? if ($abonement["PROPERTIES"]["PRICE"]["VALUE"] && $abonement["PROPERTIES"]["PRICE"]["VALUE"][0]["PRICE"] != " " && $minPrice !== '0'): ?>
									<?= $minPrice ?> руб.
								<? else: ?>
								<?= $abonement["PROPERTIES"]["PRICE_SIGN"]["VALUE"] ?>
								<? endif; ?>
							</div>
						</div>
						<div class="club__team-slide-inner club__team-slide-back club__team-slide-back--subscription">
							<div class="club__team-quote club__team-quote--subscription club__team-quote--subscription-gift"><?=$abonement['~PREVIEW_TEXT']?></div>
							<div class="club__team-wrapper-gift">
								<?
								$clubForAbonement = Utils::getClubByCode('setevoy-abonement');
								if (!empty($clubForAbonement)) {
									$count = 0;
									foreach ($abonement['PROPERTIES']['FOR_PRESENT']['VALUE'] as $present) {?>
										<? if ($present['LIST'] == $clubForAbonement['ID'] && $count < 3) {?>
											<p class="club__team-gift"><?='+ '.$present['PRICE']?></p>
										<? 	$count++;
										}
									}
								}?>
							</div>
							<?if (strlen($abonement["BASE_PRICE"]["PRICE"]) > 0){?>
								<div class="club__team-price club__team-price--gift">
									<? foreach ($abonement["PRICES"] as $key => $price):?>
										<div class="club__team-price-item">
											<div class="club__team-price-mounth"><?= $price["SIGN"] ?></div>
											<div class="club__team-price-wrap">
												<?if ($key == 0 && $abonement["SALE"]) {?>
													<span class="club__team-price-unit club__team-price-unit--old"><?= $price["PRICE"] ?> руб.</span>
													<span class="club__team-price-unit club__team-price-unit--new"><?=$abonement["SALE"]?> руб.</span>
												<?}elseif($key == 1 && $abonement["SALE_TWO_MONTH"]){?>
													<span class="club__team-price-unit club__team-price-unit--new"><?= $abonement["SALE_TWO_MONTH"] ?> руб.</span>
												<?}else{?>
													<? if ($price["PRICE"]  && $price["PRICE"] != " "): ?>
														<span class="club__team-price-unit club__team-price-unit--new"><?= $price["PRICE"] ?> руб.</span>
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
							<a href="<?=$abonement['DETAIL_PAGE_URL']?>" class="btn btn--subscription">Выбрать</a>
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
		<? if ($arResult["PROPERTIES"]["SHOW_FORM"]["VALUE"]): ?>
		<div id="js-pjax-clubs">
			<?
				$APPLICATION->IncludeComponent(
					"custom:form.aboniment",
					"clubs",
					array(
						"AJAX_MODE" => "N",
						"WEB_FORM_ID" => "3",
						"NUMBER" => $arResult["PROPERTIES"]["NUMBER"]["VALUE"],
						"TEXT_FORM" => $arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"],
					),
					false
				);
			?>
			<!-- Вывод ошибки в popup -->
		</div>
		<? endif; ?>
	<? endif; ?>

	<? if ($app): ?>
		<? if ($arResult["PROPERTIES"]["REVIEWS"]["VALUE"]): ?>
			<div data-temp="1:goods-detail" class="application_slider application_slider--rewievs product__slider">
				<div class="product__slider-inner">
					<? foreach ($arResult["PROPERTIES"]["REVIEWS"]["ITEMS"] as $arItem): ?>
						<div class="product__slider-slide">
							<div class="product__slider-slide-inner">
							<div class="product__slider-slide-info">
								<div class="product__slider-slide-info-icon" style="background-image: url('<?= $arItem["PICTURE"]["src"] ?>')"></div>
								<div class="product__slider-slide-info-name"><?= $arItem["~NAME"] ?></div>
							</div>
							<div class="product__slider-slide-feedback"><?= $arItem["PREVIEW_TEXT"] ?></div>
							</div>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		<? else: ?>
			<div class="application__cta">
				<div class="application__cta-text">С нашим приложением вы добьетесь любых поставленных спортивных целей!</div>
				<div class="application__cta-buttons">
					<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_APPSTORE"]["VALUE"] ?>" target="_blank">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/appstore.png' ?>" alt="appstore">
					</a>
					<a class="btn btn--download" href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_GOOGLEPLAY"]["VALUE"] ?>" target="_blank">
						<img src="<?= SITE_TEMPLATE_PATH . '/img/googleplay.png' ?>" alt="google play">
					</a>
				</div>
			</div>
		<? endif; ?>
	<? endif; ?>

	<? if ($app): ?>
		<div class="application__links">
			<?if ($arResult['PROPERTIES']['BTN_LEFT']['VALUE']) {?>
				<?
					$checkLink = Utils::checkLink($arResult['PROPERTIES']['BTN_LEFT']['~VALUE']);
				?>
				<a class="application__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['BTN_LEFT']['~VALUE']?>">
					<div class="application__links-item-text"><?=$arResult['PROPERTIES']['BTN_LEFT']['~DESCRIPTION']?></div>
				</a>
			<?}else{?>
				<a class="application__links-item js-pjax-link" href="/clubs/">
					<div class="application__links-item-text">Выбрать клуб</div>
				</a>
			<?}?>
			<?if ($arResult['PROPERTIES']['BTN_RIGHT']['VALUE']) {?>
				<?
					$checkLink = Utils::checkLink($arResult['PROPERTIES']['BTN_RIGHT']['~VALUE']);
				?>
				<a class="application__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['BTN_RIGHT']['~VALUE']?>">
					<div class="application__links-item-text"><?=$arResult['PROPERTIES']['BTN_RIGHT']['~DESCRIPTION']?></div>
				</a>
			<?}else{?>
				<a class="application__links-item js-pjax-link" href="/abonement/">
					<div class="application__links-item-text">Купить абонемент</div>
				</a>
			<?}?>
		</div>
	<? else: ?>
		<div class="product__links">
		<?if ($arResult['PROPERTIES']['BTN_LEFT']['VALUE']) {?>
			<?
				$checkLink = Utils::checkLink($arResult['PROPERTIES']['BTN_LEFT']['~VALUE']);
			?>
			<a class="product__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['BTN_LEFT']['~VALUE']?>">
				<div class="product__links-item-text product__links-item-text--app"><?=$arResult['PROPERTIES']['BTN_LEFT']['~DESCRIPTION']?></div>
			</a>
		<?} else {?>
			<?
				$checkLink = Utils::checkLink($arResult["SETTINGS"]["PROPERTIES"]["LINK_MOBILE"]["VALUE"]);
			?>
			<a class="product__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?= $arResult["SETTINGS"]["PROPERTIES"]["LINK_MOBILE"]["VALUE"] ?>">
				<div class="product__links-item-text product__links-item-text--app">Мобильное приложение</div>
			</a>
		<?}?>
		<?if ($arResult['PROPERTIES']['BTN_RIGHT']['VALUE']) {?>
			<?
				$checkLink = Utils::checkLink($arResult['PROPERTIES']['BTN_RIGHT']['~VALUE']);
			?>
			<a class="product__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['BTN_RIGHT']['~VALUE']?>">
				<div class="product__links-item-text product__links-item-text--choose"><?=$arResult['PROPERTIES']['BTN_RIGHT']['~DESCRIPTION']?></div>
			</a>
		<?} else {?>
			<a class="product__links-item js-pjax-link" href="/clubs/">
				<div class="product__links-item-text product__links-item-text--choose">Выбрать клуб</div>
			</a>
		<?}?>
		</div>
	<? endif; ?>

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