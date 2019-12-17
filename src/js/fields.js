import $ from 'jquery';
import { getJWT } from '../js/storage.js';

var fields = [];

export function getField(id) {
	// returns undefined if no item was found
	return fields.find(field => {
		// it's probably better to check equality using `==` instead of `===`
		// because id might be of type String or Number
		// However, `field.id` must not be 0 or "0" because 0 == "" and "0" == "" returns true!!
		return field.id == id;
	});
}

export function fetchFields(callback) {
	$.ajax({
		headers: { 'Authorization': 'Bearer ' + getJWT() },
		url: '/backend/fields.php',
		method: 'GET',
	}).done(data => {
		if (data.fields) fields = data.fields;
		if (typeof callback === 'function') callback(data);

	});
}

export function modifyField(parameters, callback) {
	$.ajax({
		headers: { 'Authorization': 'Bearer ' + getJWT() },
		url: '/backend/fields.php',
		method: 'POST',
		data: parameters
	}).done(data => {
		if (typeof callback === 'function') callback(data);	
	});
}

