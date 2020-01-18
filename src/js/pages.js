import { request } from './globals.js';

var pages = [];

export function getPage(id) {
	// returns undefined if no item was found
	return pages.find(page => {
		return page.id == id;
	});
}

export function fetchPages(callback) {
	request({ url: '/backend/pages.php', method: 'GET' }, data => {
		if (data.pages) pages = data.pages;
		if (typeof callback === 'function') callback(data);	
	});
}
