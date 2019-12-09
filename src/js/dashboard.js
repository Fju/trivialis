import $ from 'jquery';
import 'bootstrap';


import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import App from '../App.vue';
import LoginModal from '../components/LoginModal.vue';


var loginModal = new Vue(LoginModal);
loginModal.$mount('#login-modal');

var app = new Vue(App);
app.$mount('#app');


