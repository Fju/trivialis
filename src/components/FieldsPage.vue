<template>
	<div class="page">
		<h1>Fields</h1>
		<p>Fields are just pieces of texts that will be used to render a page.</p>
		<router-link to="/fields/editor" class="btn btn-success">Create new Field</router-link>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Type</th>
					<th>Content</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in rows">
					<td>{{ row.name }}</td>
					<td>{{ row.type }}</td>
					<td>{{ row.content }}</td>
					<td class="small-row">
						<button class="btn btn-primary btn-sm">Bearbeiten</button>
						<button class="btn btn-danger btn-sm">Löschen</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	import { router } from '../js/router.js';

	export default {
		data () {
			return {
				rows: [
					// Test data
					{ name: 'title', type: 'raw', content: 'Lorem ipsum dolor sit amet' }
				]
			};
		}
	}
</script>
