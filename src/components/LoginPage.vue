<template>
	<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="exampleModalLabel">Login</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="login-form" class="row">
						<div class="form-group col-6">
							<label for="login-username">Username:</label>
							<input type="text" name="username" id="login-username" class="form-control" placeholder="Username" />
						</div>
						<div class="form-group col-6">
							<label for="login-password">Password:</label>
							<input type="password" name="password" id="login-password" class="form-control"/>
						</div>
					</form>
					<div class="col-12 mt-4">
						<div class="alert alert-danger" :class="{ 'd-none': !error }" role="alert">
							{{ error_message }}
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="login-submit" v-on:click="onSubmit">Submit</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import $ from 'jquery';

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
						console.log(data);
					}
				}).bind(this));
			}
		},
		mounted () {
			$('#login-modal').modal({
				backdrop: 'static',
				keyboard: false
			}).modal('show');
		}
	}
</script>

