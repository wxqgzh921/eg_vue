<template>
	<div class="container">
		<Headbar :account="true"  :menu="true"></Headbar>
		<scroller style="top: 60px;padding:0 10px 100px 10px;">
			<div class="title" v-html="title"></div>
			<div class="author" v-html="author"></div>
			<div  class="dateline" v-html="dateline"></div>
			<div id="mesageContaniner" v-html="message"></div>
		</scroller>
		<mu-paper class="button-paper" :zDepth="1"  >
			<img src="../assets/icon/pen.png" @touchstart="gotoBBS()" width="28" height="28" alt="留言" title="留言">
		</mu-paper>
	</div>
</template>

<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'

export default{
	data () {
		return {
			artical: null,
			loading: false,
			message: '',
			title: '',
			author: '',
			dateline: ''
		}
	},
	name: 'list',
	components: {
		Headbar,
		ScrollNav
	},
	mounted () {
		let data = this.$data
		let that = this
		data.loading = true
		this.axios({
			method: 'GET',
			url: 'https://tv.gamefy.cn/api/get_art_detail.php?id=' + this.$route.params.id
		})
		.then(function (response) {
			data.loading = false
			let context = response.data.message
			context = context.replace(/<a[^>]*>[\s\S]*?<\/a>/ig, '')
			context = context.replace(/<embed[^>]*>[\s\S]*?<\/embed>/ig, '')
			context = context.replace(/width="(\d*)"/ig, 'width="100%"')
			context = context.replace(/height="(\d*)"/ig, '')
			context = context.replace(/<p[^>]*>[\s\S]*?<\/embed>/ig, '<p>')
			context = context + '<br><br><br><br>'
			data.message = context
			data.title = response.data.title
			data.author = response.data.author
			data.dateline = response.data.dateline
			// set title and keys
			document.title = '游戏风云 ' + response.data.title

			that.axios({
				method: 'GET',
				url: 'https://tv.gamefy.cn/api/update_news_pv.php?id=' + that.$route.params.id,
				headers: {
					'Content-type': 'application/json'
				}
			})
			.then(function (response) {
				let metas = document.getElementsByTagName('meta')
				for (let i = 0; i < metas.length; i++) {
					if (metas[i].getAttribute('name') === 'keywords') {
						metas[i].setAttribute('content', response.data.tags)
						break
					}
				}
			})
			.catch(function (error) {
				console.log(error)
			})
		})
		.catch(function (err) {
			data.loading = false
			console.log(err)
		})
	},
	computed: {
		// 当前选中的菜单项
		selectedClass () {
			return this.$router.name
		},
		iframe () {
			let bbsID = this.$route.params.id === undefined ? 'gamefy-review' : 'nview-' + this.$route.params.id
			return '#/changyan/' + bbsID
		}
	},
	methods: {
		gotoBBS () {
			this.$router.push('/bbs/' + this.$route.params.id)
			// this.bbsPopup = true
		}
	}
}
</script>
<style>

</style>
<style scoped>
@import '../css/login-regist.css';

.title {
	font-weight: bold;
	font-size: 12pt;
}
.author, .dateline {
	display: block;
	width: 100%;
	text-align: right;
	clear: both;
	line-height: 24px;
	padding-right: 10px;
	color: #5d5d5d;
	font-size: 10px;
}
#mesageContaniner{
	display: block;
	position: relative;
	font-size: 12pt;
}
div.button-paper {
	position: absolute;
  bottom: 20px;
  width: 4rem;
  height: 4rem;
  right: 20px;
  border-radius: 4px;
	background-color: rgba(255, 255, 255, 0.9);
}
div.button-paper img {
	width: 3.5rem;
	height: 3.5rem;
	margin: 2px;
}

</style>
