<template>
  <div class="top_menu">
		<span @click="toggle()" tooltip="菜单" v-bind:style="{display: showMenuIcon, float: 'left', padding: '8px 6px'}"><img  src="../assets/icon/menu.png"/></span>
		<router-link to="/"><img :class="LogoClass" src="../assets/images/logo2016.png"/></router-link>
		<router-link to="/user" :style="{display: showAccountIcon, float: 'right', padding: '6px 4px', 'margin-right': '3px'}" ><img :src="avatar" style="width: 24px;border-radius: 12px;" ></router-link>
		<!--<img src="../assets/icon/download.png" onclick="javascript:downloadit.showlist()"  v-bind:style="{display: showDownloadIcon, float: 'right', 'margin-top': '8px', width: '20px', 'margin-right': '8px'}" ></img>-->
		<!-- <mu-icon-button icon="account_circle" to="/user" iconClass="menu-icon" tooltip="会员中心" v-bind:style="{display: showAccountIcon, float: 'right', padding: '9px 0'}" ></mu-icon-button> -->
		<mu-drawer :open="open"  @close="toggle()" class="drawer_menu" :docked="false" width="200">
			<iframe style="width100%;height100%;position:absolute"></iframe>
			<div class="back-icon"></div>
			<mu-list v-for="(item, index) in navs" :key="item.id" @itemClick="toggle()">
				<mu-list-item :title="item.title"  :to="item.internal?item.link:''" :href="!item.internal?item.link:''" :class="{current: item.id === current,icon: item.icon}" :target="item.internal?'_self':item.id" />
			</mu-list>
			<mu-divider />
			<div style="height:10px;"></div>
			<mu-list v-for="(item, index) in members" :key="item.id" @itemClick="toggle()">
				<mu-list-item :title="item.title"  :to="item.internal?item.link:''" :href="!item.internal?item.link:''" :class="{current: item.id === current}" :target="item.internal?'_self':item.id" >
				</mu-list-item>
			</mu-list>
		</mu-drawer>
  </div>
</template>
<script>
	export default {
		name: 'headbar',
		data () {
			// get user info
			if (this.$store.state.user.avatar === undefined || this.$store.state.user.avatar.length === 0) {
				this.$store.dispatch('user')
			}

			if (this.$store.state.isAndroid) {
				return {
					open: false,
					navs: [
						{id: 'home', title: '首页', link: 'http://tv.gamefy.cn/', internal: true},
						{id: 'gamelist', title: '手游大厅', link: '/gamelist', internal: true, icon: true},
						{id: 'live', title: '电视直播', link: '/live', internal: true},
						{id: 'mryb', title: '每日游报', link: '/mryb', internal: true},
						{id: 'xxjq', title: '休闲街区', link: '/xxjq', internal: true},
						{id: 'yxdt', title: '游戏大厅', link: '/yxdt', internal: true},
						{id: 'wwsz', title: '玩物尚志', link: '/wwsz', internal: true},
						{id: 'g', title: 'G联赛', link: '/g', internal: true}
					],
					members: [
						{id: 'usercenter', title: '用户中心', link: '/user', internal: true},
						// {id: 'bbs', title: '风云部落', link: 'http://bbs.smggame.net/', internal: false},
						{id: 'video', title: '精彩视频', link: '/video', internal: true},
						{id: 'news', title: '最新资讯', link: '/news', internal: true}
					]
				}
			} else {
				return {
					open: false,
					navs: [
						{id: 'home', title: '首页', link: '/index', internal: true},
						{id: 'live', title: '电视直播', link: '/live', internal: true},
						{id: 'mryb', title: '每日游报', link: '/mryb', internal: true},
						{id: 'xxjq', title: '休闲街区', link: '/xxjq', internal: true},
						{id: 'yxdt', title: '游戏大厅', link: '/yxdt', internal: true},
						{id: 'wwsz', title: '玩物尚志', link: '/wwsz', internal: true},
						{id: 'g', title: 'G联赛', link: '/g', internal: true}
					],
					members: [
						{id: 'usercenter', title: '用户中心', link: '/user', internal: true},
						// {id: 'bbs', title: '风云部落', link: 'http://bbs.smggame.net/', internal: false},
						{id: 'video', title: '精彩视频', link: '/video', internal: true},
						{id: 'news', title: '最新资讯', link: '/news', internal: true}
					]
				}
			}
		},
		props: {
			account: Boolean,
			menu: Boolean
		},
		computed: {
			avatar () {
				if (this.$store.state.user !== undefined && this.$store.state.user.avatar !== undefined) {
					return this.$store.state.user.avatar
				} else {
					return '../static/img/member.png'
				}
			},
			showAccountIcon () {
				return this.account ? 'inline-block' : 'none'
			},
			showMenuIcon () {
				return this.menu ? 'inline-block' : 'none'
			},
			LogoClass () {
				return this.menu ? 'center' : 'left'
			},
			current () {
				return this.$route.name.replace('_play', '')
			},
			showDownloadIcon () {
				return this.$store.state.isAndroid ? 'inline-block' : 'none'
			}
		},
		mounted () {},
		methods: {
			toggle () {
				if (this.$parent.switchMovie) this.$parent.switchMovie()
				this.open = !this.open
			}
		}
	}
</script>
<style >
.top_menu .logo {
  height: 45px;
  overflow: hidden;
}
.top_menu  {
  background-color: #121212;
  height: 35px;
  color: #fff;
	position: fixed;
	z-index: 99999999;
	width: 100%;
	display: block;
}
.top_menu  a img.left{
  height: 50px;
  position: relative;
	float:left;
  top:-7px;
  margin-left: 10px;
}
.top_menu  a img.center{
  position: absolute;
  margin: 0 auto;
  display: block;
  margin-left: 50%;
  left: -55px;
	height: 50px;
	width: auto;
	top:-7px;
}
.top_menu .back-icon {
    display: block;
    position: relative;
    height: 10px;
    width: 100%;
}
.top_menu .mu-icon-menu, .menu-icon{
  float: right;
	width: 35px;
}
.top_menu .mu-icon-button{
  top: -3px;
	width: 35px;
	text-align: center;
}
.top_menu div.mu-list{
	padding: 4px;
}
.top_menu div.mu-item{
	min-height: 30px;
	padding: 6px 26px;
	font-size: 20px;
	margin: 0 auto;
}
.top_menu div.drawer_menu{
	background-color: rgba(255, 255, 255, 0.95);
	background-image: url(../assets/pic/1.png);
	background-size: 100px 100px;
	background-repeat: no-repeat;
	background-position: 80% 95%;
}
.top_menu .current{
	background-color: #ea3e28;
}
.top_menu .current .mu-item{
	color: #fff;
}
.top_menu .float-button-home {
	margin-left: 25px;
	margin-right: 10px;
}
.top_menu .float-button-usercenter {
	margin-right: 10px;
}
.top_menu .float-button-gamecenter {
	margin-right: 25px;
}
.top_menu .gray-icon{
	color: #757575;
}
.top_menu div.mu-list .icon .mu-item-title{
	color: #ea3e28;
}
iframe{
	width: 100%;
	height: 100%;
	border: 0;
}
</style>
