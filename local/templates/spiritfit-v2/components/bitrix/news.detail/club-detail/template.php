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
//$pathToMASSTRANSIT = $arResult['PROPERTIES']['PATH_TO']['VALUE'];
//$pathToAUTO = $arResult['PROPERTIES']['PATH_TO_AUTO']['VALUE'];
$cord = $arResult['PROPERTIES']['CORD_YANDEX']['VALUE'];
if(!empty($cord)){
    $cord = explode(',', $cord);
}

//$pathToImageSrc = "";
//if( !empty($arResult['PROPERTIES']['PATH_TO_IMAGE']["VALUE"]) ) {
//	$pathToImageSrc = CFile::ResizeImageGet($arResult['PROPERTIES']['PATH_TO_IMAGE']["VALUE"], array("width" => 800, "height" => 600), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
//}

$mapName = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($arResult['NAME'])));
$mapAdress = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($address['TEXT'])));
if(strpos($mapAdress, '"')) $mapAdress = str_replace('"', '\'', $mapAdress);

session_start();
$_SESSION['CLUB_NUMBER'] = $arResult["PROPERTIES"]["NUMBER"]["VALUE"];


?>
<? if($_REQUEST["ajax_send"] != 'Y') { ?>
    <?if (!empty($arResult['PROPERTIES']['UTP']['VALUE'])):?>
        <div class="club-utp">
            <?for ($i=0; $i<count($arResult['PROPERTIES']['UTP']['VALUE']); $i++):?>
                <div class="club-utp__item">
                    <div class="club-utp__item-icon" style='background-image: url("<?=CFile::GetPath($arResult['PROPERTIES']['UTP']['VALUE'][$i])?>")'></div>
                    <div class="club-utp__item-title">
                        <span><?=htmlspecialcharsBack($arResult['PROPERTIES']['UTP']['DESCRIPTION'][$i])?></span>
                    </div>
                    <?if (!empty($arResult['PROPERTIES']['UTP_DESC']['VALUE'][$i]["TEXT"])):?>
                        <div class="club-utp__desc">
                            <?=htmlspecialcharsBack($arResult['PROPERTIES']['UTP_DESC']['VALUE'][$i]['TEXT'])?>
                        </div>
                    <?endif;?>
                </div>
            <?endfor;?>
            <?if (!empty($arResult['PROPERTIES']['UTP_LINK']['VALUE'])):?>
                <a href="<?=$arResult['PROPERTIES']['UTP_LINK']['VALUE']?>" class="button club-utp__btn" style="margin-top: 30px; margin-bottom: 50px"><?=$arResult['PROPERTIES']['UTP_LINK']['DESCRIPTION']?></a>
            <?endif;?>
        </div>
    <?endif;?>
    <? if((!empty($arResult['ABONEMENTS']) && ($arResult['PROPERTIES']['SOON']['VALUE'] != 'Y' || !empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']))) && !$arResult["PROPERTIES"]["HIDE_ABONEMENT"]["VALUE_XML_ID"]=='Y'){ ?>
        <section id="abonements" class="b-cards-slider b-cards-slider--with-prices">
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
                            $imageSrc = "";
                            if(!empty($abonement['PREVIEW_PICTURE'])){
                                $img = CFile::getPath($abonement['PREVIEW_PICTURE']);
                                $imageSrc = CFile::ResizeImageGet($abonement['PREVIEW_PICTURE'], array('width' => 379, 'height' => 580), BX_RESIZE_IMAGE_EXACT)["src"];
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
                                <div class="b-twoside-card" data-sub_id="<?=$abonement['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>">
                                    <div class="b-twoside-card__inner">
                                        <div class="b-twoside-card__content"
                                             style="background-image: url(<?=$imageSrc?>);">
                                            <!--<img style="display: none;" src="<?=$imageSrc?>" alt="<?=$abonement['~NAME']?>">-->
                                            <div class="b-twoside-card__label"><?=$abonement['~NAME']?></div>
                                        </div>
                                        <div class="b-twoside-card__hidden-content">
                                            <!--<div class="b-twoside-card__text"><?//=$abonement['PREVIEW_TEXT']?></div>-->
                                            <? if( !empty($abonement["PROPERTIES"]["INCLUDE"]["VALUE"]) ) { ?>
                                                <div class="corp-abonement__front-list">
                                                    <!--noindex-->
                                                    <? foreach($abonement["PROPERTIES"]["INCLUDE"]["VALUE"] as $listItem) { ?>
                                                        <div class="corp-abonement__front-list-item"><?=htmlspecialcharsback($listItem)?></div>
                                                    <? } ?>
                                                    <!--/noindex-->
                                                </div>
                                            <? } ?>
                                            <div class="b-twoside-card__prices">
                                                <? if( $abonement['ID'] == 226 ) { ?>
                                                    <div class="b-twoside-card__prices-item">
                                                        <div class="b-twoside-card__prices-old">1000 <span class="rub">₽</span></div>
                                                        <div class="b-twoside-card__prices-current">0 <span class="rub">₽</span></div>
                                                    </div>
                                                <? } else { ?>
                                                    <?
                                                    $discountSecond = [];
                                                    foreach ($abonement["PRICES"] as $key => $price) {
                                                        if( intval($price["NUMBER"]) == 99 ) {
                                                            $discountSecond = $price;
                                                        }
                                                    }
                                                    ?>
                                                    <? foreach ($abonement["PRICES"] as $key => $price):?>
                                                        <? if( intval($price["NUMBER"]) == 99 ) continue; ?>
                                                        <div class="b-twoside-card__prices-item">
                                                            <div class="b-twoside-card__prices-title"><?=$price["SIGN"] ?></div>
                                                            <? if( $key == 1 && !empty($discountSecond) && !empty($discountSecond["PRICE"]) && $discountSecond["PRICE"] != " " ) { ?>
                                                                <div class="b-twoside-card__prices-old"><?=$discountSecond["PRICE"] ?> <span class="rub">₽</span></div>
                                                            <? } ?>
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
                                                <? } ?>

                                                <?
                                                $showLinkForPopup = false;
                                                if($showLinkForPopup){ ?>
                                                    <a href="#" data-code1c="<?=$abonement['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>" data-clubnumber="<?=$arResult["PROPERTIES"]["NUMBER"]["VALUE"]?>" data-abonementid="<?=$abonement['ID']?>" data-abonementcode="<?=$abonement['CODE']?>" class="b-twoside-card__prices-button button js-form-abonement">Купить</a>
                                                <? } ?>

                                            </div>

                                            <? if ($abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]){ ?>
                                                <div class="b-twoside-card__footnote"><?= $abonement["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></div>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="b-twoside-card__footer">
                                        <a class="button-outline b-twoside-card-detail-btn">Подробнее</a>
                                        <a class="b-twoside-card__prices-button button <?=$abonement['PROPERTIES']['ADDITIONAL_CLASS']['VALUE']?> choose-abonement-btn" href="<?=$abonement['DETAIL_PAGE_URL']?>" data-sub_id="<?=$abonement['PROPERTIES']['CODE_ABONEMENT']['VALUE']?>" style="display: none;">Купить</a>
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

<? if( (empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['SHOW_FORM']['VALUE']))
    || (!empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['HIDE_LINK_FORM']['VALUE'])) ) { ?>
    <? if($arResult["PROPERTIES"]["SOON"]["VALUE"] == 'Y') { ?>
        <div id="form-request">
            <?
            $APPLICATION->IncludeComponent(
                "custom:form.request.new",
                "on.page.block",
                array(
                    "AJAX_MODE" => "N",
                    "COMPONENT_TEMPLATE" => "on.page.block",
                    "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                    "WEB_FORM_FIELDS" => array(
                        0 => "name",
                        1 => "phone",
                        2 => "email",
                        3 => "personaldata",
                        4 => "rules",
                        5 => "privacy",
                    ),
                    "FORM_TYPE" => $arResult["FORM_TYPE"],
                    "CLUB_ID" => $arResult["ID"],
                    "TEXT_FORM" => $arResult["FORM_TITLE"],
                    "CLIENT_TYPE"=>$arResult["PROPERTIES"]["CLIENT_TYPE"]["VALUE"],
                ),
                false);
            ?>
        </div>
    <? } else { ?>
        <div id="form-request">
        <?
            $APPLICATION->IncludeComponent(
                "custom:form.request.new",
                "on.page.block",
                array(
                    "AJAX_MODE" => "N",
                    "COMPONENT_TEMPLATE" => "on.page.block",
                    "WEB_FORM_ID" => Utils::GetFormIDBySID('TRIAL_TRAINING_NEW'),
                    "WEB_FORM_FIELDS" => array(
                        0 => "name",
                        1 => "phone",
                        2 => "email",
                        3 => "personaldata",
                        4 => "rules",
                        5 => "privacy",
                    ),
                    "FORM_TYPE" => $arResult["FORM_TYPE"],
                    "TEXT_FORM" => $arResult["FORM_TITLE"],
                    "CLUB_ID"=>$arResult["ID"],
                    "CLIENT_TYPE"=>$arResult["PROPERTIES"]["CLIENT_TYPE"]["VALUE"],
                ),
                false);
            ?>
        </div>
    <? } ?>
<? } ?>

<? if($_REQUEST["ajax_send"] != 'Y') { ?>
    <? if(!empty($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"])){
        $photoCount = count($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"]) - 1;

        ?>
        <section class="b-image-plate-block b-image-plate-block_simple-mobile" id="club-about">
            <div class="content-center">
                <div class="b-image-plate-block__content">
                    <div class="b-image-plate-block__slider-nav">
                    </div>
                    <div class="b-image-plate-block__img-holder b-image-plate-block__img-holder_slider">
                        <? foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"] as $photo): ?>
                            <div class="b-image-plate-block__slide">
                                <img class="b-image-plate-block__slide-img" src="<?=$photo["SRC_1280"]?>" srcset="<?=$photo["SRC_450"]?> 450w, <?=$photo["SRC_800"]?> 800w, <?=$photo["SRC_1280"]?> 1280w" alt="" role="presentation" />
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
                                            <h2 class="slide-text-title"><?=$arResult["PROPERTIES"]['PHOTO_DESC']['~DESCRIPTION'][$i]?></h2>
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
                                <a class="b-image-plate-block__btn button is-hide-mobile" href="<?= $arResult["PROPERTIES"]["VIRTUAL_TOUR"]["VALUE"] ?>">Открыть 3D тур</a>
                            <? } ?>
                            <?if ($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO']['VALUE']):?>
                                <a class="b-image-plate-block__btn button is-hide-desktop"
                                   href="#show-club-btn"
                                   data-src="<?=CFile::GetPath($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO']['VALUE'])?>"
                                   data-title="<?=$arResult['PROPERTIES']['AJAX_MOBILE_VIDEO_TITLE']['VALUE']?>"
                                   data-poster="<?=CFile::GetPath($arResult['PROPERTIES']['AJAX_MOBILE_VIDEO_POSTER']['VALUE'])?>">посмотреть клуб</a>
                                <div class="escapingBallG-animation">
                                    <div id="escapingBall_1" class="escapingBallG"></div>
                                </div>
                                <div class="club-video-container is-hide-desktop"></div>
                            <?endif;?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <? } ?>

    <? if(!empty($arResult["PROPERTIES"]["TEAM"]["ITEMS"])){ ?>
        <section id="club_command" class="b-cards-slider">
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
                                        <div class="b-twoside-card__content" <?if (!empty($trainer["PROPERTIES"]["HOVER_GIF"]["VALUE"])):?> data-hover-gif="true" <?endif;?>>
                                            <img class="b-twoside-card__background" src="<?=$trainer['PICTURE']?>">
                                            <?if (!empty($trainer["PROPERTIES"]["HOVER_GIF"]["VALUE"])):?>
                                                <img class="b-twoside-card__hover-gif" src="<?=CFile::GetPath($trainer["PROPERTIES"]["HOVER_GIF"]["VALUE"])?>">
                                            <?endif;?>
                                            <div class="b-twoside-card__label-container">
                                                <div class="b-twoside-card__label"><?=$trainer['NAME']?></div>
                                                <?if (!empty($trainer["PROPERTIES"]["HOVER_GIF"]["VALUE"])):?>
                                                    <div class="b-twoside-card__show-gif-btn is-hide-desktop">
                                                        <div class="b-twoside-card__show-gif-btn-icon">
                                                            <div class="b-twoside-card__play-icon">
                                                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/circularplaybutton.svg')?>
                                                            </div>
                                                            <div class="b-twoside-card__stop-icon">
                                                                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/circularstopbutton.svg')?>
                                                            </div>
                                                        </div>
                                                        <div class="b-twoside-card__show-gif-text">Смотреть</div>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                        <div class="b-twoside-card__hidden-content">
                                            <div class="b-twoside-card__title"><?=$trainer['NAME']?></div>
                                            <div class="b-twoside-card__text"><!--noindex--><?=$trainer['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT']?><!--/noindex--></div>
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

    <? if(!empty($arResult["PROPERTIES"]["REVIEWS"]["VALUE"])){ ?>
        <section id="club_reviews" class="b-cards-slider">
            <div class="content-center">
                <div class="b-cards-slider__heading">
                    <div class="b-cards-slider__title">
                        <h2>Отзывы</h2>
                    </div>
                </div>
            </div>
            <div class="b-cards-slider__slider-wrap">
                <div class="content-center">
                    <div class="reviews-slider">
                        <? foreach($arResult["PROPERTIES"]["REVIEWS"]["VALUE"] as $sliderItem) { ?>
                            <div class="reviews-slider-item">
                                <div class="reviews-slider-item__content">
                                    <div class="reviews-slider-item__name-top"><?=$sliderItem["NAME_SHORT"]?></div>
                                    <div class="reviews-slider-item__name"><?=$sliderItem["PROPERTIES"]["NAME"]["VALUE"]?></div>
                                    <div class="reviews-slider-item__rating">
                                        <? for($i = 1; $i <= 10; $i += 1) { ?>
                                            <span class="rating-star">
												<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M10 0L13.0565 5.79311L19.5106 6.90983L14.9455 11.6069L15.8779 18.0902L10 15.2L4.12215 18.0902L5.05451 11.6069L0.489435 6.90983L6.94352 5.79311L10 0Z" fill="<?=($i <= $sliderItem["RATING"]) ? "#FF7628" : "#D3D3D3" ?>"/>
												</svg>
											</span>
                                        <? } ?>
                                    </div>
                                    <div class="reviews-slider-item__description"><?=$sliderItem["PREVIEW_TEXT"]?></div>
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
            "profitator.style",
            array(
                "IBLOCK_TYPE" => "content",
                "IBLOCK_CODE" => "clubs",
                "CLUB_NUMBER" => $arResult['PROPERTIES']['NUMBER']['VALUE'],
            ),
            false
        ); ?>
        <script>
            jQuery(function($) {
                $( document ).ready(function() {
                    var hash = window.location.href.split('#').pop();
                    if( typeof hash != "undefined" && hash == "timetableheader" ) {
                        $('html, body').animate({
                            scrollTop: $("#timetable").offset().top - 120
                        }, 1);
                    }

                });
            });
        </script>
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
        <script src="<?=SITE_TEMPLATE_PATH?>/js/map-leafletjs.js?version=<?=uniqid()?>"></script>
        <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        <link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
        <script src="//unpkg.com/leaflet-gesture-handling"></script>

        <div class="b-map">
            <div class="content-center is-hide-desktop">
                <h2 class="b-map__title">Контакты</h2>
            </div>
            <div class="b-map__map-wrap">
                <div class="b-map__map map-clubs-detail" id="mapid"></div>
                <div class="b-map__content">
                    <div class="content-center"  style="display: flex; justify-content: space-between">
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
                                            <div><a class="invisible-link phone-btn" href="tel:<?=$phone?>" data-position="page"><?=$phone?></a></div>
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
                                <?
                                if( (empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['SHOW_FORM']['VALUE']))
                                    || (!empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult['PROPERTIES']['HIDE_LINK_FORM']['VALUE'])) ) {
                                    ?>
                                    <a class="b-map__button button-outline" href="#form-request">Отправить заявку</a>
                                <? } ?>

                                <?if (!empty($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'])):?>
                                    <a class="b-map__button button-outline custom-button" href="#route-window" data-fancybox="route-window">Как добраться</a>
                                <?endif;?>
                            </div>
                            <?if (!empty($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'])):?>
                                <div class="b-map__route is-hide" id="route-window">
                                    <div class="b-map__route-header">
                                        <h2>Как добраться</h2>
                                        <div class="b-map__route-tabs">
                                            <div class="b-map__route-tab-links">
                                                <?
                                                $route_tab_link_width=100/count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);
                                                for($index=0;$index<count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);$index++):?>
                                                    <?
                                                    $PATH_TO=$arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'][$index];
                                                    $PATH_TO_NAME=$arResult['PROPERTIES']['PATH_TO_NEW']['DESCRIPTION'][$index];
                                                    if (empty($PATH_TO) || empty($PATH_TO_NAME)){
                                                        continue;
                                                    }
                                                    ?>
                                                    <div class="b-map__route-tab-link <?if($index==0) echo "active";?>" data-routetypeid="<?=$index?>" style="width: <?=$route_tab_link_width?>%">
                                                        <?=$PATH_TO_NAME?>
                                                    </div>
                                                    <div class="b-map__route-tabitem-desc <?if($index==0) echo "active";?> is-hide-desktop" data-routetypeid="<?=$index?>">
                                                        <?=htmlspecialcharsBack($PATH_TO["TEXT"])?>
                                                        <div class="b-map__route-mapiframe">
                                                            <?
                                                            $sContent=htmlspecialcharsBack($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE']);
                                                            $sContent = preg_replace('|(width=").+(")|isU', 'width="100%"',$sContent);
                                                            $sContent = preg_replace('|(height=").+(")|isU', 'height="100%"',$sContent);
                                                            echo $sContent;
                                                            ?>
                                                            <div class="b-map__mapiframe-info">
                                                                <div class="b-map__mapiframe-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="24" viewBox="0 0 10 24" fill="none">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.20511 0.112501C2.87975 0.453801 1.9954 1.38351 1.73816 2.70598C1.48795 3.9926 2.3051 5.49312 3.55381 6.03976C4.55054 6.47613 5.4913 6.47613 6.48803 6.03976C7.73674 5.49312 8.55389 3.9926 8.30368 2.70598C8.16761 2.0065 7.85163 1.40784 7.38084 0.95755C6.9092 0.506617 6.48594 0.279191 5.80084 0.1085C5.21523 -0.0373825 4.78293 -0.0362625 4.20511 0.112501ZM0.534561 8.06964C0.437406 8.10717 0.277322 8.23585 0.178912 8.35548C0.00803351 8.56322 0 8.65477 0 10.4014V12.2299L0.241255 12.4881C0.462343 12.7248 0.532134 12.749 1.07807 12.7785L1.67364 12.8107V15.9936V19.1764L1.07807 19.2086C0.532134 19.2381 0.462343 19.2624 0.241255 19.499L0 19.7572V21.5952V23.4332L0.244101 23.6946L0.488285 23.9559L4.96845 23.9779L9.4487 24L9.72435 23.7644L10 23.5289V21.5952V19.6615L9.7267 19.428C9.48452 19.2211 9.39188 19.1945 8.91255 19.1945H8.37163L8.34904 13.8264L8.32636 8.45815L8.05305 8.22472L7.77975 7.99122L4.24552 7.99626C2.30167 7.99906 0.631799 8.03211 0.534561 8.06964Z" fill="#FF7628"/>
                                                                    </svg>
                                                                </div>
                                                                Проводим вас до самой раздевалки
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endfor;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="b-map__route-body is-hide-mobile">
                                        <div class="b-map__route-tab-desc">
                                            <?for($index=0;$index<count($arResult['PROPERTIES']['PATH_TO_NEW']['VALUE']);$index++):?>
                                                <?
                                                $PATH_TO=$arResult['PROPERTIES']['PATH_TO_NEW']['VALUE'][$index];
                                                $PATH_TO_NAME=$arResult['PROPERTIES']['PATH_TO_NEW']['DESCRIPTION'][$index];
                                                if (empty($PATH_TO) || empty($PATH_TO_NAME)){
                                                    continue;
                                                }
                                                ?>
                                                <div class="b-map__route-tabitem-desc <?if($index==0) echo "active";?>" data-routetypeid="<?=$index?>">
                                                    <?=htmlspecialcharsBack($PATH_TO["TEXT"])?>
                                                </div>
                                            <?endfor;?>
                                        </div>
                                        <?if (!empty($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE'])):?>
                                            <div class="b-map__route-mapiframe">
                                                <?
                                                $sContent=htmlspecialcharsBack($arResult['PROPERTIES']['PATH_TO_TOUR']['VALUE']);
                                                $sContent = preg_replace('|(width=").+(")|isU', 'width="300"',$sContent);
                                                $sContent = preg_replace('|(height=").+(")|isU', 'height="300"',$sContent);
                                                echo $sContent;
                                                ?>
                                                <div class="b-map__mapiframe-info">
                                                    <div class="b-map__mapiframe-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="24" viewBox="0 0 10 24" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.20511 0.112501C2.87975 0.453801 1.9954 1.38351 1.73816 2.70598C1.48795 3.9926 2.3051 5.49312 3.55381 6.03976C4.55054 6.47613 5.4913 6.47613 6.48803 6.03976C7.73674 5.49312 8.55389 3.9926 8.30368 2.70598C8.16761 2.0065 7.85163 1.40784 7.38084 0.95755C6.9092 0.506617 6.48594 0.279191 5.80084 0.1085C5.21523 -0.0373825 4.78293 -0.0362625 4.20511 0.112501ZM0.534561 8.06964C0.437406 8.10717 0.277322 8.23585 0.178912 8.35548C0.00803351 8.56322 0 8.65477 0 10.4014V12.2299L0.241255 12.4881C0.462343 12.7248 0.532134 12.749 1.07807 12.7785L1.67364 12.8107V15.9936V19.1764L1.07807 19.2086C0.532134 19.2381 0.462343 19.2624 0.241255 19.499L0 19.7572V21.5952V23.4332L0.244101 23.6946L0.488285 23.9559L4.96845 23.9779L9.4487 24L9.72435 23.7644L10 23.5289V21.5952V19.6615L9.7267 19.428C9.48452 19.2211 9.39188 19.1945 8.91255 19.1945H8.37163L8.34904 13.8264L8.32636 8.45815L8.05305 8.22472L7.77975 7.99122L4.24552 7.99626C2.30167 7.99906 0.631799 8.03211 0.534561 8.06964Z" fill="#FF7628"/>
                                                        </svg>
                                                    </div>
                                                    Проводим вас до самой раздевалки
                                                </div>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .b-map__route {
                margin-top: 20px;
                background-color: rgba(3,3,3,.7);
                height: max-content;
                padding: 30px;
                text-align: center;
                pointer-events: auto;
            }
            .b-map__route-title{
                text-transform: uppercase;
                font-weight: 900;
                font-size: 22px;
                margin-bottom: 15px;
            }
            .b-map__route-point-item {
                display: flex;
                align-items: baseline;
                margin: 20px 0;
            }
            span.b-map__route-point-name {
                font-weight: 100;
                text-transform: lowercase;
                /* margin-right: 20px; */
                width: 100px;
                text-align: start;
            }
            input#map-point-from {
                background: transparent;
                border: none;
                border-bottom: 2px solid white;
                color: white;
                font-weight: 600;
            }
            span.b-map__route-point {
                font-weight: 600;
            }
        </style>
    <? } ?>
    <div itemscope itemtype="http://schema.org/ExerciseGym" style="display: none;">
        <span itemprop="name">Spirit.Fitness</span>
        <meta itemprop="legalName" content="ООО Рекорд Фитнес">
        <link itemprop="url" href="https://spiritfit.ru/">
        <? if( !empty($arResult['PREVIEW_PICTURE']['SRC']) ) { ?>
            <span itemprop="image">https://<?=$_SERVER['SERVER_NAME']?><?=$arResult['PREVIEW_PICTURE']['SRC']?></span>
            <? $this->SetViewTarget('inhead'); ?>https://<?=$_SERVER['SERVER_NAME']?><?=$arResult['PREVIEW_PICTURE']['SRC']?><? $this->EndViewTarget(); ?>
        <? } ?>
        <span itemprop="priceRange"><?=$arResult['ABONEMENTS_MIN_PRICE']?>-<?=$arResult['ABONEMENTS_MAX_PRICE']?></span>
        <img itemprop="logo" src="https://spiritfit.ru/images/logo.svg">
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <meta itemprop="addressCountry" content="Россия">
            <span itemprop="streetAddress"><?=$arResult['ADDRESS_SHORT']?></span>
            <? if( !empty($arResult['PROPERTIES']['INDEX']['VALUE']) ) { ?>
                <span itemprop="postalCode"><?=$arResult['PROPERTIES']['INDEX']['VALUE']?></span>
            <? } ?>
            <span itemprop="addressLocality">Москва</span>
        </div>
        <span itemprop="telephone">8 (495) 266-40-94</span>
        <? if( !empty($arResult['PROPERTIES']['EMAIL']['VALUE']) ) { ?>
            <span itemprop="email"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></span>
        <? } ?>
        <meta itemprop="openingHours" content="Mo-Su 07:00-24:00">
    </div>

<? } ?>
<?//
//if ($arResult["CODE"]!="sevastopolskaya"){
//    $APPLICATION->IncludeComponent(
//        "custom:promocode.banner",
//        "gray-purple",
//        Array(
//            "BANNER_DISCOUNT" => "-500 &#x20bd;",
//            "BANNER_TIME" => 3000,
//            "PROMOCODE" => "FITSUMMER",
//        )
//    );
//}?>