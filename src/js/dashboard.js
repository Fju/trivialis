import $ from 'jquery';
import 'bootstrap';


import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import App from '../App.vue';
import LoginModal from '../components/LoginModal.vue';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faFolder, faFile, faTrashAlt, faEdit, faUpload, faPlus, faSyncAlt, faHome } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faFolder, faFile, faTrashAlt, faEdit, faUpload, faPlus, faSyncAlt, faHome);

Vue.component('fa', FontAwesomeIcon);


var loginModal = new Vue(LoginModal);
loginModal.$mount('#login-modal');

var app = new Vue(App);
app.$mount('#app');


