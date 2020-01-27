<template>
	<div class="page h-100" v-on:dragleave="onDragLeave" v-on:dragover="onDragOver" v-on:drop="onDrop">
		<div class="drag-overlay" :class="{ hidden: !dragOverlay }">
			<fa icon="upload" size="3x" class="mb-3"></fa>
			Drop file(s) to upload
		</div>
		<input type="file" multiple hidden id="file-input" />
		<h1>Assets</h1>
		<div class="row">
			<div class="col">
				<p>Upload files to the server</p>
			</div>
			<div class="col-auto">
				<button class="btn btn-success" v-on:click="onCreateClick"><fa icon="plus"></fa> Create directory</button>
				<button class="btn btn-success" v-on:click="onUploadClick"><fa icon="upload"></fa> Upload file(s)</button>
				<button class="btn btn-secondary" v-on:click="update(cwd)"><fa icon="sync-alt"></fa> Update</button>
			</div>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="#" v-on:click.prevent="update()">
						<fa icon="home"></fa>
					</a>
				</li>
				<li v-for="dir in parentDirs" class="breadcrumb-item">
					<a v-if="!dir.active" href="#" v-on:click.prevent="update(dir.to)">{{ dir.name }}</a>
					<span v-else>{{ dir.name }}</span>
				</li>
			</ol>
		</nav>
		<table class="table table-striped">
			<thead>
				<tr>
					<th></tg>
					<th>Name</th>
					<th>Size</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-if="cwd !== ''">
					<td><fa icon="undo-alt"></fa></td>
					<td><a href="" v-on:click.prevent="update(cwd + '/..')">..</a></td>
					<td></td>
					<td></td>
				</tr>
				<assets-item v-for="file in files" :item="file"
					v-on:navigate="onNavigate" v-on:rename="onRename" v-on:delete="onDelete"></assets-item>
				<!--<tr v-for="file in files" :class="{ 'row-danger': file.state === 'error', 'row-pending': file.state === 'uploading' }">
					<td class="small-col text-center">
						<fa :icon="getFileIcon(file.type)" size="lg"></fa>
					</td>
					<td>
						!-- TODO: make this less complicated --
						<span v-if="file.type === 'file'" :class="{ hidden: file.state === 'rename' }">{{ file.name }}</span>
						<a href="" v-else :class="{ hidden: file.state === 'rename' }" v-on:click.prevent="update(cwd + '/' + file.name)">{{ file.name }}</a>
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
						<button class="btn btn-primary btn-sm" v-on:click="onRenameClick(file)"><fa icon="edit"></fa> Rename</button>
						<button class="btn btn-danger btn-sm" v-on:click="openDeleteModal(file)"><fa icon="trash-alt"></fa> Delete</button>
					</td>
				</tr>-->
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
		<div class="modal fade" id="create-dir-modal" tabindex="-1" role="dialog" aria-labelledby="create-dir-modal-label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="create-dir-modal-label">Create a new directory</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Set the name of the new directory:</p>
						<input type="text" class="form-control" v-model="createDirname" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" v-on:click="onCreateSubmit">Submit</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
	import AssetsItem from './AssetsItem.vue';
	import $ from 'jquery';
	import { uploadFile, deleteFile, renameFile, getFiles, createDir } from '../js/assets.js';

	const MAX_FILE_SIZE = 25 * 1024 * 1024; // 25 MB
	const ERROR_LIFESPAN = 7 * 1000; // 7 seconds

	export default {
		data () {
			return {
				deleteFilename: '',
				createDirname: '',
				dragOverlay: false,
				files: [],
				cwd: ''
			}
		},
		computed: {
			parentDirs () {
				// extract parent directory names and path from cwd string
				// used to display breadcrumb navigation bar
				var names = this.cwd.split('/');
				var to = '';
				var dirs = [];

				for (var i = 0; i !== names.length; ++i) {
					if (names[i] === '') continue;
					to += names[i] + '/';
					dirs.push({
						name: names[i],
						to: to,
						active: i === names.length - 1
					});
				}
				return dirs;
			}
		},
		methods: {
			update (dir) {
				if (!dir) dir = '';
				getFiles(dir, (function(data) {
					while (this.files.length > 0) this.files.pop();
					if (typeof data.cwd === 'string') this.cwd = data.cwd;
					if (typeof data.contents === 'object') {
						for (var i = 0; i < data.contents.length; ++i) {
							this.files.push({
								name: data.contents[i].name,
								type: data.contents[i].type,
								size: data.contents[i].size,
								state: 'ok'
							});
						}
					}
				}).bind(this));
			},
			onUploadClick () {
				$('#file-input').click();
			},
			onDeleteSubmit () {
				$('#delete-assets-modal').modal('hide');	
				deleteFile(this.deleteFilename, (function(data) {
					if (data.err) console.log(data.err);
					else this.update(this.cwd);
				}).bind(this));
			},
			onRename (file, rename) {
				renameFile(this.cwd + '/' + file.name, this.cwd + '/' + rename, (function(data) {
					this.update(this.cwd);
				}).bind(this));	
			},
			onDelete (name) {
				this.deleteFilename = name;
				$('#delete-assets-modal').modal('show');
			},
			onNavigate (name) {
				this.update(this.cwd + '/' + name);
			},
			onCreateClick () {
				this.createDirname = 'New folder';
				$('#create-dir-modal').modal('show');
			},
			onCreateSubmit () {
				$('#create-dir-modal').modal('hide');
				createDir(this.createDirname, (function(data) {
					if (data.err) console.error(data.err);
					else this.update(this.cwd);
				}).bind(this));
			},
			uploadFile (files) {
				[].forEach.call(files, (function(f) { 
					var file = {
						name: f.name,
						size: f.size,
						type: 'file',
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
			},
			onDragLeave () {
				this.dragOverlay = false;
			},
			onDragOver (e) {
				e.preventDefault();
				this.dragOverlay = true;
			},
			onDrop (e) {
				e.preventDefault();
				this.uploadFiles(e.dataTransfer.files);
				this.dragOverlay = false;
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
				this.uploadFiles(e.target.files);
			}).bind(this));
		},
		components: { AssetsItem }
	}
</script>
