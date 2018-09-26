<template>
	<div class="login">
		<div class="main">
			<mu-row >
				<mu-col width="5" tablet="5"   ></mu-col>
				<mu-col width="90" tablet="90"  >
					<form>
						<mu-text-field type="number" hintText="请输入手机号" v-model="phone" auto-complete="off"  fullWidth :errorText="phone_error" @change="checkPhone" />
						<mu-text-field hintText="请输入密码(至少6位)" v-model="pwd" auto-complete="off" type="password" fullWidth :errorText="pwd_error" @change="checkPwd"  />
						<div class="submitButton" >
							<mu-raised-button class="demo-raised-button"  @click="submitForm()" label="登  录" fullWidth primary/>
						</div>
						<mu-row class="top_space" >
							<mu-col width="50" tablet="50"  class="align_center" ><router-link  to="/regist">注册账号</router-link></mu-col>
							<mu-col width="50" tablet="50"  class="align_center"><router-link  to="/lookingforpwd">忘记密码?</router-link></mu-col>
						</mu-row>
					</form>
				</mu-col>
				<mu-col width="5" tablet="5"  ></mu-col>
			</mu-row>
		</div>
		<toast></toast>
	</div>
</template>
<script>
import Logindialog from '../components/Login_dialog'
import toast from '../components/Toast'
import Headbar from '../components/Headbar'
import axios from 'axios'
import qs from 'qs'
// import qs from 'qs'
import state from '../store/state'

export default{
	name: 'login',
	components: {Logindialog, toast, Headbar},
	data () {
		let state = this.$store.state
		setTimeout(function () {
			state.toastShow = false
			state.toastMessge = ''
		}, 2000)
		return {
			phone: '',
			pwd: '',
			phone_error: '',
			pwd_error: '',
			redirect: this.$route.query.redirect
		}
	},
	methods: {
		checkPhone () {
			let value = this.$data.phone
			const phoneReg = /^1[34578]{1}\d{9}$/
			let result = true
			if (value < 11) {
				this.$data.phone_error = '请输入手机号'
				result = false
			}
			if (result && phoneReg.test(value) === false) {
				this.$data.phone_error = '该手机号码不合法'
				result = false
			}
			if (result) this.$data.phone_error = ''
			return result
		},
		checkPwd () {
			let value = this.$data.pwd
			if (value < 6) {
				this.$data.pwd_error = '请输入至少6位密码'
				return false
			} else {
				this.$data.pwd_error = ''
				return true
			}
		},

		submitForm () {
			let that = this
			if (this.checkPhone() && this.checkPwd()) {
				axios({
					method: 'POST',
					url: 'https://member.gamefy.cn/api/login.php',
					data: qs.stringify({phone: this.$data.phone, password: this.$data.pwd}),
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
				.then(function (response) {
					if (response.data.status === 0) {
						state.curState = 1
						localStorage.setItem('token', response.data.acessToken)
						localStorage.setItem('timemark', Date.now())
						document.cookie = 'accesstokey=' + response.data.acessToken + '; domain=gamefy.cn; path=/'
						if (localStorage.getItem('token') != null) {
							setTimeout(function () {
								if (that.$store.state.back_path) {
									window.top.location = 'http://m.gamefy.cn/#' + that.$store.state.back_path
									// that.$router.push({path: that.$store.state.back_path})
								} else {
									window.top.location.reload()
								}
								// console.log(window)
								// console.log(window.self === window.top)
								// console.log(window.top.location)
								// window.top.location.reload()
							}, 1000)
						}
					} else {
						state.toastShow = true
						setTimeout(function () {
							state.toastShow = false
						}, 2000)
						state.toastMessge = response.data.reason
					}
				})
				.catch(function (error) {
					console.log(error)
				})
			}
		}
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';
.main{
	padding-top: 20px;
}
</style>
