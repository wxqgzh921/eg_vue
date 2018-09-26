<template>
	<div class="login">
		<mu-row>
    	<mu-col width="100" tablet="100" class="right_link"><router-link to="/index">去首页&nbsp;>&nbsp;</router-link></mu-col>
  	</mu-row>
		<mu-row>
    	<mu-col width="100" tablet="100" class="logoface" ><img src="../assets/images/logoface.png"></mu-col>
  	</mu-row>
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
							<mu-col width="50" tablet="50"  class="align_center" ></mu-col>
							<mu-col width="50" tablet="50"  class="align_center"><router-link  to="/lookingforpwd">忘记密码?</router-link></mu-col>
						</mu-row>
					</form>
				</mu-col>
				<mu-col width="5" tablet="5"  ></mu-col>
			</mu-row>
			<mu-row class="bottomLogin" >
				<div class="login">
					<div class="button"><a :href="qqlogin"><img src="../assets/icon/qq_login.png"></a></div>
					还没有账号？<router-link  to="/regist">立即注册</router-link>
				</div>
			</mu-row>
		</div>
		<toast></toast>
	</div>
</template>
<script>
import Logindialog from '../components/Login_dialog'
import toast from '../components/Toast'
import Headbar from '../components/Headbar'
// import qs from 'qs'
// import state from '../store/state'

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
			redirect: this.$store.state.back_path
		}
	},
	computed: {
		qqlogin () {
			if (this.$store.state.isAndroid) {
				return 'javascript:downloadit.loginQQ(\'' + this.$store.state.back_path + '\')'
			} else {
				return 'http://member.gamefy.cn/api/qq/login.php?pre_url=' + encodeURIComponent('/#' + this.$store.state.back_path) + '&from=10'
			}
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
			if (this.checkPhone() && this.checkPwd()) {
				this.$store.dispatch('user_signin', this.$data)
			}
		}
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';

.bottomLogin {
	position: absolute;
	bottom: 20px;
	width: 100%;
}
.bottomLogin div.login  {
	clear: both;
	display: block;
	margin: 0 auto;
}

.bottomLogin div.login img{
	position: relative;
	margin: 0 auto;
	width: 48px;
}

.bottomLogin div.button {
	width: 48px;
	position: relative;
	margin: 0 auto;
}
</style>
