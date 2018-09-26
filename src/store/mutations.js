import * as types from './mutations_types'
import axios from 'axios'
import qs from 'qs'
import VueRouter from 'vue-router'
import routes from '../router/index'

const router = new VueRouter({
	routes
})
// 获取到当前时间(年、月、日)
function getNowFormatDate () {
	var date = new Date()
	var month = date.getMonth() + 1
	var strDate = date.getDate()
	if (month >= 1 && month <= 9) {
		month = '0' + month
	} else {
		month = month.toString()
	}
	if (strDate >= 0 && strDate <= 9) {
		strDate = '0' + strDate
	} else {
		strDate = strDate.toString()
	}
	var currentdate = date.getFullYear() + month + strDate
	return currentdate
}
let date = getNowFormatDate()
// 获取到当前时间(时、分、秒)
// function getHour () {
// 	var hour = new Date()
// 	var hourtime = hour.getHours()
// 	var second = hour.getSeconds()
// 	var connect = ':'
// 	if (hourtime >= 0 && hourtime <= 9) {
// 		hourtime = '0' + hourtime
// 	}
// 	if (second >= 0 && second <= 9) {
// 		second = '0' + second
// 	}
// 	var currenthour = hourtime + connect + second
// 	return currenthour
// }

// 将获取到begin_stamp 装换成00:00格式
function formatTimeInt (int) {
	int = int * 1000
	let date = new Date()
	if (date.toString() === 'Invalid Date') return new Date()
	date.setTime(int)
	return (date.getHours() < 10 ? '0' + date.getHours() : date.getHours())	+
	':'	+
	(date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes())
}

