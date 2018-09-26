<template>
	<div class="live">
		<div  style="position: absolute;top: -1000px;" ><img :src="post"/></div>
		<Headbar :account="true" :menu="true"></Headbar>
		<div class="main">
			<div id="videobox" v-html="player" v-show="showMove"></div>
			<Share :post="post" :pv="pv"></Share>
			<iframe :src="'#/changyan/'+sid" id="changyanBox" ></iframe>
		</div>
		<mu-dialog :open="dialog" title="" dialogClass="warning">
		    <p>登录游戏风云会员，享受更多会员福利。</p>
				<img src="../assets/pic/notice.png"></img>
				<mu-raised-button slot="actions" :disabled="disabled" :label="verifyText"  @click="close" primary />
		    <mu-raised-button label="登录" slot="actions" primary @click="close" to="/login" />
		</mu-dialog>
		<toast></toast>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
import Share from '../components/share'
import { videoPlayer } from 'vue-video-player'
import toast from '../components/Toast'

export default{
	name: 'live',
	components: {
		Headbar,
		ScrollNav,
		videoPlayer,
		Share,
		toast
	},
	beforeMount () {
		let token = localStorage.getItem('token')
		if (token === null || token === undefined || token.length === 0) {
			this.dialog = true
		} else {
			this.dialog = false
		}
	},
	mounted () {
		let videobox = document.querySelector('#videobox')
		if (videobox) {
			videobox.style.height = (videobox.offsetWidth * 9) / 16 + 'px'
			let changyanbox = document.querySelector('#changyanBox')
			let body = document.querySelector('body')
			changyanbox.style.height = (body.offsetHeight - videobox.offsetHeight) + 'px'
		}
		this.getVideo()
	},
	data () {
		this.startCountdown()
		return {
			player: '',
			showMove: true,
			post: '',
			dialog: true,
			pv: 0,
			disabled: true,
			countdown: 5
		}
	},
	computed: {
		sid () {
			return this.$route.params.id
		},
		verifyText () {
			return (this.countdown === 0) ? '关闭' : '关闭 (' + this.countdown + ')'
		}
	},
	methods: {
		startCountdown () {
			this.countdown --
			if (this.countdown === 0) {
				this.disabled = false
			} else {
				setTimeout(this.startCountdown, 1000)
			}
		},
		switchMovie () {
			this.$data.showMove = !this.$data.showMove
		},
		getVideo () {
			let videoid = this.$route.params.id
			let data = this.$data
			this.axios({
				method: 'GET',
				url: 'https://tv.gamefy.cn/api/update_pv.php?id=' + videoid,
				headers: {
					'Content-type': 'application/json'
				}
			})
			.then(function (response) {
				data.post = response.data.image
				data.pv = response.data.totalpv
				document.title = '游戏风云 ' + response.data.title
				let metas = document.getElementsByTagName('meta')
				for (let i = 0; i < metas.length; i++) {
					if (metas[i].getAttribute('name') === 'keywords') {
						metas[i].setAttribute('content', response.data.tags)
						break
					}
				}
				switch (response.data.vtype) {
				case '6':
					data.player = '<iframe height=100% width=100% src="https://v.qq.com/iframe/player.html?vid=' + response.data.vkey + '&tiny=0&auto=0" allowfullscreen></iframe>'
					break
				case '1':
				default:
					data.player = '<iframe height=100% width=100% src="http://player.youku.com/embed/' + response.data.vkey + '" frameborder=0 "allowfullscreen"></iframe>'
					break
				}
			})
			.catch(function (error) {
				console.log(error)
			})
		},
		open () {
			this.dialog = true
		},
		close () {
			this.dialog = false
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';

.main{
	/*padding-top: 35px;*/
	height: 100%;
}

#videobox {
	position: relative;
	display: block;
	width: 100%;
	top: 35px;
	border-bottom: 3px solid #ea3e28;
	background-color: #121212;
}
iframe#changyanBox{
	width: 100%;
	height: 100%;
	border: 0;
}

div.warning{
	border: 2px solid #ea3e28;
	border-radius: 8px;
}
div.warning .mu-dialog-body {
	color: #000;
	font-weight:400;
}

div.warning img{
	width: 160px;
	margin: 0 auto;
	display: block;
}
div.warning .mu-dialog-body {
	padding-top: 5px;
}
div.warning .mu-raised-button{
	height: 24px;
	line-height: 24px;
	font-size: 14px;
	border-radius: 3px;
}
</style>
