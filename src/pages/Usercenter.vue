<template>
	<div class="user">
		<Headbar :menu="true"></Headbar>
    <div class="main">
			<div class="avatar">
    		<img :src="user.avatar" class="head_img"/>
				<router-link to="/avatar" >编辑头像</router-link>
			</div>
    	<div class="userinfo" v-if="!user.phone">
    		<p>你还没有登录，请先登录</p>
    		<router-link to="/login" class="user_login changepwd">登录</router-link>
    	</div>
    	<div class="userinfo" v-if="user.phone">
    		<p>您好：{{ user.nick === null || user.nick.length === 0 ? user.phone : user.nick }}<router-link to="/nickname" class="changenick">修改</router-link></p>
    		<p>G豆： 0</p>
				<mu-flat-button class="operationButton" label="更改密码" to="/changepwd" primary :style="{'margin-right': '5px'}"  />
				<mu-flat-button class="operationButton paddingAdjust" primary label="退出登录" to="/logout" :style="{left: '0px'}" />
    	</div>
    </div>
    <div class="hr"></div>
    <div class="function">
			<router-link to="/bbs" class="button" >
				<mu-row gutter>
			    <mu-col width="50" tablet="50" desktop="50"><span>留言&反馈</span></mu-col>
			    <mu-col width="50" tablet="50" desktop="50" class="align_right more" >></mu-col>
			  </mu-row>
			</router-link>
			<a href="http://bbs.gamefy.cn" target="_blank" class="button" >
				<mu-row gutter>
			    <mu-col width="50" tablet="50" desktop="50"><span>风云部落</span></mu-col>
			    <mu-col width="50" tablet="50" desktop="50" class="align_right more" >></mu-col>
			  </mu-row>
			</a>
    </div>
	  <Signoutdialog></Signoutdialog>
	</div>
</template>
<script>
import Signoutdialog from '../components/Signout_dialog'
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'

export default{
	name: 'user',
	beforeMount () {
		let token = localStorage.getItem('token')
		if (token === null || token === undefined || token.length === 0) {
			this.$router.push('/login')
		}
	},
	components: {
		Signoutdialog,
		Headbar,
		ScrollNav
	},
	computed: {
		user () {
			if (this.$store.state.user.phone === undefined) {
				this.$store.dispatch('user')
			}
			return this.$store.state.user
		}
	},
	methods: {}
}
</script>
<style scoped>
@import '../css/login-regist.css';

.main {
	padding-top: 40px;
}

.main .head_img{
	width:60px;
	float: left;
	margin-left: 20px;
	margin-top: 20px;
	border-radius: 50%;
	border: 1px solid #ddd;
}
.main .userinfo{
	width:70%;
	margin-left:95px;
	margin-top:20px;
	font-size:16px;
}
.main .userinfo p{
	line-height:10px;
}
.main .userinfo img.edit{
	width: 20px;
	position: relative;
	top:4px;
}

.operationButton{
	border: 1px solid #bdbdbd;
	border-radius: 5px;
	font-size: 12px;
	height: 24px;
	line-height: 24px;
}
.operationButton .mu-flat-button-label{
	font-size: 9pt;
	padding: 3px 6px;
}

.function {

	height: 100%;
	margin: 10px 0;
	padding: 0;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.function a {
	margin-top: 10px;
	background-color: #e0e0e0;
	display: block;
	position: relative;
}

.function .button div{
	padding: 2px 4px;
	font-style: 24px;
}

.function .button div span{
	color: #212121;
	font-family: "Microsoft YaHei", SimHei, helvetica, arial, verdana, tahoma, sans-serif;
	font-size: 15px;
	position: relative;
	padding-left: 10px;
	line-height: 30px;
}
.function .button div .more{
	font-size: 20px;
	padding: 0;
	margin: 0;
	padding-right: 6px;
	color: #808080;
	line-height: 30px;
}
.function .button div i {
	display: block;
	float: left;
	margin-right: 8px;
	font-size: 20px;
	margin-top: 1px;
}
.avatar {
	position: relative;
}
.avatar img, .avatar a{
	position: absolute;
	top: 0;
}
.avatar a {
	width: 60px;
	height: 50px;
	text-align: center;
	margin-left: 20px;
	margin-top: 85px;
	font-size: 12px;
	color: #ea3e28;
}
.changenick{
	padding-left: 10px;
	font-size: 12px;
}
</style>
