/*
 * Copyright (c) 2010 M.Samekawa
 */
 
/*
 * A number picker for jQuery
 * @name     numberPicker
 * @version  0.1 
 * @author   M.Samekawa
 * @example  $("#mytime").numberPicker();
 * @example  $("#mytime").numberPicker({step:30, startNum:0, endNum:60}); 
 */

(function($){
  
  $.fn.numberPicker = function(options) {
    // Build main options before element iteration
	  var settings = $.extend({}, $.fn.numberPicker.defaults, options);  
    
    return this.each(function() {
      $.numberPicker(this, settings);
    });
  };
  
  $.numberPicker = function (elm, settings) {
    var elm = $(elm)[0];  
    return elm.numberPicker || (elm.numberPicker = new jQuery._numberPicker(elm, settings));
  };
  
  $._numberPicker = function(elm, settings) {
    
    var tpOver = false;
    var startNum = settings.startNum;
    var endNum = settings.endNum;
    var step = settings.step;
    
    $(elm).attr('autocomplete', 'OFF'); // Disable browser autocomplete

	if (!(step > 0))
		step = 1;

    var $tpDiv = $('<div class="number-picker"></div>');
    var $tpList = $('<ul></ul>');
    
    // Build the list.
    $tpList.append("<li></li>");
    for(var i = startNum; i <= endNum; i = i + step) {
      num = formatNumber(i);
      $tpList.append("<li>" + num + "</li>");
    }
    $tpDiv.append($tpList);
    // Store element offset.
    var elmOffset = $(elm).offset();
    // Append the timPicker to the body and position it.
    $tpDiv.appendTo('body').css({'top':elmOffset.top, 'left':elmOffset.left}).hide();
    
    $("li", $tpList).unbind().mouseover(function() {
      $("li.selected", $tpDiv).removeClass("selected");  // TODO: only needs to run once.
      $(this).addClass("selected");
    }).mousedown(function() {
       tpOver = true;
    }).click(function() {
      setTimeVal(elm, this, $tpDiv, settings);
      tpOver = false;
    });
    
    // Store ananymous function in variable since it's used twice.
    var showPicker = function() {
      $tpDiv.show(); // Show picker.
      $tpDiv.mouseover(function() { // Have to use mouseover instead of mousedown because of Opera
        tpOver = true;
      }).mouseout(function() {
        tpOver = false;
      }).blur(function() {
        $tpDiv.hide();
      });
      $("li", $tpDiv).removeClass("selected");
      
      var time = this.value ? this.value : -1;

      var $matchedTime = $("li:contains(" + time + ")", $tpDiv);
      
      if ($matchedTime.length) {
        $matchedTime.addClass("selected");
        // Scroll to matched time.
        $tpDiv[0].scrollTop = $matchedTime[0].offsetTop;
      }
    };
    
    $(elm).unbind().focus(showPicker).click(showPicker)
    // Hide numberPicker on blur
    .blur(function() {
      if (!tpOver && $tpDiv[0].parentNode) { // Don't remove when numberPicker is clicked or when already removed
        $tpDiv.hide();
      }
    })
    
    // Key support
    .keypress(function(e) {
      switch (e.keyCode) {
        case 38: // Up arrow.
        case 63232: // Safari up arrow.
          var $selected = $("li.selected", $tpList);
          var prev = $selected.prev().addClass("selected")[0];
          if (prev) {
            $selected.removeClass("selected");
            $tpDiv[0].scrollTop = prev.offsetTop;
          }
          return false;
          break;
        case 40: // Down arrow.
        case 63233: // Safari down arrow.
          var $selected = $("li.selected", $tpList);
          var next = $selected.length ? $selected.next().addClass("selected")[0] : $("li:first").addClass("selected")[0];
          if (next) {
            $selected.removeClass("selected");
            $tpDiv[0].scrollTop = next.offsetTop;
          }
          return false;
          break;
        case 13: // Enter
          if (!$tpDiv.is(":hidden")) {
            var sel = $("li.selected", $tpList)[0];
            setTimeVal(elm, sel, $tpDiv, settings);
            return false;
          }
          break;
      }
    });
  }; // End fn;   
  
  // Plugin defaults.
  $.fn.numberPicker.defaults = {
    step: 1,
    startNum: 0,
    endNum: 60
  };
  
  // Private functions.
  
  function setTimeVal(elm, sel, $tpDiv, settings) {
    // Update input field
    elm.value = $(sel).text();
    // Trigger element's change events.
    $(elm).change();
    // Keep focus for all but IE (which doesn't like it)
    if (!$.browser.msie) {
      elm.focus();
    }
    // Hide picker
    $tpDiv.hide();
  }
  
  function formatNumber(value) {
    return (parseInt(value) < 10 ? '0' : '') + value;
  }
})(jQuery);
