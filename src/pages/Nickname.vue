<template>
	<div class="user">
		<mu-row>
    	<mu-col width="100" tablet="100" class="right_link"><router-link to="/user">用户中心&nbsp;>&nbsp;</router-link></mu-col>
  	</mu-row>
    <div class="main">
			<img src="../assets/images/edit.png" class="prview">
			<mu-text-field hintText="请输入新昵称" fullWidth  v-model="uploadInfo.nick" />
			<mu-raised-button label="保存修改" class="uploadBox" backgroundColor="#f4511e" @click="update"/>
		</div>
		<toast></toast>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import toast from '../components/Toast'
import qs from 'qs'

function showWarning (that, message) {
	let state = that.$store.state
	state.toastShow = true
	state.toastMessge = message
	setTimeout(function () {
		state.toastShow = false
	}, 5000)
	that.$data.showProgress = 'none'
}

export default{
	name: 'nick',
	beforeMount () {
		// 检查当前用户是否已经登录
		let token = localStorage.getItem('token')
		if (token === null || token === undefined || token.length === 0) {
			this.$router.push('/login')
		}
	},
	components: {
		Headbar,
		toast
	},
	data () {
		return {
			message: '',
			topPopup: false,
			uploadInfo: {
				key: localStorage.getItem('token'),
				nick: ''
			}
		}
	},
	methods: {
		update (e) {
			let that = this
			let nickname = this.$data.uploadInfo.nick
			if (nickname.length === 0) {
				let state = that.$store.state
				state.toastShow = true
				state.toastMessge = '昵称不能为空'
				setTimeout(function () {
					state.toastShow = false
				}, 2000)
			} else {
				// 更新数据库中昵称
				that.axios({
					method: 'POST',
					url: 'https://member.gamefy.cn/api/update_nick.php',
					data: qs.stringify({k: localStorage.getItem('token'), n: nickname}),
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
				.then(function (response) {
					if (response.data.result === 0) {
						showWarning(that, '用户不存在')
					} else if (response.data.result === -1) {
						showWarning(that, '您来晚了<br>昵称已经被占用了')
					} else {
						that.$store.dispatch('user')
						that.$router.push('/user')
					}
				})
				.catch(function (err) {
					showWarning(that, '昵称更新失败<br/>程序猿已经被吊起来了！')
					console.log(err)
				})
			}
		}
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';
.prview{
	width: 70px;
	height: auto;
	display: block;
	position: relative;
	margin: 20px auto;
}
.note{
	font-size: 10px;
	line-height: 30px;
	height: 30px;
	color:#f4511e;
	position: relative;
	top: -10px;
}
h1.head{
	color: #f4511e;
	text-align: center;
	font-size: 20px;
}
.main {
	padding-top: 20px;
	width: 80%;
	margin: 0 auto;
}
.uploadBox {
	position: relative;
	width: 60%;
	margin: 0 auto;
	border-radius: 4px;
  display: block;
	margin-top: 10px;
}
.showProgress{
	display: block;
}
</style>
