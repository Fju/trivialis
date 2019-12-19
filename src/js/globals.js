import Vue from 'vue';
import $ from 'jquery';
import { getJWT } from './storage.js';

// can be imported by components for global event handling and calling
export const events = new Vue();

var unauthorizedRequests = [];
events.$on('login-successful', () => {
	// after successful authorization, all requests that have failed due to
	// wrong authorization will be retried
	while (unauthorizedRequests.length) {
		var reqObj = unauthorizedRequests.pop();
		request(reqObj.options, reqObj.callback);
	}
});

// function wrapper for sending XHR requests to the backend
// if the response contains the "unauth" attribute, the user will be prompt
// to provide valid user credentials. If the login was successful, the original
// request will be sent again
export function request(options, callback) {
	// add authorization header
	Object.assign(options, { 
		headers: { 'Authorization': 'Bearer ' + getJWT() }
	});
	$.ajax(options).done(data => {
		if (data.unauth) {
			events.$emit('open-login', data.unauth);
			unauthorizedRequests.unshift({ options, callback });
			return;
		}
		if (typeof callback === 'function') callback(data);
	});
}

