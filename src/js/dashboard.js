import $ from 'jquery';
import 'bootstrap';

/*$('#login-submit').on('click', function() {
	var username = $('#login-username').val();
	var password = $('#login-password').val();

	$.post('/backend/login.php', { username: username, password: password }, function(result) {
		console.log(result);	
	});
});
*/

import Vue from 'vue';
import LoginPage from '../components/LoginPage.vue';

var loginPage = new Vue(LoginPage);
loginPage.$mount('#login-page');


