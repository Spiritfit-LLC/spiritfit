$(document).ready(function(){
    $('a[href="#delete-my-personal"]').click(function(e){
        e.preventDefault();

        var answer=confirm("Вы подтверждаете свой запрос на удаление аккаунта и персональной информации?");
        if (answer){
            BX.ajax.runComponentAction(componentName, 'deletePersonal', {
                mode:'class',
                method:'POST',
            }).then(function(response){
                if (response.data['reload']===true){
                    setTimeout(function(){
                        if (response.data.section!==undefined){
                            window.location.search='?SECTION='+response.data.section;
                        }
                        else{
                            window.location = window.location.pathname;
                        }
                    }, 500);
                }
            }, function(response){

            });
        }
    });



    var $preview_item=$('.personal-media_preview__item.exist');
    if ($preview_item.length>0){
        var preview_width=$(".personal-profile__center-block").width()-40;
        $preview_item.height(preview_width);
    }

});

// var getAcrobatInfo = function() {
//
//     var getBrowserName = function() {
//         return this.name = this.name || function() {
//             var userAgent = navigator ? navigator.userAgent.toLowerCase() : "other";
//
//             if(userAgent.indexOf("chrome") > -1){
//                 return "chrome";
//             } else if(userAgent.indexOf("safari") > -1){
//                 return "safari";
//             } else if(userAgent.indexOf("msie") > -1 || navigator.appVersion.indexOf('Trident/') > 0){
//                 return "ie";
//             } else if(userAgent.indexOf("firefox") > -1){
//                 return "firefox";
//             } else {
//                 //return "ie";
//                 return userAgent;
//             }
//         }();
//     };
//
//     var getActiveXObject = function(name) {
//         try { return new ActiveXObject(name); } catch(e) {}
//     };
//
//     var getNavigatorPlugin = function(name) {
//         for(key in navigator.plugins) {
//             var plugin = navigator.plugins[key];
//             if(plugin.name == name) return plugin;
//         }
//     };
//
//     var getPDFPlugin = function() {
//         return this.plugin = this.plugin || function() {
//             if(getBrowserName() == 'ie') {
//                 //
//                 // load the activeX control
//                 // AcroPDF.PDF is used by version 7 and later
//                 // PDF.PdfCtrl is used by version 6 and earlier
//                 return getActiveXObject('AcroPDF.PDF') || getActiveXObject('PDF.PdfCtrl');
//             } else {
//                 return getNavigatorPlugin('Adobe Acrobat') || getNavigatorPlugin('Chrome PDF Viewer') || getNavigatorPlugin('WebKit built-in PDF');
//             }
//         }();
//     };
//
//     var isAcrobatInstalled = function() {
//         return !!getPDFPlugin();
//     };
//
//     var getAcrobatVersion = function() {
//         try {
//             var plugin = getPDFPlugin();
//
//             if(getBrowserName() == 'ie') {
//                 var versions = plugin.GetVersions().split(',');
//                 var latest = versions[0].split('=');
//                 return parseFloat(latest[1]);
//             }
//
//             if(plugin.version) return parseInt(plugin.version);
//             return plugin.name
//         }
//         catch(e) {
//             return null;
//         }
//     }
//
//     //
//     // The returned object
//     //
//     return {
//         browser: getBrowserName(),
//         acrobat: isAcrobatInstalled() ? 'installed' : false,
//         acrobatVersion: getAcrobatVersion()
//     };
// };


// var pdfViewerInfo=getAcrobatInfo();

var personal_file_input=function(files, preview_block_id){
    const file = files[0];
    if (!file) return;



    var file_type=file.type;
    var $preview=$(`#${preview_block_id}_media-preview`);
    var $dropzone=$(`#${preview_block_id}_dropzone`);

    $preview.find(".field-error").remove();

    if (!file_type.includes("image")){
        $('<span className="field-error">Не удается обработать документ. Загрузите изображение.</span>').appendTo($preview);
        return;
    }

    // if (file_type.includes("image")){
    //     $(`<div class="personal-media_preview__item image" style='background-image: url("${URL.createObjectURL(file)}")' id="${preview_block_id}_preview_item">` +
    //         '<div class="closer-small"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="96px" height="96px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg></div>'+
    //         '</div>').appendTo($preview);
    //
    // }
    // else if (file_type.includes("pdf")){
    //     $(`<object data="${URL.createObjectURL(file)}" type="${file_type}" id="${preview_block_id}_preview_item"><span class="field-error">Не удается обработать PDF документ. Загрузите изображение.</span></object>`).appendTo($preview);
    // }

    $(`<div class="personal-media_preview__item image" style='background-image: url("${URL.createObjectURL(file)}")' id="${preview_block_id}_preview_item">` +
        `<div class="closer-small" onclick="reset_file_input('${preview_block_id}')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="96px" height="96px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg></div>`+
        '</div>').appendTo($preview);


    // if (file_type.includes("pdf") && pdfViewerInfo.acrobat===false){
    //     return;
    // }
    $dropzone.hide();


    $(`#${preview_block_id}_file_exist`).val(1);

    var $preview_item=$(`#${preview_block_id}_preview_item`);
    $preview_item.width('calc(100% - 0px)');
    var preview_width=$preview_item.width();
    $preview_item.height(preview_width);
}

var reset_file_input=function(id){
    $(`#${id}_preview_item`).remove()
    $(`#${id}`).val('');
    $(`#${id}_dropzone`).show();
    $(`#${id}_file_exist`).val(0);
}