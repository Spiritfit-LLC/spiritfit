"use strict";function startPreventBodyScroll(){$("body").addClass("is-fixed"),$(window).width()<1025&&$("body").css({position:"fixed",width:$("body").width()+"px"})}function endPreventBodyScroll(){$("body").removeClass("is-fixed"),$(window).width()<1025&&$("body").css({position:"static",width:"auto"})}$(function(){$("body").addClass("is-body-loaded")}),$(function(){$("select").each(function(){var t=$(this),d=t.attr("data-placeholder");t.select2({width:"100%",minimumResultsForSearch:100,placeholder:d,dropdownParent:t.parent(),language:{noResults:function(){return"Ничего не найдено"}}})})});
//# sourceMappingURL=../sourcemaps/global.js.map