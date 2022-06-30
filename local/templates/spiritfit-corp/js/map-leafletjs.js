let mapL;

console.log('mapL')

document.addEventListener("DOMContentLoaded", function(){
// function mapInit() {
    let cord1 = 55.756115;
    let cord2 = 37.618707;
    let markers = [];

    if(window.cord !== undefined){
        cord1 = window.cord[0];
        cord2 = window.cord[1];
    }

    // опции карты
    let mapZoom = (window.innerWidth < 650) ? 9 : 10;
    let mapOptions = {
        center: [cord1, cord2],
        zoom: mapZoom,
        zoomControl: false,
        gestureHandling: true,
    }

    // инициализация карты
    mapL = new L.map('mapid', mapOptions);
    // подключаем слой карты
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(mapL);

    // настраиваем иконки. пути и размеры от ширины экрана
    const iconPath = "/local/templates/spiritfit-v2/img/";
    let spiritFitnessIcon = iconPath + "map-pin-small2.png";
    let spiritFitnessIconActive = iconPath + "map-pin-small2-active.png";
    let IconAnchor=[20, 47];
    // const spiritFitnessIconNetwork = iconPath + "transparent.png";

    if(window.innerWidth < 800){
        spiritFitnessIcon = iconPath + "map-pin-small_25.png";
        spiritFitnessIconActive = iconPath + "map-pin-small_25-active.png";
        IconAnchor=[12.5, 29];
    }

    // получаем массив с данными клубов
    let locations = window.clubs;

    let networkAbonementID = '265';

    // места вставки контента маркеров
    const $titlePlace = $('.b-map__info-title');
    const $contactsPlace = $('.b-map__contacts');
    const $linkButton = $('.b-map__button_link');

    // функция для установки активного маркера
    function setActiveIcon(marker) {
        let iconActive = L.icon({iconUrl: spiritFitnessIconActive, iconAnchor:   IconAnchor});
        let iconNotActive = L.icon({iconUrl: spiritFitnessIcon, iconAnchor:   IconAnchor});
        let iconNetwork =  L.divIcon({
            html: '',
            className: 'invisible-icon',
            iconSize: [0, 0]
        })

        let markerId = 0;
        if(marker == networkAbonementID){
            for (var j = 0; j < markers.length; j++) {
                if(markers[j].options.id == networkAbonementID){
                    markers[j].setIcon(iconNetwork);
                }else{
                    markers[j].setIcon(iconActive);
                }
            }
            markerId = networkAbonementID;
        } else {
            for (var j = 0; j < markers.length; j++) {
                // для "приближения" активного маркера
                // изменяем его z-index
                if(markers[j].options.isActive){
                    markers[j].setZIndexOffset(-1000);
                    markers[j].options.isActive = false;
                }

                if(markers[j].options.id == networkAbonementID){
                    markers[j].setIcon(iconNetwork);
                }else{
                    markers[j].setIcon(iconNotActive);
                }
            }

            marker.setZIndexOffset(1000);
            marker.setIcon(iconActive);
            marker.options.isActive = true;

            markerId = marker.options.id;

            $('select.b-map__switch').val(marker.options.id);
            $('select.b-map__switch').trigger('change');
        }
        var slickElem = $("#mapslider .map-slider-item__select[data-id="+markerId+"]");
        if( slickElem.length > 0 ) {
            $("#mapslider .map-slider-item__select").removeClass("active");
            $(slickElem).addClass("active");

            let maxCount = $("#mapslider .map-slider-item").length;
            let currentNum = parseInt( $(slickElem).data("key") );

            if( maxCount > 4 ) maxCount -= 4;

            if( currentNum > maxCount ) {
                currentNum = maxCount;
            } else {
                currentNum -= 1;
            }

            /* --------------------- Отключено --------------------- */
            /*
                $("#mapslider").slick('slickGoTo',  currentNum);
            */
        }
    }

    // функция обновления контента информации клуба
    function setInfoContent(marker) {
        const regexp = /\D/ig
        // const refactoredPhone = marker.options.phone.replaceAll("[\\D]", "");
        let refactoredPhone = marker.options.phone.replace(regexp, "");
        if(marker.options.phone[0] === '+') {
            refactoredPhone = '+' + refactoredPhone;
        }
        let shedule = '';

        if(marker.options.club_soon_open == 'Y' || marker.options.page === ''){
            $titlePlace.html("<span>" + marker.options.title + "</span>");
            $linkButton.addClass('is-hide').attr('href', '#');
        }else{
            $titlePlace.html("<a href='https://spiritfit.ru" + marker.options.page + "'>" + marker.options.title + "</a>");
            $linkButton.removeClass('is-hide').attr('href', 'https://spiritfit.ru'+marker.options.page);
            shedule = `<div class="b-map__contact-item">
              <a href="https://spiritfit.ru${marker.options.page}#timetable">Расписание</a>
	  </div>`;
        }

        // блок "короткое описание клуба"
        let shortDescrBlock = '';
        // если поле в админке не пустое, то добавляем блок с описанием.
        if(marker.options.description.length > 0) {
            shortDescrBlock = `
      <div class="b-map__contact-item-descr-wrapper">
          <div class="b-map__contact-item-descr">${marker.options.description}</div>
          <a class="b-map__contact-item-descr-more" href="#">Подробнее</a>
      </div>
      `;
            // устанавливаем содержимое модального окна согласно текущему выбранному клубу.
            $('.b-map__contact-full-descr').html(marker.options.description + '<a class="b-map__contact-full-close" href="#">X</a>');
        }

        let workHoursBlock = marker.options.workHours.map(function (workHoursItem) {
            return `<div>${workHoursItem}</div>`;
        }).join('');

        let contactLayout = `${shortDescrBlock}
    <div. data-coord-x="${cord1}" data-coord-y="${cord2}" class="b-map__contact-item">
        ${marker.options.address}
    </div.>
    <div class="b-map__contact-item">
        <div><a href="tel:${refactoredPhone}" class="invisible-link">${marker.options.phone}</a></div>
        <div><a href="mailto:${marker.options.email}" class="invisible-link">${marker.options.email}</a></div>
    </div>
    <div class="b-map__contact-item">${workHoursBlock}</div>${shedule}`;

        $contactsPlace.html(contactLayout);
    }

    // рассчет сдвигов карты
    function adaptiveMapL(coords = []) {
        let width = window.innerWidth;
        let panX = width/7;

        if(width < 1000) {
            panX = width/6;
        }
        if(width < 640) {
            panX = width/6;
        }

        mapL.panBy([-panX,0]);
    }

    // Обработчик выбора клуба в select слева, над инфой клуба
    function clubSelectHandler(){
        $('.b-map__switch').on("select2:select", function () {
            $('.b-map__switch-holder .select2-results__options').getNiceScroll().hide();
            const $selectedOption = $(':selected', $(this));
            const optionValue = $selectedOption.val();
            const activeMarker = markers.find(function (marker) {
                return marker.options.id == optionValue;
            });
            if(optionValue == networkAbonementID){
                setActiveIcon(networkAbonementID);
            }else{
                mapL.panTo(activeMarker.getLatLng());
                setActiveIcon(activeMarker);
            }

            setInfoContent(activeMarker);
        });
    }

    // Обработчик выбора клуба в слайдере
    function clubSelectSlider() {
        $("#mapslider").on('init', function(event, slick) {
            /* --------------------- Отключено --------------------- */
            //$("#mapslider .map-slider-item__select").click(function(e) {
            $("#mapslider .map-slider-item__select").mouseenter(function(e) {
                e.preventDefault();

                var id = parseInt( $(this).data("id") );

                $('select.b-map__switch').val(id);
                $('select.b-map__switch').trigger('change');

                var activeMarkerDef = markers.find(function (marker) {
                    return marker.options.id == id;
                });
                if( activeMarkerDef !== undefined ) {
                    if( id == networkAbonementID ) {
                        setActiveIcon(networkAbonementID);
                    } else {
                        mapL.panTo(activeMarkerDef.getLatLng());
                        setActiveIcon(activeMarkerDef);
                        setInfoContent(activeMarkerDef);
                    }
                }
            });
        });
        $("#mapslider").slick({
            dots: false,
            infinite: false,
            speed: 1200,
            adaptiveHeight: false,
            draggable: true,
            centerMode: false,
            slidesToShow: 6,
            slidesToScroll: 1,
            prevArrow: '<button class="slick-prev"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7,13L1,7l6-6" stroke="#7F7F7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
            nextArrow: '<button class="slick-next"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#7F7F7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
            responsive: [
                {
                    breakpoint: 1680,
                    settings: {
                        arrows: true,
                        slidesToScroll: 1,
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 1300,
                    settings: {
                        arrows: true,
                        slidesToScroll: 1,
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        arrows: true,
                        slidesToScroll: 1,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 560,
                    settings: {
                        arrows: true,
                        slidesToScroll: 1,
                        slidesToShow: 1
                    }
                }]
        });
    }

    // функия для блока "поиск клуба" под картой
    function clubSearchSelect() {
        let id = 0;
        let club = '';
        let url = decodeURI(document.location.href);
        if (url.indexOf('?') !== -1) {
            url = url.split('?');
            let param = url[1];

            if (param.indexOf('&') !== -1) {
                param = param.split('&');
                for (let i = 0; i < param.length; i++) {
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
                return marker.options.id == id;
            });

            if(activeMarkerDef !== undefined) {
                mapL.panTo(activeMarkerDef.getLatLng());
                // mapL.panBy(-mapElement.clientWidth / 5, window.innerHeight / 5);
                setActiveIcon(activeMarkerDef);
                setInfoContent(activeMarkerDef);
            }
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

    if(window.club !== undefined){
        // для страниц клубов (где 1 метка)

        let markerIcon = L.icon({
            iconUrl: spiritFitnessIconActive, iconAnchor:   IconAnchor
        });

        const marker = L.marker([+cord1, +cord2],{
            id: window.club.id,
            itemCords: [cord1, cord1],
            name: window.club.name,
            title: window.club.name,
            address: window.club.address,
            phone:  window.club.phone,
            email:  window.club.email,
            workHours: window.club.workHours,
            page: window.club.page,
            icon: markerIcon,
            map: mapL,
            description: window.club.description,
        });
        marker.addTo(mapL);
        markers.push(marker);

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

            let markerIcon = L.icon({
                iconUrl: iconPathToMap, iconAnchor:   IconAnchor
            });

            if(locations[i].id == networkAbonementID){
                // iconPathToMap = spiritFitnessIconNetwork;

                // если это маркер абонементов, то назначаем ему невидимую иконку.
                markerIcon =  L.divIcon({
                    html: '',
                    className: 'invisible-marker-icon',
                    iconSize: [0, 0]
                })

            }



            const marker = L.marker([itemCord1, itemCord2],{
                id: locations[i].id,
                itemCords: [itemCord1, itemCord2],
                name: locations[i].name,
                title: locations[i].title,
                address: locations[i].address,
                phone: locations[i].phone,
                email: locations[i].email,
                workHours: locations[i].workHours,
                page: locations[i].page,
                icon: markerIcon,
                map: mapL,
                description: locations[i].description,
                club_not_open: locations[i].club_not_open,
                club_soon_open: locations[i].club_soon_open,
                isActive: false
            });

            marker.on('click', function(e){
                setActiveIcon(e.target);
                setInfoContent(e.target);
            });

            marker.addTo(mapL);
            markers.push(marker);
        }
    }

    // window.addEventListener('resize',adaptiveMapL);

    clubSelectSlider();
    clubSelectHandler();
    clubSearchSelect();
    adaptiveMapL();
});
// }
// ================================================================================
// ================================================================================
// ================================================================================
// ================================================================================
// ================================================================================
// mapInit();