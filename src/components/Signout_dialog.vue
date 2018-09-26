<template>
	<div class="signoutdialog">
		<mu-dialog :open="isShow_signout" title="退出登录" @close="cancelEvent">
			亲，确定要退出吗？  (＞﹏＜)
			<mu-flat-button slot="actions" @click="cancelEvent" primary label="取消"/>
			<mu-flat-button slot="actions" primary @click="sureEvent" label="确定"/>
		</mu-dialog>
	</div>
</template>
<script>
	export default{
		name: 'signoutdialog',
		computed: {
			isShow_signout () {
				return this.$store.state.isShow_signout
			}
		},
		methods: {
			cancelEvent () {
				this.$store.dispatch('cancel_signout')
			},
			sureEvent () {
				this.$store.dispatch('user_signout')
			}
		}
	}
</script>

<style>
	
</style>
