// import App from '../App'

export default [
	{
		path: '/index',
		meta: {title: '游戏风云官网-主站'},
		name: 'index'
	},
	{
		path: '/android',
		meta: {
			title: '游戏风云官网-主站',
			android: true
		},
		name: 'android_index'
	},
	{
		path: '/sygame',
		meta: {
			title: '游戏风云-风云水友赛',
			android: true
		},
		name: 'sygame',
		component: (resolve) => require(['../pages/sygame.vue'], resolve)
	},
	{
		path: '/gift',
		meta: {
			title: '游戏风云-礼包领取',
			android: true
		},
		name: 'gift',
		component: (resolve) => require(['../pages/gift.vue'], resolve)
	},
	{
		path: '/login',
		meta: {title: '游戏风云官网-登录'},
		name: 'login',
		component: (resolve) => require(['../pages/Login.vue'], resolve)
	},
	{
		path: '/logout',
		meta: {title: '游戏风云官网-登出'},
		name: 'logout',
		component: (resolve) => require(['../pages/Logout.vue'], resolve)
	},
	{
		path: '/logindialog',
		meta: {title: '登出'},
		name: 'logindialog',
		component: (resolve) => require(['../pages/Loginmin.vue'], resolve)
	},
	{
		path: '/regist',
		meta: {title: '游戏风云官网-注册'},
		name: 'regist',
		component: (resolve) => require(['../pages/Regist.vue'], resolve)
	},
	{
		path: '/lookingforpwd',
		meta: {title: '游戏风云官网-忘记密码'},
		name: 'lookingforpwd',
		component: (resolve) => require(['../pages/Lookingforpwd.vue'], resolve)
	},
	{
		path: '/user',
		meta: {
			requireAuth: true, // 添加该字段，表示进入这个路由是需要登录的
			title: '游戏风云官网-用户中心'
		},
		name: 'user',
		component: (resolve) => require(['../pages/Usercenter.vue'], resolve)
	},
	{
		path: '/changepwd',
		meta: {title: '游戏风云官网-修改密码'},
		name: 'changepwd',
		component: (resolve) => require(['../pages/Changepwd.vue'], resolve)
	},
	{
		path: '/aboutus',
		meta: {
			title: '关于我们_游戏风云官网'
		},
		name: 'aboutus',
		component: (resolve) => require(['../pages/Aboutus.vue'], resolve)
	},
	{
		path: '/avatar',
		meta: {
			title: '修改头像_游戏风云官网'
		},
		name: 'avatar',
		component: (resolve) => require(['../pages/Avatar.vue'], resolve)
	},
	{
		path: '/nickname',
		meta: {
			title: '修改昵称_游戏风云官网'
		},
		name: 'nickname',
		component: (resolve) => require(['../pages/Nickname.vue'], resolve)
	},
	{
		path: '*',
		redirect: '/gift'
	}
]
