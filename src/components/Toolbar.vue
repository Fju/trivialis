<template>
	<div class="toolbar">
		<div class="row">
			<div class="col-auto">Current page:</div>
			<div class="col-auto">
				<ol class="breadcrumb">
					<li class="breadcrumb-item" v-for="item in items">{{ item }}</li>
				</ol>
			</div>
			<div class="col"></div>
			<div class="col-auto">
				<div class="dropdown">
					<a class="text-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ name }}</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item" href="#" v-on:click="onLogoutClick">Logout</a>
					</div>
				</div>	
			</div>
		</div>
	</div>
</template>
<script>
	import { events } from '../js/globals.js';
	import { getJWTBody, removeJWT } from '../js/storage.js';

	export default {
		data () {
			return {
				name: '',
				items: []
			}
		},
		methods: {
			updateBreadcrumbs () {
				if (typeof this.$route.name	=== 'string') {
					this.items = this.$route.name.split('/');
				}
			},
			onLoginSuccessful () {
				var info = getJWTBody();
				if (info.usr) this.name = info.usr;	
			},
			onLogoutClick () {
				removeJWT();
			}
		},
		watch: {
			$route () {
				this.updateBreadcrumbs();
			}
		},
		mounted () {
			this.updateBreadcrumbs();
			events.$on('login-successful', this.onLoginSuccessful);	

			this.onLoginSuccessful();
		}
	}
</script>
