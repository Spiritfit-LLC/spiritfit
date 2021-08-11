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

$phone = $arResult['PROPERTIES']['PHONE']['VALUE'];
$email = $arResult['PROPERTIES']['EMAIL']['VALUE'];
$address = $arResult['PROPERTIES']['ADRESS']['VALUE'];
$workHours = $arResult['PROPERTIES']['WORK']['VALUE'];
$pathTo = $arResult['PROPERTIES']['PATH_TO']['VALUE'];
$cord = $arResult['PROPERTIES']['CORD_YANDEX']['VALUE'];
if(!empty($cord)){
	$cord = explode(',', $cord);
}

$mapName = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($arResult['NAME'])));
$mapAdress = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($address['TEXT'])));
if(strpos($mapAdress, '"')) $mapAdress = str_replace('"', '\'', $mapAdress);

session_start();
$_SESSION['CLUB_NUMBER'] = $arResult["PROPERTIES"]["NUMBER"]["VALUE"];
?>
<? if($_REQUEST["ajax_send"] != 'Y') { ?>
	<? if(!empty($arResult['ABONEMENTS']) && $arResult['PROPERTIES']['SOON']['VALUE'] != 'Y'){ ?>
		<section class="b-cards-slider b-cards-slider--with-prices">
			<div class="content-center">
				<div class="b-cards-slider__heading">
					<div class="b-cards-slider__title">
						<h2><?=($arResult['PROPERTIES']['BLOCK_ABONEMENT_NAME']['VALUE'] ? $arResult['PROPERTIES']['BLOCK_ABONEMENT_NAME']['VALUE'] : 'Абонементы')?></h2>
					</div>
					<div class="b-cards-slider__slider-nav"></div>
				</div>
			</div>
			<div class="b-cards-slider__slider-wrap">
				<div class="content-center">
					<div class="b-cards-slider__slider">
						<? foreach ($arResult['ABONEMENTS'] as $abonement) {
							if(!empty($abonement['PREVIEW_PICTURE'])){
								$img = CFile::getPath($abonement['PREVIEW_PICTURE']);
							}
							
							$abonement["PREVIEW_TEXT"] = strip_tags($abonement["PREVIEW_TEXT"]);
							$abonement["PREVIEW_TEXT"] = mb_strimwidth($abonement["PREVIEW_TEXT"], 0, 325, "...");

							$arDataAbonement = Abonement::getItem($abonement['ID'], $arResult['ID']); 
							$arDataAbonement = CUtil::PhpToJSObject($arDataAbonement);
							$arDataAbonement = str_replace("'", '"', $arDataAbonement);
							?>
							<script>
								if(window.abonement === undefined){ window.abonement = {} };
								window.abonement["<?=$abonement['ID']?>"] = <?=$arDataAbonement?>;
							</script>
							<div class="b-cards-slider__item">
								<div class="b-twoside-card">
									<div class="b-twoside-card__inner">
										<div class="b-twoside-card__content"
											style="background-image: url(<?=$img?>);">
											<img style="display: none;" src="<?=$img?>" alt="<?=$abonement['~NAME']?>">
											<div class="b-twoside-card__label"><?=$abonement['~NAME']?></div>
										</div>
										<div class="b-twoside-card__hidden-content">
											<div class="b-twoside-card__text"><?=$abonement['PREVIEW_TEXT']?></div>
											<div class="b-twoside-card__prices">
												
												<? foreach ($abonement["PRICES"] as $key => $price):?>
													<div class="b-twoside-card__prices-item">
														<div class="b-twoside-card__prices-title"><?= $price["SIGN"] ?></div>
														<?if ($key == 0 && $abonement["SALE"]) {?>
															<div class="b-twoside-card__prices-old"><?= $price["PRICE"] ?> <span class="rub">₽</span></div>
															<div class="b-twoside-card__prices-current"><?=$abonement["SALE"]?> <span class="rub">₽</span></div>
														<?}elseif($key == 1 && $abonement["SALE_TWO_MONTH"]){?>
															<div class="b-twoside-card__prices-current"><?=$abonement["SALE_TWO_MONTH"] ?> <span class="rub">₽</span></div>
														<?}else{?>
															<? if ($price["PRICE"]  && $price["PRICE"] != " "): ?>
																<div class="b-twoside-card__prices-current"><?=$price["PRICE"] ?> <span class="rub">₽</span></div>
															<? endif; ?>
														<?}?>
													</div>
												<? endforeach; ?>

												<a href="<?=$abonement['DETAIL_PAGE_URL']?>" class="b-twoside-card__prices-button button">Выбрать</a>

												<? 
												$showLinkForPopup = false;
												if($showLinkForPopup){ ?>
													<a href="#" data-code1c="<?=$abonement['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>" data-clubnumber="<?=$arResult["PROPERTIES"]["NUMBER"]["VALUE"]?>" data-abonementid="<?=$abonement['ID']?>" data-abonementcode="<?=$abonement['CODE']?>" class="b-twoside-card__prices-button button js-form-abonement">Выбрать</a>
												<? } ?>
												
											</div>
											
											<? if ($abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]){ ?>
												<div class="b-twoside-card__footnote"><?= $abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
											<? } ?>
										</div>
									</div>
								</div>
							</div>
						<? } ?>
						
					</div>
				</div>
			</div>
		</section>
	<? } ?>
