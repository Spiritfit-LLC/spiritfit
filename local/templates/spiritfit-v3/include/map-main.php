<?
$clubs = Clubs::getList();
$clubsJson = Clubs::clubsJson($clubs);
$clubSection = Clubs::getClubSecDesc();
?>
<?php
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/map-main.css');
?>
<?$APPLICATION->AddHeadString('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>',true)?>
<script>window.clubs = <?=$clubsJson?></script>
<script defer src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
<script defer src="<?=SITE_TEMPLATE_PATH?>/js/map-main-leafletjs.min.js?version=20"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

<link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">
<script defer src="//unpkg.com/leaflet-gesture-handling"></script>

<div class="b-map b-map_page b-map_page-main">
    <div class="b-map__map-wrap">
        <div class="b-map__map" id="mapid"></div>
        <div class="b-map__content">
            <div class="content-center">
                <div class="b-map__info-plate hidden">
                    <div class="b-map__info-plate-closer closer white hidden-desktop">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
                    </div>
                    <div class="b-map__switch-holder select2-black">
                        <select class="b-map__switch select2 " data-placeholder="Найти клуб">
                            <option></option>
                            <? foreach ($clubs as $itemClub) { ?>
                                <? //if($itemClub['ID'] > 1162) continue; ?>
                                <option value="<?=$itemClub['ID']?>"><?=$itemClub['NAME']?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div class="b-map__info">
                        <h2 class="b-map__info-title">Выберите клуб</h2>
                        <div class="b-map__contacts"><?=$clubSection['DESCRIPTION']?></div>
                    </div>
                    <div class="b-map__buttons"><a
                            class="b-map__button b-map__button_link button-outline is-hide" href="">Выбрать</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>