<template>
</template>
<script>

export default{
	name: 'loginout',
	components: {},
	methods: {
		delCookie () {
			var exp = new Date()
			exp.setTime(exp.getTime() - 1)
			var cval = this.getCookie('accesstokey')
			if (cval != null) {
				// document.cookie = 'accesstokey=' + cval + ';expires=' + exp.toGMTString()
				document.cookie = 'accesstokey=' + cval + ';expires=' + exp.toGMTString() + ';domain=gamefy.cn; path=/'
			}
		},
		getCookie () {
			let reg = new RegExp('(^| )accesstokey=([^;]*)(;|$)')
			let arr = document.cookie.match(reg)
			if (Array.isArray(arr) && arr.length >= 2) {
				return unescape(arr[2])
			}
			return null
		}
	},
	beforeMount () {
		this.$store.state.curState = -1
		this.$store.state.user = {}
		localStorage.removeItem('token')
		this.$store.state.isShow_signout = false
		this.delCookie()
		this.$router.push({path: './index'})
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';

.center-button{
	width:95%;
	margin-left: 5%;
	margin-top: 10px;
}

</style>
