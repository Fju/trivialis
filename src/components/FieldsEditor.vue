<template>
	<div class="page">
		<h2>{{ pageTitle }}</h2>
		<form id="field-form" class="row" v-on:submit="onSubmit">
			<div class="col-12 form-group">
				<label>Name:</label>
				<input type="text" class="form-control" v-model="fieldName" />
			</div>
			<div class="col-6">
				<label>Content</label>
				<textarea class="w-100" v-on:input="compile" v-model="fieldContent"></textarea>
			</div>
			<div class="col-6">
				<label>Preview</label>
				<div v-html="compiledMarkdown"></div>
			</div>
			<div class="col-12">
				<button type="submit" class="btn btn-primary" v-on:click="onSubmit">Submit</button>
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

	var compile_id;

	export default {
		data () {
			return {
				fieldId: '',
				fieldName: '',
				fieldContent: '',
				pageTitle: '',
				input: ''
			};
		},
		methods: {
			compile () {
				if (compile_id) clearInterval(compile_id);
				compile_id = setInterval((function() {
					// update value to trigger re-computing `compiledMarkdown`
					this.input = this.fieldContent;
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
					this.compile();

					this.pageTitle = 'Edit Field "' + this.fieldName + '"';
				}
			},
			handleCreateResponse (data) {
				console.log(data);
				this.$router.push('/fields');
			},
			handleUpdateResponse (data) {
				console.log(data);
			}
		},
		computed: {
			compiledMarkdown () {
				return marked(this.input); 
			}
		},
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
