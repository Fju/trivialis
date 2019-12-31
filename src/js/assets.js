import { request } from './globals.js';

export function uploadFile(file, callback) {
	let data = new FormData();
	data.append('file', file);
	data.append('method', 'upload');
			
	request({ 
		url: '/backend/assets.php',
		data: data,
		processData: false,
		contentType: false,
		method: 'POST'
	}, d => {
		if (typeof callback === 'function') callback(d);
	});
}

export function deleteFile(name, callback) {
	request({
		url: '/backend/assets.php',
		data: {
			method: 'delete',
			name: name
		},
		method: 'POST'
	}, d => {
		if (typeof callback === 'function') callback(d);
	});
}

export function getFiles(callback) {
	request({ url: '/backend/assets.php', method: 'GET' }, data => {
		if (typeof callback === 'function') callback(data);
	});
}
