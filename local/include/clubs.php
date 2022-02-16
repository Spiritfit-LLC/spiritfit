<? 
$clubs = Clubs::getList();
$clubsJson = Clubs::clubsJson($clubs);
$clubSection = Clubs::getClubSecDesc();
?>
<?$APPLICATION->AddHeadString('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>',true)?>
<script>window.clubs = <?=$clubsJson?></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/map-leafletjs.js"></script>

<div class="b-map b-map_page margin-top">
    <div class="b-map__map-wrap">
        <div class="b-map__map" id="mapid"></div>
        <div class="b-map__content">
            <div class="content-center">
                <div class="b-map__info-plate">
                    <div class="b-map__switch-holder">
                        <select class="b-map__switch" data-placeholder="Найти клуб">
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