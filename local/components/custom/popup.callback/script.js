var getSession = function() {
    var session = [];

    $.ajax({
        async: false,
        method: "POST",
        data: {
            "ajax": "Y",
            "action": "getSession"
        }
    }).done(function(data) {
        session = JSON.parse(data);
    });

    return session;
}

var setSession = function() {
    $.ajax({
        async: false,
        method: "POST",
        data: {
            "ajax": "Y",
            "action": "setShowPopup"
        }
    }).done(function(data) {

    });
}

var showPopup = function() {
    $.ajax({
        method: "POST",
        url: "/local/templates/spiritfit/ajax/popup.callback.php",
    }).done(function(data) {
        

        $('body').append(data);

        var isMacLike = navigator.platform.match(/(iPhone|iPod|iPad)/i) ? true : false;
    
        if (!isMacLike) {
            $('select.input--select').styler({
                selectSmartPositioning: false
            });
        }
        $.when($(".popup--call").fadeIn(300)).then(function() {
            if (!$(".popup--appearing .input--tel").inputmask("hasMaskedValue")) {
                $(".popup--appearing .input--tel").mask("+7 (999) 999-99-99");
            }
        });
        $('.input--checkbox').styler(); 
    });
}

var popupSetTimeout = function(popupTime) {
    setTimeout(function() {
        showPopup();
        setSession();
    }, popupTime);
}

var popupModal = function() {
    var data = getSession();

    if (data.PM_SHOW != "Y" && data.PM_ACTIVE) {
        var popupTime = (data.PM_CURRENT_TIME + data.PM_TIME) - data.PM_LAST_TIME;

        if (popupTime > 0) {
            popupSetTimeout(popupTime);
        } else {
            showPopup();
        }
    }
}

popupModal();