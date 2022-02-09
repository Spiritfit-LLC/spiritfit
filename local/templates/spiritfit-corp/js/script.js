function current_domain() {
    var domain = document.location.href;
    domain = domain.substr(domain.indexOf('//')+2);
    return domain.substr(0,domain.indexOf('/'));
}

function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}

function dataLayerSend(eCategory, eAction, eLabel, eNI = false) {
    return false;
}
function dataLayerSendCorp(eCategory, eAction, eLabel, eNI = false) {
	(dataLayer = window.dataLayer || []).push({
        'eCategory': eCategory,
        'eAction': eAction,
        'eLabel': eLabel,
        'eNI': eNI,
        'event': 'GAEvent'
    });
}

function getCookies(name) {
    var matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ))
    return matches ? decodeURIComponent(matches[1]) : undefined
}

function setCookies(name, value, props) {
    props = props || {}
    var exp = props.expires

    if (typeof exp == "number" && exp) {
        var d = new Date()
        d.setTime(d.getTime() + exp*1000)
        exp = props.expires = d
    }

    if(exp && exp.toUTCString) { props.expires = exp.toUTCString() }
    value = encodeURIComponent(value)
    var updatedCookie = name + "=" + value
    for(var propName in props){
        updatedCookie += "; " + propName
        var propValue = props[propName]
        if(propValue !== true){ updatedCookie += "=" + propValue }
    }
    document.cookie = updatedCookie
}


