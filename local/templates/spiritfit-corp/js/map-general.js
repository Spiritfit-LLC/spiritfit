let map;
function initMap() {
    const mapElement = document.getElementById("map_main");

    let cord1 = 56;
    let cord2 = 36.8123999;

    // let cord1 = 57.146115;
    // let cord2 = 38.118707;
    
    // let cord1 = 56.146115;
    // let cord2 = 38.118707;


    // let cord1 = 55.747091;
    // let cord2 = 37.625472;
    // 55.756115, 37.618707
    
    if(window.cord !== undefined){
        cord1 = window.cord[0];
        cord2 = window.cord[1];
    }

    map = new google.maps.Map(mapElement, {
        center: { lat: cord1, lng: cord2 },
        zoom: 10,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_CENTER,
        },
        styles: [
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#212121"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#212121"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "administrative.country",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "administrative.locality",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "lightness": -20
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#181818"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#1b1b1b"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#2c2c2c"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#8a8a8a"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#373737"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#3c3c3c"
                    }
                ]
            },
            {
                "featureType": "road.highway.controlled_access",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#4e4e4e"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#000000"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#3d3d3d"
                    }
                ]
            }
        ]
    });
    const bounds = new google.maps.LatLngBounds();
    const iconPath = "/local/templates/spiritfit-corp/img/";
    let spiritFitnessIcon = iconPath + "map-pin-small2.png";
    let spiritFitnessIconActive = iconPath + "map-pin-small2-active.png";

    if(window.innerWidth < 800){
        spiritFitnessIcon = iconPath + "map-pin-small_25.png";
        spiritFitnessIconActive = iconPath + "map-pin-small_25-active.png";
    }

    const spiritFitnessIconNetwork = iconPath + "transparent.png";
    const locations = window.clubs;
    
    const $titlePlace = $('.b-map__info-title');
    const $contactsPlace = $('.b-map__contacts');
	const $clubId = $('#club_id');
	
    /*const $linkButton = $('.b-map__button_link');*/
    let markers = [];
    let networkAbonementID = '265';

    $('.b-map__switch').val(null).trigger('change');
    function setActiveIcon(marker) {
        if(marker == networkAbonementID){
            for (var j = 0; j < markers.length; j++) {
                if(markers[j].id == networkAbonementID){
                    markers[j].setIcon(spiritFitnessIconNetwork);
                }else{
                    markers[j].setIcon(spiritFitnessIconActive);
                }
                
            }
        }else{
            for (var j = 0; j < markers.length; j++) {
                if(markers[j].id == networkAbonementID){
                    markers[j].setIcon(spiritFitnessIconNetwork);
                }else{
                    markers[j].setIcon(spiritFitnessIcon);
                }
            }
            marker.setIcon(spiritFitnessIconActive);
        }
        
    }

    // функционал вывода краткого описания для клубов.
    // ниже идет модальное окно для вывода всего поля "краткое описание клуба"
    let fullDescrBlock = `
    <div class="b-map__contact-full-descr-wrapper" style="display:none">
        <div class="b-map__contact-full-descr"></div>
        <div class="b-map__contact-full-descr-bg"></div>
        
    </div>
    `;
    $('footer.b-footer').after(fullDescrBlock);

    // обработчики для модального окна
    $('.b-map__info-plate').click(function(e){
        if(e.target.classList.contains('b-map__contact-item-descr-more')) {
            $('.b-map__contact-full-descr-wrapper').show();
        }
    });
    $('.b-map__contact-full-descr-wrapper').click(function(e){
        if(e.target.classList.contains('b-map__contact-full-descr-bg') || e.target.classList.contains('b-map__contact-full-close')) {
            e.preventDefault();
            $('.b-map__contact-full-descr-wrapper').hide();
        }
    });
    


    function setInfoContent(marker) {
	
        const refactoredPhone = marker.phone.replaceAll("[\\D]", "");
        let shedule = '';

        if(marker.club_soon_open == 'Y'){
            $titlePlace.html("<span>" + marker.title + "</span>");
            /*$linkButton.addClass('is-hide').attr('href', '#');*/
        }else{
            $titlePlace.html("<a href='" + marker.page + "'>" + marker.title + "</a>");
            /*$linkButton.removeClass('is-hide').attr('href', marker.page);*/
            shedule = `<div class="b-map__contact-item">
                    <a href="https://spiritfit.ru${marker.page}#timetable">Расписание</a>
                </div>`;
        }
        
		$clubId.attr("data-id", marker.id);
        
        // блок "короткое описание клуба"
        let shortDescrBlock = '';
        // если поле в админке не пустое, то добавляем блок с описанием.
        if(marker.description.length > 0) {
            shortDescrBlock = `
            <div class="b-map__contact-item-descr-wrapper">
                <div class="b-map__contact-item-descr">${marker.description}</div>
                <a class="b-map__contact-item-descr-more" href="#">Подробнее</a>
            </div>
            `;
            // устанавливаем содержимое модального окна согласно текущему выбранному клубу.
            $('.b-map__contact-full-descr').html(marker.description + '<a class="b-map__contact-full-close" href="#">X</a>');
        }

        $contactsPlace.html(`
                        ${shortDescrBlock}
                        <div. data-coord-x="${cord1}" data-coord-y="${cord2}" class="b-map__contact-item">
                            ${marker.address}
                        </div.>
                        <div class="b-map__contact-item">
                            <div><a href="tel:${refactoredPhone}" class="invisible-link">${marker.phone}</a></div>
                            <div><a href="mailto:${marker.email}" class="invisible-link">${marker.email}</a></div>
                        </div>
                        <div class="b-map__contact-item">
                            ${marker.workHours.map(function (workHoursItem) {
                return `<div>${workHoursItem}</div>`;
        }).join('')}
                        </div>
                        ${shedule}
                    `);
    }


    if(window.club !== undefined){
        // для страниц клубов (где 1 метка)
        const marker = new google.maps.Marker({
            id: window.club.id,
            position: new google.maps.LatLng(+cord1, +cord2),
            name: window.club.name,
            title: window.club.name,
            address: window.club.address,
            phone:  window.club.phone,
            email:  window.club.email,
            workHours: window.club.workHours,
            page: window.club.page,
            icon: spiritFitnessIconActive,
            map: map,
            description: window.club.description,
        });
        markers.push(marker);
        bounds.extend(marker.position);

        map.fitBounds(bounds);
        const listener = google.maps.event.addListener(map, "idle", function () {
            if (window.innerHeight < 800) {
                map.setZoom(11);
                map.panBy(-mapElement.clientWidth / 5, 0);
            } else {
                map.setZoom(10);
                map.panBy(-mapElement.clientWidth / 5, 0);
            }
            google.maps.event.removeListener(listener);
        });
    }else{
        for (let i = 0; i < locations.length; i++) {
            var itemCord1 = '';
            var itemCord2 = '';
            
            if(locations[i].coords[0].length > 0){
                itemCord1 = +locations[i].coords[0];
                itemCord2 = +locations[i].coords[1];
            }else{
                itemCord1 = cord1;
                itemCord2 = cord2;
            }

            var iconPathToMap = locations.length > 1 ? spiritFitnessIcon : spiritFitnessIconActive;
            
            if(locations[i].id == networkAbonementID){
                iconPathToMap = spiritFitnessIconNetwork;
            }
            
            const marker = new google.maps.Marker({
                id: locations[i].id,
                position: new google.maps.LatLng(itemCord1, itemCord2),
                name: locations[i].name,
                title: locations[i].title,
                address: locations[i].address,
                phone: locations[i].phone,
                email: locations[i].email,
                workHours: locations[i].workHours,
                page: locations[i].page,
                icon: iconPathToMap,
                map: map,
                description: locations[i].description,
                club_not_open: locations[i].club_not_open,
                club_soon_open: locations[i].club_soon_open,
            });
            markers.push(marker);
            bounds.extend(marker.position);
            if (locations.length > 1) {
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        setActiveIcon(this);
                        setInfoContent(locations[i]);
                    }
                })(marker, i));
            }
        }

        if (locations.length < 2) {
            map.fitBounds(bounds);
            const listener = google.maps.event.addListener(map, "idle", function () {
                map.setZoom(11);
                map.panBy(-mapElement.clientWidth / 4, 0);
                google.maps.event.removeListener(listener);
            });
        } else {
            map.fitBounds(bounds);
            const listener = google.maps.event.addListener(map, "idle", function () {

                if((window.innerWidth < 400)){
                    console.log('z1');
                    map.setZoom(8);
                    map.panBy(-20, 50);
                } else if(window.innerWidth < 800){
                    console.log('z2');
                    map.setZoom(9);
                    let xPan =  -(window.innerWidth - 800)*(0.375);
                    let yPan =  -(window.innerWidth - 800)*(0.375);
                    map.panBy(0, 0);
                } else if((window.innerWidth < 1024)) {
                    console.log('z3');
                    map.setZoom(9);
                    map.panBy(-100, 100);
                } else {    
                    console.log('z4');
                    map.setZoom(10);
                    let xPan = (1000 - window.innerWidth) > 0 ? (1000 - window.innerWidth)/3 : 0;
                    map.panBy(xPan, 100);
                }
                google.maps.event.removeListener(listener);
            });
        }
    }
	
    // навешиваем кастомный скроллбар на кастомный селект 
    // $('.b-map__switch').on("select2:open", function(e){
    //     $('.b-map__switch-holder .select2-results__options').niceScroll({
    //         cursorcolor:"#FF7628", 
    //         cursorborder: 0, 
    //         background: "#222",
    //         cursorborderradius: 0,
    //         autohidemode: false
    //     });
    // });
    
    $('.b-map__switch').on("select2:select", function () {
        
        $('.b-map__switch-holder .select2-results__options').getNiceScroll().hide();
        const $selectedOption = $(':selected', $(this));
        const optionValue = $selectedOption.val();
        const activeMarker = markers.find(function (marker) {
            return marker.id === optionValue;
        });
        
        if(optionValue == networkAbonementID){
            setActiveIcon(networkAbonementID);
        }else{
            map.panTo(activeMarker.getPosition());
            map.panBy(-mapElement.clientWidth / 5, window.innerHeight / 5);
            setActiveIcon(activeMarker);
        }

        setInfoContent(activeMarker);
    });
    
    var id = 0;
    var club = '';
    var url = decodeURI(document.location.href);
    if (url.indexOf('?') !== -1) {
        url = url.split('?');
        var param = url[1];

        if (param.indexOf('&') !== -1) {
            param = param.split('&');
            for (var i = 0; i < param.length; i++) {
                param[i] = param[i].split('=');
                
                if (param[i][0] == 'club') {
                    club = param[i][1];
                    break;
                }
            }
        } else {
            param = param.split('=');
            if (param[0] == 'club') {
                club = param[1];
            }
        }
    }

    if (club.length > 0) {
        if (club.indexOf('+') !== -1) {
            club = club.replaceAll('+', ' ');
        }
        
        $('select.b-map__switch option').each(function () {
            if ($(this).text().toLowerCase() == club.toLowerCase()) {
                id = $(this).val();
            }
        })
        $('select.b-map__switch').val(id);
        $('select.b-map__switch').trigger('change');

        var activeMarkerDef = markers.find(function (marker) {
            return marker.id === id;
        });

        
        if(activeMarkerDef !== undefined) {
            map.panTo(activeMarkerDef.getPosition());
            map.panBy(-mapElement.clientWidth / 5, window.innerHeight / 5);
            setActiveIcon(activeMarkerDef);
            setInfoContent(activeMarkerDef);
        }
    }

}