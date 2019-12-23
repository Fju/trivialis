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
