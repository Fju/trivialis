<template>
	<div class="page">
		<h1>Pages</h1>
		<div class="row mb-4">
			<div class="col">Fields are just pieces of texts that will be used to render a page.</div>
			<div class="col-auto">
				<router-link to="/pages/new" class="btn btn-success">
					<fa icon="plus"></fa> Create new Page
				</router-link>
				<button class="btn btn-secondary" v-on:click="update">
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
						<router-link :to="row.to" class="btn btn-primary btn-sm">
							<fa icon="edit"></fa> Edit
						</router-link>
						<button class="btn btn-danger btn-sm" v-on:click="onDeleteClick(row.id, row.name)">
							<fa icon="trash-alt"></fa> Delete
						</button>
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
	import { fetchPages, modifyPage, getPage } from '../js/pages.js';

	export default {
		data () {
			return {
				deletePageName: '',
				rows: []
			};
		},
		methods: {
			update () {
				fetchPages((function(data) {
					if (data.err) console.log(data.err);
					if (data.pages) this.rows = data.pages.map(page => {
						page.to = { name: 'Pages/Edit', params: { id: page.id } };

						// show layout name instead of id
						// if there is no layout display "-"
						var layout = getPage(page.layout);
						if (layout) page.layout = layout.name;
						else page.layout = '-';

						return page;
					});		
				}).bind(this));
			},
			onDeleteClick (id, name) {
				this.deleteFieldId = id;
				this.deleteFieldName = name;
				$('#delete-page-modal').modal('show');
			},
			onDeleteSubmit () {
				$('#delete-page-modal').modal('hide');
				modifyPage({ id: this.deleteFieldId, method: 'delete' }, (function(data) {
					if (data.err) console.log('Error when deleting: ' + data.err);
					else this.update();
				}).bind(this));
			}
		},
		mounted () {
			this.update();
		}
	}
</script>
