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

// пробная подписка
define('ABTEST_TRIAL_SUBSCRIPTION', true);
$abTestClubs = [
	/*'metro-semenovskaya',
	'metro-voykovskaya',
	'metro-ramenki',
	'gorod-br-reutov'*/
];
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
		<h1 class="club__heading"><?= $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] ?></h1>
		<div class="club__head-wrap-mobile">
			<div class="club__head-wrap">
				<a class="btn js-pjax-link" href="/schedule/?club=<?= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] ?>">Расписание</a>
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
			<div class="block_right-club-info">
				<a class="btn js-pjax-link" href="/schedule/?club=<?= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] ?>">Расписание</a>
				<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]): ?>
					<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]): ?>
						<? $cord = explode(',', $arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]); ?>
						<a class="btn btn--margin" href="https://www.google.com/maps/dir/?api=1&destination=<?= $cord[0] ?>,<?= $cord[1] ?>" target="_blank">Как добраться</a>
					<? endif; ?>
				<? endif; ?>
				<div class="block__info-club" itemscope itemtype="http://schema.org/Organization">
					<meta itemprop="name" content="Spirit.Fitness">
					<meta itemprop="telephone" content="<?= $arResult["PROPERTIES"]["PHONE"]["VALUE"] ?>">
					<?if (!empty($arResult["PROPERTIES"]["ADRESS"]["~VALUE"]["TEXT"])):?>
					<div class="info-club__adress" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<div><span class="heading__info-club">Адрес клуба:</span></div> <div><?=$arResult["PROPERTIES"]["ADRESS"]["~VALUE"]["TEXT"]?></div>
					</div>
					<?endif;?>
					<?if (!empty($arResult["PROPERTIES"]["WORK"]["VALUE"])):?>
					<div class="info-club__work">
						<div><span class="heading__info-club">Часы работы:</span></div> <div><?=$arResult["PROPERTIES"]["WORK"]["VALUE"]?></div>
					</div>
					<?endif;?>
				</div>
			</div>

	</div>

	<div class="club__subheading club__subheading--hidden">О клубе</div>
	<? if ($arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"]): ?>
		<div class="video" style="background-image: url('<?= $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] ?>')">
			<div class="video__info">
				<div class="video__info-icon"></div>
				<div class="video__info-text">Смотреть видео</div>
			</div>
		</div>
	<? endif; ?>

	<? if ($arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"]) { ?>
		<div class="popup popup--video" itemscope itemtype="http://schema.org/VideoObject">
			<meta itemprop="name" content="О клубе" />
			 <meta itemprop="description" content="<?= $arResult["PREVIEW_TEXT"] ?>" />
			 <meta itemprop="duration" content="<?=$arResult["PROPERTIES"]["TIME_VIDEO"]["VALUE"]?>">
    		<meta itemprop="isFamilyFriendly" content="true">
            <meta itemprop="uploadDate" content="<?=(new \Bitrix\Main\Type\DateTime($arItem['TIMESTAMP_X']))->format('Y-m-d');?>">
			<div class="popup__bg"></div>
				<div class="popup__window" itemtype="http://schema.org/ImageObject" itemprop="thumbnail">
					<meta itemprop="thumbnailUrl" content="https://spiritfit.ru<?= $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] ?>">
					<div id="player" data-id-video="<?=$arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"]?>"></div>
				<div class="popup__close">
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	<? } ?>

	<? if ($arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"]): ?>
		<h2 class="club__subheading">Преимущества</h2>
		<div class="club__advantages">
			<? foreach ($arResult["PROPERTIES"]["ADVANTAGES"]["ITEMS"] as $arItem): ?>
            <div class="club__advantages-slide js-parent-slide">
                <div class="club__advantages-slide-wrap <?/*flip_box*/?>">
                    <div class="club__advantages-slide-inner <?/*club__advantages-slide-inner--front*/?>">
                        <div class="club__advantages-slide-title"><?= $arItem["~NAME"] ?></div>
                        <div class="club__advantages-slide-pic">
                            <img alt="<?= $arItem["~NAME"] ?>"  class="club__advantages-slide-pic-img" data-src="<?= $arItem["PICTURE"]["src"] ?>" alt="advantages slider img">
                        </div>
                    </div>
                    <div class="club__advantages-slide-inner <?/*club__advantages-slide-inner--back*/?>">
                        <div class="club__advantages-info">
                            <div class="club__advantages-info-title"><?= $arItem["~NAME"] ?></div>
                            <div class="club__advantages-info-desc"><?= $arItem["PREVIEW_TEXT"] ?></div>
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
	<? if ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"]): ?>
		<h2 class="club__subheading">
			Фотогалерея
			<? if ($arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"]): ?>
				<a class="btn" href="<?= $arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"] ?>" target="_blank">Виртуальный тур 360&deg;</a>
			<? endif; ?>
		</h2>
		<div class="club__slider js-club__slider-popup">
			<? foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"] as $key => $photo): ?>
				<div class="club__slider-item" <?/*data-src='<?= $photo ?>'*/?>>
					<img alt="<?= $arResult["PROPERTIES"]["PHOTO_GALLERY"]["DESCRIPTION"][$key] ?>"  class="club__slider-item-img" src="<?= $photo ?>">
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

			// Для клубов из списка
			if(ABTEST_TRIAL_SUBSCRIPTION && in_array($arResult['CODE'], $abTestClubs)) {
				$IS_TRIAL = true;
				$abonement['DETAIL_PAGE_URL'] = "/abonement/modal.php?ELEMENT_CODE=probnaya-trenirovka&club=".$arResult['PROPERTIES']['NUMBER']['VALUE'];
			}

			// Только для Федерации
			/*if(ABTEST_TRIAL_SUBSCRIPTION && $abonement['CODE'] == 'ezhemesyachnyy-abonement-po-podpiske' && $arResult['CODE']  == 'bashnia-federatsia') {
				$abonement["PRICES"][0]["SIGN"] = "Пробный месяц";
				unset($abonement["PRICES"][1]);
			} */

			?>

			<div class="club__team-slide js-parent-slide">
				<div class="club__team-flip_box flip_box">
					<div class="club__team-slide-inner club__team-slide-inner_custom club__team-slide-front club__team-slide-front--subscription_custom club__team-slide-front--subscription" <?/*data-src ='<?=CFile::GetPath($abonement['PREVIEW_PICTURE']);?>'*/?>>
						<img alt="<?= $abonement["~NAME"] ?>" class="club__team-img" src='<?=CFile::GetPath($abonement['PREVIEW_PICTURE']);?>' >
						<div class="club__team-info">
							<div class="club__team-name club__team-name_custom club__team-name--bolder"><?= $abonement["~NAME"] ?></div>
							<?php $nameAboniment = $abonement["~NAME"]; ?>
							<div class="club__team-position club__team-position_custom club__team-position--free">
							<? if ($abonement["PROPERTIES"]["PRICE"]["VALUE"] && $abonement["PROPERTIES"]["PRICE"]["VALUE"][0]["PRICE"] != " " && $minPrice !== '0'): ?>
								<?= $minPrice ?> руб.
							<? else: ?>
							<?= $abonement["PROPERTIES"]["PRICE_SIGN"]["VALUE"] ?>
							<? endif; ?>
							</div>
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
												<span class="club__team-price-unit club__team-price-unit--old"><?= $price["PRICE"] ?> руб. </span>
												<span class="club__team-price-unit club__team-price-unit--new"><?=$abonement["SALE"]?> руб. </span>
											<?}elseif($key == 1 && $abonement["SALE_TWO_MONTH"]){?>
												<span class="club__team-price-unit club__team-price-unit--new"><?= $abonement["SALE_TWO_MONTH"] ?> руб. </span>
											<?}else{?>
												<? if ($price["PRICE"]  && $price["PRICE"] != " "): ?>
													<span class="club__team-price-unit club__team-price-unit--new"><?= $price["PRICE"] ?> руб. </span>
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
						<? if($IS_TRIAL) { ?>
							<a href="<?=$abonement['DETAIL_PAGE_URL']?>&aboniment=<?=$nameAboniment?>" class="btn btn--subscription js-trial-subscription">Выбрать</a>
						<? } else { ?>
							<a href="<?=$abonement['DETAIL_PAGE_URL']?>" class="btn btn--subscription js-pjax-link">Выбрать</a>
						<? } ?>
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
	<? if ($arResult["PROPERTIES"]["TEAM"]["VALUE"]): ?>
		<h2 class="club__subheading">Команда</h2>
		<div class="club__team">
			<? foreach ($arResult["PROPERTIES"]["TEAM"]["ITEMS"] as $slide): ?>
				<?foreach ($slide as $arItem): ?>
					<div class="club__team-slide js-parent-slide">
						<div class="club__team-flip_box flip_box">
							<div class="club__team-slide-inner club__team-slide-inner_custom club__team-slide-front club__team-slide-front_custom" <?/*data-src ='<?=$arItem["PICTURE"]["src"]?>'*/?>>
								<img alt="<?=$arItem["NAME"]?>" class="club__team-img" src="<?=$arItem["PICTURE"]["src"]?>">
								<div class="club__team-info">
									<div class="club__team-name club__team-name_custom"><?=$arItem["NAME"]?></div>
									<div class="club__team-position"><?=$arItem["PROPERTIES"]["POSITION"]["VALUE"]?></div>
								</div>
							</div>
							<?if ($arItem['BACK']) {?>
								<div class="club__team-slide-inner club__team-slide-back" <?=$arItem["BACK"]["IMAGE"] ? 'style="background-image: url(\''. $arItem["BACK"]["IMAGE"] . '\')"' : ''?>>
									<div class="club__team-quote" <?=$arItem["BACK"]["COLOR"] ? 'style="color: #'. $arItem["BACK"]["COLOR"] . '"' : ''?>>
										<?=$arItem["BACK"]["TEXT"]?>
									</div>
								</div>
							<?}?>
						</div>
						<div class="r_wrap">
							<div class="b_round"></div>
							<div class="s_round">
								<div class="s_arrow"></div>
							</div>
						</div>
					</div>
				<?endforeach;?>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	<? if ($arResult["PROPERTIES"]["CORD_YANDEX"]): ?>
		<?php if ($arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]): ?>
			<? $cord = explode(',', $arResult["PROPERTIES"]["CORD_YANDEX"]["VALUE"]); ?>
			<div class="club__map" id="map" data-coord-mark-lat="<?= $cord[0] ?>" data-coord-mark-lng="<?= $cord[1] ?>" data-coord-center-lat="<?= $cord[0] ?>" data-coord-center-lng="<?= $cord[1] ?>">
				<div class="club__map-address">
					<?= $arResult["PROPERTIES"]["ADRESS"]["~VALUE"]["TEXT"] ?>
					<div class="btn btn_path_to js-path-to">
						<span>Как добраться</span>
					</div>
				</div>
				<div class="club__map-contacts">
					<div class="call_phone_1">
						<a href="tel:<?= $arResult["PROPERTIES"]["PHONE"]["CALL"] ?>" onclick="dataLayerSend('UX', 'clickCallButton', '')"><?= $arResult["PROPERTIES"]["PHONE"]["~VALUE"] ?></a>
					</div>
					<br>
					<a href="mailto:<?= $arResult["PROPERTIES"]["EMAIL"]["~VALUE"] ?>"><?= $arResult["PROPERTIES"]["EMAIL"]["~VALUE"] ?></a>
				</div>
				<?php if ($arResult["PROPERTIES"]["PATH_TO"]["VALUE"]): ?>
					<div class="btn btn_path_to js-path-to">
						<span>Как добраться</span>
					</div>
				<?php endif ?>
			</div>
		<?php endif ?>
	<?endif;?>

	<div class="club__links">
		<?if ($arResult['PROPERTIES']['NOT_OPEN_YET']['VALUE_XML_ID'] == 'YES') {?>
            <a class="club__links-item js-pjax-link" href="/catalog/mobilnoe-prilozheniya/">
                <div class="club__links-item-text">Мобильное приложение</div>
            </a>
        <?} elseif ($arResult['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE'] && $arResult['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']) {?>
			<?
				$checkLink = Utils::checkLink($arResult['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE']);
			?>
			<a class="club__links-item <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['LEFT_BUTTON_LINK']['VALUE']?>">
				<div class="club__links-item-text" ><?=$arResult['PROPERTIES']['LEFT_BUTTON_TEXT']['VALUE']?></div>
			</a>
        <?}?>
		<?if ($arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE'] && $arResult['PROPERTIES']['RIGHT_BUTTON_TEXT']['VALUE']) {?>
			<?
				$checkLink = Utils::checkLink($arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']);
			?>
			<a class="club__links-item <? if ($arResult["PROPERTIES"]["SHOW_SIGN_FORM"]["VALUE"]): ?>open--popup--sign<?endif;?> <?if ($checkLink) {?>js-pjax-link<?}?>" <?if (!$checkLink) {?>rel="nofollow" target="_blank"<?}?> href="<?=$arResult['PROPERTIES']['RIGHT_BUTTON_LINK']['VALUE']?>">
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

<? if ($arResult["PROPERTIES"]["SHOW_SIGN_FORM"]["VALUE"]): ?>
    <div class="popup popup--sign" style="display: none;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close">
                <div></div>
                <div></div>
            </div>
            <div class="popup__heading">ПРЕДВАРИТЕЛЬНАЯ ЗАПИСЬ</div>
            <form class="popup__form" method="POST" enctype="multipart/form-data">

                <div class="popup__desc">Необходима только для посещения групповых тренировок по расписанию</div>

                <div class="popup__form-row">
                    <input type="text" name="sign_club" value="<?= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] ?>" hidden>

                    <input class="input input--text input--name" type="text" placeholder="Ваше имя" name="sign_name" maxlength="20" required>

                    <input class="input input--tel" type="tel" placeholder="Ваш телефон" name="sign_phone" inputmode="text" required>

                    <select class="input input--select js-time-select" name="sign_time" required>
                        <option disabled="disabled" selected="selected">Выберите время</option>
                        <option value="7:30 - 9:00">7:30 - 9:00</option>
                        <option value="9:00 - 10:30">9:00 - 10:30</option>
                        <option value="10:30 - 12:00">10:30 - 12:00</option>
                        <option value="12:00 - 13:30">12:00 - 13:30</option>
                        <option value="13:30 - 15:00">13:30 - 15:00</option>
                        <option value="15:00 - 16:30">15:00 - 16:30</option>
                        <option value="16:30 - 18:00">16:30 - 18:00</option>
                        <option value="18:00 - 19:30">18:00 - 19:30</option>
                        <option value="19:30 - 21:00">19:30 - 21:00</option>
                        <option value="21:00 - 22:30">21:00 - 22:30</option>
                        <option value="22:30 - 00:00">22:30 - 00:00</option>
                    </select>
                </div>

                <div class="popup__privacy__form-row">
                    <label class="input-label">
                        <input class="input input--checkbox" type="checkbox" name="form_checkbox_privacy" checked value="14" required>
                        <div class="input-label__text">Согласен с&nbsp;<a href="/upload/form/Политика_конфиденциальности.pdf" target="_blank">Политикой конфиденциальности</a></div>
                    </label>
                </div>

                <input class="popup__btn btn js-sign-submit" type="submit" value="Отправить">
            </form>
        </div>
    </div>

    <div class="popup popup--success-sign" style="display: none;">
        <div class="popup__bg"></div>
        <div class="popup__window">
            <div class="popup__close js-club-close-popup">
                <div></div>
                <div></div>
            </div>
            <div class="popup__success" style="font-size: 22px;font-weight: normal;">Заявка принята, ждём вас в клубе.</div>
        </div>
    </div>

    <style>
        .popup--sign .input--select.jq-selectbox .jq-selectbox__dropdown ul {
            max-height: 300px;
        }
    </style>
<?endif;?>

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

<div class="popup popup--trial" id="trial-subscription-popup" style="display: none;">
    <div class="popup__bg"></div>
    <div class="popup__window">
    	<div class="popup__window-wrapper">
	        <div class="popup__close">
	            <div></div>
	            <div></div>
	        </div>
	        <div id="trial-subscription-container"></div>
    	</div>
    </div>
</div>

<style>
.popup--trial .popup__window {
	background: none;
	padding: 0;
}
.popup--trial .popup__close {
	z-index: 1;
	border: none;
}
.popup--trial .popup__close:hover {
	background: white;
}
.popup__window-wrapper {
	position: relative;
}
@media screen and (max-width: 1260px), screen and (min-aspect-ratio: 76/35) and (min-width: 1260px) {
	.popup--trial .popup__window {
		align-items: center;
	}
}
@media screen and (max-width: 552px){
	.club__team-slide-front--subscription_custom{
		padding: 0!important;
	}
}
</style>
<script>
	$(document).ready(function() {
		$(document).on('click', '.js-trial-subscription', function(e) {
			e.preventDefault();
			var aboniment = $(this).closest('.club__team-slide-back--subscription').find('.club__team-price-mounth').text();
			var url = e.target.getAttribute('href');
			$.pjax({
				timeout: false,
				url: url,
				container: '#trial-subscription-container',
				push: false,
				scrollTo: false,
				fragment: '#trial-subscription-fragment'
			});
		});
		$(document).on('pjax:success', '#trial-subscription-container', function(e) {
			$('#trial-subscription-popup').fadeIn(300);
		});
	});
</script>