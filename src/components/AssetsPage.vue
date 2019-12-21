<template>
	<div class="page h-100">
		<h1>Assets</h1>
		<p>Upload files to the server</p>
		<div class="row">
			<div class="col-12">
				<file-pond v-bind:files="files" allow-multiple="true"></file-pond>
			</div>
		</div>
		<p>Current Assets</p>
		<button v-on:click="onClick">Test</button>
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Content</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="file in files">
					<td>{{ file.filename }}</td>
					<td>{{ file.fileSize }}</td>
					<td>
						<button class="btn btn-sm btn-danger">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	import vueFilePond from 'vue-filepond';
	
	const FilePond = vueFilePond();

	export default {
		data () {
			return {
				files: []
			}
		},
		methods: {
			onClick () {
				console.log(this.files);
			}
		},
		components: { FilePond }
	}
</script>
