<template>
	<div class="live">
		<Headbar :account="true" :menu="true"></Headbar>
		<div class="main">
			<video-player :options="playerOptions" @ready="playerReadied($event)"></video-player>
			<section class="list">
				<div class="program-view"  >
					<div v-for="(list,index) in livelist" :class ="{ 'active activeMark': (index==current), 'program': true }">
							<div class="program-stamp">{{ list.begin_stamp }}</div>
							<div class="program-name"><div class="programContainer">{{ list.detail }}</div></div>
					</div>
				</div>
			</section>
		</div>
		<mu-paper class="button-paper" :zDepth="1"  >
			<img src="../assets/icon/message.png" @touchstart="gotoBBS()" alt="留言" title="留言">
		</mu-paper>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
import { videoPlayer } from 'vue-video-player'

function reset (player) {
	if (player.readyState() === 0) {
		setTimeout(() => {
			reset(player)
		}, 2000)
	} else {
		player.play()
	}
}

export default{
	name: 'live',
	components: {
		Headbar,
		ScrollNav,
		videoPlayer
	},
	mounted () {
		setTimeout(() => { this.$store.dispatch('livelist') }, 100)
	},
	data () {
		return {
			playerOptions: {
				height: (document.querySelector('body').offsetWidth * 9) / 16,
				// component options
				start: 0,
				playsinline: true,
				// videojs options
				preload: 'auto',
				language: 'zh-CN',
				sources: [{
					withCredentials: false,
					type: 'application/x-mpegURL',
					src: 'http://tv.gamefy.cn/api/live.php'
				}],
				flash: { hls: { withCredentials: false } },
				html5: { hls: { withCredentials: false } },
				poster: 'static/img/bg.jpg',
				autoplay: true
			}
		}
	},
	computed: {
		livelist () {
			return this.$store.state.liveList
		},
		current () {
			return this.$store.state.curIndex
		}
	},
	updated () {
		var videojs = document.querySelector('.video-js')
		var top = document.querySelector('.top_menu')
		var app = document.querySelector('#app')
		var list = document.querySelector('.list')
		var view = document.querySelector('.program-view')

		function setScroll (index) {
			if (view && view !== null) {
				var oneProgram = document.querySelector('.program')
				var height = oneProgram !== null ? oneProgram.clientHeight : 60
				list.scrollTop = (index < 4 ? 0 : (index - 2)) * height
				list.style.opacity = 1
			} else {
				setTimeout(function () {
					setScroll(index)
				}, 2000)
			}
		}
		list.style.height = (app.clientHeight + 45 - (videojs.clientHeight + top.clientHeight)) + 'px'
		setScroll(this.current)
	},
	methods: {
		playerReadied (player) {
			reset(player)
			player.tech({ IWillNotUseThisInPlugins: true })
			player.tech_.hls.xhr.beforeRequest = function (options) {
				return options
			}
		},
		gotoBBS () {
			this.$router.push('/bbs')
		}
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';

.list{
	opacity: 0;
	height: 400px;
	display: block;
	width: 100%;
	overflow-y: scroll;
	position: relative;
	border-top: 3px solid #ea3e28
}

.program-view {
		width: 100%;
		top: 0;
		bottom: 0;
		left:0;
		overflow: auto;
		-webkit-overflow-scrolling: touch;
	}

.program {
	background-color: #cfd0d1;
	background-image: url("../assets/images/line.png");
	background-repeat: no-repeat;
	height: 50px;
	color: #696d71;
	font-size: 30px;
	font-weight: normal;
	overflow: hidden;
}

.active .program-stamp, .active .programContainer {
	color: #f24e22;
}

.activeMark{
	background-image: url("../assets/images/now.png");
	background-size:30px 30px;
	background-repeat: no-repeat;
	background-position: top right;
}

.program:nth-child(even) {
	background-color: #e7e9ec;
	color: #696d71
}

.program-stamp, .program-name {
	float: left;
	line-height: 30px;
}

.program-stamp {
	width: calc((2 / 7 ) * 100%);
	text-align: center;
	letter-spacing: 2px;
	padding-top: 12px;
	font-size: 17px;
	font-weight: bold;
}

.program-name {
	width: calc((5 / 7 ) * 100%);
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	height: 100%;
	align-items:center;
	display: table;
}

.programContainer{
	padding-right: 10px;
	font-size: 13px;
	word-wrap: break-word;
	word-break: normal;
	white-space: normal;
	line-height: 18px;
	display: table-cell;
	vertical-align: middle;
	letter-spacing: 1px;
	padding-top: 4px;
}

.main{
	padding-top: 35px;
	height: 100%;
	background-color: #efefef;
}
.video-js{
	width: 100%;
	background:#000;
	height: auto;
}
.mu-tabs{
	top:-10px;
	position: relative;
}
.programList{
	display: block;
	position: relative;
	height: 100%;
}
.live{
	height: 100%;
	min-height: 100%;
}
div.button-paper {
	position: absolute;
  bottom: 20px;
  width: 48px;
  height: 48px;
  right: 20px;
  border-radius: 4px;
	background-color: rgba(255, 255, 255, 0.5);
}
div.button-paper img {
	width: 44px;
	height: 44px;
	margin: 2px;
}
</style>
