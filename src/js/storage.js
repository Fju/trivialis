export function getJWT() {
	return sessionStorage.getItem('jwt');
}

export function setJWT(token) {
	sessionStorage.setItem('jwt', token);
}

