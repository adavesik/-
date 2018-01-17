$(function() {

	// New Event
	$('#newstartDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#neweventTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	
	$('#newendDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#newendTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	
	// Edit Event
	$('#editstartDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editeventTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	
	$('#editendDate').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editendTime').datetimepicker({
		format: 'hh:ii',
		startDate: '2014-01-01',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		forceParse: 0,
		minuteStep: 15
	});
	
	// Color Picker
    $("[name='colorPick']").change(function () {
        if ($('.236b9b').is(':checked')) {
            $('.radPrimary i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radPrimary i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
		if ($('.1e5d86').is(':checked')) {
            $('.radPrimary2 i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radPrimary2 i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
        if ($('.4da0d7').is(':checked')) {
            $('.radInfo i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radInfo i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
		if ($('.3895d2').is(':checked')) {
            $('.radInfo2 i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radInf2o i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
        if ($('.77c123').is(':checked')) {
            $('.radSuccess i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radSuccess i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
		if ($('.6aab1f').is(':checked')) {
            $('.radSuccess2 i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radSuccess2 i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
        if ($('.e5ad12').is(':checked')) {
            $('.radWarning i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radWarning i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
		if ($('.cd9b10').is(':checked')) {
            $('.radWarning2 i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radWarning2 i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
        if ($('.d64e18').is(':checked')) {
            $('.radDanger i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radDanger i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
		if ($('.a83d13').is(':checked')) {
            $('.radDanger2 i.fa').removeClass('fa-square-o').addClass('fa-check');
        } else {
            $('.radDanger2 i.fa').removeClass('fa-check').addClass('fa-square-o');
        }
    });

});