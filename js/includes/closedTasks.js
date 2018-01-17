$(document).ready(function(){

	$('#taskList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 5
		}],
		"order": [ 4, 'desc' ],
		"pageLength": 25
	});

});