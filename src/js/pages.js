import { request } from './globals.js';

var pages = [];

export function getPage(id) {
	// returns undefined if no item was found
	return pages.find(page => {
		return page.id == id;
	});
}

export function getPages() {
	return pages;
}

export function fetchPages(callback) {
	request({ url: '/api/pages.php', method: 'GET' }, data => {
		if (data.pages) pages = data.pages;
		if (typeof callback === 'function') callback(data);	
	});
}

export function modifyPage(parameters, callback) {
	request({ url: '/api/pages.php', method: 'POST', data: parameters }, data => {
		if (typeof callback === 'function') callback(data);
	});
}
