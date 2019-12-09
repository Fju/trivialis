<template>
	<div class="main">
		<navigation></navigation>
		<div class="col px-0">
			<toolbar></toolbar>
			<router-view></router-view>
		</div>
	</div>
</template>
<script>
	import VueRouter from 'vue-router';

	import Navigation from './components/Navigation.vue';
	import Toolbar from './components/Toolbar.vue';
	import FieldsPage from './components/FieldsPage.vue';
	import BlankPage from './components/BlankPage.vue';

	const routes = [
		{ path: '/fields', name: 'Fields', component: FieldsPage },
		{ path: '/pages', name: 'Pages', component: BlankPage },
		{ path: '/assets', name: 'Assets', component: BlankPage },
		{ path: '/statistics', name: 'Statistics', component: BlankPage }
	];
	
	export default {
		data () {
			return {};
		},
		components: { Navigation, Toolbar },
		router: new VueRouter({
			routes: routes
		})
	}
</script>
