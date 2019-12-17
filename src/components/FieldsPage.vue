<template>
	<div class="page">
		<h1>Fields</h1>
		<p></p>
		<div class="row mb-4">
			<div class="col">Fields are just pieces of texts that will be used to render a page.</div>
			<div class="col-auto">
				<router-link to="/fields/new" class="btn btn-success">Create new Field</router-link>
				<button class="btn btn-secondary" v-on:click="loadData">Update</button>
			</div>
		</div>
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
					<td>{{ row.name }} ({{ row.id }})</td>
					<td>
						{{ row.content }}
						<i class="text-muted" v-if="!row.content">no content</i>
					</td>
					<td class="small-row">
						<router-link :to="row.to" class="btn btn-primary btn-sm">Edit</router-link>
						<button class="btn btn-danger btn-sm" v-on:click="onDeleteClick(row.id)">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	import $ from 'jquery';
	import { getJWT } from '../js/storage.js';
	import { fetchFields, modifyField } from '../js/fields.js';

	export default {
		data () {
			return {
				rows: []
			};
		},
		methods: {
			loadData () {
				fetchFields((function(data) {
					if (data.err) console.log(data.err);
					if (data.fields) this.rows = data.fields.map(field => {
						field.to = { name: 'Fields/Edit', params: { id: field.id, name: field.name } };
						return field;
					});		
				}).bind(this));
			},
			onDeleteClick (id) {
				modifyField({ id: id, method: 'delete' }, (function(data) {
					if (data.err) console.log('Error when deleting: ' + data.err);
					else this.loadData();
				}).bind(this));
			}
		},
		mounted () {
			this.loadData();
		}
	}
</script>
