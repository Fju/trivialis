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
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in rows">
					<td>{{ row.name }} ({{ row.id }})</td>
					<td>{{ row.route }}</td>
					<td class="small-col">
						<a :href="row.link" target="_blank" class="btn btn-sm btn-success" :class="{ 'disabled': row.link === false }">
							<fa icon="external-link-alt"></fa> View page
						</a>
						<router-link :to="{ name: 'Pages/Edit', params: { id: row.id } }" class="btn btn-primary btn-sm">
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
				deletePageId: 0,
				deletePageName: '',
				rows: []
			};
		},
		methods: {
			update () {
				fetchPages((function(data) {
					if (data.err) console.log(data.err);
					if (data.pages) this.rows = data.pages.map(page => {
						return {
							id: page.id,
							name: page.name,
							route: page.route || '-',
							link: page.route ? page.route : false
						}
					});		
				}).bind(this));
			},
			onDeleteClick (id, name) {
				this.deletePageId = id;
				this.deletePageName = name;
				$('#delete-page-modal').modal('show');
			},
			onDeleteSubmit () {
				$('#delete-page-modal').modal('hide');
				modifyPage({ id: this.deletePageId, method: 'delete' }, (function(data) {
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