$(document).ready(function() {

    if(getCookies('firstVisit') != 'Y'){
        setTimeout(function(){
            var clientID = getCookies('_ga');
            console.log('Первый визит, ваш ID - '+clientID);
            var current = window.sbjs.get.current;
            $.ajax({
                method: "POST",
                url: "/local/ajax/send-ga.php",
                data: {
                    'type':    'visit',
                    'clientid': clientID,
                    'src': current.src,
                    'mdm': current.mdm,
                    'cmp': current.cmp,
                    'cnt': current.cnt,
                    'trm': current.trm,
                },
                success: function(data){

                }
            })
            setCookies('firstVisit', 'Y');
        }, 1000);
    }

    $('a').on('click', function(){
        var href = $(this).attr('href');

        if(href.indexOf('http') !== -1){
            dataLayerSend('UX', 'clickExternalLink', href);
        }
    });

    var maskValue = null;
    $(".trial-button-link").on("click", function(e) {
        e.preventDefault();
        var target = $(".subscription__aside");
        if (target.length > 0){
            $('html,body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    var pjaxLoadDataParametrs = function() {
        if($('#seo-div') != undefined) {
            $("meta[property='og:title']").attr('content', $('#seo-div').data('title'));
            document.title = $('#seo-div').data('title');
            $("meta[property='og:description']").attr('content', $('#seo-div').data('description'));
            $("meta[name='description']").attr('content', $('#seo-div').data('description'));
            $("meta[name='keywords']").attr('content', $('#seo-div').data('keywords'));
            $("meta[property='og:image']").attr('content', $('#seo-div').data('image'));
        }
    }

    function replaceSliderBlock(){
        if ($(".subscription.fixed").hasClass("js-page-trial-training")) {
            if ($(window).innerWidth() <= 425) {
                if ($(".abonement-additional-block").find(".js-photogallery-body").length > 0){
                    $(".js-photogallery-title").hide();
                    var slider = $(".abonement-additional-block").find(".js-photogallery-body").detach();
                    $(".subscription__common .subscription__desc").after(slider);
                }
            }else{
                if ($(".subscription__common").find(".js-photogallery-body").length > 0){
                    $(".js-photogallery-title").show();
                    var slider = $(".subscription__common").find(".js-photogallery-body").detach();
                    $(".js-photogallery-title").after(slider);
                }
            }
        }
    }
    function replaceSubscriptionBlock(){
        if ($(".subscription.fixed").hasClass("js-page-trial-training")) {
            if ($(window).innerWidth() <= 425) {
                if ($(".subscription.fixed").find(".subscription__aside").not(".replaced").length > 0){
                    var block = $(".subscription.fixed").find(".subscription__aside").addClass("replaced").removeClass("not-replaced").detach();
                    if ($(".subscription__common").find(".js-photogallery-body").length > 0)
                        $(".subscription__common .js-photogallery-body").after(block);
                    else
                        $(".subscription__common .subscription__desc").after(block);
                }
            }else{
                if ($(".subscription__common").find(".subscription__aside").not(".not-replaced").length > 0){
                    var block = $(".subscription__common").find(".subscription__aside").addClass("not-replaced").removeClass("replaced").detach();
                    $(".subscription__main").after(block);
                }
            }
        }
    }

    replaceSliderBlock();
    replaceSubscriptionBlock();

    $(window).resize(function() {
        replaceSliderBlock();
        replaceSubscriptionBlock();
    });

    if(bowser.msie) {
        $('#map-yandex').addClass('ie');
    }
    $(document).on('pjax:complete', function(xhr, status, data, options) {
        $('#js-pjax-container').fadeIn(500);
        if (xhr.target.id === "js-pjax-container") {
            init();
        }

        if(window.oldTitleDocument !== undefined){
            document.title = window.oldTitleDocument;
        }
        
        if (xhr.target.id === "js-pjax-container" && options.data.ajax_menu != false) {
            var data = {};
            data["ajax_menu"] = "true";

            if (options['data']['club']) {
                data['club'] = options['data']['club'];
            };

            if (options['data']['theme']) {
                data['theme'] = options['data']['theme'];
            };



            $.pjax.reload('nav', {
                timeout: false,
                dataType: 'html',
                push: false,
                type: 'POST',
                data: data
            });
            if ($(window).innerWidth() < 1260) {
                $('.page-wrapper main, .footer').fadeIn(300);
            }
            if ($('.header__burger').hasClass('header__burger--active')) {
                $('.header__burger').removeClass('header__burger--active');
                $('.header__nav').slideUp(400);
            }
        }
        $(".trial-button-link").on("click", function(e) {
            e.preventDefault();
            var target = $(".subscription__aside");
            if (target.length > 0){
                $('html,body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
        replaceSliderBlock();
        replaceSubscriptionBlock();
        pjaxLoadDataParametrs();
    });

    $(document).on('pjax:popstate', function(e) {
        e.preventDefault();
        $('#js-pjax-container').hide();
        $.pjax.reload('#js-pjax-container');
    });

    var pjaxLoad = function(url, container, data) {
        $.pjax({
            timeout: false,
            url: url,
            container: container,
            data: data,
            type: 'POST',
            dataType: 'html',
        });
    }

    var showArrows = function(e, slick, currentSlide) {
		if (currentSlide === slick.$slides.length - 1) {
			slick.$prevArrow.fadeIn(300);
			slick.$nextArrow.fadeOut(300);
		} else if (currentSlide === 0) {
			slick.$prevArrow.fadeOut(300);
			slick.$nextArrow.fadeIn(300);
		} else {
			slick.$prevArrow.fadeIn(300);
			slick.$nextArrow.fadeIn(300);
		}
	}

    var init = function() {
        $('.club__slider').on('init', function (e, slick) {
            slick.$prevArrow.hide(0);
            var dataSrc = $(slick.$slides[0]).attr("data-src")
            $(slick.$slides[0]).css('background-image', 'url('+dataSrc+')')
        });
        if ($('.club__slider').length) {
            $('.club__slider').slick({
                dots: true,
                infinite: false,
                slidesToShow: 1,
                touchThreshold: 50
            });

            $('.club__slider').slickLightbox({
                src: 'data-src',
                itemSelector: '.club__slider-item',
                background: 'rgba(0, 0, 0, 1)'
            });

            $('.club__slider').on('afterChange', function (e, slick, currentSlide) {
                showArrows(e, slick, currentSlide);
            });

            $(".club__slider").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
                var nextX = slick.$slides.eq(nextSlide)
                nextX.prevAll().not('.loaded').each(function(){
                    var dataSrcT = $(this).attr("data-src")
                    $(this).css('background-image', 'url('+dataSrcT+')')
                })
                var dataSrcT = nextX.attr("data-src")
                nextX.css('background-image', 'url('+dataSrcT+')')
            });
        }

        var stockSlider = $('.js-slider__stock');
        if (stockSlider.length > 0 && !stockSlider.hasClass("slick-slider")) {
            $('.js-slider__stock').slick({
                arrows: false,
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000
            });
        }

        // $('.club__advantages').slick({
        //     dots: true,
        //     infinite: false,
        //     slidesToShow: 1,
        //     centerMode: true,
        //     centerPadding: '160px'
        // });
        $(".club__advantages").on("init", function (event, slick) {
            var $activeSlide = slick.$slider.find('.slick-slide.slick-current');
            var dataSrc1 = $activeSlide.find(".club__advantages-slide-pic-img").attr("data-src")
            $activeSlide.addClass('loaded').find(".club__advantages-slide-pic-img").attr("src", ''+dataSrc1+'')
            var $activeSlideNext = $activeSlide.next()
            var dataSrc2 = $activeSlideNext.find(".club__advantages-slide-pic-img").attr("data-src")
            $activeSlideNext.addClass('loaded').find(".club__advantages-slide-pic-img").attr("src", ''+dataSrc2+'')
        }), $(".club__advantages").slick({
            dots: !0,
            infinite: !1,
            slidesToShow: 1,
            centerMode: !0,
            touchThreshold: 50
        }),$(".club__advantages").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            var nextC = slick.$slides.eq(nextSlide)
                        nextC.next().not('.loaded').each(function(){
                var dataSrcC = $(this).find(".club__advantages-slide-pic-img").attr("data-src")
                $(this).find(".club__advantages-slide-pic-img").attr("src", ''+dataSrcC+'')
            })
            nextC.prev().not('.loaded').each(function(){
                var dataSrcC = $(this).find(".club__advantages-slide-pic-img").attr("data-src")
                $(this).find(".club__advantages-slide-pic-img").attr("src", ''+dataSrcC+'')
            })
            var dataSrcC = nextC.find(".club__advantages-slide-pic-img").attr("data-src")
            nextC.find(".club__advantages-slide-pic-img").attr("src", ''+dataSrcC+'')
        }),

        $(".club__team").on("init", function (event, slick, nextSlide) {
            if($(window).width() > 1260) {
                slick.$slides.each(function(){
                    var dataSrcT = $(this).find(".club__team-slide-front").attr("data-src")
                    $(this).find(".club__team-slide-front").css('background-image', 'url('+dataSrcT+')')
                })
            } else {
                slick.$slides.each(function(){
                    var l = $(this).offset().left
                    if(l < window.innerWidth && l > 0){
                        var dataSrcT = $(this).find(".club__team-slide-front").attr("data-src")
                        $(this).addClass('loaded').find(".club__team-slide-front").css('background-image', 'url('+dataSrcT+')')
                    }
                })
            }
        }),
        $(window).innerWidth() < 1260 ? ($(".club__subheading .btn").insertAfter(".video")) : ($(".club > .btn").appendTo(".club__subheading--tour"))
        $('.club__team').slick({
            dots: !0,
            infinite: !1,
            slidesToShow: 1,
            centerMode: !0,
            variableWidth: true,
            centerMode: false,
            touchThreshold: 50
        }), $(".club__team").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            var next = slick.$slides.eq(nextSlide)
            next.prevAll().not('.loaded').each(function(){
                var dataSrcT = $(this).find(".club__team-slide-front").attr("data-src")
                $(this).find(".club__team-slide-front").css('background-image', 'url('+dataSrcT+')')
            })
            var j = slick.$slides.filter(function(){
                var l = $(this).offset().left
                return l > 0 && l < window.innerWidth
            }).length;
            for (var i=0; i<=j; i++){
                var el = slick.$slides.eq(nextSlide + i)
                if(!!el){
                    var dataSrcT = el.find(".club__team-slide-front").attr("data-src")
                    el.addClass('loaded').find(".club__team-slide-front").css('background-image', 'url('+dataSrcT+')')
                }
            }
        });

        if ($('.flip_button').length) {
            $('.flip_button').click(function () {
                var block = $(this).parents('.club__team-slide-inner').is(".club__team-slide-front");
                if (block) {
                    $(this).parents('.club__team-slide-inner').find('.flip_button').hide();
                } else {
                    $(this).parents('.club__team-flip_box').find('.flip_button').show();
                }
                $(this).parents('.club__team-slide').children('.club__team-flip_box').toggleClass('flipped');
                return false;
            });
        }
        $('.product__slider-inner').slick({
            dots: true,
            arrows: false,
            adaptiveHeight: false,
            touchThreshold: 50
        });

        $('.application__possibilities').slick({
            dots: true,
            infinite: false,
            slidesToShow: 1,
            centerMode: true,
            centerPadding: '160px',
            touchThreshold: 50
        });

        $('.input--num').on('keyup', function (evt) {
            if (evt.target.value > 1) {
                this.value = evt.target.value.slice(0, 1)
            }
            if (evt.target.value !== '') {
                $(this).next('.input--num').focus();
            }
            if (evt.key === 'Backspace') {
                $(this).prev('.input--num').focus();
            }
        });

        var isMacLike = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;

        if (!isMacLike) {
            // $('select.js-pjax-select').styler({
            //     selectSmartPositioning: false
            // });
            let parent = $('select.js-pjax-select').parent();
            $('select.js-pjax-select').select2({
                width: '100%',
                minimumResultsForSearch: 100,
                dropdownParent: parent,
            });
        }

        $(".input--checkbox").styler();
        $(".input--tel").inputmask({
            'mask': '+7 (999) 999-99-99',
            onBeforeMask: function (value) {
                if (value[0] == '8') {
                    var processedValue = value.replace('8', "");
                }

                return processedValue;
            }
        });

        $(".video").on("click", function (e) {
            e.preventDefault(), $(".popup--video").fadeIn(300)
            !!player && !!player.stopVideo && player.stopVideo()
        });

        $(".popup__close, .popup__bg").on("click touchend", function () {
            $(this).closest(".popup").fadeOut(300);
            // !!player && !!player.stopVideo && player.stopVideo()
        });

        //onYouTubeIframeAPIReady();

        
        moveFAQButtonsS();

        if ($(".timetable__container").length > 0) {
            var d = null;
            $(".timetable__container").each(function() {
                d = new PerfectScrollbar(this);
            });
        }

        function l() {
            $(".timetable__container.ps .ps__rail-x--top .ps__thumb-x").css("width", $(".timetable__container.ps .ps__rail-x:not(.ps__rail-x--top) .ps__thumb-x").css("width")), $(".timetable__container.ps").on("scroll", function() {
                var e = $(this),
                    t = e.find(".ps__rail-x").not(".ps__rail-x--top").css("left"),
                    i = e.find(".ps__rail-x:not(.ps__rail-x--top) .ps__thumb-x").css("left");
                e.find(".ps__rail-x--top").css("left", t), e.find(".ps__rail-x--top .ps__thumb-x").css("left", i)
            })
        }

        function c() {
            $(".timetable__container.ps").on("scroll", function() {
                var e = $(this);
                scrollWidth = e.get(0).scrollWidth, scrollLeft = e.scrollLeft(), innerWidth = e.innerWidth(), 0 < e.scrollLeft() ? ($(".timetable__arrows-item--left").removeClass("timetable__arrows-item--disabled"), $(".timetable__table-less").fadeIn(300)) : ($(".timetable__arrows-item--left").addClass("timetable__arrows-item--disabled"), $(".timetable__table-less").fadeOut(300)), scrollWidth - scrollLeft > innerWidth ? ($(".timetable__arrows-item--right").removeClass("timetable__arrows-item--disabled"), $(".timetable__table-more").fadeIn(300)) : ($(".timetable__arrows-item--right").addClass("timetable__arrows-item--disabled"), $(".timetable__table-more").fadeOut(300))
            })
        }
        $(".ps__rail-x").clone().addClass("ps__rail-x--top").appendTo(".timetable__container"), l(),
        $('select.timetable__filter-dept').addClass('remove-events');
        $('select.timetable__filter-dept').each(function(){
            if (!$(this).parent().is('.jq-selectbox')) {
                !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
                    selectSmartPositioning: !1
                })
            }
        })
        $("select.timetable__filter-dept").on("change", function() {
            var e = '',
                t = void 0;
            var value = $(this).val()
            $(this).find('option').each(function() {
                if ($(this).text().replace(/\s/g,'') == value.replace(/\s/g,'')) {
                    e = $(this).data('timetable');
                }
            });
            $(".timetable__container").each(function() {
                $(this).is(":visible") && (t = $(this))
            }), e !== t.data("timetable") && (t.fadeOut(300), d.destroy(), d = null, setTimeout(function() {
                $('.timetable__container[data-timetable="' + e + '"]').css("display", "flex").hide().fadeIn()
            }, 300), setTimeout(function() {
                if ($('.timetable__container[data-timetable="' + e + '"]').length > 0) {
                    d = new PerfectScrollbar('.timetable__container[data-timetable="' + e + '"]'), $("ps__rail-x--top").remove(), c(), l(), $(".timetable__container.ps").scrollLeft(0)
                }
            }, 300))
        }), $(".timetable__arrows-item--right").on("click", function() {
            $(this).hasClass("timetable__arrows-item--disabled") || $(".timetable__container.timetable__container-active.ps").scrollLeft($(".timetable__container.timetable__container-active.ps").scrollLeft() + 218)
        }), $(".timetable__arrows-item--left").on("click", function() {
            $(this).hasClass("timetable__arrows-item--disabled") || $(".timetable__container.timetable__container-active.ps").scrollLeft($(".timetable__container.timetable__container-active.ps").scrollLeft() - 218)
        }), c();
        var p = !1,
            u = 0;
        $(window).on("mousemove", function(e) {
            !0 === p && $(".timetable__container.ps").scrollLeft($(".timetable__container.ps").scrollLeft() + (u - e.pageX))
        }), $(window).on("mousedown", function(e) {
            $(".timetable__container.ps").addClass("is-grabbing"), p = !0, u = e.pageX
        }), $(window).on("mouseup", function() {
            $(".timetable__container.ps").removeClass("is-grabbing"), p = !1
        })

        $(document).trigger('gridMain:init');

        $('select.input--select').each(function(){
            // if (!$(this).parent().is('.jq-selectbox')) {
            //     !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
            //         selectSmartPositioning: !1
            //     })
            // }
        })
        $('select.custom--select').select2({
            minimumResultsForSearch: Infinity,
        });

        $('select.custom--select-video').select2({
            minimumResultsForSearch: Infinity,
            dropdownParent: $('.grid-element--select-videos'),
        });

        // if (!isMacLike) {
        //     $('.input--select').styler({
        //         selectSmartPositioning: false
        //     });
        // }

        if(!!clubCardsQuoteHeight) clubCardsQuoteHeight();
    }

    if ($("#map-yandex").length) {
        ymaps.ready(function () {
            var e = parseFloat($("#map-yandex").data("coord-center-lat")),
                t = parseFloat($("#map-yandex").data("coord-center-lng")),
                i = parseFloat($("#map-yandex").data("coord-mark-lat")),
                a = parseFloat($("#map-yandex").data("coord-mark-lng")),
                zoom = 17,
                size = (window.innerWidth > 768)? [69, 86] : [35, 43],
                controls = [];

            if ($("#map-yandex").data("abonement") == "Y"){
                var str = $("#map-yandex").data("coord-mark-lat");
                var cord1 = str.split(',');
                str = $("#map-yandex").data("coord-mark-lng");
                var cord2 = str.split(',');
                var e = parseFloat(55.751262971955754);
                t = parseFloat(37.622468001794026);
                zoom = 10;
                controls = ["zoomControl"];
            }
            var n = new ymaps.Map("map", {
                center: [e, t],
                zoom: zoom,
                controls: controls
            });
            if ($("#map-yandex").data("abonement") == "Y"){
                for (var k = 0; k < cord1.length; k++) {
                    var s = new ymaps.Placemark([cord1[k], cord2[k]], {}, {
                        iconLayout: 'default#image',
                        iconImageHref: "/local/templates/spiritfit/img/icons/pin.png",
                        iconImageSize: [size[0], size[1]],
                        iconImageOffset: [-size[0]/2, -size[1]]
                    });
                    n.geoObjects.add(s);
                }
            }else{
                var s = new ymaps.Placemark([i, a], {}, {
                    iconLayout: 'default#image',
                    iconImageHref: "/local/templates/spiritfit/img/icons/pin.png",
                    iconImageSize: [size[0], size[1]],
                    iconImageOffset: [-size[0]/2, -size[1]]
                });
                n.geoObjects.add(s);
            }
            n.behaviors.disable("scrollZoom");
            n.geoObjects.each(function (geoObject, index) {
                geoObject.events.add(['click'],  function (e) {
                    $(".club_info").not("[data-id='"+index+"']").hide();
                    $(".club_info[data-id='"+index+"']").fadeIn();
                });
            });
        });
    }

    if ($('.js-slider__stock').length) {
        $('.js-slider__stock').slick({
            arrows: false,
            dots: true,
            infinite: false,
            autoplay: true,
            autoplaySpeed: 4000,
            touchThreshold: 50
        });
    }

    $(document).on('click', '.js-pjax-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if($(this).attr('data-href') !== undefined){
            url = $(this).attr('data-href');
        }
        var data = {};
        if ($('select.js-pjax-select-abonements').val()) {
            data['club'] = $('select.js-pjax-select-abonements').val();
        }
        if ($('#clubNumber').val()) {
            data['club'] = $('#clubNumber').val();
        }
        $('#js-pjax-container').fadeOut(500, function() {
            pjaxLoad(url, '#js-pjax-container', data);
        });

    });
    $(document).on('focus','.input--placeholder',function(){
        $(this).parent().find('label').css({
            "display":"none"
        })
    });
    $(document).on('focusout','.input--placeholder',function(){
        if ($(this).val().length < 18)
        $(this).parent().find('label').css({
            "display":"block"
        })
    });
    $(document).on('input','.js-placeholder',function() {
        if ($(this).val().length > 0) {
            $(this).parent().find('label').css({
                "display":"none"
            })
        } else {
            $(this).parent().find('label').css({
                "display":"block"
            })
        }
    });
    $(document).on('click','.popup--success .popup__close', function() {
        var popup = $(this).closest('.popup');
        popup.fadeOut(300, function() {
            popup.remove();
            $('.popup--choose').remove();
        });
        var message = $('.popup--success').prev();
        message.find('.popup__form').each(function() {
            this.reset();
            $('.input-wrapper').css({
                "display" : "block"
            })
        });
    });

    $(document).on("click", ".popup--choose .popup__close, .popup--call .popup__close, .popup--message .popup__close, .popup--subscribtion-video .popup__close, .popup--choose-video .popup__close", function() {
        var popup = $(this).closest('.popup');

        popup.fadeOut(300, function() {
            popup.remove();
            $('.popup--choose').remove();
        });

        if ($(window).innerWidth() < 1260) {
			$('.header__burger--active').removeClass('header__burger--active');
			$('.header__nav').hide();
			$('.page-wrapper main, .footer').fadeIn(400);
        }
    });

    $(document).on("click", ".popup--choose .popup__bg, .popup--call .popup__bg, .popup--message .popup__bg, .popup--subscribtion-video .popup__bg, .popup--choose-video .popup__bg", function() {
        var popup = $(this).closest('.popup');

        popup.fadeOut(300, function() {
            popup.remove();
        });
    });

    $(document).on("click", ".footer__btn, .header__nav-footer-btn", function(e) {

        dataLayerSend('UX', 'openFormFeedback', '');

        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit/ajax/callback.php",
        }).done(function(data) {
            $('body').append(data);
            $(".popup--choose").fadeIn(300);
        });
    });

    $(document).on('click','.js-popup-call',function() {

        dataLayerSend('UX', 'openFormCallback', '');

        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit/ajax/call.php",
        }).done(function(data) {
            $('body').append(data);

            var isMacLike = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;
            $('select.custom--select').select2({
                minimumResultsForSearch: Infinity
            });
            // if (!isMacLike) {
            //     $('select.input--select').styler({
            //         selectSmartPositioning: false
            //     });
            // }
            $(".input--tel").inputmask({
                mask: "+7 (999) 999-99-99",
                oncomplete: function() {
                    maskValue = $(this).val().length;
                },
                onBeforeMask: function (value, opts) {
                    if (value[0] == '8') {
                        var processedValue = value.replace('8', "");
                    }

                    return processedValue;
                }
            });
            $(".popup--call").fadeIn(300);
            $('.input--checkbox').styler();
            $('.popup--choose').fadeOut();
        });
    });

    $(document).on('click','.js-popup-message',function() {

        dataLayerSend('UX', 'openFormWriteUs', '');

        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit/ajax/call-message.php",
        }).done(function(data) {
            $('body').append(data);

            var isMacLike = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;
            $('select.custom--select').select2({
                minimumResultsForSearch: Infinity
            });
            if (!isMacLike) {
                // $('select.input--select.js-msg-select').styler({
                //     selectSmartPositioning: false,
                //     onSelectOpened: function() {
                //         $(this).find('.jq-selectbox__select-text').addClass('removed-symbol');
                //     }
                // });
            }
            $(".input--tel").inputmask({
                'mask': '+7 (999) 999-99-99',
                onBeforeMask: function (value, opts) {
                    if (value[0] == '8') {
                        var processedValue = value.replace('8', "");
                    }

                    return processedValue;
                }
            });
            $(".popup--message").fadeIn(300);
            $('.input--checkbox').styler();
            $('.popup--choose').fadeOut();
        });
    });

    $(document).on('click','.js-forbidden-video', function() {
        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit/ajax/forbidden-video.php",
        }).done(function(data) {
            $('body').append(data);

            $(".popup--choose-video").fadeIn(300);
        });
    });

    $(document).on('click','.js-popup-forbidden-video', function() {
        var buttonPopupForbiddenVideo = $(this);

        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit/ajax/video-authorization.php",
        }).done(function(data) {
            $('body').append(data);

            $(".input--tel").inputmask({
                mask: "+7 (999) 999-99-99",
                oncomplete: function() {
                    maskValue = $(this).val().length;
                },
                onBeforeMask: function (value, opts) {
                    if (value[0] == '8') {
                        var processedValue = value.replace('8', "");
                    }

                    return processedValue;
                }
            });

            $(".popup--subscribtion-video").fadeIn(300);

            var popup = buttonPopupForbiddenVideo.closest('.popup');
            popup.fadeOut(300, function() {
                popup.remove();
            });
        });
    });

    function checkAvailableVideo() {
        var allElemsPaidVideo = $('.js-video');

        $(allElemsPaidVideo[0]).find('.paid-video-popup__button--prev').prop('disabled', 'disabled');
        $(allElemsPaidVideo[allElemsPaidVideo.length - 1]).find('.paid-video-popup__button--next').prop('disabled', 'disabled');
    }

    checkAvailableVideo();

    function calcMarginVideo(popup, video) {
        if ($(window).height() > $(window).width() && $(window).width() < 1260) {
            $(popup).find('.paid-video-popup__wrapper-video').css('margin-top', '');
            var halfHeightPopupVideo = $(popup).height() / 2;
            var halfHeightVideo = $(video).height() / 2;

            $(popup).find('.paid-video-popup__wrapper-video').css('margin-top', (halfHeightPopupVideo - halfHeightVideo) + 'px');
        } else {
            $(popup).find('.paid-video-popup__wrapper-video').css('margin-top', '');
        }
    }

    function calcWidthContainerVideo(popup, video) {
        if ($(window).width() > 1260) {
            $(popup).find('.paid-video-popup__content-video').css('max-width', '');
            var widthVideo = $(video).width();

            if (widthVideo > 0) {
                $(popup).find('.paid-video-popup__content-video').css('max-width', widthVideo + 'px');
            }

        } else {
            $(popup).find('.paid-video-popup__content-video').css('max-width', '');
        }
    }

    $(window).on('resize', function() {
        var visiblePopupVideo = $('.popup--paid-video:visible');

        if (visiblePopupVideo.length) {
            var visibleVideo = visiblePopupVideo.find('.paid-video-popup__video');

            calcMarginVideo(visiblePopupVideo, visibleVideo);
            calcWidthContainerVideo(visiblePopupVideo, visibleVideo);
        }
    });

    $('.js-video .paid-video-popup__video').on('loadedmetadata', function() {
        $(window).trigger('resize');
    });

    $(document).on('click', '.js-video', function(evt) {
        if(!$(evt.target).closest('.popup--paid-video').length) {
            var currentPopupVideo = $(this).find('.popup--paid-video');

            if (currentPopupVideo.length) {
                var currentVideo = $(currentPopupVideo).find('.paid-video-popup__video');
                var currentVideoDescription = $(currentPopupVideo).find('.paid-video-popup__description')[0];

                currentPopupVideo.show();

                calcMarginVideo(currentPopupVideo, currentVideo);
                calcWidthContainerVideo(currentPopupVideo, currentVideo);

                var psPaidVideoDescription = new PerfectScrollbar(currentVideoDescription, {
                    suppressScrollX: false,
                    wheelPropagation: false
                });

                // Проверка на доступность файла, если данные подгружены, то запускаем воспроизведение, иначе запускаем загрузку видео с последующим воспроизведением
                currentVideo.removeAttr('src');
                if(currentVideo[0].readyState >= 2) {
                    currentVideo[0].play();
                } else {
                    currentVideo[0].addEventListener('canplay', function start() {
                        currentVideo.is(':visible') && currentVideo[0].play();
                        currentVideo[0].removeEventListener('canplay', start);
                    });
                    currentVideo[0].load();
                }

                $(window).on('resize', function() {
                    calcMarginVideo(currentPopupVideo, currentVideo);
                    calcWidthContainerVideo(currentPopupVideo, currentVideo);
                    psPaidVideoDescription.update();
                });
            }
        }
    });

    $(document).on('click', '.js-paid-video-button', function() {
        var direction = $(this).attr('data-direction');
        var currentElement = $(this).closest('.js-video');
        var currentVideo = $(currentElement).find('.paid-video-popup__video');

        $(this).closest('.popup--paid-video').hide();

        currentVideo[0].pause();
        currentVideo[0].currentTime = 0;

        switch (direction) {
            case 'prev':
                var prevElement = $(currentElement).prevAll('.js-video')[0];
                var prevPopupVideo = $(prevElement).find('.popup--paid-video');
                var prevVideo = $(prevElement).find('.paid-video-popup__video');
                var prevVideoDescription = $(prevElement).find('.paid-video-popup__description')[0];

                prevPopupVideo.show();

                calcMarginVideo(prevPopupVideo, prevVideo);
                calcWidthContainerVideo(prevPopupVideo, prevVideo);

                var psPaidVideoDescription = new PerfectScrollbar(prevVideoDescription, {
                    suppressScrollX: false,
                    wheelPropagation: false
                });

                // Проверка на доступность файла, если данные подгружены, то запускаем воспроизведение, иначе запускаем загрузку видео с последующим воспроизведением
                prevVideo.removeAttr('src');
                if(prevVideo[0].readyState >= 2) {
                    prevVideo[0].play();
                } else {
                    prevVideo[0].addEventListener('canplay', function start() {
                        prevVideo.is(':visible') && prevVideo[0].play();
                        prevVideo[0].removeEventListener('canplay', start);
                    });
                    prevVideo[0].load();
                }

                $(window).on('resize', function() {
                    calcMarginVideo(prevPopupVideo, prevVideo);
                    calcWidthContainerVideo(prevPopupVideo, prevVideo);
                    psPaidVideoDescription.update();
                });

                break;
            case 'next':
                var nextElement = $(currentElement).nextAll('.js-video')[0];
                var nextPopupVideo = $(nextElement).find('.popup--paid-video')
                var nextVideo = $(nextElement).find('.paid-video-popup__video');
                var nextVideoDescription = $(nextElement).find('.paid-video-popup__description')[0];

                nextPopupVideo.show();

                calcMarginVideo(nextPopupVideo, nextVideo);
                calcWidthContainerVideo(nextPopupVideo, nextVideo);

                var psPaidVideoDescription = new PerfectScrollbar(nextVideoDescription, {
                    suppressScrollX: false,
                    wheelPropagation: false
                });

                // Проверка на доступность файла, если данные подгружены, то запускаем воспроизведение, иначе запускаем загрузку видео с последующим воспроизведением
                nextVideo.removeAttr('src');
                if(nextVideo[0].readyState >= 2) {
                    nextVideo[0].play();
                } else {
                    nextVideo[0].addEventListener('canplay', function start() {
                        nextVideo.is(':visible') && nextVideo[0].play();
                        nextVideo[0].removeEventListener('canplay', start);
                    });
                    nextVideo[0].load();
                }

                $(window).on('resize', function() {
                    calcMarginVideo(nextPopupVideo, nextVideo);
                    calcWidthContainerVideo(nextPopupVideo, nextVideo);
                    psPaidVideoDescription.update();
                });

                break;
        }

        var sources = currentVideo.children();
        currentVideo.children().remove();
        currentVideo[0].src = '';
        currentVideo[0].load();
        currentVideo.append(sources);
    });

    $(document).on('click', '.popup--paid-video .popup__close, .popup--paid-video .popup__bg', function() {
        var currentVideo = $(this).closest('.js-video').find('.paid-video-popup__video');

        currentVideo[0].pause();
        currentVideo[0].currentTime = 0;

        var sources = currentVideo.children();
        currentVideo.children().remove();
        currentVideo[0].src = '';
        currentVideo[0].load();
        currentVideo.append(sources);
    });

    $(document).on('click', '.paid-video-popup__title', function() {
        $(this).toggleClass('is-active');
        $(this).next('.paid-video-popup__description').toggleClass('is-active');
    });

    $(document).on('click', '.paid-video-popup__wrapper-video', function(evt) {
        if (!$(evt.target).hasClass('paid-video-popup__video')) {
            $(this).closest('.popup').find('.popup__close').trigger('click');
        }
    });

    $(document).on('contextmenu', '.paid-video-popup__video', function() {
        return false;
    });

    $(document).on("input", ".input--num", function() {
        this.value = this.value.replace(/[^0-9]/gi,"");
    });

    $(document).on('input', '.input--tel', function() {
        if ($(this).inputmask('unmaskedvalue').length < 10) {
            $(this).addClass('error');
        } else {
            $(this).removeClass('error');
        }
    });

    $(document).on("click", ".js-callback-submit", function(e) {
        var buttonCallbackSubmit = $(this);
        var lengthPhone = $(this).closest('form').find('.input--tel').inputmask('unmaskedvalue').length;

        var valid = true;

        $(this).parents('form').find('input:required').each(function() {
            if ($(this).attr('type') == 'checkbox') {
                if ($(this).prop('checked') == false) valid = false;
            } else {
                if ($(this).val() == '') valid = false;
            }
        });

        if (lengthPhone > 0 && lengthPhone < 10) {
            valid = false;
            e.preventDefault();
        }

        if (valid) {
            e.preventDefault();

            var form = $(".popup__form").serializeArray();
            var data = {};
            var ext = {
                "ajax_send": "Y"
            }

            var url = $(this).parent().attr('action');

            for (var i in form) {
                data[form[i].name] = form[i].value;
            }

            for (var i in ext) {
                data[i] = ext[i];
            }
            $.ajax({
                method: "POST",
                url: url,
                data: data,
            }).done(function (data) {
                if (buttonCallbackSubmit.parents('.popup--message').length) {
                    $('body').append(data);
                } else {
                    buttonCallbackSubmit.closest('.popup').remove();
                    $('body').append(data);
                }

                $(".input--tel").inputmask({
                    mask: "+7 (999) 999-99-99",
                    onBeforeMask: function (value, opts) {
                        if (value[0] == '8') {
                            var processedValue = value.replace('8', "");
                        }

                        return processedValue;
                    }
                });
            });
        }
    });

    $(document).on("click", ".js-callback-code-submit", function(e) {
        var buttonCallbackSubmit = $(this);

        var valid = true;

        if (valid) {
            e.preventDefault();

            var form = $(".popup__form").serializeArray();
            var data = {};
            var ext = {
                "ajax_send": "Y"
            }

            var url = $(this).parent().attr('action');

            for (var i in form) {
                data[form[i].name] = form[i].value;
            }

            for (var i in ext) {
                data[i] = ext[i];
            }
            $.ajax({
                method: "POST",
                url: url,
                data: data,
            }).done(function (data) {
                if (buttonCallbackSubmit.parents('.popup--message').length) {
                    $('body').append(data);
                } else {
                    buttonCallbackSubmit.closest('.popup').remove();
                    $('body').append(data);
                }

                $(".input--tel").inputmask({
                    mask: "+7 (999) 999-99-99",
                    onBeforeMask: function (value, opts) {
                        if (value[0] == '8') {
                            var processedValue = value.replace('8', "");
                        }

                        return processedValue;
                    }
                });
            });
        }
    });

    $(document).on("click", ".js-forbidden-video-submit", function(e) {
        var buttonCallbackSubmit = $(this);
        var lengthPhone = $(this).closest('form').find('.input--tel').inputmask('unmaskedvalue').length;

        var valid = true;

        $(this).parents('form').find('input:required').each(function() {
            if($(this).attr('type') == 'checkbox') {
                if($(this).prop('checked') == false) valid = false;
            } else {
                if($(this).val() == '') valid = false;
            }
        });

        if (lengthPhone > 0 && lengthPhone < 10) {
            valid = false;
            e.preventDefault();
        }

        if (valid) {
            e.preventDefault();

            var form = $(".popup__form").serializeArray();
            var data = {};
            var ext = {
                "ajax_send": "Y"
            }
            var url = $(this).parent().attr('action');

            for (var i in form) {
                data[form[i].name] = form[i].value;
            }

            for (var i in ext) {
                data[i] = ext[i];
            }

            $.ajax({
                method: "POST",
                url: url,
                data: data,
            }).done(function (data) {
                buttonCallbackSubmit.closest('.popup').remove();
                    var token = $(data).find("[name=auth_token]").val();
                    if(token){
                        setCookie('video_token', token, {
                            path: '/',
                            expires: 1 * 60 * 60 * 24
                        });
                    } else {
                        $('body').append(data);

                        $(".input--tel").inputmask({
                            mask: "+7 (999) 999-99-99",
                            onBeforeMask: function (value, opts) {
                                if (value[0] == '8') {
                                    var processedValue = value.replace('8', "");
                                }

                                return processedValue;
                            }
                        });
                    }
                    $.pjax.reload('#js-pjax-container');
            });
        }
    });

    $(document).on("change", "select.js-pjax-select", function() {
        if (!$(this).parents(".subscription").hasClass("js-page-trial-training"))
            sendForm({"club": $(this).val(), "ajax_menu": false, "no_save_price": true});
    });

    $(document).on("change", "select.js-pjax-select-abonements", function() {
        sessionStorage.setItem('selectedClub', $(this).val());
        sendForm({"club": $(this).val(), "ajax_menu": false, "no_save_price": true});
    });

    $(window).on('popstate', function() {
        var $selects = $('select.js-pjax-select-abonements');
        if($selects.length) {
            $selects.val(sessionStorage.getItem('selectedClub')).trigger('change');
        }
    });
    var selectChoise = false
    $(document).on('pjax:end', function() {
        if(selectChoise) {
            selectChoise = false
            var $selects = $('select.js-pjax-select-abonements');
            if($selects.length) {
                $selects.val(sessionStorage.getItem('selectedClub')).trigger('change');
            }
        }
    });

    $(document).on("change", "select.js-pjax-select-videos", function() {
        sendForm({"theme": $(this).val(), "ajax_menu": false});
    });

    $(document).on('click', '.subscription__close.js-pjax-link', function() {
        selectChoise = true;
    });

    $(document).on("click", ".subscription__code", function(e) {
        e.preventDefault();
        $.ajax({
            url: "",
            method: "POST",
            data: {
                "phone": $(".subscription__sent-tel").text(),
                "mode": "try_sms",
            }
        });
    });

    $(document).on("click", ".subscription__promo-btn", function(e, isFormSubmit) {
        e.preventDefault();
        var form = $(this).parents('form');
        var value = $(this).siblings(".subscription__promo-input").val();
        if(value != ""){
            $.ajax({
                url: "",
                method: "POST",
                dataType: "JSON",
                data: {
                    "coupon": value,
                    "mode": "coupon",
                }
            }).done(function(data){
                if(data.errorCode == "0"){
                    var sub_id = $('[name=sub_id]').val();
                    var month = '';
                    switch(sub_id){
                        case "month":
                            month = data.result.month;
                            break;
                        case "year":
                            month = data.result.year;
                            break;
                        case "network":
                            month = data.result.network;
                            break;
                        case "special":
                            month = data.result.special;
                            break;
                        case "online":
                            month = data.result.online;
                            break;
                        case "unior":
                            month = data.result.unior;
                            break;
                    }
                    var oldPrice = $(form).find('.subscription__total-value-old span').text();
                    var newPrice = "";
                    if(oldPrice){
                        oldPrice = "<span>" + oldPrice + "</span>";
                    }
                    var twoMonth = '';
                    if(month){
                        month.forEach(function(item, index, array) {
                            if($('[name=form_text_5]').val() == item.clubid){
                                newPrice = item.price;
                                if (item.prices) {
                                    if (item.prices.month == 2) {
                                        twoMonth = item.prices.price
                                    }
                                }

                            };
                        });
                    }

                    if (twoMonth) {
                        $("[name=two_month]").val(twoMonth);
                        $("[data-mouth=1] b").text(twoMonth+' руб.');
                    }
                    console.log(oldPrice, '111111')
                    if(newPrice){
                        $("[name=form_hidden_10]").val(newPrice);
                        $(".subscription__total-value").html("<div class='subscription__total-value-old'>" + oldPrice + "</div>" + newPrice + "&#x20bd;");
                        if(!$('.promocode_info').length){
                            $('.subscription__promo').append('<div class="promocode_info">Ваш промокод применен</div>');
                            if (isFormSubmit) {
                                showLegalInformation();
                            }
                        }else{
                            $('.promocode_info').text('Ваш промокод применен');
                            if (isFormSubmit) {
                                showLegalInformation();
                            }
                        }
                    }else{
                        if(!$('.promocode_info').length){
                            $('.subscription__promo').append('<div class="promocode_info">Введен неверный промокод</div>');
                        }else{
                            $('.promocode_info').text('Введен неверный промокод');
                        }
                    }


                }else{
                    if(!$('.promocode_info').length){
                        $('.subscription__promo').append('<div class="promocode_info">Введен неверный промокод</div>');
                    }else{
                        $('.promocode_info').text('Введен неверный промокод');
                    }
                }
            });
        }
    });

    $(document).on("click", ".js-check-code", function(e) {
        e.preventDefault();
        var code = "";

        $(this).closest('form').find(".input--num").each(function() {
            code += $(this).val();
        });

        if(code != "") {
            reachGo($(this).closest('form'));
            sendForm({
                "ajax_send": "Y",
                "last_step":"Y",
                "club": $('select.js-pjax-select').val(),
                "ajax_menu": false,
                "num": code,
                "mode": "check_sms",
            });
        }
    });

    $(document).on("click", ".js-check-code-training", function(e) {
        e.preventDefault();
		checkCodeTraining( $(this) );
    });

    function showLegalInformation() {
        $.ajax({
            url: "/html/oferta.php",
            cache: false,
            success: function(html){
                $('.popup__legal-information').append(html);
            }
        });
        $('.popup--legal-information').fadeIn(300);
        let scrollbarLegalInformation = new PerfectScrollbar('.popup__legal-information', {
            suppressScrollX: true,
            minScrollbarLength: 100
        });

        if (scrollbarLegalInformation.containerHeight !== scrollbarLegalInformation.contentHeight) {
            $('body').css('overflow', 'hidden');
        }

        $('.popup__close, .popup__bg').on('click', function() {
            $('body').css('overflow', '');

            scrollbarLegalInformation.destroy();
        });

        $('input[name="form_checkbox_legal-information"]').change(function() {
            if ($(this).is(':checked')) {
                $('.subscription__total-btn--reg').removeAttr('disabled');
            } else {
                $('.subscription__total-btn--reg').attr('disabled', true);
            }
        });

        $(window).on('resize', function() {
            scrollbarLegalInformation.update();
        });
    }

    $(document).on("submit", ".subscription__aside-form", function(e) {
        e.preventDefault();

        if ($('[name=promo]').val()) {
            $(".subscription__promo-btn").trigger("click", ["is-form-submit"]);
        } else {
            showLegalInformation();
        }
    });

    $(document).on("submit", ".training__aside-form", function(e) {
        console.log(1111);
        e.preventDefault();
        sendFormTraining({
            "ajax_send": "Y",
            "club": $('.training__aside-form .club').val(),
            "ajax_menu": false
        });
    });

    $(document).on('click', '.subscription__total-btn--legal-information', function() {
        sendForm({
            "ajax_send": "Y",
            "club": $('.subscription__aside-form').find('select.js-pjax-select').val(),
            "ajax_menu": false
        });
    });

    $(document).on('click', '.subscription__total-btn--legal-training', function() {
        sendFormTraining({
            "ajax_send": "Y",
            "club": $('.training__aside-form .club').val(),
            "ajax_menu": false
        });
    });

    var sendForm = function(ext) {
        var form = $(".subscription__aside-form").serializeArray();
        window.oldTitleDocument = document.title;

        var data = {};
        for (var i in form) {
            data[form[i].name] = form[i].value;
        }

        for (var i in ext) {
            data[i] = ext[i];
        }

        $.pjax.reload('#js-pjax-container', {
            timeout: false,
            type: 'POST',
            dataType: 'html',
            data: data
        });
    }
	
	var checkCodeTraining = function( obj ) {
		
		var code = "";
        $(obj).closest('form').find(".input--num").each(function() {
            code += $(this).val();
        });
		
        if( code != "" ) {
            reachGo( $(obj).closest('form') );
            sendFormTraining({
                "ajax_send": "Y",
                "last_step":"Y",
                "club": $('[name=form_text_15]').val(),
                "ajax_menu": false,
                "num": code,
                "mode": "check_sms",
            });
        }
	}
	
	$(document).on('pjax:complete', function(xhr, status, data, options) {
		$(".training__aside-form .btn--white").removeClass("disabled");
		
		$(".js-check-code-training").unbind();
		$(".js-check-code-training").click( function(e) {
        	e.preventDefault();
			checkCodeTraining( $(this) );
    	});
		
		$('html, body').animate({
            scrollTop: $("#js-pjax-clubs").offset().top
        }, 1);
	});

    var sendFormTraining = function(ext) {
        
		if( $(".training__aside-form .btn--white").hasClass("disabled") ) return;
		$(".training__aside-form .btn--white").addClass("disabled");
		
		$(".training__aside-form input[name=form_text_30]").val( $('#club_id').attr("data-id") );
		
		var form = $(".training__aside-form").serializeArray();
        window.oldTitleDocument = document.title;

        var data = {};

        for (var i in form) {
            data[form[i].name] = form[i].value;
        }

        for (var i in ext) {
            data[i] = ext[i];
        }

        if( data["form_text_30"] === "" ) {
			data["form_text_30"] = data["club"];
		}
		
        $.pjax.reload('#js-pjax-clubs', {
            timeout: false,
			url: '/',
            //replace: false,
            type: 'POST',
            dataType: 'html',
            data: data,
			fragment: '#js-pjax-clubs'
        });
    }

    var reachGo = function(form) {
        var value = form.find('input[name=form_hidden_10]').val();
        var club = form.find('input[name=form_text_5]').val();
        var additional = form.find('input[name=additional]').val();
        var go = null;

        if (value == 0) {
            switch (club) {
                case "01":
                    go = "callback-uz";
                    break;
                case "02":
                    go = "callback-kr";
                    break;
                case "03":
                    go = "callback-ch";
                    break;
                case "04":
                    go = "callback-mr";
                    break;
                case "05":
                    go = "callback-be";
                    break;
                case "06":
                    go = "callback-pd";
                    break;
                case "07":
                    go = "callback-rp";
                    break;
                case "08":
                    go = "callback-go";
                    break;
                case "09":
                    go = "callback-mk";
                    break;
                case "10":
                    go = "callback-ms";
                    break;
            }
        }

        if (value != 0 && additional != undefined) {
            switch (club) {
                case "01":
                    go = "god-uz";
                    break;
                case "02":
                    go = "god-kr";
                    break;
                case "03":
                    go = "god-ch";
                    break;
                case "05":
                    go = "god-be";
                    break;
                case "06":
                    go = "god-pd";
                    break;
                case "07":
                    go = "god-rp";
                    break;
                case "08":
                    go = "god-go";
                    break;
                case "09":
                    go = "god-mk";
                    break;
                case "10":
                    go = "god-ms";
                    break;
            }
        }

        if (value != 0 && additional == undefined) {
            switch (club) {
                case "01":
                    go = "mes-uz";
                    break;
                case "02":
                    go = "mes-kr";
                    break;
                case "03":
                    go = "mes-ch";
                    break;
                case "04":
                    go = "mes-mr";
                    break;
                case "05":
                    go = "mes-be";
                    break;
                case "06":
                    go = "mes-pd";
                    break;
                case "07":
                    go = "mes-rp";
                    break;
                case "08":
                    go = "mes-go";
                    break;
                case "09":
                    go = "mes-mk";
                    break;
                case "10":
                    go = "mes-ms";
                    break;
            }
        }

        if (go !== null) {

        }
    }

    function reachGoOnline() {
        if ($("form.subscription__aside-form").length) {
            var form = $("form.subscription__aside-form");
            var subID = form.find("input[name=\"sub_id\"]").val();

            if (subID == "online") {

            }
        }
    }

    $(document).on("click", ".js-path-to", function() {
        var popup = $('.popup-path_to');
        if (!popup.is(':visible')) {
            if ($(this).parents(".club_info").length > 0 && $(this).parents(".club_info").data("id") != "") {
                var id = $(this).parents(".club_info").data("id");
                $(".popup__desc").not("[data-id='"+id+"']").hide();
                $(".popup__desc[data-id='"+id+"']").fadeIn();
            }
            popup.fadeIn(300);
        }else{
            popup.fadeOut(300);
        }
    });
    $(document).on("click", ".js-btn-pay", function() {
        reachGo($(this).closest('form'));
        reachGoOnline();
        sendForm({
            "ajax_send": "Y",
            "club": $('select.js-pjax-select').val(),
            "ajax_menu": false,
        });
    });
    /*faq*/
    $(document).on("click", ".faq__block-title", function() {
        $('.faq__block-title').next().slideUp(300);
		if (!$(this).next().is(':visible')) {
			$(this).next().slideDown(300);
		}
    });

    $(document).on("click", ".faq__aside-buttons-caret", function() {
		$('.faq__aside-btn').toggle(0);
		$('.faq__aside-btn--active').show(0);
	});

    function moveFAQButtonsS() {
        $(".faq__aside-buttons-caret").click(function(){});
		if ($(window).innerWidth() < 1260) {
			$('.faq__aside-buttons').appendTo('.faq__main-title');
		} else {
			$('.faq__aside-buttons').appendTo('.faq__aside-heading');
		}
    }
    moveFAQButtonsS();

    $(window).on('resize', function(){
        moveFAQButtonsS();
    })

