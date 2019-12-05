import $ from 'jquery';

$('#login-submit').on('click', function() {
	var username = $('#login-username').val();
	var password = $('#login-password').val();

	$.post('/backend/login.php', { username: username, password: password }, function(result) {
		console.log(result);	
	});
});

