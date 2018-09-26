<template>
	<div>
		<div class="main" v-bind:style="{display: isAndroid()?'none':'block'}">
			<span class="pv"><img src="../assets/icon/view.png"/><span v-html="$props.pv"></span></span>
			<a style="color: #fff;" @click="share()" ><img src="../assets/icon/share.png"/></a>
			<a style="color: #fff;" :href="getWeiboUrl()" target="_blank"><img src="../assets/icon/sina.png"/></a>
			<a style="color: #fff;" :href="getQQUrl()" target="_blank"><img src="../assets/icon/qq.png"/></a>
		</div>
		<div class="main" v-bind:style="{display: isAndroid()?'block':'none'}">
			<span class="pv"><img src="../assets/icon/view.png"/><span v-html="$props.pv"></span></span>
			<a style="color: #fff;" :onclick="getAndroidShare()" ><img src="../assets/icon/share.png"/></a>
		</div>
	</div>
</template>
<script>

export default{
	name: 'share',
	props: ['post', 'pv'],
	components: {},
	methods: {
		isAndroid () {
			return this.$store.state.isAndroid
		},
		getAndroidShare () {
			let param = {
				id: this.$route.fullPath,
				image: this.$props.post,
				title: document.title.replace(/'/g, ''),
				vdesc: document.title.replace(/'/g, ''),
				vurl: 'http://m.gamefy.cn/#' + this.$route.fullPath
			}
			let result = JSON.stringify(param)
			return 'downloadit.shareApk(\'' + result + '\')'
		},
		getWeiboUrl () {
			let url = encodeURIComponent('http://m.gamefy.cn/#' + this.$route.fullPath)
			let img = encodeURIComponent(this.$props.post)
			return 'http://service.weibo.com/share/share.php?appkey=&title=' + document.title + '&url=' + url + '&pic=' + img
		},
		getQQUrl () {
			let url = encodeURIComponent('http://m.gamefy.cn/#' + this.$route.fullPath)
			let img = encodeURIComponent(this.$props.post)
			return 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + url + '&title=' + document.title + '&pics=' + img
		},
		share () {
			let state = this.$store.state
			state.toastShow = true
			state.toastMessge = '可以使用浏览器分享按钮<br>进行分享'
			setTimeout(function () {
				state.toastShow = false
			}, 2000)
		}
	}
}
</script>

<style scoped>
div.main {
	position: relative;
	margin-top:35px;
	background-color:#ea3e28;
	color:#fff;
	padding:1px 1px;
	width:100%;
	display:block;
	height: 22px;
}
div.main a img {
	float:right;
	display: block;
	height: 16px;
	padding-right: 16px;
	opacity: 0.85;
}
div.main span.pv {
	padding-left: 10px;
	padding-right: 10px;
	opacity: 0.9;
	height: 30px;
}
div.main span.pv img {
	padding-right: 5px;
}
div.main span.pv span {
	line-height: 24px;
	height: 24px;
	position: relative;
	top: -2px;
}
</style>
