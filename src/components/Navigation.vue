<template>
	<ul class="nav nav--sidebar col-auto bg-dark flex-column">
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
		<li class="nav-item">
			<router-link to="/statistics" class="nav-link">Statistics</router-link>
		</li>
	</ul>
</template>
<script>
	export default {
		data () {
			return {};
		}
	}
</script>
