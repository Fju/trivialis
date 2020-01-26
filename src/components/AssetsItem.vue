<template>
	<tr :class="{ 'row-pending': item.state === 'uploading' }">
		<td class="text-center small-col">
			<!-- icon column -->
			<fa :icon="icon" size="lg"></fa>
		</td>
		<td v-if="item.state === 'rename'">
			<input class="form-control" type="text" v-model="rename"
				v-on:keydown.enter="onRenameSubmit" v-on:keydown.esc="onRenameCancel"
				v-on:blur="onRenameCancel" />
		</td>
		<td v-else>
			<div v-if="item.state === 'uploading'" class="spinner-border spinner-border-sm mr-2" role="status"></div>
			<!-- name -->
			<a v-if="item.type === 'dir'" href="" v-on:click.prevent="onNameClick">{{ item.name }}</a>
			<span v-else>{{ item.name }}</span>
		</td>
		<td>{{ size }}</td>
		<td class="small-col text-right" v-if="item.state === 'rename' || item.state === 'uploading'">
			<button class="btn btn-danger btn-sm" v-on:click="onCancelClick"><fa icon="times"></fa> Cancel</button>
		</td>
		<td class="small-col text-right" v-else>
			<button class="btn btn-primary btn-sm" v-on:click="onRenameClick"><fa icon="edit"></fa> Rename</button>
			<button class="btn btn-danger btn-sm" v-on:click="onDeleteClick"><fa icon="trash-alt"></fa> Delete</button>
		</td>
	</tr>
</template>
<script>
	import $ from 'jquery';

	export default {
		data () {
			return {
				rename: ''
			}
		},
		props: [ 'item' ],
		computed: {
			icon () {
				if (this.item.type === 'dir') return 'folder';
				else if (this.item.type === 'file') return 'file';
				return '';
			},
			size () {
				if (this.item.size === undefined) return '-';

				var i = 0;
				var size = this.item.size;
				while (size > 1000) {
					size /= 1024;
					i++;
				}

				var suffix = 'B';
				switch(i) {
					case 1: suffix = 'KB'; break;
					case 2: suffix = 'MB'; break;
					case 3: suffix = 'GB'; break;
					case 4: suffix = 'TB'; break;
				}
			
				if (i === 0) return Math.round(size) + ' ' + suffix;
				else return size.toFixed(1) + ' ' + suffix;
			}
		},
		methods: {
			onNameClick () {
				this.$emit('navigate', this.item.name);	
			},
			onRenameClick () {
				this.rename = this.item.name;
				this.item.state = 'rename';

				// input can only be focused in the next tick
				this.$nextTick((function() {
					$('input', this.$el).focus();
				}).bind(this));
			},
			onRenameSubmit () {
				this.$emit('rename', this.item, this.rename);
			},
			onRenameCancel () {
				this.item.state = 'ok';
			},
			onDeleteClick () {
				this.$emit('delete', this.item.name);
			},
			onCancelClick () {
				this.item.state = 'ok';	
				if (this.item.state === 'uploading') {
					// TODO: cancel upload transaction
				}
			}
		}
	}
</script>
