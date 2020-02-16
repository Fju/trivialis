<template>
	<ul class="nav nav--sidebar bg-dark flex-column">
		<!-- TODO: generate router-link through a component property -->
		<li class="nav-item">
			<router-link to="/fields" class="nav-link active">Fields</router-link>
		</li>
		<li class="nav-item">
			<router-link to="/pages" class="nav-link">Pages</router-link>
		</li>
		<li class="nav-item">
			<router-link to="/assets" class="nav-link">Assets</router-link>
		</li>
		<li class="nav-divider"></li>
		<li class="nav-item">
			<router-link to="/statistics" class="nav-link">Statistics</router-link>
		</li>
		<li class="nav-item">
			<router-link to="/admin" class="nav-link">Administration</router-link>
		</li>
	</ul>
</template>
<script>
	import { router } from '../js/router.js';

	export default {
		data () {
			return {};
		},
		//router: router
	}
</script>
