export function getJWTBody() {
	var jwt = sessionStorage.getItem('jwt');
	if (!jwt) return {};
	return JSON.parse(atob(jwt.split('.')[1]));
}

export function getJWT() {
	return sessionStorage.getItem('jwt') || '';
}

export function setJWT(token) {
	sessionStorage.setItem('jwt', token);
}

export function removeJWT() {
	sessionStorage.removeItem('jwt');
}

