<template>
	<div class="changePwd">
		<mu-row>
    	<mu-col width="100" tablet="100" class="right_link"><router-link to="/user">用户中心&nbsp;>&nbsp;</router-link></mu-col>
  	</mu-row>
		<mu-row>
    	<mu-col width="100" tablet="100" class="logoface" ><img src="../assets/images/logoface.png"></mu-col>
  	</mu-row>
    <div class="main">
			<mu-row >
				<mu-col width="5" tablet="5"   ></mu-col>
				<mu-col width="90" tablet="90"  >
		    	<form>
						<mu-text-field type="password" label="" hintText="请输入旧密码" :fullWidth="true" v-model="oldPwd" auto-complete="off"  :errorText="oldPwdError" @change="checkOldPassword" />
						<mu-text-field type="password" label="" hintText="请输入新密码" :fullWidth="true" v-model="newPwd" auto-complete="off"   :errorText="newError" />
						<mu-text-field type="password" label="" hintText="请再一次输入新密码" :fullWidth=true v-model="confirmPwd" auto-complete="off"  :errorText="confimError" @change="checkNewPassword" />
						<div class="submitButton" >
							<mu-raised-button class="demo-raised-button center-button"  label="更新密码" @click="submitForm()"  fullWidth primary/>
						</div>
					</form>
				</mu-col>
				<mu-col width="5" tablet="5"  ></mu-col>
			</mu-row>
    </div>
    <toast></toast>
	</div>
</template>
<script>
import toast from '../components/Toast'
// import qs from 'qs'
export default{
	name: 'changePwd',
	components: {
		toast
	},
	beforeMount () {
		let token = localStorage.getItem('token')
		if (token === undefined || token.length === 0) {
			this.$router.push('/login')
		}
	},
	data () {
		return {
			oldPwd: '',
			newPwd: '',
			confirmPwd: '',
			confimError: '',
			oldPwdError: '',
			newError: ''
		}
	},
	methods: {
		showError (message) {
			let state = this.$store.state
			state.toastShow = true
			state.toastMessge = message
			setTimeout(function () {
				state.toastShow = false
				state = null
			}, 1500)
		},
		checkOldPassword () {
			if (this.$data.oldPwd.length < 6) {
				this.$data.oldPwdError = '旧密码不正确,密码长度至少6位'
				return false
			} else {
				this.$data.oldPwdError = ''
				return true
			}
		},
		checkNewPassword () {
			if (this.$data.newPwd.length < 6) {
				this.$data.newError = '密码长度至少6位'
				return false
			} else if (this.$data.newPwd.length >= 6 && this.$data.oldPwd.length >= 6 && this.$data.newPwd === this.$data.oldPwd) {
				this.$data.confimError = '新密码不能和旧密码不一致'
				return false
			} else {
				this.$data.newError = ''
				return true
			}
		},
		checkConfirmPassword () {
			let newPassword = this.$data.newPwd
			let confirmPassword = this.$data.confirmPwd
			if (confirmPassword.length < 6) {
				this.$data.confimError = '确认密码长度不正确'
				return false
			} else if (confirmPassword.length >= 6 && newPassword.length >= 6 && confirmPassword !== newPassword) {
				this.$data.confimError = '两次输入密码不一致'
				return false
			}
			this.$data.confimError = ''
			return true
		},
		submitForm (formName) {
			let result = true
			result = this.checkOldPassword() && result
			result = this.checkNewPassword() && result
			result = this.checkConfirmPassword() && result
			if (result) {
				// console.log(this.$data)
				this.$store.dispatch('updatepwd', this.$data)
			}
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';
</style>
