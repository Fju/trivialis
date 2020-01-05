<template>
	<div class="page">
		<h1>{{ pageTitle }}</h1>
		<form id="field-form" class="row" v-on:submit="onSubmit">
			<div class="col form-group form-group--inline">
				<label class="mr-4">Name:</label>
				<input type="text" class="form-control" v-model="fieldName" />
			</div>
			<div class="col-auto">
				<button type="submit" class="btn btn-primary" v-on:click="onSubmit">Submit</button>
			</div>
			<div class="col-12"></div>
			<div class="col-6">
				<label>Content:</label>
				<MonacoEditor height="500" :options="editorOptions" v-model="fieldContent" v-on:change="onSourceChange" language="markdown"></MonacoEditor>
			</div>
			<div class="col-6">
				<label>Preview:</label>
				<div v-html="compiledMarkdown"></div>
			</div>
		</form>
	</div>
</template>
<script>
	import $ from 'jquery';
	// TODO: santize output HTML!
	import marked from 'marked';
	import { getJWT } from '../js/storage.js';
	import { getField, modifyField, fetchFields } from '../js/fields.js';

	import MonacoEditor from 'monaco-editor-vue';
	import 'monaco-editor/esm/vs/basic-languages/markdown/markdown.contribution.js';

	var compile_id;

	export default {
		data () {
			return {
				fieldId: '',
				fieldName: '',
				fieldContent: '',
				pageTitle: '',
				input: '',
				editorOptions: {
					minimap: { enabled: false },
					automaticLayout: true
				}
			};
		},
		methods: {
			onSourceChange (value) {
				if (compile_id) clearInterval(compile_id);
				compile_id = setInterval((function() {
					// update value to trigger re-computing `compiledMarkdown`
					this.input = value;
				}).bind(this), 300);
			},
			onSubmit (e) {
				// prevent default behaviour of submitting forms
				e.preventDefault();

				var parameters = {
					id: this.fieldId,
					name: this.fieldName,
					content: this.fieldContent
				};

				if (this.$route.name === 'Fields/New') {
					parameters.method = 'create';
					modifyField(parameters, this.handleCreateResponse);
				} else {
					parameters.method = 'update';
					modifyField(parameters, this.handleUpdateResponse);
				}
			},
			loadField (attempt) {
				var field = getField(this.fieldId);

				if (!field) {
					if (attempt === 1) {
						// unable to load field, go back to fields page
						this.$router.push('/fields');
					}
					fetchFields((function() {
						this.loadField(attempt + 1);
					}).bind(this));
				} else {
					this.fieldName = field.name;
					this.fieldContent = field.content;
					this.pageTitle = 'Edit Field "' + this.fieldName + '"';
				}
			},
			handleCreateResponse (data) {
				console.log(data);
				this.$router.push('/fields');
			},
			handleUpdateResponse (data) {
				this.$router.push('/fields');
				console.log(data);
			}
		},
		computed: {
			compiledMarkdown () {
				return marked(this.input); 
			}
		},
		components: { MonacoEditor },
		mounted () {
			this.fieldId = this.$route.params.id;

			if (!this.fieldId) {
				this.pageTitle = 'Create new Field';
			} else {
				this.loadField(0);
			}
		}
		//components: { Multipane, MultipaneResizer }
	}
</script>
