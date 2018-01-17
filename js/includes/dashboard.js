$(document).ready(function () {

	$('#close-tasks, #close-dates').hide();

	/** ******************************
	 * Collapse Panels
	 * [data-perform="panel-collapse"]
	 ****************************** **/
	(function($, window, document){
		var panelSelector = '[data-perform="panel-collapse"]';

		$(panelSelector).each(function() {
			var $this = $(this),
			parent = $this.closest('.panel'),
			wrapper = parent.find('.panel-wrapper'),
			collapseOpts = {toggle: false};

			if( ! wrapper.length) {
				wrapper =
				parent.children('.panel-heading').nextAll()
				.wrapAll('<div/>')
				.parent()
				.addClass('panel-wrapper');
				collapseOpts = {};
			}
			wrapper
			.collapse(collapseOpts)
			.on('hide.bs.collapse', function() {
				$this.children('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
			})
			.on('show.bs.collapse', function() {
				$this.children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
			});
		});
		$(document).on('click', panelSelector, function (e) {
			e.preventDefault();
			var parent = $(this).closest('.panel');
			var wrapper = parent.find('.panel-wrapper');
			wrapper.collapse('toggle');
		});
	}(jQuery, window, document));
    
    /** ******************************
	 * Tasks Toggles
	 ****************************** **/
	$('#open-tasks').click(function(e) {
		e.preventDefault();
		$('.task-toggle').addClass('in');
		$('.panel-wrapper').css({ 'height': "auto" });
		$('.task-toggle i').removeClass('fa-chevron-right');
        $('.task-toggle i').addClass('fa-chevron-down');
		$('#open-tasks').hide();
		$('#close-tasks').show();
	});
	$('#close-tasks').click(function(e) {
		e.preventDefault();
		$('.task-toggle').removeClass('in');
		$('.panel-wrapper').css({ 'height': "0" });
		$('.task-toggle i').removeClass('fa-chevron-down');
        $('.task-toggle i').addClass('fa-chevron-right');
		$('#close-tasks').hide();
		$('#open-tasks').show();
	});
	
	/** ******************************
	 * Toggle All Dates
	 ****************************** **/
	$('#open-dates').click(function(e) {
		e.preventDefault();
		$('.date-toggle').addClass('in');
		$('.panel-wrapper').css({ 'height': "auto" });
		$('.date-toggle i').removeClass('fa-chevron-right');
        $('.date-toggle i').addClass('fa-chevron-down');
		$('#open-dates').hide();
		$('#close-dates').show();
	});
	$('#close-dates').click(function(e) {
		e.preventDefault();
		$('.date-toggle').removeClass('in');
		$('.panel-wrapper').css({ 'height': "0" });
		$('.date-toggle i').removeClass('fa-chevron-down');
        $('.date-toggle i').addClass('fa-chevron-right');
		$('#close-dates').hide();
		$('#open-dates').show();
	});

});