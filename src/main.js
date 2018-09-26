// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
// import App from './App'
// import Usercenter from './components/Usercenter'
import VueRouter from 'vue-router'
import routes from './router/index'
import store from './store'
// import { sync } from 'vuex-router-sync'
import axios from 'axios'

import MuseUI from 'muse-ui'
import 'muse-ui/dist/muse-ui.css'
import './css/theme-light.css'
import VueScroller from 'vue-scroller'
import VueAwesomeSwiper from 'vue-awesome-swiper'
Vue.use(MuseUI)
Vue.use(VueRouter)
Vue.use(VueScroller)
Vue.use(VueAwesomeSwiper)

// 将axios挂载到prototype上，在组件中可以直接使用this.axios访问
Vue.prototype.axios = axios
Vue.config.productionTip = false

const router = new VueRouter({
	routes
})

function isNeedRedirect (path) {
	if (path === '/user') {
		return true
	} else {
		return false
	}
}

router.beforeEach((to, from, next) => {
	// we will not save path if current page is login or user page.
	if (to.path !== '/login' && to.path !== '/user' && to.path !== '/logindialog') {
		store.state.back_path = to.path
	}
	// we will save a mark for android user
	if (to.meta.android) {
		store.state.isAndroid = true
	}
	if (to.meta.requireAuth) {  // 判断该路由是否需要登录权限
		const accesstoken = localStorage.getItem('token')
		const timeMark = localStorage.getItem('timemark')
		const needRedirect = isNeedRedirect(to.fullPath)
		// We do local storage time mark check first to improve the performance.
		if ((Date.now() - timeMark) > 60 * 15 * 1000) {
			localStorage.removeItem('token')
			localStorage.removeItem('timemark')
			if (needRedirect) {
				store.state.toastShow = true
				store.state.toastMessge = '登录已超时，请重新登录 ^.^'
				next({
					path: '/login',
					query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
				})
			} else {
				next()
			}
		}
		if (localStorage.getItem('timemark') !== null) localStorage.setItem('timemark', Date.now())
		// We do check user info from server
		axios({
			method: 'GET',
			url: 'https://member.gamefy.cn/api/userinfo.php?tread=&access=' + accesstoken
		})
		.then(function (response) {
			if (response.data.result !== undefined && response.data.result === 1) {
				localStorage.removeItem('token')
				localStorage.removeItem('timemark')
				if (needRedirect) {
					if (to.name !== 'user') {
						store.state.toastShow = true
						store.state.toastMessge = '登录以后就可以继续访问了 ^.^'
					}	else {
						store.state.toastShow = false
						store.state.toastMessge = ''
					}
					next({
						path: '/login',
						query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
					})
				} else {
					next()
				}
			} else {
				next()
			}
		})
	}	else {
		if (localStorage.getItem('timemark') !== null) localStorage.setItem('timemark', Date.now())
		next()
	}
})

router.afterEach(route => {
	document.title = route.meta.title
})

// sync(store, router)

/* eslint-disable no-new */
new Vue({
	el: '#app',
	router,
	store
})
