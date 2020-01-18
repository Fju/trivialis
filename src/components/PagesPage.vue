<template>
	<div class="page">
		<h1>Pages</h1>
		<div class="row mb-4">
			<div class="col">Fields are just pieces of texts that will be used to render a page.</div>
			<div class="col-auto">
				<router-link to="/fields/new" class="btn btn-success">
					<fa icon="plus"></fa> Create new Page
				</router-link>
				<button class="btn btn-secondary" v-on:click="loadData">
					<fa icon="sync-alt"></fa> Update
				</button>
			</div>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Route</th>
					<th>Layout</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in rows">
					<td>{{ row.name }} ({{ row.id }})</td>
					<td>{{ row.route }}</td>
					<td>{{ row.layout }}</td>
					<td class="small-col">
						<router-link :to="row.to" class="btn btn-primary btn-sm">Edit</router-link>
						<button class="btn btn-danger btn-sm" v-on:click="onDeleteClick(row.id, row.name)">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="modal fade" id="delete-page-modal" tabindex="-1" role="dialog" aria-labelledby="delete-page-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="delete-page-modal-label">Are you sure?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">Do you really want to delete the page "{{ deletePageName }}"</div>
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
	import { fetchPages } from '../js/pages.js';
	import { getJWT } from '../js/storage.js';

	export default {
		data () {
			return {
				deletePageName: '',
				rows: []
			};
		},
		methods: {
			loadData () {
				fetchPages((function(data) {
					if (data.err) console.log(data.err);
					if (data.pages) this.rows = data.pages.map(page => {
						page.to = { name: 'Pages/Edit', params: { id: page.id } };
						if (!page.layout_id) page.layout = '-';
						else page.layout = page.layout_name + ' (' + page.layout_id + ')';

						return page;
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
