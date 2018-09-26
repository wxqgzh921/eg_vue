<template>
	<div class="tools" :class="{'show-tools':Showtools}">
		<ul class="tools-sidebar">
			<li><router-link to="/user"><img src="../assets/images/user.png" class="user" /></router-link></li>
			<li><router-link to="/live">直播节目单</router-link></li>
		</ul>
	</div>
</template>
<script>
	export default{
		name: 'tools',
		data () {
			return {}
		},
		props: ['Showtools']
	}
</script>
<style>
	.tools{
        position: fixed;
        top:0.5rem;
        left:0;
        bottom:0;
        width:5rem;
        padding:30px 20px;
        box-sizing: border-box;
        transform: translateX(-5rem);
        transition: transform .3s;
        text-align: center;
        background: rgba(0,0,0,.5);
        font-size: 16px;
				opacity: 0;
    }
    .show-tools{
				opacity: 1;
        transform: translateX(0);
    }
    .tools-sidebar li{
        line-height: 1rem;
    }
    .tools-sidebar li a{
    	text-decoration: none;
        font-weight: bold;
        color:#fff;
        font-size: 0.25rem;
    }
</style>
