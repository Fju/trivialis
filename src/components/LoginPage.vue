<template>
	<form id="login-form">
		<div class="row">
			<div class="col-12">
				<h1>Login</h1>
			</div>
			<div class="form-group mb-2 col-6">
				<label for="login-username">Username:</label>
				<input type="text" name="username" id="login-username" class="form-control" placeholder="Username" />
			</div>
			<div class="form-group mb-4 col-6">
				<label for="login-password">Password:</label>
				<input type="password" name="password" id="login-password" class="form-control"/>
			</div>
			<div class="col-12">
				<button type="submit" class="btn btn-primary" id="login-submit" v-on:click="onSubmit">Submit</button>
			</div>
		</div>
	</form>
</template>
<script>
	import $ from 'jquery';

	export default {
		data () {
			return {};
		},
		methods: {
			onSubmit (e) {
				// prevent submitting the form
				e.preventDefault();
				$.post('/backend/login.php', $('#login-form').serialize()).done(function(data) {
					console.log(data);
				});
			}
		}
	}
</script>