export default{
	// 登录提示
	[types.LOGINTIP] (state) {
		if (state.curState === 1) {
			state.isShow = false
			return state.isShow
		} else {
			state.isShow = false
		}
	},
	// 登录
	[types.USER_SIGNIN] (state, user) {
		axios({
			method: 'POST',
			url: 'https://member.gamefy.cn/api/login.php',
			data: qs.stringify({phone: user.phone, password: user.pwd}),
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function (response) {
			if (response.data.status === 0) {
				state.curState = 1
				localStorage.setItem('token', response.data.acessToken)
				localStorage.setItem('timemark', Date.now())
				// save cookie in current domain
				// document.cookie = 'accesstokey=' + response.data.acessToken + '; path=/ ;'
				// save cookie in top domain
				document.cookie = 'accesstokey=' + response.data.acessToken + '; domain=gamefy.cn; path=/'
				state.toastShow = true
				setTimeout(function () {
					state.toastShow = false
				}, 2000)
				state.toastMessge = '登录成功'
				if (localStorage.getItem('token') != null) {
					setTimeout(function () {
						if (user.redirect !== undefined && user.redirect.length > 0) {
							router.push({path: user.redirect})
						} else {
							router.push({path: './user'})
						}
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
	},
	// 退出登录提示
	[types.SIGN_BTN] (state) {
		state.isShow_signout = true
		return state.isShow_signout
	},
	// 退出登录
	[types.USER_SIGNOUT] (state) {
		state.curState = -1
		state.user = {}
		localStorage.removeItem('token')
		state.isShow_signout = false
		router.push({path: './index'})
	},
	// 取消登录
	[types.CANCEL_SIGNOUT] (state) {
		state.isShow_signout = false
	},
	// 注册
	[types.REGIST] (state, obj) {
		// do reg
		axios({
			method: 'POST',
			url: 'https://member.gamefy.cn/api/register.php?from=' + (state.isAndroid ? '0' : '10'),
			data: qs.stringify({
				phone: obj.phone,
				password: obj.pwd,
				verification: obj.code
			}),
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function (response) {
			if (response.data.status === 0) {
				state.toastShow = true
				state.toastMessge = '注册成功，请登录！'
				setTimeout(function () {
					state.toastShow = false
					state.toastMessge = ''
					router.push({path: './login'})
				}, 2000)
			} else {
				state.toastShow = true
				state.toastMessge = response.data.reason
				setTimeout(function () {
					state.toastShow = false
				}, 2000)
			}
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 修改密码
	[types.UPDATEPWD] (state, obj) {
		axios({
			method: 'POST',
			url: 'https://member.gamefy.cn/api/updatepass.php',
			data: qs.stringify({
				old: obj.oldPwd,
				new: obj.newPwd,
				access: localStorage.getItem('token')
			}),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.then(function (response) {
			if (response.data.result === 0) {
				state.toastShow = true
				state.toastMessge = '修改密码成功,请重新登录'
				setTimeout(function () {
					state.token = null
					state.user = {}
					localStorage.removeItem('token')
					localStorage.removeItem('user')
					state.toastShow = false
					router.push({path: './login'})
				}, 1000)
			}
			if (response.data.result === 1) {
				state.toastShow = true
				state.toastMessge = '旧密码不正确'
				setTimeout(function () {
					state.toastShow = false
				}, 2000)
			}
			if (response.data.result === 2) {
				state.toastShow = true
				state.toastMessge = '没有该用户，请先注册'
				setTimeout(function () {
					state.toastShow = false
					router.push({path: './regist'})
				}, 2000)
			}
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 忘记密码
	[types.FORGOT] (state, obj) {
		console.log(obj)
		axios({
			method: 'POST',
			url: 'https://member.gamefy.cn/api/forgot.php',
			data: qs.stringify({
				phone: obj.phone,
				password: obj.pwd,
				verification: obj.code
			}),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.then(function (response) {
			// console.log(response)
			if (response.data.status === 0) {
				state.toastShow = true
				setTimeout(function () {
					state.toastShow = false
				}, 2000)
				state.toastMessge = response.data.reason
				setTimeout(function () {
					router.push({path: './login'})
				}, 1000)
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
	},
	// 获取直播节目单
	[types.LIVELIST] (state) {
		axios({
			method: 'GET',
			url: 'http://tvlist.gamefy.cn/getlist.php?date=' + date
		})
		.then(function (response) {
			for (var i = 0; i < response.data.programs.length; i++) {
				if (Date.now() >= (response.data.programs[i].begin_stamp * 1000) && Date.now() <= (response.data.programs[i].end_stamp * 1000)) {
					state.curIndex = i
				}
			}state.liveList = response.data.programs
			for (var j = 0; j < state.liveList.length; j++) {
				state.liveList[j].begin_stamp = formatTimeInt(state.liveList[j].begin_stamp)
			}
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取礼包列表
	[types.GIFTLIST] (state) {
		axios({
			method: 'GET',
			url: 'https://api.service.gamefy.cn/lottery/retrive'
		})
		.then(function (response) {
			state.giftList = response.data
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取游戏大厅视频
	[types.YXDTVIDEO] (state) {
		axios({
			method: 'GET',
			url: 'https://tv.gamefy.cn/api/get_yxdt.php?page=0',
			headers: {
				'Content-type': 'application/json'
			}
		})
		.then(function (response) {
			state.videolist = response.data
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取休闲街区视频
	[types.XXJQVIDEO] (state) {
		axios({
			method: 'GET',
			url: 'https://tv.gamefy.cn/api/get_xxjq.php?page=0',
			headers: {
				'Content-type': 'application/json'
			}
		})
		.then(function (response) {
			state.videolist = response.data
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取玩物尚志视频
	[types.WWSZVIDEO] (state) {
		axios({
			method: 'GET',
			url: 'https://tv.gamefy.cn/api/get_wwsz.php?page=0',
			headers: {
				'Content-type': 'application/json'
			}
		})
		.then(function (response) {
			state.videolist = response.data
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取每日游报视频
	[types.MRYBVIDEO] (list, keywords) {
		axios({
			method: 'GET',
			url: 'https://tv.gamefy.cn/api/get_mryb.php?page=0',
			headers: {
				'Content-type': 'application/json'
			}
		})
		.then(function (response) {
			list = response.data
		})
		.catch(function (error) {
			console.log(error)
		})
	},
	// 获取到用户的信息
	[types.USER] (state) {
		const accesstoken = localStorage.getItem('token')
		if (accesstoken === null || accesstoken.length === 0) return
		axios({
			method: 'GET',
			url: 'https://member.gamefy.cn/api/userinfo.php?tread=&access=' + accesstoken
		})
		.then(function (response) {
			if (response.data.result !== undefined && response.data.result === 1) {
				localStorage.removeItem('token')
				state.toastMessge = '登录超时,请重新登录'
				state.toastShow = true
				router.push({path: './login'})
			} else {
				state.user = response.data
				if (state.user.avatar.indexOf('http://q.qlogo.cn') !== 0) {
					state.user.avatar = 'http://avatar.gamefy.cn/' + state.user.avatar + '?imageView2/5/w/80/h/80/format/jpg/q/75|imageslim'
				}
				if (state.user.level === 0) {
					state.user.level = '普通会员'
				}
			}
		})
		.catch(function (err) {
			console.log(err)
		})
	}
}
