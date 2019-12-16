<template>
	<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="exampleModalLabel">Login</h3>
				</div>
				<div class="modal-body">
					<div class="mt-4">
						<div class="alert alert-danger" :class="{ 'd-none': !error }" role="alert">
							{{ error_message }}
						</div>
					</div>
					<form id="login-form" v-on:submit="onSubmit">
						<div class="form-group">
							<label for="login-username">Username:</label>
							<input type="text" name="username" id="login-username" class="form-control" placeholder="Username" />
						</div>
						<div class="form-group">
							<label for="login-password">Password:</label>
							<input type="password" name="password" id="login-password" class="form-control" />
						</div>
						<div class="row align-items-center">
							<div class="col text-muted">
								<small>Trivialis CMS v0.0.1</small>
							</div>
							<div class="col-auto">
								<button class="btn btn-primary" id="login-submit" v-on:click="onSubmit">Login</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import $ from 'jquery';

	//import { events } from '../js/globals.js';
	import { setJWT, getJWT } from '../js/storage.js';

	export default {
		data () {
			return {
				error: false,
				error_message: ''
			};
		},
		methods: {
			onSubmit (e) {
				// prevent submitting the form
				e.preventDefault();
				$.post('/backend/login.php', $('#login-form').serialize()).done((function(data) {
					if (data.err) {
						this.error = true;
						this.error_message = data.err;
						// clear password input
						$('#login-password').val('');
					} else {
						this.error = false;
						setJWT(data.token);

						this.$emit('successful-login');
						this.closeDialog();
					}
				}).bind(this));
			},
			openDialog () {
				$('#login-modal').modal({
					// disable dismissing the modal when clicking on the backdrop
					backdrop: 'static',
					// disable dismissing the modal when pressing ESC
					keyboard: false,
					show: true
				});
			},
			closeDialog () {
				$('#login-modal').modal('hide');
			}
		},
		mounted () {
			// TODO: maybe move to another script
			if (!getJWT()) {
				// no web token, login required
				this.openDialog();	
			}
		}
	}
</script>

