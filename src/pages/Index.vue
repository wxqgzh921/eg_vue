<template>
  <div id="app">
    <Headbar :account="true" ></Headbar>
		<ScrollNav ></ScrollNav>
		<scroller style="padding-top:90px">
				<div class="Hot_list">
						<mu-list>
					 	 <template v-for="(vlist, index) in list">
					 		 <mu-list-item >
					 			 <router-link :id="vlist.id" :to="getUrl(vlist.id, vlist.tag_id)"  >
					 				 <img src="../assets/images/placeholder.gif" :data-src="vlist.image" class="lazyload placeholder" >
					 				 <div class="video_desc">
										 <span v-html="vlist.title" />
									 </div>
					 			 </router-link>
					 		 </mu-list-item>
					 	 </template>
					  </mu-list>
				</div>
				<mu-row gutter class="title_box" >
					<mu-col width="100" tablet="100" desktop="100">电视栏目</mu-col>
				</mu-row>
				<mu-row gutter class="index_box" >
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/mryb"><img src="../assets/images/daily.jpg"/></router-link></mu-col>
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/xxjq"><img src="../assets/images/zone.jpg"/></router-link></mu-col>
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/yxdt"><img src="../assets/images/hall.jpg"/></router-link></mu-col>
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/wwsz"><img src="../assets/images/toy.jpg"/></router-link></mu-col>
				</mu-row>
				<mu-row gutter class="foot_box" >
					<mu-col width="100" tablet="100" desktop="100">Copyright©2017 游戏风云 <a href="www.gamefy.cn">gamefy.com</a> 版权所有</mu-col>
					<mu-col width="100" tablet="100" desktop="100">沪ICP备12018235号</mu-col>
				</mu-row>
			<Install v-if="!$store.state.isAndroid"></Install>
		</scroller>
  </div>
</template>

<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
import Install from '../components/Install'

export default{
	name: 'index',
	components: {Headbar, ScrollNav, Install},
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
div.index_box {
	padding: 2px 8px;
}
div.index_box img{
	width:100%;
}
.Hot_list{
	width: 100%;
	border: 0px;
}
.Hot_list .mu-list{
	width:100%;
}
.Hot_list .mu-list > div {
	width: 100%;
	display: block;
	position: relative;
	float: left;
}
.Hot_list .mu-list .mu-item{
	padding: 2px 8px;
}
	.Hot_list .mu-list .mu-item:first{
		padding-top: 0;
	}
.Hot_list .mu-list .mu-item a{
	display: block;
	position: relative;
}
.Hot_list a img, .index_box a img {
	width: 100%;
	border-radius: 5px;
}
.Hot_list a .video_desc{
	position: absolute;
	width: 100%;
	color: #fff;
}
.Hot_list a .video_desc span {
	position: absolute;
	bottom: 7px;
	width: 95%;
	margin-left: 5px;
	float: right;
	overflow: hidden;
	height: 20px;
	text-shadow: #555 1px 0 0, #555 0 1px 0, #555 -1px 0 0, #555 0 -1px 0;
}
.foot_box{
	text-align: center;
	width: 95%;
	margin: 0 auto;
	border-top: 1px solid #ddd;
	margin-top: 1rem;
	padding-top: 1rem;
	line-height: 2rem;
	font-size: 1rem;
}
.title_box{
	color: #909090;
	font-size: 2rem;
	margin-left: 1rem;
	font-family: "Microsoft YaHei", SimHei, helvetica, arial, verdana, tahoma, sans-serif;
	margin-top: 1rem;
}
.placeholder{
	width: 100vmin;
	height: auto;
}
</style>
