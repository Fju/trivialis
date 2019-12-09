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
					<a class="text-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item" href="#">Logout</a>
					</div>
				</div>	
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				items: []
			}
		},
		methods: {
			updateBreadcrumbs () {
				if (typeof this.$route.name	=== 'string') {
					this.items = this.$route.name.split('/');
				}
			}
		},
		watch: {
			$route () {
				this.updateBreadcrumbs();
			}
		},
		mounted () {
			this.updateBreadcrumbs();
		}
	}
</script>
