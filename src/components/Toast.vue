<template>
	<transition name="expend">
		<div class="toast" :class="{'toast-wrapper':toastShow}" >
			<div class="toast-content" v-html="message"></div>
		</div>
	</transition>
</template>
<script>
	export default{
		name: 'toast',
		computed: {
			toastShow () {
				return this.$store.state.toastShow
			},
			message () {
				return this.$store.state.toastMessge
			}
		}
	}
</script>

<style>
	.expend-enter-active,.expend-leave-active{transition: all .5s ease;}
	.expend-enter,.expend-leave-active{ opacity: 0;}
    .toast-wrapper {
        position: absolute;
        left: 15%;
        top: 38%;
        width:70%;
        background: rgba(0, 0, 0, .6);
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
    }
    .toast-content {
    	padding: 10px;
      color: #fff;
			line-height: 30px;
			font-size: 14px;
      text-align: center;
    }
</style>
