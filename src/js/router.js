import VueRouter from 'vue-router';

import FieldsPage from '../components/FieldsPage.vue';
import BlankPage from '../components/BlankPage.vue';

import FieldsEditor from '../components/FieldsEditor.vue';

export const routes = [
	{ path: '/fields', name: 'Fields', component: FieldsPage },
	{ path: '/fields/editor', name: 'Fields/Editor', component: FieldsEditor },
	{ path: '/pages', name: 'Pages', component: BlankPage },
	{ path: '/assets', name: 'Assets', component: BlankPage },
	{ path: '/statistics', name: 'Statistics', component: BlankPage }
];

export var router = new VueRouter({
	routes: routes
});
