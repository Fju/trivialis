import { request } from './globals.js';

export function fetchStats(callback) {
	request({ url: '/api/stats.php', method: 'GET' }, data => {
		//if (data.fields) fields = data.fields;
		if (typeof callback === 'function') callback(data);
	});
}

