<template>
  <div id="app">
    <Headbar :account="true" ></Headbar>
		<scroller style="padding-top:40px">
				<div class="Hot_list">
						<mu-list>
					 	 <template v-for="(vlist, index) in list">
					 		 <mu-list-item >
					 			 <router-link :id="vlist.id" :vkey="vlist.vkey" :to="getUrl(vlist.id, vlist.vkey, vlist.tag_id)"  >
					 				 <img src="../assets/images/placeholder.gif" :data-src="vlist.image" class="lazyload" >
					 				 <div class="video_desc">
										 <span v-html="vlist.title" />
									 </div>
					 			 </router-link>
					 		 </mu-list-item>
					 	 </template>
					  </mu-list>
				</div>
				<mu-row gutter class="index_box" >
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/yxdt"><img src="../assets/images/hall.jpg"/></router-link></mu-col>
				</mu-row>
				<mu-row gutter class="index_box" >
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/xxjq"><img src="../assets/images/zone.jpg"/></router-link></mu-col>
				</mu-row>
				<mu-row gutter class="index_box" >
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/wwsz"><img src="../assets/images/toy.jpg"/></router-link></mu-col>
				</mu-row>
				<mu-row gutter class="index_box" >
					<mu-col width="100" tablet="100" desktop="100"><router-link to="/mryb"><img src="../assets/images/daily.jpg"/></router-link></mu-col>
				</mu-row>
		</scroller>
  </div>
</template>

<script>
import Headbar from '../components/Headbar'

export default{
	name: 'index',
	components: {Headbar},
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
		getUrl (id, vkey, tag) {
			let selectedClass = ''
			switch (tag) {
			case '2':
				selectedClass = 'g'
				break
			case '100':
				selectedClass = 'mryb'
				break
			case '15023':
				selectedClass = 'wwsz'
				break
			case '887':
				selectedClass = 'xxjq'
				break
			case '99':
				selectedClass = 'yxdt'
				break
			}
			return './' + selectedClass + '/' + id + '/' + vkey
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
}
</style>
