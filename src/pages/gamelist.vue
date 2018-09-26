<template>
  <div id="app">
		<Headbar :account="true" ></Headbar>
		<ScrollNav ></ScrollNav>
		<div class="swiper-content">
			<swiper :options="swiperOption" ref="mySwiper" class="swiper-slide">
		    <!-- slides -->
		    <swiper-slide v-for="item in ad">
		    	<a :href="item.url"><img :src="item.image" class="index_img"></a>
		    </swiper-slide>
		    <!-- Optional controls -->
		    <div class="swiper-pagination"  slot="pagination"></div>
    	</swiper>
		</div>
		<div class="selected">
			<div class="title"><i></i>精选游戏</div>
			<hr/>
			<swiper :options="swiperOptiontwo" ref="mySwiper">
		    <swiper-slide v-for="item in hot">
		    	<a :onclick="'downloadit.onclick(\'' + do_download(item.id, item.title, item.cover, item.downloadurl) + '\')'" class="slected_info" >
		    		<img :src="item.cover" class="jximg">
		    		<p>{{ item.title}}</p>
		    		<div class="gogame">开始游戏</div>
		    	</a>
		    </swiper-slide>
    	</swiper>
		</div>
		<div class="hot">
			<div class="title"><i></i>热门游戏</div>
			<hr/>
    	<a :onclick="'downloadit.onclick(\'' + do_download(item.id, item.title, item.cover, item.downloadurl) + '\')'"  class="hot_info" v-for="item in rec">
    		<img :src="item.cover" class="jximg">
    		<div class="hot_right">
    			<p>{{ item.title}}</p>
    			<i>{{item.size}}M</i>
    			<div class="hot_gogame">开始游戏</div>
    			<i>{{ item.remark }}</i>
    		</div>
    	</a>
		</div>
  </div>
</template>

<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'

export default{
	name: 'index',
	components: {
		swiper,
		swiperSlide,
		Headbar,
		ScrollNav
	},
	data () {
		return {
			hot: [],
			rec: [],
			ad: [],
			result: [],
			swiperOption: {
				notNextTick: true,
				autoplay: 3000,
				direction: 'horizontal',
				grabCursor: true,
				setWrapperSize: true,
				autoHeight: true,
				pagination: '.swiper-pagination',
				paginationClickable: true,
				mousewheelControl: true,
				observeParents: true
			},
			swiperOptiontwo: {
				slidesPerView: 3,
				paginationClickable: true,
				notNextTick: true,
				autoplay: false,
				direction: 'horizontal',
				grabCursor: true,
				setWrapperSize: true
			}
		}
	},
	mounted () {
		this.getHot()
		this.getRec()
		this.getAd()
	},
	computed: {
		swiper () {
			return this.$refs.mySwiper.swiper
		}
	},
	methods: {
		do_download (id, title, cover, url) {
			var paramters = {}
			paramters.id = id
			paramters.title = title
			paramters.cover = cover
			paramters.downloadurl = url
			return JSON.stringify(paramters)
		},
		getAd () {
			let data = this.$data
			this.axios({
				method: 'GET',
				url: 'http://m.gamefy.cn/api/ad.php'
			})
			.then(function (response) {
				// console.log(response.data)
				data.ad = response.data
			})
			.catch(function (err) {
				console.log(err)
			})
		},
		getHot () {
			let data = this.$data
			this.axios({
				method: 'GET',
				url: 'http://m.gamefy.cn/api/ugame.php?a=hot'
			})
			.then(function (response) {
				// console.log(response.data)
				data.hot = response.data
			})
			.catch(function (err) {
				console.log(err)
			})
		},
		getRec () {
			let data = this.$data
			this.axios({
				method: 'GET',
				url: 'http://m.gamefy.cn/api/ugame.php?a=rec'
			})
			.then(function (response) {
				// console.log(response.data)
				data.rec = response.data
				for (var i = 0; i < Math.ceil(data.rec.length / 4); i++) {
					var start = i * 4
					var end = start + 4
					data.result.push(data.rec.slice(start, end))
				}
			})
			.catch(function (err) {
				console.log(err)
			})
		}
	}
}
</script>
<style>
@import '../css/swiper.min.css';
.swiper-content{
	float:left;
	margin-top: 90px;
	width:100%;
}
.swiper-slide{
	width:100%;
	float:left;
}
.index_img{
	width:100%;
	float:left;
}
.swiper-pagination-bullet{
	width:6px;
	height:6px;
}
.selected{
	width:100%;
	margin-top:20px;
	float:left;
	padding-left:2%;
	padding-right:2%;
}
.title{
	width:100%;
	height:30px;
	font-size: 20px;
	color:#cb341c;
	line-height: 30px;
}
.title i{
	width:15px;
	height:15px;
	border-radius: 50%;
	background: #cb341c;
	margin-left:3%;
	margin-right:1%;
	display:inline-block;
	margin-top:4px;
}
hr{
  border: 1px solid #ebebeb;
}
.swiper-wrapper{
	width:100%!important;
}
/*.selected .swiper-wrapper .swiper-slide{
	width:20%!important;
	margin-right:3%;
	margin-left:2%;
	float: left;
}*/
.slected_info{
	width: 70%;
  float: left;
  margin-left: 15%;
  margin-right: 0%;
}
.slected_info img{
	width: 100%;
}
.slected_info p{
	margin:0;
	padding:0;
	text-align: center;
	height:42px;
}
.gogame{
	border: 1px solid rgba(234,64,41,0.8);
	background-color: rgba(234,64,41,0.8);
	border-radius: 5px;
	color: #fff;
	text-decoration: none;
	padding:5px;
	font-size:12px;
	cursor: pointer;
	text-align: center;
}
.hot{
	width:100%;
	margin-top:30px;
	float:left;
	padding-left:2%;
	padding-right:2%;
}
.hot_info{
	width:100%;
	float:left;
	margin-bottom:20px;
}
.hot_info img{
	width:20%;
	float:left;
}
.hot_right{
	width:75%;
	float: left;
	margin-left:5%;
}
.hot_info p{
	width:100%;
	margin:0;
	padding: 0;
	float: left;
}
.hot_info i{
	color:#a9a9a9;
	font-style: normal;
	font-size: 12px;
	width:100%;
	display: block;
	float: left;
	height:20px;
	overflow: hidden;
}
.hot_gogame{
	float:right;
	margin-right:2%;
	border: 1px solid rgba(234,64,41,0.8);
	background-color: rgba(234,64,41,0.8);
	border-radius: 5px;
	color: #fff;
	text-decoration: none;
	font-size: 12px;
	padding:2px 4px;
	margin-top:-20px;
	margin-bottom:5px;
}
</style>
