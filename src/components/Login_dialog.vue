<template>
	<div class="logindialog" :class="{'dialog-show':isShow}" >
		<div class="dialog-wrapper">
			<div class="dialog-header">
				<h5>提示</h5>
			</div>
			<div class="dialog-content">
				所选择的内容登录才能查看，请登录
			</div>
			<div class="dialog-btn" @click="sureEvent">
				<div class="sure_btn" >确定</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'logindialog',
		computed: {
			isShow () {
				return this.$store.state.isShow
			}
		},
		methods: {
			sureEvent () {
				this.$store.dispatch('logintip')
			}
		}
	}
</script>

<style>
	.logindialog {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: auto;
        background: rgba(0, 0, 0, .4);
        z-index: -1;
        opacity: 0;
        transition: all .3s linear;
    }
    .dialog-show {
        opacity: 1;
        z-index: 1000;
    }
    .dialog-wrapper {
        position: absolute;
        left: 20%;
        top: 38%;
        width:60%;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .3);
    }
    .dialog-header {
        padding: 0.2rem 0.2rem 0;
        height:0.5rem;
    }
    .dialog-header h5{
    	font-size:0.3rem;
    	text-align:center;
    }
    .dialog-content {
    	padding:0.2rem 0.2rem 0.2rem;
        color: #475669;
        font-size: 0.2rem;
    }
    .dialog-btn {
        padding:0rem 0.2rem 0;
        text-align: center;
        height:0.5rem;
        border-top:1px solid #DFDFDF;
    }
    .dialog-btn .sure_btn {
    	padding: 0.1rem 0.1rem;
	    font-size: 0.3rem;
	    border-radius: 0.05rem;
	    border: none;
	    color: #ff8400;
	    float: left;
	    font-weight: bold;
	    margin-left: 39%;
    }
</style>
