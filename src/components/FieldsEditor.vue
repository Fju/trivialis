<template>
	<div class="page">
		
		<h2>Edit</h2>
		<form class="row">
			<div class="col-12 form-group">
				<label>Name:</label>
				<input type="text" class="form-control" name="name" />
			</div>
			<div class="col-6">
				<label>Content</label>
				<textarea class="w-100" v-on:input="compile"></textarea>
			</div>
			<div class="col-6">
				<label>Preview</label>
				<div v-html="compiledMarkdown">
			</div>
		</form>
	</div>
</template>
<script>
	//import { Multipane, MultipaneResizer } from 'vue-multipane';

	import marked from 'marked';

	var compile_id;

	export default {
		data () {
			return {
				input: ''
			};
		},
		methods: {
			compile (e) {
				var value = e.target.value;

				if (compile_id) clearInterval(compile_id);
				compile_id = setInterval((function() {

					this.input = value;
				}).bind(this), 300);
			}
		},
		computed: {
			compiledMarkdown () {
				return marked(this.input); 
			}	
		},
		//components: { Multipane, MultipaneResizer }
	}
</script>
