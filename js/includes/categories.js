$(document).ready(function(){

	$('#taskList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 5
		}],
		"order": [ 3, 'asc' ],
		"pageLength": 25
	});

});