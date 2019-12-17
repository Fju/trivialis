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
	import { router } from './js/router.js';

	import Navigation from './components/Navigation.vue';
	import Toolbar from './components/Toolbar.vue';
	
	export default {
		data () {
			return {};
		},
		components: { Navigation, Toolbar },
		router: router
	}
</script>
