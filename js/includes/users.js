$(document).ready(function(){

	$('#userList').dataTable({
		"columnDefs": [{
			"orderable": false, "targets": 6
		}],
		"order": [ 2, 'asc' ],
		"pageLength": 25
	});

});