<? } ?>

<? if($arResult["PROPERTIES"]["SOON"]["VALUE"] == 'Y') { ?>
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
	</div>
<? }else{ ?>
	<div id="js-pjax-clubs">
		<?
			$APPLICATION->IncludeComponent(
				"custom:form.aboniment",
				"clubs-v2",
				array(
					"AJAX_MODE" => "N",
					"WEB_FORM_ID" => "3",
					"NUMBER" => $arResult["PROPERTIES"]["NUMBER"]["VALUE"],
					"TEXT_FORM" => $arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"],
				),
				false
			);
		?>
	</div>
<? } ?>

<? if($_REQUEST["ajax_send"] != 'Y') { ?>
	<? if(!empty($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"])){ 
		$photoCount = count($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"]) - 1;
		
		?>
		<section class="b-image-plate-block b-image-plate-block_simple-mobile">
			<div class="content-center">
				<div class="b-image-plate-block__content">
					<div class="b-image-plate-block__slider-nav">
					</div>
					<div class="b-image-plate-block__img-holder b-image-plate-block__img-holder_slider">
						<? foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"] as $photo): ?>
							<div class="b-image-plate-block__slide">
								<img class="b-image-plate-block__slide-img" src="<?=$photo?>" alt="" role="presentation" />
							</div>
						<? endforeach; ?>
					</div>
					
						<div class="b-image-plate-block__text-content text-center">
							<div class="b-image-plate-block__text-content-inner">
								<div class="b-image-plate-block__text">
									<? 
									$i = 0;
									while ($i <= $photoCount) {
										$photoText = $arResult["PROPERTIES"]['PHOTO_DESC']['~VALUE'][$i];
										if(!empty($photoText['TEXT'])){ ?>
											<div class="b-image-plate-block__text-item">
												<div class="b-image-plate-block__text-item-wrap">
												<?=$photoText['TEXT']?>
												</div>
											</div>
										<? }else{ ?>
											<div class="b-image-plate-block__text-item"></div>
										<? }
										$i++;
									} ?>
								</div>
								<? if ($arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]){ ?>
									<a class="b-image-plate-block__btn button" href="<?= $arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"] ?>">Открыть 3D тур</a>
								<? } ?>
							</div>
						</div>
					
				</div>
			</div>
		</section>
	<? } ?>

	<? if(!empty($arResult["PROPERTIES"]["TEAM"]["ITEMS"])){ ?>
		<section class="b-cards-slider">
			<div class="content-center">
				<div class="b-cards-slider__heading">
					<div class="b-cards-slider__title">
						<h2>Команда</h2>
					</div>
					<div class="b-cards-slider__slider-nav">
					</div>
				</div>
			</div>
			<div class="b-cards-slider__slider-wrap">
				<div class="content-center">
					<div class="b-cards-slider__slider">
						<? foreach ($arResult["PROPERTIES"]["TEAM"]["ITEMS"] as $trainer) { ?>
							<div class="b-cards-slider__item">
								<div class="b-twoside-card">
									<div class="b-twoside-card__inner">
										<div class="b-twoside-card__content"
											style="background-image: url('<?=$trainer['PICTURE']['src']?>');">
											<img style="display: none;" src="<?=$trainer['PICTURE']['src']?>" alt="<?=$trainer['NAME']?>">
											<div class="b-twoside-card__label"><?=$trainer['NAME']?></div>
										</div>
										<div class="b-twoside-card__hidden-content">
											<div class="b-twoside-card__title"><?=$trainer['NAME']?></div>
											<div class="b-twoside-card__text"><?=$trainer['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT']?></div>
										</div>
									</div>
								</div>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
		</section>
	<? } ?>

	<? if($arResult['PROPERTIES']['SCHEDULE_JSON']['VALUE'] !== 'false' && !empty($arResult['PROPERTIES']['SCHEDULE_JSON']['VALUE'])){ ?>
		<? $APPLICATION->IncludeComponent(
			"custom:shedule.club", 
			"", 
			array(
				"IBLOCK_TYPE" => "content",
				"IBLOCK_CODE" => "clubs",
				"CLUB_NUMBER" => $arResult['PROPERTIES']['NUMBER']['VALUE'],
			),
			false
		); ?>
	<? } ?>

	<? if($arResult['PROPERTIES']['MAP_HIDDEN']['VALUE'] != 'Да'){ ?>

		<script>
			window.cord = [<?=$cord[0]?>, <?=$cord[1]?>];
			window.club = {
				id: "<?=$arResult['ID']?>",
				name: "<?=$mapName?>",
				title: "<?=$mapName?>",
				address: "<?=$mapAdress?>",
				phone: "<?=$phone?>",
				email: "<?=$email?>",
				workHours: "<?=$workHours?>",
				page: "<?=$APPLICATION->GetCurPage();?>",
			};
		</script>
		
		<?$APPLICATION->AddHeadString('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>',true)?>
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/map-leafletjs.js"></script>
		
		<div class="b-map">
			<div class="content-center is-hide-desktop">
				<h2 class="b-map__title">Контакты</h2>
			</div>
			<div class="b-map__map-wrap">
				<div class="b-map__map map-clubs-detail" id="mapid"></div>
				<div class="b-map__content">
					<div class="content-center">
						<div class="b-map__info-plate">
							<div class="b-map__info">
								<h2 class="b-map__info-title"><?=$arResult['~NAME']?></h2>
								<div class="b-map__contacts">
									<? if(!empty($address)){ 
										$address['TEXT'] = htmlspecialcharsBack($address['TEXT']);
										?>
										<div class="b-map__contact-item"><?=$address['TEXT']?></div>
									<? } ?>
									<div class="b-map__contact-item">
										<? if(!empty($phone)){ ?>
											<div><a class="invisible-link" href="tel:<?=$phone?>"><?=$phone?></a></div>
										<? } ?>
										<? if(!empty($email)){ ?>
											<div><a class="invisible-link" href="mailto:<?=$email?>"><?=$email?></a></div>
										<? } ?>
									</div>
									<? if(!empty($workHours)){ ?>
										<div class="b-map__contact-item">
											<div><?=$workHours?></div>
										</div>
									<? } ?>
								</div>
							</div>
							<div class="b-map__buttons">
								<a class="b-map__button button-outline" href="#js-pjax-clubs">Отправить заявку</a>
								<? if(!empty($pathTo)){ ?>
									<a class="b-map__button button-outline custom-button" href="#route-window" data-fancybox="route-window">Как добраться</a>
								<? } ?>
							</div>
							<? if(!empty($pathTo)){ 
								$pathTo['TEXT'] = htmlspecialcharsBack($pathTo['TEXT']);
								?>
								<div class="b-map__route is-hide" id="route-window">
									<div class="content-area">
										<?=$pathTo['TEXT']?>
									</div>
								</div>
							<? } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<? } ?>
	<div itemscope itemtype="http://schema.org/ExerciseGym" style="display: none;">
		<span itemprop="name">Spirit.Fitness</span>
		<meta itemprop="legalName" content="ООО Рекорд Фитнес">
		<link itemprop="url" href="https://spiritfit.ru/">
		<? if( !empty($arResult['PREVIEW_PICTURE']['SRC']) ) { ?>
			<span itemprop="image"><?=$_SERVER['REQUEST_SCHEME']?>://<?=$_SERVER['SERVER_NAME']?><?=$arResult['PREVIEW_PICTURE']['SRC']?></span>
		<? } ?>
		<span itemprop="priceRange"><?=$arResult['ABONEMENTS_MIN_PRICE']?>-<?=$arResult['ABONEMENTS_MAX_PRICE']?></span>  
		<img itemprop="logo" src=" http://spiritfit.ru/images/logo.svg ">
		<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<meta itemprop="addressCountry" content="Россия">
			<span itemprop="streetAddress"><?=$arResult['ADDRESS_SHORT']?></span>
			<? if( !empty($arResult['PROPERTIES']['INDEX']['VALUE']) ) { ?>	
				<span itemprop="postalCode"><?=$arResult['PROPERTIES']['INDEX']['VALUE']?></span>
			<? } ?>
			<span itemprop="addressLocality">Москва</span>
		</div>
		<span itemprop="telephone">+7(495)266-40-95</span>
		<? if( !empty($arResult['PROPERTIES']['EMAIL']['VALUE']) ) { ?>	
			<span itemprop="email"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></span>
		<? } ?>
		<meta itemprop="openingHours" content="Mo-Su 07:00-24:00">
  </div>

<? } ?>