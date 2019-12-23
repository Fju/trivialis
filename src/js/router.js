import VueRouter from 'vue-router';

import FieldsPage from '../components/FieldsPage.vue';
import AssetsPage from '../components/AssetsPage.vue';
import BlankPage from '../components/BlankPage.vue';

import FieldsEditor from '../components/FieldsEditor.vue';

export const routes = [
	{ path: '/fields', name: 'Fields', component: FieldsPage },
	{ path: '/fields/new', name: 'Fields/New', component: FieldsEditor },
	{ path: '/fields/edit/:id', name: 'Fields/Edit', component: FieldsEditor },
	{ path: '/pages', name: 'Pages', component: BlankPage },
	{ path: '/assets', name: 'Assets', component: AssetsPage },
	{ path: '/statistics', name: 'Statistics', component: BlankPage }
];

export var router = new VueRouter({
	routes: routes
});

