<template>
  <div id="app">
    <Headbar :account="true" ></Headbar>
		<ScrollNav ></ScrollNav>
		<div class="box">
			<h3>关于游戏风云</h3>
			<p>“上海游戏风云文化传媒有限公司”起步于“游戏风云”数字电视频道，是中国最早的跨电视和互联网的专业游戏媒体平台，也是切入游戏产业运营的首个电视媒体，旗下诞生了中国第一个稳定的、长期举办的电子竞技联赛“G联赛”。
    “游戏风云”以“弘扬健康游戏文化，服务广大游戏受众”的理念，贴近游戏玩家需求，以有线网络、IPTV及互联网为载体，依托高新技术的传播手段，将优秀、高质的游戏资讯、赛事赛况及娱乐视听等内容生动、快速、便捷的呈现给观众。
    截止至2013年，游戏风云电视频道已成功覆盖全国超过五千万家庭用户，并通过互联网影响到更为广阔的游戏人群。</p>
		<h3>联系方式</h3>
			<p><strong>地    址：</strong><a href="http://j.map.baidu.com/iVMnC">上海市洛川东路487号</a></p>
			<p><strong>邮    编：</strong>200070</p>
			<p><strong>总    机：</strong>021-56389988</p>
			<p><strong>邮    箱：</strong><a href="mailto:bd@gamefy.cn">bd@gamefy.cn</a></p>
		</div>
  </div>
</template>

<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'

export default{
	name: 'index',
	components: {Headbar, ScrollNav},
	data () {
		return {
			tools: false,
			list: []
		}
	},
	mounted () {
		this.getVideoList()
	},
	methods: {
		getUrl (id, tag) {
			return './' + tag + '/' + id
		},
		getVideoList () {
			let data = this.$data
			this.axios({
				method: 'GET',
				url: 'https://tv.gamefy.cn/api/get_news.php'
			})
			.then(function (response) {
				data.list = data.list.concat(response.data)
			})
			.catch(function (err) {
				console.log(err)
			})
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';

.box{
	padding-top: 90px;
	display: block;
	height: 100%;
	margin-left: 10px;
	margin-right: 10px;
}
.box p {
	margin-left: 10px;
	margin-right: 10px;
}
</style>
