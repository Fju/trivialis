<template>
	<div class="page h-100">
		<input type="file" multiple hidden id="file-input" />
		<h1>Assets</h1>
		<p>Upload files to the server</p>
		<div class="row">
			<div class="col">
			</div>
			<div class="col-auto">
				<button class="btn btn-success" v-on:click="onUploadClick">Upload file(s)</button>
				<button class="btn btn-secondary" v-on:click="update">Update</button>
			</div>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Size</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="file in files" :class="{ 'row-danger': file.state === 'error', 'row-pending': file.state === 'uploading' }">
					<td>
						<span :class="{ hidden: file.state === 'rename' }">{{ file.name }}</span>
						<div class="form-inline" :class="{ hidden: file.state !== 'rename' }">
							<input class="form-control" type="text" v-model="file.new_name" 
								v-on:keydown.enter="onRenameSubmit(file)" v-on:keydown.esc="onRenameCancel(file)" />
						</div>
						<div v-if="file.state === 'error'" class="my-2">{{ file.err }}</div>
					</td>
					<td>{{ file.size }}</td>
					<td class="small-col" v-if="file.state !== 'ok'">
						<div v-if="file.state === 'uploading'" class="spinner-border spinner-border-sm" role="status">
							<span class="sr-only">Uploading</span>
						</div>
						<button type="button" class="close" v-if="file.state === 'error'" v-on:click="file.until = 0"><span>&times;</span></button>
					</td>
					<td class="small-col" v-else>
						<button class="btn btn-primary btn-sm" v-on:click="onRenameClick(file)">Rename</button>
						<button class="btn btn-danger btn-sm" v-on:click="openDeleteModal(file)">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="modal fade" id="delete-assets-modal" tabindex="-1" role="dialog" aria-labelledby="delete-assets-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="delete-assets-modal-label">Are you sure?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">Do you really want to delete the file "{{ deleteFilename }}"</div>
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
	import { uploadFile, deleteFile, renameFile, getFiles } from '../js/assets.js';

	const MAX_FILE_SIZE = 25 * 1024 * 1024; // 1 MB
	const ERROR_LIFESPAN = 7 * 1000; // 7 seconds

	function formatFilesize(size) {
		var i = 0;
		while (size > 1000) {
			size /= 1024;
			i++;
		}

		var suffix = 'B';
		switch(i) {
			case 1: suffix = 'KB'; break;
			case 2: suffix = 'MB'; break;
			case 3: suffix = 'GB'; break;
			case 4: suffix = 'TB'; break;
		}
	
		if (i === 0) return size + ' ' + suffix;
		else return size.toFixed(1) + ' ' + suffix;
	}

	export default {
		data () {
			return {
				deleteFilename: '',
				files: []
			}
		},
		methods: {
			update () {
				getFiles((function(data) {
					console.log(data);
					if (typeof data.files === 'object') this.files = data.files.map(item => {
						// convert filesize to human readable format
						item.size = formatFilesize(item.size);
						item.state = 'ok';
						return item;
					});
				}).bind(this));
			},
			onUploadClick () {
				$('#file-input').click();
			},
			openDeleteModal (file) {
				this.deleteFilename = file.name;
				$('#delete-assets-modal').modal('show');
			},
			onRenameClick (file) {
				file.state = 'rename';
				file.new_name = file.name;
			},
			onDeleteSubmit () {
				$('#delete-assets-modal').modal('hide');	
				deleteFile(this.deleteFilename, (function(data) {
					if (data.err) console.log(data.err);
					else this.update();
				}).bind(this));
			},
			onRenameSubmit (file) {
				if (file.new_name === file.name) {
					file.state = 'ok';	
				} else {
					renameFile(file.name, file.new_name, (function(data) {
						file.state = 'ok';
						this.update();
					}).bind(this));	
				}
			},
			onRenameCancel (file) {
				file.state = 'ok';
			}
		},
		mounted () {
			this.update();

			setInterval((function() {
				// filter files array
				this.files = this.files.filter(item => {
					if (typeof item.until !== 'number') return true;
					else return item.until > Date.now();
				});
			}).bind(this), 100);

			$('#file-input').on('change', (function(e) {
				[].forEach.call(e.target.files, (function(f) { 
					var file = {
						name: f.name,
						size: formatFilesize(f.size),
						state: 'uploading'
					};

					this.files.unshift(file);
			
					if (f.size > MAX_FILE_SIZE) {
						file.state = 'error';
						file.err = 'File is too big!';
						file.until = Date.now() + ERROR_LIFESPAN;
						return;
					}
					uploadFile(f, (function(data) {
						if (data.err) {
							file.state = 'error';
							file.err = data.err;
							file.until = Date.now() + ERROR_LIFESPAN;
						} else file.state = 'ok';
					}).bind(this));
				}).bind(this));
			}).bind(this));
		}
	}
</script>
