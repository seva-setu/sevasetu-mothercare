/*
 * Requires jquery ui
 */
 
 
(function($){
	var $calroot;

    function selectCurrentWeek() {
        window.setTimeout(function () {
            var t = $calroot.find('.ui-datepicker-current-day a');//.addClass('ui-state-active');
			t= t.closest('tr');
			t.find('td>a').addClass('ui-state-active');//.parent().addClass('ui-state-active');
        }, 1);
		
    }
	function onSelect(dateText, inst) { 
        var date = $(this).datepicker('getDate');
        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
        var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
		$calroot.trigger('weekselected',{
			start:startDate,
			end:endDate,
			weekOf:startDate
		});
        selectCurrentWeek();
    }
	var reqOpt = {
		onSelect:onSelect,
		showOtherMonths: true,
        selectOtherMonths: true
	};
    $.fn.weekpicker = function(options){
		var $this = this;
		$calroot = $this;
		
		$this.datepicker(reqOpt);
		//events
		$dprow = $this.find('.ui-datepicker');
		
		$dprow.on('mousemove','tr', function() { 
			$(this).find('td a').addClass('ui-state-hover'); 
		});
		$dprow.on('mouseleave','tr', function() { 
			$(this).find('td a').removeClass('ui-state-hover'); 
		});
	};
})(jQuery);