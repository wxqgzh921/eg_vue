<template>
	<div class="changyan">
		<changyanBox ></changyanBox>
		<changyanScript ></changyanScript>
	</div>
</template>
<script>

export default{
	components: {
		'changyanScript': {
			render (createElement) {
				return createElement(
					'script',
					{
						attrs: {
							type: 'text/javascript',
							id: 'changyan_mobile_js',
							charset: 'utf-8',
							src: 'https://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=cysme1fwA&conf=prod_5d2545dfe07426a54f836f252d5bab22'
						}
					}
				)
			}
		},
		'changyanBox': {
			render (createElement) {
				return createElement(
					'div',
					{
						attrs: {
							id: 'SOHUCS',
							sid: this.$route.params.sid,
							style: 'font-size:10px'
						}
					}
				)
			}
		}
	},
	created () {
		// console.log(document.querySelector('#SOHU_MAIN'))
	},
	computed: {},
	methods: {}
}
</script>
<style>
@import '../css/login-regist.css';
div#SOHUCS  div.dialog-wrapper div.dialog-unknown{
	top:200px;
}
body{
	font-size: 10pt;
}
div#SOHU_MAIN {
	font-size: 10px;
}
#SOHUCS #SOHU_MAIN .user-center-log-out div.user-center-log-out-center {
	top: 5rem;
	bottom:auto;
}
#SOHUCS #SOHU_MAIN .module-login-box span.login-state span{
	font-size: 1.5rem;
}
#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .find-password span, #SOHUCS #SOHU_MAIN .module-login-box div.login-by-mobile input{
	font-size: 1.5rem;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .input-wrap{
	height: 3rem;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .password-wrap .input-wrap{
	height: 3rem;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .find-password, #SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .login-button, #SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile input
{
	height: 3rem;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .find-password span, #SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile input{
	font-size: 1.5rem;
}
div#SOHUCS  div.dialog-wrapper div.dialog-unknown {
	top:9em;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .find-password{
	line-height: 3rem;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-mobile .login-button{
	line-height: 2.5em;
	margin-top: 0.5em;
	height: 2.6em;
	font-size: 0.8em;
	width: 100%;
}
div#SOHUCS #SOHU_MAIN .module-login-box .login-by-other .login-group{
	height: 3em;
	margin-top: 0.4em;
}
</style>
