<? 
	Bitrix\Main\Loader::includeModule("iblock");
	
	$clubs = Clubs::getList();
	$clubsJson = Clubs::clubsJson($clubs);
	$clubSection = Clubs::getClubSecDesc();
?>
<div class="b-map b-map_page">
	<div class="b-map__map-wrap">
        <div class="b-map__map" id="map_main"></div>
        <div class="b-map__content">
            <div class="content-center">
                <div class="b-map__info-plate">
                    <div class="b-map__switch-holder">
                        <select class="b-map__switch" data-placeholder="Найти клуб">
                            <option></option>
							<? foreach ($clubs as $itemClub) { ?>
								<option value="<?=$itemClub['ID']?>"><?=$itemClub['NAME']?></option>
							<? } ?>
                        </select>
                    </div>
                    <div class="b-map__info">
                        <h2 class="b-map__info-title">Выберите клуб</h2>
                        <div class="b-map__contacts"><?=$clubSection['DESCRIPTION']?></div>
                    </div>
                    <div class="b-map__buttons">
						<a class="b-map__button b-map__button_link button-outline is-hide" href="">Выбрать</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div id="club_id" style="display: none;" data-id="0"></div>
</div>

<script>window.clubs = <?=$clubsJson?></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/map-general.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLeuvKxsVzHDbWO8NE5cwM79ZC5wWNN1Y&callback=initMap&libraries=&v=weekly"></script>