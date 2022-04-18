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
?>
<title><?= strip_tags($arResult["~NAME"]) ?></title>

<div class="subscription fixed">
	<a href="<?= $arResult["LIST_PAGE_URL"] ?>" class="subscription__close js-pjax-link">
		<div></div>
		<div></div>
	</a>
	<div class="subscription__main">
	<div class="subscription__stage">
		<div class="subscription__stage-item subscription__stage-item--done" data-stage="1">1. Регистрация</div>
		<div class="subscription__stage-item <?= $_REQUEST["step"] >= 1 ? "subscription__stage-item--done" : "" ?>" data-stage="2">2. Оформление</div>
		<div class="subscription__stage-item <?= $_REQUEST["step"] >= 2 ? "subscription__stage-item--done" : "" ?>" data-stage="3">3. Оплата</div>
	</div>
	<?if($_REQUEST["last_step"] == "Y" ):?>
		<div class="subscription__ready" style="display: block;">
			<div class="subscription__title">Абонемент готов</div>
			<div class="subscription__desc"><?= $arResult["~DETAIL_TEXT"] ?></div>
			<div class="subscription__info"><img class="subscription__info-img" src="<?=SITE_TEMPLATE_PATH?>/img/cloud-logo.png" alt="cloud logo">
			<div class="subscription__info-text">Для оплаты абонемента мы используем сервис CloudPayments, защищенный по технологии 3D secure. Это надежно и безопасно.</div>
			</div>
		</div>
	<?else:?>
		<div class="subscription__common">
			<div class="subscription__title"><?= $arResult["~NAME"] ?></div>
			<div class="subscription__desc"><?= $arResult["PREVIEW_TEXT"] ?></div>

			<? if ($arResult["PRICES"]): ?>
				<div class="subscription__label">
					<? foreach ($arResult["PRICES"] as $arPrice): ?>
						<div class="subscription__label-item">
							<?= $arPrice["SIGN"] ?>
							<? if ($arPrice["PRICE"]  && $arPrice["PRICE"] != " "): ?>
								 - <b><?= $arPrice["PRICE"] ?> руб.</b>
							<? endif; ?>
						</div>
					<? endforeach; ?>
				</div>
			<? endif; ?>

			<? if ($arResult["PROPERTIES"]["INCLUDE"]["VALUE"]): ?>
				<div class="subscription__subheading">Включено в абонемент:</div>
				<ul class="subscription__include">
					<? foreach ($arResult["PROPERTIES"]["INCLUDE"]["VALUE"] as $value): ?>
						<li class="subscription__include-item"><?= $value ?></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>

			<? if ($arResult["PROPERTIES"]["FOR_PRESENT"]["VALUE"]): ?>
				<div class="subscription__subheading">Услуги в подарок:</div>
				<ul class="subscription__gift">
					<? foreach ($arResult["PROPERTIES"]["FOR_PRESENT"]["VALUE"] as $value): ?>
						<li class="subscription__gift-item"><?= $value ?></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</div>
	<?endif;?>
</div>
<div class="subscription__aside">
	<? $APPLICATION->IncludeComponent(
		"custom:form.aboniment", 
		"", 
		array(
			"AJAX_MODE" => "N",
			"WEB_FORM_ID" => "2",
			"PRICE" => $arResult["PRICES"][0]["PRICE"],
			"SALE" => $arResult["SALE"],
			"DESCRIPTION_SALE" => $arResult["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"],
			"ADD_TO_1C" => $arResult["PROPERTIES"]["ADD_TO_1C"]["VALUE"],
		),
		false
	);?>
</div>