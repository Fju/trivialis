<template>
	<div class="page">
		<h1>{{ title }}</h1>
		<form id="field-form" class="row" v-on:submit="onSubmit">
			<div class="col form-group form-group--inline">
				<label class="mr-4">Name:</label>
				<input type="text" class="form-control" v-model="pageName" />
			</div>
			<div class="col form-group form-group--inline">
				<label class="mr-4">Route:</label>
				<input type="text" class="form-control" v-model="pageRoute" />
			</div>
			<div class="col-auto">
				<button type="submit" class="btn btn-primary" v-on:click="onSubmit">Submit</button>
			</div>
			<div class="col-12">
				<label>Content:</label>
				<MonacoEditor height="500" :options="editorOptions" v-model="pageContent" language="html"></MonacoEditor>
			</div>
		</form>
	</div>
</template>
<script>
	import $ from 'jquery';
	import { fetchPages, getPage, getPages, modifyPage } from '../js/pages.js';

	import MonacoEditor from 'monaco-editor-vue';
	import 'monaco-editor/esm/vs/basic-languages/html/html.contribution.js';

	export default {
		data () {
			return {
				title: '',
				pageId: '',
				pageName: '',
				pageRoute: null,
				pageContent: '',
				editorOptions: {
					minimap: { enabled: false },
					automaticLayout: true
				}
			};
		},
		methods: {
			onSubmit (e) {
				// prevent default behaviour of submitting forms
				e.preventDefault();

				var parameters = {
					id: this.pageId,
					name: this.pageName,
					route: this.pageRoute,
					content: this.pageContent
				};

				if (this.$route.name === 'Pages/New') {
					parameters.method = 'create';
					modifyPage(parameters, this.handleCreateResponse);
				} else {
					parameters.method = 'update';
					modifyPage(parameters, this.handleUpdateResponse);
				}
			},
			tryLoad (attempt) {
				var page = getPage(this.pageId);

				if (!page) {
					if (attempt === 1) {
						// unable to load field, go back to fields page
						this.$router.push('/pages');
					}
					fetchPages((function() {
						this.tryLoad(attempt + 1);
					}).bind(this));
				} else {
					this.pageName = page.name;
					this.pageRoute = page.route || '';
					this.pageContent = page.content;
					this.title = 'Edit Page "' + this.pageName + '"';
				}
			},
			handleCreateResponse (data) {
				this.$router.push('/pages');
			},
			handleUpdateResponse (data) {
				this.$router.push('/pages');
			}
		},
		components: { MonacoEditor },
		mounted () {
			this.pageId = this.$route.params.id;

			if (!this.pageId) {
				this.title = 'Create new Page';
				// set to default values
				this.pageName = '';
				this.pageRoute = '';
				this.pageContent = '';
			} else {
				this.tryLoad(0);
			}
		}
	}
</script>