/* Grid Main PS */
    $(document).on('wheel', function(event) {
        var $gridMain = $('.grid__main');
        var $ignoreElements = $(event.target).closest('.subscription__aside-form-row--select');

        if ($gridMain.length && $ignoreElements.length) {
            if (!$(event.target).closest('.ps').length) {
                $gridMain.scrollTop($gridMain.scrollTop() + event.originalEvent.deltaY);
            }
        }
    });

    $(document).on('gridMain:init', function() {
        $('body').css('overflow', '');
        $('.grid__main:not(.grid__main--subscription), .subscription__include-wrapper, .grid-element__inner--free, .club__team-quote--subscription').each(function(index, elem) {
            var ps = new PerfectScrollbar(elem, {
                suppressScrollX: true
            });
            // if(ps.containerHeight !== ps.contentHeight) {
            //     $('body').css('overflow','hidden');
            // }
            // if(window.innerWidth <= 1260 || (window.innerWidth >= 1260 && window.innerWidth / window.innerHeight >= 76/35)) {
            //     $('body').css('overflow','');
            // }

            $(elem).on('update', function() {
                ps.update();
                // if(ps.containerHeight !== ps.contentHeight) {
                //     $('body').css('overflow','hidden');
                // } else {
                //     $('body').css('overflow','');
                // }
                if (window.innerWidth <= 1260 || (window.innerWidth >= 1260 && window.innerWidth / window.innerHeight >= 76/35)) {
                    $('body').css('overflow','');
                }
            });

            $(window).on('resize', function(){
                ps.update();
                // if(ps.containerHeight !== ps.contentHeight) {
                //     $('body').css('overflow','hidden');
                // } else {
                //     $('body').css('overflow','');
                // }

                if (window.innerWidth <= 1260 || (window.innerWidth >= 1260 && window.innerWidth / window.innerHeight >= 76/35)) {
                    $('body').css('overflow','');
                }
            });

            if (bowser.msie) {
                $('#map-yandex').addClass('ie');
            }

            checkAvailableVideo();
        });
        $('.grid__main--subscription').each(function(index, element) {
            var ps = new PerfectScrollbar(element, {
                suppressScrollY: true,
                useBothWheelAxes: true
            });

            $(window).on('resize', function(){
                ps.update();
            });
        });
    });
    $(document).trigger('gridMain:init');

    var clubFormSuccessTimer = 0;
    $(document).on('clubFormSuccessOpen', function(){
        $('.js-club-popup-success').fadeIn();
        $('html, body').css('overflow', 'hidden');
        clubFormSuccessTimer = setTimeout(function() {
            $('.js-club-popup-success').fadeOut();
            $('html, body').css('overflow', '');
        }, 4000)
    });
    $(document).on('clubFormSuccessClose', function(){
        clearTimeout(clubFormSuccessTimer);
        $('.js-club-popup-success').fadeOut();
        $('html, body').css('overflow', '');
    });

    $('.js-club-close-popup').on('click', function(){
        clubFormSuccessClose();
    });

    $(document).on('click', '.s_round', function(){
        $(this).parents('.club__team-slide, .club__advantages-slide, .grid__main-inner, .application__possibilities-slide--traning').find('.flip_box').toggleClass('flipped');
        $(this).addClass('s_round_click');
        $(this).parents('.club__team-slide, .club__advantages-slide, .grid__main-inner, .application__possibilities-slide--traning').find('.s_arrow').toggleClass('s_arrow_rotate');
        $('.b_round').toggleClass('b_round_back_hover');
        return false;
    });
    $(document).on('transitionend', '.s_round', function(){
        $(this).removeClass('s_round_click');
        $(this).addClass('s_round_back');
        return false;
    });
    $(document).on('click', '.club__team-slide, .club__advantages-slide, .grid__main-inner, .application__possibilities-slide--traning', function(){
        $(this).find('.s_round').trigger('click');
    });

    $(document).on('click', '.btn--subscription', function(e){
        e.stopPropagation();
    });

    $(document).on('click', '.training__aside--subscription-form, .subscription__total-btn--reg', function(e){
        e.stopPropagation();
    });

    $(document).on('click', '.video', function(){
        !!player && !!player.stopVideo && player.stopVideo()
        $(".popup--video").fadeIn(300)
    });

    $(document).on("click touchend", ".popup__close, .popup__bg", function () {
        $(this).closest(".popup").fadeOut(400), $(window).innerWidth() < 1260 && ($(".header__burger--active").removeClass("header__burger--active"), $(".header__nav").hide(), $(".page-wrapper main, .footer").fadeIn(400));
        //!!player && !!player.stopVideo && player.stopVideo()
    });

    if ($(".timetable__container").length > 0) {
        var d = null;
        $(".timetable__container").each(function() {
            d = new PerfectScrollbar(this);
        });
    }
    // $('select.input--select').each(function(){
    //     if (!$(this).parent().is('.jq-selectbox')) {
    //         !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
    //             selectSmartPositioning: !1
    //         })
    //     }
    // })
    $('select.custom--select').select2({
        minimumResultsForSearch: Infinity
    });
    $('select.custom--select-video').select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: $('.grid-element--select-videos'),
    });
    $("select.timetable__filter-dept").on("change", function() {
        var e = '',
            t = void 0;
        var value = $(this).val()
        $(this).find('option').each(function() {
            if ($(this).text().replace(/\s/g,'') == value.replace(/\s/g,'')) {
                e = $(this).data('timetable');
            }
        });
        $(this).styler('close');
        $(".timetable__container").each(function() {
            $(this).is(":visible") && (t = $(this))
        }), e !== t.data("timetable") && (t.fadeOut(300), setTimeout(function() {
            $('.timetable__container[data-timetable="' + e + '"]').css("display", "flex").hide().fadeIn()
        }, 300), setTimeout(function() {
            if ($('.timetable__container[data-timetable="' + e + '"]').length > 0) {
                d = new PerfectScrollbar('.timetable__container[data-timetable="' + e + '"]'), $(".ps__rail-x--top").remove(), $(".timetable__container.ps").scrollLeft(0)
            }
        }, 300))
    });

    var setCookie = function(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
          d.setTime(d.getTime() + expires * 1000);
          expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
          options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
          updatedCookie += "; " + propName;
          var propValue = options[propName];
          if (propValue !== true) {
          updatedCookie += "=" + propValue;
          }
        }

        document.cookie = updatedCookie;
    };



    //Antyxweb
    var isMacLike2 = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;
    if (!isMacLike2) {
        // $('.popup--sign select.input--select').styler({
        //     selectSmartPositioning: true
        // });
    } else {
        // $('.popup--sign select.input--select').styler({
        //     selectSmartPositioning: !1
        // });
    }

    // $('.popup--sign select.input--select').each(function(){
    //     if (!$(this).parent().is('.jq-selectbox')) {
    //         !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
    //             selectSmartPositioning: !1
    //         })
    //     }
    // })

    $('body').on('click', '.open--popup--sign', function (e) {
        e.preventDefault();
        $('.popup--sign').fadeIn(600);
    });

    $('body').on('submit', '.popup--sign .popup__form', function (e) {
        e.preventDefault();
        $formData = $(this).serialize();

        $('.popup--sign').fadeOut(300);
        $('.popup--success-sign').fadeIn(600);

        $.post( "/local/templates/spiritfit/ajax/sign.php", $formData)
            .done(function( data ) {
                console.log( data );
            });
    });

    $(document).on('click', '#quality_form .btn--white', function(e) {
        e.preventDefault();

        var text68,text69,text70,text71,text72,text73,text74,text75,text76,text77,text78;

        request_form= '#quality_form';

        $('.errortext').fadeOut(1);
        $(request_form).find('.errortext').empty();

        if (!$(request_form).find('input[name="form_text_68"]').val()) {
          request_err1 = 1;
          text68 = '&nbsp;&nbsp;»&nbsp;"Выберите клуб"<br>';
        } else {
          request_err1 = 0;
        }
        if (!$(request_form).find('input[name="form_text_69"]').val()) {
          request_err2 = 1;
          text69 = '&nbsp;&nbsp;»&nbsp;"Оцените работу консультантов фитнес-клуба"<br>';
        } else {
          request_err2 = 0;
        }
        if (!$(request_form).find('input[name="form_text_70"]').val()) {
          request_err3 = 1;
          text70 = '&nbsp;&nbsp;»&nbsp;"Оцените чистоту фитнес-клуба"<br>';
        } else {
          request_err3 = 0;
        }
        if (!$(request_form).find('input[name="form_text_71"]').val()) {
          request_err4 = 1;
          text71 = '&nbsp;&nbsp;»&nbsp;"Оцените работу приложения"<br>';
        } else {
          request_err4 = 0;
        }
        if (!$(request_form).find('input[name="form_text_72"]').val()) {
          request_err5 = 1;
          text72 = '&nbsp;&nbsp;»&nbsp;"Оцените работу тренеров тренажерного зала"<br>';
        } else {
          request_err5 = 0;
        }
        if (!$(request_form).find('input[name="form_text_73"]').val()) {
          request_err6 = 1;
          text73 = '&nbsp;&nbsp;»&nbsp;"Оцените расписание групповых программ"<br>';
        } else {
          request_err6 = 0;
        }
        if (!$(request_form).find('input[name="form_text_74"]').val()) {
          request_err7 = 1;
          text74 = '&nbsp;&nbsp;»&nbsp;"Оцените тренажеры фитнес-клуба"<br>';
        } else {
          request_err7 = 0;
        }
        if (!$(request_form).find('input[name="form_text_75"]').val()) {
          request_err8 = 1;
          text75 = '&nbsp;&nbsp;»&nbsp;"Оцените фитнес-клуб в целом"<br>';
        } else {
          request_err8 = 0;
        }
        
        console.log();
        if (!$(request_form).find('input[name="form_checkbox_personal"]').prop('checked')) {
            request_err9 = 1;
            text76 = '&nbsp;&nbsp;»&nbsp;"Ознакомлен с Политикой, согласен на Обработку персональных данных"<br>';
        } else {
            request_err9 = 0;
        }

        if (!$(request_form).find('input[name="form_checkbox_rules"]').prop('checked')) {
            request_err10 = 1;
            text77 = '&nbsp;&nbsp;»&nbsp;"Ознакомлен и согласен с Офертой и Правилами клуба"<br>';
        } else {
            request_err10 = 0;
        }

        if (!$(request_form).find('input[name="form_checkbox_privacy"]').prop('checked')) {
            request_err11 = 1;
            text78 = '&nbsp;&nbsp;»&nbsp;"Согласен с Политикой конфиденциальности"<br>';
        } else {
            request_err11 = 0;
        }

        if(request_err1 == 0 && request_err2 == 0 && request_err3 == 0 && request_err4 == 0 && request_err5 == 0 && request_err6 == 0 && request_err7 == 0 && request_err8 == 0 && request_err9 == 0 && request_err10 == 0 && request_err11 == 0){

            $.ajax({
            type: 'POST',
            url: '/local/templates/spiritfit/ajax/quality-send.php',
            data: $("#quality_form").serialize(),
            success:function(data){
               $('#body_quality').html(data);
            //    $('select.input--select').each(function(){
            //         if (!$(this).parent().is('.jq-selectbox')) {
            //             !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
            //                 selectSmartPositioning: !1
            //             })
            //         }
            //     })
               $('h1.quality__heading').remove();
            },
            error:function (data){
               $('#body_quality').html(data);
            }
          });
        }else{
            $(request_form).find('.errortext').append('Не заполнены следующие обязательные поля:<br>');
            $(request_form).find('.errortext').append(text68);
            $(request_form).find('.errortext').append(text69);
            $(request_form).find('.errortext').append(text70);
            $(request_form).find('.errortext').append(text71);
            $(request_form).find('.errortext').append(text72);
            $(request_form).find('.errortext').append(text73);
            $(request_form).find('.errortext').append(text74);
            $(request_form).find('.errortext').append(text75);
            $(request_form).find('.errortext').append(text76);
            $(request_form).find('.errortext').append(text77);
            $(request_form).find('.errortext').append(text78);

            $('.errortext').fadeIn(500);
        }
       
    });

    $(document).on('click', '#poll_form .btn--white', function(e) {
        e.preventDefault();

        var text86,travel_time,transport,location,text83;

        request_form= '#poll_form';

        $('.errortext').fadeOut(1);
        $(request_form).find('.errortext').empty();

        if (!$(request_form).find('input[name="form_text_86"]').val()) {
          request_err1 = 1;
          text86 = '&nbsp;&nbsp;»&nbsp;"Выберите клуб"<br>';
        } else {
          request_err1 = 0;
        }
        if ($("#form_dropdown_travel_time").val() ==76) {
          request_err2 = 1;
          travel_time = '&nbsp;&nbsp;»&nbsp;"Укажите время в пути до фитнес-клуба"<br>';
        } else {
          request_err2 = 0;
        }
        if ($("#form_dropdown_transport").val() ==79) {
          request_err3 = 1;
          transport = '&nbsp;&nbsp;»&nbsp;"Как вы добираетесь до фитнес-клуба"<br>';
        } else {
          request_err3 = 0;
        }
        if ($("#form_dropdown_location").val() ==80) {
          request_err4 = 1;
          location = '&nbsp;&nbsp;»&nbsp;"Фитнес-клуб находится рядом"<br>';
        } else {
          request_err4 = 0;
        }
        if (!$(request_form).find('input[name="form_text_83"]').val()) {
          request_err5 = 1;
          text83 = '&nbsp;&nbsp;»&nbsp;"Укажите ближайшую станцию метро"<br>';
        } else {
          request_err5 = 0;
        }

        if (request_err1 == 0 && request_err2 == 0 && request_err3 == 0 && request_err4 == 0 && request_err5 == 0) {
            $.ajax({
            type: 'POST',
            url: '/local/templates/spiritfit/ajax/poll_send.php',
            data: $("#poll_form").serialize(),
            success:function(data){
               $('#body_poll').html(data);
            //    $('select.input--select').each(function(){
            //         if (!$(this).parent().is('.jq-selectbox')) {
            //             !!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i) || $(this).styler({
            //                 selectSmartPositioning: !1
            //             })
            //         }
            //     })
               $('h1.quality__heading').remove();
            },
            error:function (data){
               $('#body_poll').html(data);
            }
          });
        }else{
            $(request_form).find('.errortext').append('Не заполнены следующие обязательные поля:<br>');
            $(request_form).find('.errortext').append(text86);
            $(request_form).find('.errortext').append(travel_time);
            $(request_form).find('.errortext').append(transport);
            $(request_form).find('.errortext').append(location);
            $(request_form).find('.errortext').append(text83);

            $('.errortext').fadeIn(500);
        }
       
    });
});