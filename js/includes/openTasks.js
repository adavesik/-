$(document).ready(function(){

	$('#taskList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 6
		}],
		"order": [ 5, 'asc' ],
		"pageLength": 25
	});

});