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
			</div>
		</div>
		<!--<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Content</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>-->
	</div>
</template>
<script>
	import $ from 'jquery';
	import { request } from '../js/globals.js';

	const MAX_FILE_SIZE = 1 * 1024 * 1024; // 1 MB

	export default {
		data () {
			return {
				files: []
			}
		},
		methods: {
			onUploadClick () {
				$('#file-input').click();
			}
		},
		mounted () {
			$('#file-input').on('change', e => {
				var files = e.target.files;
				for (var i = 0; i < files.length; ++i) { 
					if (files[i].size > MAX_FILE_SIZE) console.log('File too big');

					var data = new FormData();
					data.append('file', files[i]);
			
					request({ url: '/backend/assets.php',
						data: data,
						processData: false,
						contentType: false,
						method: 'POST' }, d => {
						console.log('upload', d);
					});
				}
			});
		}
	}
</script>
