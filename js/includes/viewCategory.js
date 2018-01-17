$(document).ready(function(){

	$('#taskList').dataTable({
		"columns": [
			{"visible": false},
			null,
			null,
			null,
			null,
			null,
			null,
			{"orderable": false}
		],
		"order": [[ 0, 'asc' ], [ 5, 'asc' ]],
		"pageLength": 25
	});

});