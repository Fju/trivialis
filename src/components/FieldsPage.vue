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
					<td class="small-col">
						<router-link :to="row.to" class="btn btn-primary btn-sm">Edit</router-link>
						<button class="btn btn-danger btn-sm" v-on:click="onDeleteClick(row.id, row.name)">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="modal fade" id="delete-field-modal" tabindex="-1" role="dialog" aria-labelledby="delete-field-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="delete-field-modal-label">Are you sure?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">Do you really want to delete field "{{ deleteFieldName }}"</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-danger" v-on:click="onDeleteSubmit">Delete</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import $ from 'jquery';
	import { getJWT } from '../js/storage.js';
	import { fetchFields, modifyField } from '../js/fields.js';

	export default {
		data () {
			return {
				deleteFieldName: '',
				deleteFieldId: '',
				rows: []
			};
		},
		methods: {
			loadData () {
				fetchFields((function(data) {
					if (data.err) console.log(data.err);
					if (data.fields) this.rows = data.fields.map(field => {
						field.to = { name: 'Fields/Edit', params: { id: field.id } };
						return field;
					});		
				}).bind(this));
			},
			onDeleteClick (id, name) {
				this.deleteFieldId = id;
				this.deleteFieldName = name;
				$('#delete-field-modal').modal('show');
			},
			onDeleteSubmit () {
				$('#delete-field-modal').modal('hide');
				modifyField({ id: this.deleteFieldId, method: 'delete' }, (function(data) {
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
