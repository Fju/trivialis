import VueRouter from 'vue-router';

import FieldsPage from '../components/FieldsPage.vue';
import PagesPage from '../components/PagesPage.vue';
import AssetsPage from '../components/AssetsPage.vue';
import BlankPage from '../components/BlankPage.vue';

import FieldsEditor from '../components/FieldsEditor.vue';
import PagesEditor from '../components/PagesEditor.vue';

export const routes = [
	{ path: '/fields', name: 'Fields', component: FieldsPage },
	{ path: '/fields/new', name: 'Fields/New', component: FieldsEditor },
	{ path: '/fields/edit/:id', name: 'Fields/Edit', component: FieldsEditor },
	{ path: '/pages', name: 'Pages', component: PagesPage },
	{ path: '/pages/new', name: 'Pages/New', component: PagesEditor },
	{ path: '/pages/edit/:id', name: 'Pages/Edit', component: PagesEditor },
	{ path: '/assets', name: 'Assets', component: AssetsPage },
	{ path: '/statistics', name: 'Statistics', component: BlankPage }
];

export var router = new VueRouter({
	routes: routes
});

