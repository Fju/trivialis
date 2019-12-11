<template>
	<div class="page">
		<h1>Fields</h1>
		<p>Fields are just pieces of texts that will be used to render a page.</p>
		<router-link to="/fields/editor" class="btn btn-success">Create new Field</router-link>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Content</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in rows">
					<td>{{ row.name }}</td>
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
	import $ from 'jquery';
	import { router } from '../js/router.js';
	import { getJWT } from '../js/globals.js';

	export default {
		data () {
			return {
				rows: [
					// Test data
				]
			};
		},
		methods: {
			loadData () {
				var token = getJWT();	
				$.ajax({ headers: { 'Authorization': 'Bearer ' + token }, url: '/backend/fields.php' }).done((function(data) {
					if (data.fields) this.rows = data.fields;
				}).bind(this));
			}
		},
		mounted () {
			this.loadData();
		}
	}
</script>
