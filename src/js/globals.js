import Vue from 'vue';

// can be imported by components for global event handling and calling
export const events = new Vue();


var jwt = '';


export function setJWT(value) {
	jwt = value;
};

export function getJWT() {
	return jwt;
}
