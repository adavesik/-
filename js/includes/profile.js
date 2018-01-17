$(document).ready(function(){

	$('#hide2').hide();

	// Show the Password field as plain text
	$('#show2').click(function(e) {
		e.preventDefault();
		$('#passwordNew').prop('type','text');
		$('#passwordRepeat').prop('type','text');
		$('#show2').hide();
		$('#hide2').show();
	});
	// Show the Password field as asterisks
	$('#hide2').click(function(e) {
		e.preventDefault();
		$('#passwordNew').prop('type','password');
		$('#passwordRepeat').prop('type','password');
		$('#hide2').hide();
		$('#show2').show();
	});
	$('#clear2').click(function(e) {
		e.preventDefault();
		$('#currentPass, #passwordNew, #passwordRepeat').val('');
	});

	// Generate Random Password
	$('#generatePass').click(function (e) {
		e.preventDefault();

		// You can change the password length by changing the
		// integer to the length you want in generatePassword(8).
		var pwd = generatePassword(8);

		// Populates the fields with the new generated password
        $('#passwordNew, #passwordRepeat').val(pwd);
    });

});