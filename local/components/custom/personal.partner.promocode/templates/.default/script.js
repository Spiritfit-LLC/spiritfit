var copyPromocode=function(promocode){
    var $tmp = $("<textarea>");
    $("body").append($tmp);
    $tmp.val(promocode).select();
    document.execCommand("copy");
    $tmp.remove();
    alert("Промокод скопирован!");
}