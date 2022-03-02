function sendToUpMetrika(sendData){
    // console.log({
    //     'phoneMd5': CryptoJS.MD5(sendData['phone']).toString(),
    //     'emailMd5': CryptoJS.MD5(sendData['email']).toString(),
    //     'phoneSha256': CryptoJS.SHA256(sendData['phone']).toString(),
    //     'emailSha256': CryptoJS.SHA256(sendData['email']).toString(),
    //     'typeSetClient': sendData['setTypeClient']
    // })
    sendData['phone']=sendData['phone'].replace(/[^0-9]/g,"");

    acfp.setClient({
        'phoneMd5': CryptoJS.MD5(sendData['phone']).toString(),
        'emailMd5': CryptoJS.MD5(sendData['email']).toString(),
        'phoneSha256': CryptoJS.SHA256(sendData['phone']).toString(),
        'emailSha256': CryptoJS.SHA256(sendData['email']).toString(),
        'typeSetClient': sendData['setTypeClient']
    });
        
}

function setScrollFormModal(){
    var block = document.querySelector('.modalForm .popup__window--modal-form');
    var clientH = document.documentElement.clientHeight;
    if(block != null){
        var formH = document.querySelector('.modalForm .form-standart').clientHeight;
        var blockH = block.clientHeight;
        
        if(blockH > clientH){
            $(block).addClass('scroll');
        }else if(formH > blockH){
            $(block).addClass('scroll'); 
        }else{
            $(block).removeClass('scroll');
        }
    }
}

