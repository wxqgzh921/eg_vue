<template>
	<div class="daily">
	  	<div class="swiper-container swiper-container-1">
	    	<div class="swiper-wrapper box">
	    	 	<div class="swiper-slide" id="one">
		    		<Headbar :account="true"></Headbar>
		    		<img src="../assets/mryb_imgs/jt.png" class="jt"/>
	    		</div>
	   			<div class="swiper-slide" id="two">
		   			<Headbar :account="true"></Headbar>
		   			<div class="two_top"></div>
		   			<div class="mryb_list">
		   				<a href="http:www.baidu.com"><img src="../assets/mryb_imgs/qq.png" class="qq"/></a>
		   				<a href="/Index"><img src="../assets/mryb_imgs/weibo.png" class="weibo"/></a>
		   				<a href="#/wwsz"><img src="../assets/mryb_imgs/share.png" class="share"/></a>
		   				<div class="pl">2,166</div>
		   				<div class="look">12,304</div>
				   		<div class="detail_content">
							<div class="swiper-container swiper-container-2">
                				<div class="swiper-wrapper">
                    				<div class="swiper-slide swiper-slide-2">
									 	<ul>
									 		 <li v-for="vlist in list" class="mryb_list_div">
									 				 <img :src="vlist.image" class="lazyload" />
									 				 <div class="video_desc">
									 					 <div class="desc_name">{{vlist.title}}</div>
									 					 <div class="info_box">
									 						 <div class="desc_time"><img src="../assets/mryb_imgs/desc_vedio.png"/>{{vlist.len}}</div>
									 						 <div class="desc_comment"><img src="../assets/mryb_imgs/desc_comment.png"/>{{vlist.pv}}</div>
									 					 </div>
									 				 </div>
									 		 </li>
									 	</ul>
									</div>
								</div>
						  		<div class="swiper-scrollbar swiper-scrollbar-2"></div>
							</div>
						</div>
	   				</div>
	   			</div>
			</div>
	    </div>
	</div>
</template>
  
<script>
import Headbar from '../components/Headbar'
export default {
	name: 'daily',
	components: {
		Headbar
	},
	data () {
		return {
			list: [
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				},
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				},
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				},
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				},
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				},
				{
					image: 'static/img/11.jpg',
					title: '《非常英雄》悟空新预告 猴王挥舞金箍棒大显神通',
					len: '123456',
					pv: '1223'
				}
			]
		}
	},
	computed: {},
	mounted () {
		const mySwiper = new window.Swiper('.swiper-container-1', {
			direction: 'vertical',
			spaceBetween: 10,
			loop: false
		})
		console.log(mySwiper)
		const mySwiper2 = new window.Swiper('.detail_content .swiper-container-2', {
			scrollbar: '.swiper-scrollbar-2',
			nested: true,
			scrollbarHide: false,
			direction: 'vertical',
			mousewheelControl: true,
			slidesPerView: 'auto',
			freeMode: true
		})
		console.log(mySwiper2)
	},
	methods: {}
}
</script>
<style>
@import '../css/swiper.min.css';
html,body{
	height:100%;
}
#app,.daily{
	height:100%;
}
.swiper-container{
	width:100%;
	height:100%;
}
#one{
	height:100%;
	background-image: url(../assets/mryb_imgs/mryb_bg1.jpg);
	background-repeat: no-repeat;
	background-position:0 35px;
	background-size: 100%;
	position: relative;
}
.jt{
	position: absolute;
	bottom:0;
	left:0;
	width:100%;
}
#two{
	height:100%;
	background-color:#818181;
	position: relative;
}
.two_top{
	width:100%;
	height:35%;
	position: absolute;
	top:35px;
	left:0;
}
.mryb_list{
	position: absolute;
	bottom:0;
	left:0;
	width:100%;
	height:60%;
	background-image: url(../assets/mryb_imgs/mryb_bg2.png);
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
.qq{
	position: absolute;
	top:6.5%;
	left:19%;
	width:6%;
}
.weibo{
	position: absolute;
	top:6.5%;
	left:28%;
	width:7%;
}
.share{
	position: absolute;
	top:7%;
	left:38%;
	width:4%;
}

.pl{
	position: absolute;
	top:4.5%;
	right:27%;
	color:#2b1605;
	font-size: 12px;
}
.look{
	position: absolute;
	top:4.5%;
	right:7%;
	color:#2b1605;
	font-size: 12px;
}
.detail_content {
	width:100%;
	margin-top:76px;
	height:88%;
	position:absolute;
	left:0;
}
.swiper-slide-2{
    font-size: 18px;
    height: auto;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
ul,li{
	list-style: none;
	padding:0;
	margin:0;
}
.mryb_list_div{
	width:100%;
	padding:5%;
	padding-top:3%;
	height:114px;
	background: url(../assets/mryb_imgs/list_bg.png) no-repeat;
	background-size:100% 84%;
}
.lazyloaded{
	border:2px solid #1d140e;
	width:32%;
	float:left;
	border-radius: 5px;
}
.video_desc{
	width:58%;
	float:left;
	margin-left:2%;
	font-size:12px;
}
.video_desc .desc_name{
	width:100%;
	font-size: 14px;
	height: 46px;
	overflow: hidden;
	color:#352c26;
}
.video_desc .desc_time{
	width:50%;
	color:#352c26;
	float: left;
}
.video_desc .desc_time img{
	width:20px;
	margin-right:5px;
	margin-top:2px;
}
.video_desc .desc_comment{
	width:50%;
	color:#352c26;
	float: right;
}
.video_desc .desc_comment img{
	width:20px;
	margin-right:5px;
	margin-top:2px;
}

.info_box{
	width: 100%;
	display: block;
	margin-top:5px;
}
.swiper-scrollbar{
	background:#1d140e;
	border-radius:15px;
}
.swiper-scrollbar-drag{
	width:222%;
	background:url(../assets/mryb_imgs/hk.png) no-repeat;
	background-size:110%;
	left: -4px;
}
</style>
