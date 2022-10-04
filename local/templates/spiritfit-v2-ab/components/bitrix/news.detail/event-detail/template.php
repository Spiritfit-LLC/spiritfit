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

$address = $arResult['PROPERTIES']['ADRESS']['VALUE'];
$workHours = $arResult['PROPERTIES']['WORK']['VALUE'];
$cord = $arResult['PROPERTIES']['CORD_YANDEX']['VALUE'];
if(!empty($cord)){
    $cord = explode(',', $cord);
}

$mapName = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($arResult['NAME'])));
$mapAdress = str_replace("\r\n", '', HTMLToTxt(htmlspecialcharsBack($address['TEXT'])));
if(strpos($mapAdress, '"')) $mapAdress = str_replace('"', '\'', $mapAdress);?>


<? if(!empty($arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"])):?>
<?
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
                </div>
            </div>

        </div>
    </div>
</section>
<?endif;?>
<? if(!empty($arResult["PROPERTIES"]["EVENT_MAP"]["VALUE"])):?>
    <section class="event-map__plan">
        <div class="content-center">
            <img src="<?=CFile::GetPath($arResult["PROPERTIES"]["EVENT_MAP"]["VALUE"])?>" class="is-hide-mobile">
            <a href="<?=CFile::GetPath($arResult["PROPERTIES"]["EVENT_MAP"]["VALUE"])?>" class="button-outline is-hide-desktop" target="_blank">Скачать план мероприятия</a>
        </div>
    </section>
<?endif;?>

<section class="event-schedule <?=$arResult["PROPERTIES"]["SCHEDULE_STYLE"]["VALUE_XML_ID"]?>" id="schedule">
    <div class="content-center">
        <div class="event-schedule__title <?=$arResult["PROPERTIES"]["SCHEDULE_STYLE"]["VALUE_XML_ID"]?>">
            <?=$arResult["PROPERTIES"]["SCHEDULE_TITLE"]["VALUE"]?>
        </div>
        <div class="event-schedule__list <?=$arResult["PROPERTIES"]["SCHEDULE_STYLE"]["VALUE_XML_ID"]?>">
            <?
            $objects=[];
            $filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>Utils::GetIBlockIDBySID('event-card'), 'ID'=>$arResult["PROPERTIES"]["SCHEDULE"]["VALUE"]];
            $order = ['SORT' => 'ASC'];

            $rows = CIBlockElement::GetList($order, $filter);
            while ($row = $rows->fetch()) {
                $row['PROPERTIES'] = [];
                $objects[$row['ID']] =& $row;
                $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
                unset($row);
            }


            CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
            unset($rows, $filter, $order);
            ?>
            <?foreach ($objects as $id=>$element):?>
                <div class="event-schedule__item <?=$arResult["PROPERTIES"]["SCHEDULE_STYLE"]["VALUE_XML_ID"]?>">
                    <div class="event-schedule__item-title">
                        <?=htmlspecialcharsback($element["PROPERTIES"]["TITLE"]["VALUE"])?>
                    </div>
                    <div class="event-schedule__item-desc">
                        <?=$element["PREVIEW_TEXT"]?>
                    </div>
                    <div class="event-schedule__item-schedule">
                        <?for($i=0; $i<count($element["PROPERTIES"]["SCHEDULE"]["VALUE"]);$i++):?>
                        <div class="event-schedule__item-block">
                            <div class="event-schedule__time <?if (!empty($element["PROPERTIES"]["SCHEDULE"]["DESCRIPTION"][$i])) echo "half-width";?>"><?=$element["PROPERTIES"]["SCHEDULE"]["VALUE"][$i]?></div>
                            <?if (!empty($element["PROPERTIES"]["SCHEDULE"]["DESCRIPTION"][$i])):?>
                            <div class="event-schedule__desc"><?=$element["PROPERTIES"]["SCHEDULE"]["DESCRIPTION"][$i]?></div>
                            <?endif;?>
                        </div>
                        <?endfor;?>
                    </div>
                    <div class="event-schedule__additional">
                        <?if (!empty($element["PROPERTIES"]["ADD_TEXT"]["DESCRIPTION"])):?>
                        <a href="<?=$element["PROPERTIES"]["ADD_TEXT"]["DESCRIPTION"]?>" class="event-schedule__add-text"><?=$element["PROPERTIES"]["ADD_TEXT"]["VALUE"]?></a>
                        <?else:?>
                        <div class="event-schedule__add-text"><?=$element["PROPERTIES"]["ADD_TEXT"]["VALUE"]?></div>
                        <?endif;?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</section>















<script>
    window.cord = [<?=$cord[0]?>, <?=$cord[1]?>];
    window.club = {
        id: "<?=$arResult['ID']?>",
        name: "<?=$mapName?>",
        title: "<?=$mapName?>",
        address: "<?=$mapAdress?>",
        workHours: "<?=$workHours?>",
        page: "<?=$APPLICATION->GetCurPage();?>",
    };
</script>

<?$APPLICATION->AddHeadString('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>',true)?>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/map-leafletjs.js?version=<?=uniqid()?>"></script>
<link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
<script src="//unpkg.com/leaflet-gesture-handling"></script>

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
                            <a class="b-map__button button-outline" href="#js-pjax-clubs">Отправить заявку</a>
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