function initFormFields($context) {
    var $form = $('form', $context);
    var $textInputs = $('input[type="text"], input[type="email"], input[type="tel"]', $form);
    var $textareas = $('textarea', $form);
    var $selects = $('select', $form);
    var $checks = $('input[type="checkbox"], input[type="radio"]', $form);
    // var $successMessage = $(".".concat($context[0].classList[0], "__success-message"));

    function checkVal($input, regExp) {
        var result = regExp.test($input.val());
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));

        if (result) {
            $relatedField.removeClass('is-error');
        } else {
            $relatedField.addClass('is-error');
        }
    }

    function checkTextInputFill($input) {
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));

        if ($input.val().length > 0 && $input.val() !== '+7(___)___-__-__') {
            $relatedField.addClass('is-dirty').removeClass('is-empty');
        } else {
            $relatedField.removeClass('is-dirty').addClass('is-empty');
        }

        $relatedField.removeClass('is-focus');
    }

    function checkBinaryInput($input) {
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));

        if ($input.is('[data-necessary]') && $input.prop('checked') === false) {
            $relatedField.addClass('is-error');
        } else {
            $relatedField.removeClass('is-error');
        }
    }

    function checkSelect($input) {
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));

        if ($input.is('[data-necessary]') && $input.val() === '') {
            $relatedField.addClass('is-none');
        } else {
            $relatedField.removeClass('is-none');
        }
    }

    function checkTextarea(textarea) {
        var $relatedField = $(textarea).closest(".".concat($context[0].classList[0], "__field"));

        if ($(textarea).is('[data-necessary]') && textarea.value.length < 1) {
            $relatedField.addClass('is-none');
        } else {
            $relatedField.removeClass('is-none');
        }
    }

    function validateTextInput($input) {
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));
        if (!$input.is('[data-necessary]')) return;

        if ($input.val().length < 1 || $input.val() === '+7(___)___-__-__') {
            $relatedField.addClass('is-none');
        } else if ($input.is('[type="tel"]')) {
            var regex = /\+7\(\d{3}\)\d{3}-\d{2}-\d{2}/;
            $relatedField.removeClass('is-none');
            checkVal($input, regex);
        } else if ($input.is('[type="email"]')) {
            var _regex = /^([A-Za-z0-9_-]+\.)*[A-Za-z0-9\+_-]+@[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*\.[a-z]{2,6}$/g;
            $relatedField.removeClass('is-none');
            checkVal($input, _regex);
        } else {
            $relatedField.removeClass('is-none');
        }
    }

    $('input[type="tel"]', $form).mask('+7(999)999-99-99', {
        autoclear: false
    });
    $textInputs.each(function () {
        checkTextInputFill($(this));
    });
    $form.on('focusout', function (e) {
        var $input = $(e.target).closest('input');
        var textarea = e.target.closest('textarea');
        if (!$input && !textarea) return;

        if ($input.is('[type="text"]') || $input.is('[type="email"]') || $input.is('[type="tel"]')) {
            checkTextInputFill($input);
            validateTextInput($input);
        }

        if (textarea) {
            checkTextarea(textarea);
        }
    }).on('focusin', function (e) {
        var $input = $(e.target).closest('input');
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));
        $relatedField.addClass('is-focus');
    }).on('change', function (e) {
        var $input = $(e.target).closest('input');
        var $relatedField = $input.closest(".".concat($context[0].classList[0], "__field"));
        $relatedField.removeClass('is-empty').addClass('is-dirty').removeClass('is-error');
    }).on('submit', function (e) {
        e.preventDefault();
        $textInputs.each(function () {
            var $input = $(this);
            checkTextInputFill($input);
            validateTextInput($input);
        });
        $textareas.each(function () {
            validateTextInput($(this));
        });
        $checks.each(function () {
            checkBinaryInput($(this));
        });
        $selects.each(function () {
            checkSelect($(this));
        });

        if ($('.is-error, .is-none', $form).length < 1) {
            //$form.hide();
            //$successMessage.show();
        }
    });
    $selects.each(function () {
        var $select = $(this);
        if (!$select.is('.select2-hidden-accessible')) {
            var placeholder = $select.attr('data-placeholder');
            $select.select2({
                width: '100%',
                minimumResultsForSearch: 100,
                placeholder: placeholder,
                dropdownParent: $select.parent(),
                "language": {
                    "noResults": function noResults() {
                        return "Ничего не найдено";
                    }
                }
            });
        }
    });
    $context.addClass('is-ready');
}
var reachGo_v2 = function(form) {
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

function reachGoOnline_v2() {
    if ($("form.subscription__aside-form_v2").length) {
        var form = $("form.subscription__aside-form_v2");
        var subID = form.find("input[name=\"sub_id\"]").val();

        if (subID == "online") {

        }
    }
}

var sendForm_v2 = function(ext) {
    var form = $(".subscription__aside-form_v2").serializeArray();
    var trial = false;
    window.oldTitleDocument = document.title;

    for (var i in ext) {
        if(i == 'trial' && ext[i] == 'Y') {
            trial = true;
            form = $(".subscription__aside-form-trial_v2").serializeArray();
        }
    }

    var data = {};
    for (var i in form) {
        data[form[i].name] = form[i].value;
    }

    for (var i in ext) {
        data[i] = ext[i];
    }

    if(trial){
        $.pjax.reload('#js-pjax-container-trial', {
            url: form = $(".subscription__aside-form-trial_v2").attr('action'),
            replace: false,
            timeout: false,
            type: 'POST',
            dataType: 'html',
            data: data
        });
    }else{
        $.pjax.reload('#js-pjax-container', {
            timeout: false,
            type: 'POST',
            dataType: 'html',
            data: data
        });
    }
    
}

var sendFormTraining_v2 = function(ext) {
    var form = $(".training__aside-form_v2").serializeArray();
    window.oldTitleDocument = document.title;

    var data = {};

    for (var i in form) {
        data[form[i].name] = form[i].value;
    }

    for (var i in ext) {
        data[i] = ext[i];
    }

    $.pjax.reload('#js-pjax-clubs', {
        timeout: false,
        type: 'POST',
        dataType: 'html',
        data: data
    });
}

function showLegalInformation_v2(form) {
    var block = $(form).find('.popup__legal-information');
    var block2 = $(form).find('.popup--legal-information');
    var block3 = $(form).find('.subscription__total-btn--reg');
    var blockScroll = $(form).parents('.popup__window--modal-form');

    $.ajax({
        url: "/html/oferta.php",
        cache: false,
        success: function(html){
            block.append(html);
        }
    });
    blockScroll.addClass('is-info');
    block2.fadeIn(300);
    let scrollbarLegalInformation = new PerfectScrollbar(block[0], {
        suppressScrollX: true,
        minScrollbarLength: 100
    });

    if (scrollbarLegalInformation.containerHeight !== scrollbarLegalInformation.contentHeight) {
        $('body').css('overflow', 'hidden');
    }

    $('.popup__close, .popup__bg').on('click', function() {
        $('body').css('overflow', '');
        blockScroll.removeClass('is-info');

        scrollbarLegalInformation.destroy();
    });

    $(form).find('input[name="form_checkbox_legal-information"]').change(function() {
        if ($(this).is(':checked')) {
            block3.removeAttr('disabled');
        } else {
            block3.attr('disabled', true);
        }
    });

    $(window).on('resize', function() {
        scrollbarLegalInformation.update();
    });
}

function clickBtn(el){
    var form = $(el).parents('form');
    $(form).find('[type=submit]').trigger('click');
    $('.subscription__promo-btn').trigger('click', ["is-form-submit"]);
    $('.subscription__promo-btn_v2').trigger('click', ["is-form-submit"]);
}

$(document).ready(function(){
    
	/* Открываем нужную вкладку блока FAQ */
	var url = window.location.href, idx = url.indexOf("#");
	var hash = idx != -1 ? url.substring(idx+1) : "";
	if( hash !== "" ) {
		var hashes = [];
		$(".b-faq .b-faq__tab-link").each(function() {
			hashes.push($(this).attr("id"));
		});
		if( hashes.includes(hash) ) {
			var currentTab = $("#"+hash);
			var wrapper = $(currentTab).parents(".b-faq__content").eq(0);
			
			var contentTabs = $(wrapper).find(".b-faq__tabs-list").eq(0);
			var tabLinks = $(wrapper).find(".b-faq__tab-links").eq(0);
			
			$(tabLinks).find(".b-faq__tab-link").removeClass("is-active");
			$(contentTabs).find(".b-faq__tab").removeClass("is-active");
			
			$(currentTab).addClass("is-active");
			$(contentTabs).find(".b-faq__tab").eq($(currentTab).index()).addClass('is-active');
			
			if ($(window).width() < 1025) {
                $('html, body').animate({
                    scrollTop: currentTab.offset().top - 100
                }, 1000);
            }
		}
	}
	
	$('.b-info-slider__btn').click(function(){
        var titleSale = $(this).parents('.b-info-slider__item').find('.b-info-slider__title').text();
        dataLayerSend('UX', 'clickOrderLinkSliderPromo', titleSale);
    });

    $('[data-fancybox="feedback-choice"]').click(function(){
        dataLayerSend('UX', 'openFormFeedback', '');
    });

    $('a').click(function(e){
        var link = $(this).attr('href');
        if(link !== undefined && link.indexOf('http') != -1){
            e.preventDefault();
            window.open(link, '_blank');
        }

        if(link !== undefined && link.indexOf('tel') != -1){
            dataLayerSend('UX', 'clickCallButton', '');
        }
    })
    
    $(document).on("click", ".js-callback-submit_v2", function(e) {
        var buttonCallbackSubmit = $(this);
        var form = $(this).parents('form');
        var lengthPhone = $(form).find('[type="tel"]').inputmask('unmaskedvalue').length;
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

            var url = $(form).attr('action');
            var form = $(form).serializeArray();
            var data = {};
            var ext = {
                "ajax_send": "Y"
            }

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

                $(form).find('[type="tel"]').inputmask({
                    mask: "+7 (999) 999-99-99",
                    onBeforeMask: function (value, opts) {
                        if (value[0] == '8') {
                            var processedValue = value.replace('8', "");
                        }

                        return processedValue;
                    }
                });

                initFormFields($('.form-standart.form-standart__popup-call'));
            });
        }
    });

    // функция для кнопки "заявка на звонок"
    function jsPopupCallV2(){
        dataLayerSend('UX', 'openFormCallback', '');
        console.log('call_v2-click');
        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit-v2/ajax/call.php",
        }).done(function(data) {
            $.fancybox.close();

            $('body').append(data);

            var isMacLike = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;
            $('select.custom--select').select2({
                minimumResultsForSearch: Infinity
            });
            if (!isMacLike) {
                // $('select.input--select').styler({
                //     selectSmartPositioning: false
                // });
            }
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

            
            initFormFields($('.form-standart.form-standart__popup-call'));

            $(".popup--call").fadeIn(300);
            $('.input--checkbox').styler();
            $('.popup--choose').fadeOut();
        });
    }

    // функция для кнопки "отправить сообщение"
    function jsPopupMessageV2(){
        dataLayerSend('UX', 'openFormWriteUs', '');

        $.ajax({
            method: "POST",
            url: "/local/templates/spiritfit-v2/ajax/call-message.php",
        }).done(function(data) {
            $.fancybox.close();
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

            initFormFields($('.form-standart.form-standart__popup-message'));

            $(".popup--message").fadeIn(300);
            $('.input--checkbox').styler();
            $('.popup--choose').fadeOut();
        });
    }
    // из за jquery.ui.touch-punch событие click не работает для тачпада
    // приходится разделять на 2 события.
    $(document).on('click','.js-popup-call_v2',jsPopupCallV2);
    $(document).on('touchstart','.js-popup-call_v2',jsPopupCallV2);
    $(document).on('click','.js-popup-message_v2',jsPopupMessageV2);
    $(document).on('touchstart','.js-popup-message_v2',jsPopupMessageV2);

    // animate scroll
    var anchors = ['a[href="#js-pjax-clubs"]'];
    for (var anchor of anchors) {
        $(anchor).click(function (e) {
            e.preventDefault()
            
            var blockID = $(this).attr('href');
            var destination = $(blockID).offset().top - 106;
            $("html,body").animate({
                scrollTop: destination
            }, 400);
        })
    }


    $('.form-standart:not(.is-ready)').each(function () {
        initFormFields($(this));
    });

    $(document).on("click touchend", ".popup__close, .popup__bg", function () {
        if($(this).parents('.popup__window--modal-form').length > 0){
            if($(this).parents('.popup-info').length > 0){
                $(this).parents('.popup__window--modal-form').removeClass('is-info');
            }
        }
    })

    $(window).resize(function(){
        setScrollFormModal();
    })

    // after loading form
    $(document).on('pjax:complete', function(xhr, status, data, options) {
        $('.form-standart:not(.is-ready)').each(function () {
            initFormFields($(this));
        });
		
        if(window.oldTitleDocument !== undefined){
            document.title = window.oldTitleDocument;
        }
        
        var blockPopupScroll = $('.popup__window--modal-form');
        if(blockPopupScroll.find('.popup').length > 0){
            if(blockPopupScroll.find('.popup').is(':visible')){
                blockPopupScroll.addClass('is-info');
            }else{
                blockPopupScroll.removeClass('is-info');
            }
        }else{
            blockPopupScroll.removeClass('is-info');
        }

        $(document).on("click touchend", ".popup__close, .popup__bg", function () {
            if($(this).parents('.popup__window--modal-form').length > 0) {
                if($(this).parents('.popup-info').length > 0 || $(this).parents('.subscription__aside-form-trial_v2').length){
                    $(this).parents('.popup__window--modal-form').removeClass('is-info');
                }
            }
        })

        setScrollFormModal();
    })

    // popup form
    $('.js-form-abonement').click(function(e){
        e.preventDefault();
        
		//console.log('jsformabon');
		
        var formPopup = $('#modalForm');
        var link = $(this);
        var type = ''+link.data('type');
        var clubNumber = link.data('clubnumber');
        var abonementID = link.data('abonementid');
        var abonementcode = link.data('abonementcode');
        var code1c = link.data('code1c');
        var clubSelected = '';
        
        if(clubNumber == '0') clubNumber = '00';
        
        if(type == 'trial'){
            formPopup = $('#modalFormTrial');
            dataLayerSend('UX', 'openMembershipRegPage', '-/Пробная тренировка');
        }

        $(formPopup).find('[name="abonement_code"]').val(abonementcode);
        $(formPopup).find('[name="sub_id"]').val(code1c);

        if(type == 'trial'){
            $(formPopup).find('[name="old_price"]').val(1);
            $(formPopup).find('.actual-price').val(1);
            $(formPopup).find('.subscription__bottom').hide();
            $(formPopup).find('.subscription__promo').hide();
        }else{
            // club
            $(formPopup).find('option').each(function(){
                if($(this).val() == clubNumber){
                    $(this).attr("selected", "selected");
                    clubSelected = $(this).val();
                }
            })
            
            $(formPopup).find('select').val(clubSelected);
            $(formPopup).find('select').trigger('change');

            // price
            var priceActual = '';
            var priceOld = '';
            
            if(window.abonement[abonementID].SALE.length > 0){
                priceActual = window.abonement[abonementID].SALE;
                priceOld = window.abonement[abonementID].BASE_PRICE.PRICE;
            }else{
                priceActual = window.abonement[abonementID].BASE_PRICE.PRICE;
            }

            var priceActualString = '<span>' + priceActual + '</span> <i class="rub">₽</i>';
            var priceOldString = '<span>' + priceOld + '</span> <i class="rub">₽</i>';
            var priceDescription = window.abonement[abonementID].DESCRIPTION_SALE;

            $(formPopup).find('.subscription__total-actual').html(priceActualString);

            if(priceOld > 0){
                $(formPopup).find('[name="old_price"]').val(priceOld);
                $(formPopup).find('.actual-price').val(priceActual);
                $(formPopup).find('.subscription__total-value-old').html(priceOldString);
            }else{
                $(formPopup).find('.actual-price').val(priceActual);
            }

            if(priceDescription.length > 0){
                $(formPopup).find('.subscription__total-subtext').text(priceDescription);
            }

            $(formPopup).find('.subscription__bottom').show();
            $(formPopup).find('.subscription__promo').show();
        }
        
		console.log( formPopup );
        formPopup.show();
        formPopup.find('.form-standart').show();
        setScrollFormModal();
    })

    // resend sms code
    $(document).on("click", ".subscription__code_v2", function(e) {
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

    // check promocode
    $(document).on("click", ".subscription__promo-btn_v2", function(e, isFormSubmit) {
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
                    var sub_id = $(form).find('[name=sub_id]').val();
                    var month = '';
					if( (sub_id in data.result) ) {
						month = data.result[sub_id];
					}
					/*var month = '';
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
                    }*/
                    var oldPrice = $(form).find('.subscription__total-value-old span').text();
                    var newPrice = '';
                    var twoMonth = '';
                    
                    if(month){
                        month.forEach(function(item, index, array) {
                            if($(form).find('[name=form_text_5]').val() == item.clubid || $(form).find('[name=form_text_15]').val() == item.clubid){
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
                        $(form).find("[name=two_month]").val(twoMonth);
                        $(form).find("[data-mouth=1] b").text(twoMonth+' руб.');
                    }

                    if(newPrice){
                        $(form).find("[name=form_hidden_10]").val(newPrice);
                        $(form).find(".actual-price").val(newPrice);
                        $(form).find("[name=old_price]").val(oldPrice);

                        $(form).find(".subscription__total-value").html(`
                            <div class='subscription__total-value-old'><span>${oldPrice}</span> <i class='rub'>₽</i></div>
                            <span class='subscription__total-actual'><span>${newPrice}<span> <i class='rub'>₽</i></span>
                        `);

                        if(!$(form).find('.promocode_info').length){
                            $(form).find('.subscription__promo').append('<div class="promocode_info">Ваш промокод применен</div>');
                            if (isFormSubmit) {
                                showLegalInformation_v2(form);
                            }
                        }else{
                            $(form).find('.promocode_info').text('Ваш промокод применен');
                            if (isFormSubmit) {
                                showLegalInformation_v2(form);
                            }
                        }
                    }else{
                        if(!$(form).find('.promocode_info').length){
                            $(form).find('.subscription__promo').append('<div class="promocode_info">Введен неверный промокод</div>');
                        }else{
                            $(form).find('.promocode_info').text('Введен неверный промокод');
                        }
                    }


                }else{
                    if(!$(form).find('.promocode_info').length){
                        $(form).find('.subscription__promo').append('<div class="promocode_info">Введен неверный промокод</div>');
                    }else{
                        $(form).find('.promocode_info').text('Введен неверный промокод');
                    }
                }
            });
        }
    });

    // check sms code
    $(document).on("click", ".js-check-code_v2", function(e) {
        e.preventDefault();
        var code = "";
        var form = $(this).parents('form');

        $(this).closest('form').find(".input--num").each(function() {
            code += $(this).val();
        });

        if(code != "") {
            reachGo_v2($(this).closest('form'));
            sendForm_v2({
                "ajax_send": "Y",
                "last_step":"Y",
                "club": $(form).find('select.js-pjax-select').val(),
                "ajax_menu": false,
                "num": code,
                "mode": "check_sms",
                "modal_form": true,
                "trial": $(form).find('[name="trial"]').val(),
            });
        }
    });

    $(document).on("click", ".js-check-code-training_v2", function(e) {
        e.preventDefault();
        var code = "";
        var form = $(this).parents('form');

        $(this).closest('form').find(".input--num").each(function() {
            code += $(this).val();
        });

        if(code != "") {
            reachGo_v2($(this).closest('form'));
            sendFormTraining_v2({
                "ajax_send": "Y",
                "last_step":"Y",
                "club": $(form).find('[name=form_text_15]').val(),
                "ajax_menu": false,
                "num": code,
                "mode": "check_sms",
            });
        }
    });

    // submit form
    $(document).on("submit", ".subscription__aside-form_v2", function(e) {
        e.preventDefault();
        var form = $(this).parents('.form-standart');

        if ($(form).find('[name=promo]').val()) {
            $(form).find(".subscription__promo-btn_v2").trigger("click", ["is-form-submit"]);
        } else {
            showLegalInformation_v2(form);
        }
    });

    $(document).on("submit", ".subscription__aside-form-trial_v2", function(e) {
        e.preventDefault();
        var form = $(this).parents('.form-standart');

        if ($(form).find('[name=promo]').val()) {
            $(form).find(".subscription__promo-btn_v2").trigger("click", ["is-form-submit"]);
        } else {
            showLegalInformation_v2(form);
        }
    });

    $(document).on("submit", ".training__aside-form_v2", function(e) {
        e.preventDefault();
        sendFormTraining_v2({
            "ajax_send": "Y",
            "club": $('.training__aside-form_v2 .club').val(),
            "ajax_menu": false
        });
    });

    // popup oferta
    $(document).on('click', '.subscription__total-btn--legal-information_v2', function() {
        var form = $(this).parents('.form-standart');
        

        sendForm_v2({
            "ajax_send": "Y",
            "club": $(form).find('select').val(),
            "ajax_menu": false,
            "modal_form": true,
            "abonement_code": $(form).find('[name="abonement_code"]').val(),
            "trial": $(form).find('[name="trial"]').val(),
        });
    });
    
    $(document).on('click', '.subscription__total-btn--legal-training_v2', function() {
        sendFormTraining_v2({
            "ajax_send": "Y",
            "club": $('.training__aside-form_v2 .club').val(),
            "ajax_menu": false
        });
    });

    // payment
    $(document).on("click", ".js-btn-pay_v2", function() {
        var form = $(this).parents('.form-standart');

        reachGo_v2($(this).closest('form'));
        reachGoOnline_v2();
        sendForm_v2({
            "ajax_send": "Y",
            "club": $(form).find('select').val(),
            "ajax_menu": false,
            "modal_form": true,
            "trial": $(form).find('[name="trial"]').val(),
        });
    });

    // автокомплит для поиска.
    let availableClubs = [
        "Метро Юго-Западная",
        "Метро Крымская",
        "Метро Чертановская",
        "Метро Марьина Роща",
        "Проезд Дежнева",
        "Метро Беляево",
        "Рязанский проспект",
        "Метро Крылатское",
        "Город Одинцово",
        "Метро Селигерская",
        "Сетевой абонемент",
        "Башня Федерация",
        "Метро Щукинская",
        "Метро Строгино",
        "Метро Ясенево",
        "Метро Семеновская",
        "Каширское шоссе",
        "Метро Войковская",
        "Метро Новокосино",
        "Метро Раменки"
    ];
	if( typeof clubsList !== "undefined" ) {
		availableClubs = clubsList;
	}
    let position = window.innerWidth < 1025 ? {my:"left top",at:"left bottom",collision:"none"}:{my:"left bottom",at:"left top",collision:"none"};
    let appendElem = window.innerWidth < 1025 ? ".b-header__mobile-clubs .b-club-search__input-wrap":".b-top-menu__holder .b-club-search__input-wrap";
    
	let searchInput = false;
	if( window.innerWidth < 1025 ) {
		if( $(".b-club-search__input").length > 0 ) {
			$(".b-top-menu__holder .b-club-search__input").autocomplete({
        		appendTo: ".b-top-menu__holder .b-club-search__input-wrap",
        		source: availableClubs,
        		position: position,
				select: function( event, ui ) {
					var clubName = $(ui)[0].item.value;
					$( event.target ).val(clubName);
					$( event.target ).parents("form").eq(0).submit();
				}
    		});
			$(".b-header__mobile-clubs .b-club-search__input").autocomplete({
        		appendTo: ".b-header__mobile-clubs .b-club-search__input-wrap",
        		source: availableClubs,
        		position: position,
				select: function( event, ui ) {
					var clubName = $(ui)[0].item.value;
					$( event.target ).val(clubName);
					$( event.target ).parents("form").eq(0).submit();
				}
    		});
		}
	} else {
		let searchInput = $(".b-club-search__input");
		searchInput.autocomplete({
        	appendTo: appendElem,
        	source: availableClubs,
        	position: position,
			select: function( event, ui ) {
				var clubName = $(ui)[0].item.value;
				$( event.target ).val(clubName);
				$( event.target ).parents("form").eq(0).submit();
			}
    	});
		
	}
    
    // cursorcolor:"#FF7628", 

    // кастомный скролл на странице клубов для яблочников
    // функия для отмены действия по умолчанию
    function touchEventStop(e) {
        // console.log('stop');
        e.preventDefault();
    }

    if(!!navigator.platform.match(/(Mac|iPhone|iPod|iPad|Pike)/i)) {
        $('.b-map__switch').on("select2:open", function(e){
            // боремся с косячной работой тач событий
            let result =  document.querySelector(".select2-results__options")
            result.addEventListener('touchmove', touchEventStop, { passive: false });
            // подключаем кастомный скролл
            $('.b-map__switch-holder .select2-results__options').niceScroll(
                {
                    cursorcolor:"#FF7628", 
                    cursorborder: 0, 
                    background: "#222",
                    cursorborderradius: 0,
                    autohidemode: false
                });

            // вешаем функцию на событие закрытия селекта для уничтожения кастомного скролла
            $('.b-map__switch').one("select2:closing", ()=>{
                // убираем кастомный скролл
                $('.b-map__switch-holder .select2-results__options').getNiceScroll().remove();
                // открепляем событие с отменой дефолтного поведения.
                result.removeEventListener('touchstart', touchEventStop, { passive: false });
                // console.log('remove');
            });
        });
        
    }

    // $('.subscription__aside-form:not(.is-ready)').each(function () {
    //     initFormFields($(this));
    // });
})
