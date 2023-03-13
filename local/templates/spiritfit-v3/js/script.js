var clickHandler = ("ontouchstart" in window ? "touchend" : "click");

window.addEventListener('DOMContentLoaded', (e) => {
    yallJs.yall();
});

function startPreventBodyScroll(){
    $("body").addClass("is-fixed");
    if($(window).width()<1025)
        $("body").css({
            position:"fixed",
            width:$("body").width()+"px"
        })
}
function endPreventBodyScroll(){
    $("body").removeClass("is-fixed");
    if ($(window).width()<1025)
        $("body").css({
            position:"static",
            width:"auto"
        })
}


function dataLayerSend (eCategory, eAction, eLabel, eNI = false) {
    /*console.log("dataLayerSend:" + eCategory + "_" + eAction);*/
    (dataLayer = window.dataLayer || []).push({
        'eCategory': eCategory,
        'eAction': eAction,
        'eLabel': eLabel,
        'eNI': eNI,
        'event': 'GAEvent'
    });
}

//Кнопка звонка
function phone_btn_position(){
    if (window.innerWidth<=1024){
        $('.main-phone-btn').data('position', 'mobileRoundButton');
    }
    else{
        $('.main-phone-btn').data('position', 'header');
    }
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

$(document).ready(function(){
    $("body").addClass("is-body-loaded")

    phone_btn_position();
    $(window).resize(phone_btn_position);

    $('.phone-btn').click(function(){
        if ($(this).data('position')==='page'){
            position=document.location.protocol+'//'+document.location.host+document.location.pathname
        }
        else{
            position=$(this).data('position');
        }
        dataLayerSend('UX','clickCallButton', position);
    });

    //Хедер
    $('.b-header').each(function () {
        var $context = $(this);
        var lastScrollTop = 100;
        $(document).on('scroll', function (e) {
            if ($(window).width() < 1200) return;
            var currentScroll = $(document).scrollTop();
            $context.addClass('remove-animate');

            if (currentScroll > lastScrollTop) {
                $context.addClass('is-hidden');
            } else {
                $context.removeClass('is-hidden');
            }

            lastScrollTop = currentScroll <= 100 ? 100 : currentScroll;
        });
    });

    //Кнопка меню в моб версии
    $('.b-top-menu').each(function () {
        var $context = $(this);
        var $toggle = $('.b-top-menu__toggle', $context);
        var $page = $('body');
        var scrollTopOnClick;
        $toggle.on('click', function () {
            $context.toggleClass('is-menu-opened');

            if ($context.is('.is-menu-opened')) {
                scrollTopOnClick = window.pageYOffset || document.documentElement.scrollTop;
                $page.css({
                    "top": -1 * scrollTopOnClick
                });
                startPreventBodyScroll();
            } else {
                endPreventBodyScroll();
                $page.css({
                    "top": '0'
                });
                window.scrollTo(0, scrollTopOnClick);
            }
        });
    });


    //SELECTS
    var $selects=$("select.select2");
    $selects.each(function(){
        var $this=$(this);
        if ($this.data('placeholder')!==undefined){
            var placeholder=$this.data('placeholder');
        }
        else{
            placeholder='';
        }

        if ($this.data('close')!==undefined){
            var close=$this.data('close')
        }
        else{
            close=true;
        }

        $this.select2(
            {
                width:"100%",
                placeholder:placeholder,
                dropdownParent:$this.parent(),
                language:{
                    noResults:function(){
                        return"Ничего не найдено"
                    }
                },
                closeOnSelect: close,
            }
        );
    });





    $('.dont-touch').removeAttr('href');

    $('.dont-touch').click(function(e){
        e.preventDefault();
        return false;
    });

    //Общая функция отправки в ГА
    $('[data-layer="true"]').on(clickHandler, function(){
        if ($(this).data("layercategory")!==undefined){
            var ecategory=$(this).data("layercategory");
        }
        else{
            ecategory="UX";
        }

        if ($(this).data("layerlabel")!==undefined){
            if ($(this).data("layerlabel")==="current_url"){
                var elabel = window.location.href;
            }
            else{
                elabel=$(this).data("layerlabel");
            }
        }
        else{
            elabel="";
        }

        var eaction=$(this).data("layeraction");

        dataLayerSend(ecategory, eaction, elabel);
    });


    $("select#club-search").on("select2:select", function(){
        window.location.href=$(this).val();
    });


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
        var link=$(this).attr("href")
        if (link !== undefined && link.indexOf('http') !== -1){
            var url = new URL($(this).attr("href"));
            if (url.hostname!==location.hostname){
                dataLayerSend('UX', 'clickExternalLink', url.href);
            }
        }
    });


    var lazyBackgrounds = [].slice.call(document.querySelectorAll(".lazy-img-bg"));

    if ("IntersectionObserver" in window) {
        let lazyBackgroundObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var $target=$(entry.target);
                    $target.css("background-image", 'url('+$target.data("src")+')');
                    lazyBackgroundObserver.unobserve(entry.target);
                }
            });
        });

        lazyBackgrounds.forEach(function(lazyBackground) {
            lazyBackgroundObserver.observe(lazyBackground);
        });
    }

    var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

    if ("IntersectionObserver" in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var $target=$(entry.target);
                    $target.attr("src", $target.data("src"));
                    lazyImageObserver.unobserve(entry.target);
                }
            });
        });

        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }


    //Подсказки
    var dadata_token="fa43a728a5f92101fcb6e4afa7ad6eda489da066";

    $('input[data-dadata-type]').each(function(index, el){
        if ($(el).data('dadata-type')==="NAME"){
            var options= {
                token: dadata_token,
                type: "NAME",
                count:5,
                deferRequestBy:500,
                hint:false,
                params: {
                    parts: [$(el).data('dadata-part')]
                },
                beforeRender:function(container){
                    console.log(container, this)
                }
            }
        }
        else if ($(el).data('dadata-type')==="ADDRESS"){
            options= {
                token: dadata_token,
                type: "ADDRESS",
                count: 5,
                deferRequestBy: 500,
                hint: false,
                minChars: 2,
                triggerSelectOnBlur:true,
                triggerSelectOnEnter:true,
                triggerSelectOnSpace:true,
                /* Вызывается, когда пользователь выбирает одну из подсказок */
                onSelect: function (suggestion) {
                    if (suggestion.data.city === null) {
                        var error_message = "Город не выбран";
                    } else if (suggestion.data.house === null) {
                        error_message = "Дом не выбран";
                    } else if ($(el).data('moscow')!==false && (parseFloat(suggestion.data.geo_lat)<54.288991 || parseFloat(suggestion.data.geo_lat)>56.929291)){
                        error_message = "Адрес находится за пределами Москвы или области";
                    }
                    else if ($(el).data('moscow')!==false && (parseFloat(suggestion.data.geo_lon)>40.180157 || parseFloat(suggestion.data.geo_lon)<35.177239)){
                        error_message = "Адрес находится за пределами Москвы или области";
                    }
                    else {
                        if ($(this).next('.field-error').length > 0) {
                            $(this).next('.field-error').remove()
                        }
                        if ($(this).closest('form').find('input[name="geo_lat"]').length===0){
                            $(this).closest('form').append($(`<input name="geo_lat" type="hidden" value="${suggestion.data.geo_lat}">`));
                        }
                        else{
                            $(this).closest('form').find('input[name="geo_lat"]').val(suggestion.data.geo_lat);
                        }
                        if ($(this).closest('form').find('input[name="geo_lon"]').length===0){
                            $(this).closest('form').append($(`<input name="geo_lon" type="hidden" value="${suggestion.data.geo_lon}">`));
                        }
                        else{
                            $(this).closest('form').find('input[name="geo_lon"]').val(suggestion.data.geo_lon);
                        }
                        $(this).closest('form').find('input[type="submit"]').removeAttr('disabled');
                        return;
                    }
                    if ($(this).next('.field-error').length > 0) {
                        $(this).next('.field-error').text(error_message)
                    } else {
                        $(this).after(`<span class="field-error">${error_message}</span>`);
                    }
                    $(this).closest('form').find('input[type="submit"]').prop('disabled', 'disabled');

                },
                onSelectNothing:function(){
                    var error_message = "Не удалось определить адрес";
                    if ($(this).next('.field-error').length > 0) {
                        $(this).next('.field-error').text(error_message)
                    } else {
                        $(this).after(`<span class="field-error">${error_message}</span>`);
                    }
                    $(this).closest('form').find('input[type="submit"]').prop('disabled', 'disabled');
                }
            }
        }
        var sgt = $(el).suggestions(options);
    });

    $('input[type="email"]').each(function(index, el){
        $(el).suggestions({
            token: dadata_token,
            type: "EMAIL",
            count:5,
            deferRequestBy:500,
            hint:false
        });
    });
});


