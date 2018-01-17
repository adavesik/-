$(document).ready(function(){

	// Set the Category Select
	var taskCat = $("#taskCat").val();
	$("#catId").find("option:contains('"+taskCat+"')").each(function() {
		if ($(this).text() == taskCat) {
			$(this).attr("selected","selected");
		}
	});

	/** ******************************
    * Date Pickers
    ****************************** **/
    $('#editTaskStart').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editTaskDue').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});
	$('#editDateClosed').datetimepicker({
		format: 'yyyy-mm-dd',
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		forceParse: 0
	});

});