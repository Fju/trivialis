<template>
	<div class="row h-100">
		<navigation></navigation>
		<router-view></router-view>
	</div>
</template>
<script>
	import VueRouter from 'vue-router';

	import Navigation from './components/Navigation.vue';
	import FieldsPage from './components/FieldsPage.vue';
	import BlankPage from './components/BlankPage.vue';

	const routes = [
		{ path: '/fields', component: FieldsPage },
		{ path: '/pages', component: BlankPage},
		{ path: '/assets', component: BlankPage},
		{ path: '/statistics', component: BlankPage }
	];
	
	export default {
		data () {
			return {};
		},
		components: { Navigation },
		router: new VueRouter({
			routes: routes
		})
	}
</script>
