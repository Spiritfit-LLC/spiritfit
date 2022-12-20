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
    })

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
