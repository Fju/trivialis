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

export function renameFile(name, name_new, callback) {
	request({
		url: '/backend/assets.php',
		method: 'POST',
		data: {
			method: 'rename',
			name: name,
			name_new: name_new
		}
	}, d => {
		if (typeof callback === 'function') callback(d);
	});
}

export function getFiles(path, callback) {
	console.log('get files', path);
	request({ url: '/backend/assets.php', method: 'GET', data: { path: path } }, data => {
		if (typeof callback === 'function') callback(data);
	});
}
