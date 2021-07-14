/** Align items with same data-height-group on the same row by giving them an equal height **/

(function($) {

  var sameheighttimeout;
  var RESIZE_TIMEOUT = 300;
  var EVENT_REPEAT_TIMEOUT = 500;
  var eventsInited = false;

  function init() {
    update();
    initEvents();
  }

  function initEvents() {
    if(eventsInited){
      return;
    }
    $(window).on('resize', onResizeHandler);
    $(document).on('sameheightupdaterequired', onUpdateHandler);
    eventsInited = true;
  }

  function onResizeHandler(){
    clearTimeout(sameheighttimeout);
    sameheighttimeout = setTimeout(update, RESIZE_TIMEOUT);
  }

  function onUpdateHandler(){
    update();
    // Repeat in case of partial load of content
    clearTimeout(sameheighttimeout);
    sameheighttimeout = setTimeout(update, EVENT_REPEAT_TIMEOUT);
  }

  function disableEvents() {
    clearTimeout(sameheighttimeout);
    $(window).off('resize', onResizeHandler);
    $(document).off('sameheightupdaterequired', onUpdateHandler);
    eventsInited = false;
  }

  function removeHeights() {
    $('[data-height-group]').each(function(){
      $(this).removeAttr('style');
    });
  }

  function update() {
    var $elements = $('[data-height-group]');
    if($elements.length == 0){
      return;
    }

    // Sorting by groups
    var groups = {};
    $elements.each(function(key, element){
      var group = $(element).attr('data-height-group');
      if(!groups.hasOwnProperty(group)){
        groups[group] = [];
      }
      groups[group].push(element);
    });
    
    // For each groups sorting by rows
    $.each(groups, function(key, elements){
      var rows = {};
      $.each(elements, function(k2, element){
        var top = $(element).offset().top;
        if(!rows.hasOwnProperty(top)){
          rows[top] = [];
        }
        rows[top].push(element);
      });

      // For each row getting max height and seting it to all elements
      $.each(rows, function(k2, elements){
        var maxHeight = 0;
        $.each(elements, function(k3, element){
          // Remove previously set height
          $(element).removeAttr('style');
          if($(element).height() > maxHeight){
            maxHeight = $(element).height();
          }
        });
        // Setting heights
        $.each(elements, function(k3, element){
          $(element).height(maxHeight);
        });
      });
    });
  }
 
  $(init);
  $(window).on('load', init);
  
  $.extend($, {sameheight: function(action, options) {
    if(action == 'update'){
      update();
    } else if(action == 'disable'){
      disableEvents();
      removeHeights();
    } else if(action == 'enable'){
      init();
    }
  }});
  
}(jQuery));