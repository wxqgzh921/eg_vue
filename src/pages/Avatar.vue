<template>
	<div class="user">
		<mu-row>
    	<mu-col width="100" tablet="100" class="right_link"><router-link to="/user">用户中心&nbsp;>&nbsp;</router-link></mu-col>
  	</mu-row>
    <div class="main">
			<img :src="src" class="prview">
			<div class="uploadBox">
				<span class="btn">上传头像</span>
				<input style="opacity:0" name="file" type="file" accept="image/png,image/gif,image/jpeg" @change="update"/>
			</div>
		</div>
		<div :style="'position: absolute;top: 50%;padding-left: 50%;left: -30px;display:' + showProgress">
			<mu-circular-progress :size="60" color="red" :strokeWidth="10"/>
		</div>
		<toast></toast>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import toast from '../components/Toast'
import qs from 'qs'

function generateUUID () {
	let d = new Date().getTime()
	let uuid = 'xxxxxxxxxxxxfxxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
		let r = (d + Math.random() * 16) % 16 | 0
		d = Math.floor(d / 16)
		return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16)
	})
	return uuid
}

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
	name: 'user',
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
		let that = this
		if (this.$store.state.user.acessToken === undefined) {
			this.$router.push('/login')
			return {
				src: '',
				message: '',
				topPopup: false,
				showProgress: 'none'
			}
		} else {
			// 取得上传token
			this.axios({
				method: 'GET',
				url: 'https://tv.gamefy.cn/api/get_key.php'
			})
			.then(function (response) {
				that.$data.uploadInfo.token = response.data
			})
			.catch(function (err) {
				console.log(err)
			})
			return {
				src: this.$store.state.user.avatar.replace('imageView2/5/w/80/h/80/format/jpg/q/75|imageslim', 'imageView2/5/w/100/h/100/format/jpg/q/75|imageslim'),
				message: '',
				topPopup: false,
				showProgress: 'none',
				uploadInfo: {
					key: localStorage.getItem('token'),
					token: ''
				}
			}
		}
	},
	methods: {
		update (e) {
			let that = this
			let file = e.target.files[0]
			// 检查上传文件大小
			if (file.size > 1048576) {
				showWarning(that, '头像不能大于1M!')
			} else {
				// 上传图片
				let key = generateUUID()
				let param = new FormData()
				param.append('file', file, file.name)
				param.append('key', key)
				param.append('token', this.$data.uploadInfo.token)
				let config = {
					headers: {'Content-Type': 'multipart/form-data'}
				}
				that.$data.showProgress = 'block'
				that.axios.post('http://upload.qiniu.com/', param, config)
				.then(response => {
					if (response.data && response.data.key === key) {
						// 对图片进行鉴黄
						that.axios({
							method: 'GET',
							url: 'http://avatar.gamefy.cn/' + response.data.key + '?nrop'
						})
						.then(function (response) {
							if (response.data.code === 0 && response.data.fileList[0].label !== 0) {
								// 更新数据库中头像
								that.axios({
									method: 'POST',
									url: 'https://member.gamefy.cn/api/update_avatar.php',
									data: qs.stringify({k: localStorage.getItem('token'), a: key}),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								})
								.then(function (response) {
									that.$store.dispatch('user')
									that.$router.push('/user')
								})
								.catch(function (err) {
									showWarning(that, '头像更新失败<br/>程序猿已经被吊起来了！')
									console.log(err)
								})
							} else {
								showWarning(that, '头像没有通过审核<br/>请不要坑我们！')
							}
						})
						.catch(function (err) {
							showWarning(that, '头像没有通过审核<br/>请不要坑我们！')
							console.log(err)
						})
					} else {
						showWarning(that, '头像上传失败!')
					}
				})
			}
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';

.main {
	padding-top: 40px;
}
.btn{
	width: 100%;
  position: absolute;
  display: block;
  margin: 0 auto;
  text-align: center;
  background: #f4511e;
  color: #fff;
  line-height: 30px;
  border-radius: 4px;
}
.prview{
	width: 100px;
	height: auto;
	display: block;
	position: relative;
	margin: 0 auto;
	border-radius: 50%;
	border: 1px solid #ddd;
}
.uploadBox {
	position: relative;
	width: 60%;
	margin: 0 auto;
	padding-top: 20px;
}
.showProgress{
	display: block;
}
</style>
