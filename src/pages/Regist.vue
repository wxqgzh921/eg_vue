<template>
	<div class="regist">
		<mu-row>
    	<mu-col width="100" tablet="100" class="right_link"><router-link to="/login">返回登录&nbsp;>&nbsp;</router-link></mu-col>
  	</mu-row>
		<mu-row>
    	<mu-col width="100" tablet="100" class="logoface" ><img src="../assets/images/logoface.png"></mu-col>
  	</mu-row>
		<div class="main">
			<mu-row >
				<mu-col width="5" tablet="5"   ></mu-col>
				<mu-col width="90" tablet="90"  >
				<form>
					<mu-text-field type="number" hintText="输入手机号" v-model="phone" auto-complete="off"  fullWidth :errorText="phone_error" @change="checkPhone" />
					<mu-row gutter>
					<mu-col width="55" tablet="80" ><mu-text-field type="number" hintText="输入验证码" v-model="code" auto-complete="off" :errorText="code_error"  fullWidth/></mu-col>
					<mu-col width="45" tablet="20"  ><mu-flat-button backgroundColor="#fff" color="#616161"  class="verifybutton" :disabled="disabled"  @click="sendCode()" :label="verifyText"  /></mu-col>
					</mu-row>
					<mu-text-field hintText="输入密码(至少6位)" v-model="pwd" auto-complete="off" type="password" fullWidth :errorText="pwd_error" @change="checkPwd"  />
					<div class="submitButton" ><mu-raised-button class="demo-raised-button" @click="submitForm()" label="注 册" fullWidth primary/></div>
					<mu-row class="top_space" >
						<mu-col width="65" tablet="65" ></mu-col>
						<mu-col width="30" tablet="30" ><router-link class="looking-pwd" to="/login">立即登录</router-link></mu-col>
						<mu-col width="5" tablet="5"  ></mu-col>
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
import qs from 'qs'
import toast from '../components/Toast'
// import state from '../store/state'

export default{
	name: 'regist',
	components: {toast},
	data () {
		return {
			phone: '',
			code: '',
			pwd: '',
			time: 0,
			disabled: false,
			phone_error: '',
			pwd_error: '',
			code_error: ''
		}
	},
	computed: {
		verifyText: function () {
			return this.time > 0 ? '重新获取 ' + this.time + 's' : '获取验证码'
		}
	},
	methods: {
		checkPhone () {
			let value = this.$data.phone
			const phoneReg = /^1[34578]{1}\d{9}$/
			if (value < 11) {
				this.$data.phone_error = '请输入手机号'
				return false
			}
			if (phoneReg.test(value) === false) {
				this.$data.phone_error = '该手机号码不合法'
				return false
			}
			this.$data.phone_error = ''
			return true
		},
		checkPwd () {
			let value = this.$data.pwd
			if (value < 6) {
				this.$data.pwd_error = '请输入至少6位密码'
				return false
			} else {
				this.$data.pwd_error = ''
			}
			return true
		},
		checkVerify () {
			let value = this.$data.code
			if (value.length !== 4) {
				this.$data.code_error = '请输入4位验证码'
				return false
			} else {
				this.$data.code_error = ''
				return true
			}
		},
		getToken (callback) {
			this.axios({
				method: 'POST',
				url: 'https://member.gamefy.cn/api/getToken.php',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				data: qs.stringify({
					phone: this.$data.phone
				})
			})
			.then((response) => {
				if (response && response.data) {
					callback(response.data.token, this.$data.phone)
				}
			})
			.catch((error) => {
				console.log(error)
				this.code_error = '验证码发送失败'
				setTimeout(this.sended, 1000)
			})
		},
		sendCode () {
			if (this.$data.disabled) return
			if (this.checkPhone()) {
				setTimeout(this.sended, 1000)
				this.getToken((token, phone) => {
					this.axios({
						method: 'post',
						url: 'https://member.gamefy.cn/api/checkVerifycation.php',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						data: qs.stringify({
							phone,
							token
						})
					})
					.then((response) => {
					})
					.catch((error) => {
						console.log(error)
						this.code_error = '验证码发送失败'
						setTimeout(this.sended, 1000)
					})
				})
			}
		},
		timer () {
			if (this.$data.time > 0) {
				this.$data.time--
				setTimeout(this.timer, 1000)
			} else {
				this.$data.disabled = false
			}
		},
		sended () {
			this.$data.disabled = true
			this.$data.time = 60
			this.timer()
		},
		submitForm (formName) {
			if (this.checkPhone() && this.checkVerify() && this.checkPwd()) {
				this.$store.dispatch('regist', this.$data)
			} else {
				return false
			}
		}
	}
}
</script>
<style >
@import '../css/login-regist.css';
.verifybutton{
	border: 1px solid #bdbdbd;
	border-radius: 5px;
	height: 30px;
	line-height: 30px;
	margin-top: 6px;
	float: right;
	width: 95%;
}
.verifybutton .mu-flat-button-label{
	font-size: 8pt;
	padding: 5px 10px;
	width: 100%;
}

</style>