function getGaId()
{
    var id;
    if (typeof window.ga === 'function')
    {
        ga(function(tracker) {
            id = tracker.get('clientId');
        });
        if (id)
        {
            return id;
        }

        if (ga.getAll && ga.getAll()[0])
        {
            id = ga.getAll()[0].get('clientId');
        }
    }

    if (id)
    {
        return id;
    }

    id = (document.cookie || '').match(/_ga=(.+?);/);
    if (id)
    {
        id = (id[1] || '').split('.').slice(-2).join(".")
    }

    return id ? id : null;
}

function  getYaId()
{
    var id;
    if (window.Ya)
    {
        var yaId;
        if (Ya.Metrika && Ya.Metrika.counters()[0])
        {
            yaId = Ya.Metrika.counters()[0].id;
        }
        else if (Ya.Metrika2 && Ya.Metrika2.counters()[0])
        {
            yaId = Ya.Metrika2.counters()[0].id;
        }

        if (!yaId)
        {
            return null;
        }

        if (window.ym && typeof window.ym === 'object')
        {
            ym(yaId, 'getClientID', function(clientID) {
                id = clientID;
            });
        }

        if (!id && window['yaCounter' + yaId])
        {
            id = window['yaCounter' + yaId].getClientID();
        }
    }

    if (!id)
    {
        id = webPacker.cookie.get('_ym_uid');
    }

    return id ? id : null;
}

function setConversion(module, callback=null){
    BX.ajax.runComponentAction("custom:conversion", 'setConversion', {
        mode: 'class',
        data: {
            "module":module,
            "callback":callback
        },
        method:'POST'
    });
}
