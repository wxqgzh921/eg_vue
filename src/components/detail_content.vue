<template>
	<div class="detail_content">
		<div class="searchbox">
			<input  type="text" v-model="searchfor"></input>
			<img src="../assets/icon/search.png" @touchstart="dosearch" />
		</div>
		<div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20" class="infinite-container" @scroll="setTop()" >
			<mu-list>
		 	 <template v-for="vlist in list">
		 		 <mu-list-item >
		 			 <router-link :id="vlist.id" :vkey="vlist.vkey" :to="getPlayUrl(vlist.id,vlist.vkey)">
		 				 <img src="../assets/images/placeholder.gif" :data-src="vlist.image" class="lazyload" >
		 				 <div class="video_desc">
		 					 <div class="desc_name">{{vlist.title}}</div>
		 					 <div class="info_box">
		 						 <div class="desc_time"><img src="../assets/images/desc_vedio.png"/>{{vlist.len}}</div>
		 						 <div class="desc_comment"><img src="../assets/images/desc_comment.png"/>{{vlist.pv}}</div>
		 					 </div>
		 				 </div>
		 			 </router-link>
		 		 </mu-list-item>
		 	 </template>
		  </mu-list>
		</div>
		<mu-paper class="button-paper" :zDepth="1"  v-bind:style="{display: showTop}" >
			<img src="../assets/icon/top.png" @touchstart="goTop()" alt="留言" title="留言">
		</mu-paper>
		<mu-circular-progress v-show="loading" color="#ff3d00" :size="40" :strokeWidth="5"/>
		<div id="nomoredate" v-show="noMore"></div>
	</div>
</template>

<script>
import infiniteScroll from 'vue-infinite-scroll'

export default {
	directives: {
		infiniteScroll
	},
	data () {
		return {
			list: [],
			loading: false,
			scroller: null,
			current: 0,
			searchfor: '',
			showTop: 'none',
			noMore: false
		}
	},
	props: {
		selectedClass: String
	},
	watch: {
		selectedClass () {
			// 分类信息更新后刷新列表
			this.$data.current = 0
			this.getVideoList(true)
		}
	},
	created () {
		// 初始化加载列表
		this.$data.current = 0
		// this.getVideoList(true)
	},
	computed: {},
	mounted () {
		var container = document.querySelector('.detail_content')
		var body = document.querySelector('body')
		this.scroller = container
		container.style.height = (body.clientHeight - 45) + 'px'
	},
	methods: {
		getPlayUrl (id, vkey) {
			return './' + this.selectedClass + '/' + id
		},
		dosearch () {
			this.loading = true
			let data = this.$data
			data.current = 0
			setTimeout(() => {
				this.getVideoList(true)
			}, 1000)
		},
		loadMore () {
			// this checking used to protect from loading more.
			if (!this.$data.noMore) {
				this.loading = true
				setTimeout(() => {
					this.getVideoList(false)
					this.loading = false
				}, 1000)
			}
		},
		getVideoList (clear) {
			let data = this.$data
			// We need to clear list if we do resarch.
			if (clear) data.list = []
			this.axios({
				method: 'GET',
				url: 'https://tv.gamefy.cn/api/get_' + this.selectedClass + '.php?page=' + data.current + '&k=' + data.searchfor
			})
			.then(function (response) {
				data.loading = false
				data.list = data.list.concat(response.data)
				data.current ++
				data.noMore = response.data.length === 0
			})
			.catch(function (err) {
				data.loading = false
				data.noMore = true
				console.log(err)
			})
		},
		goTop () {
			document.querySelector('.infinite-container').scrollTop = 0
			event.preventDefault()
		},
		setTop () {
			this.$data.showTop = document.querySelector('.infinite-container').scrollTop > document.querySelector('body').offsetHeight ? 'inline-block' : 'none'
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';

	.searchbox{
		width: calc(100% - 40px);
		border: 1px solid #dbdbdb;
		margin: 0 20px;
		border-radius: 4px;
		padding: 2px;
	}
	.searchbox img {
		width: 20px;
		margin: 2px;
		position: relative;
		float: right;
		margin-right: 5px;
	}
	.searchbox input {
		border: 0;
		outline: none;
		height: 25px;
		padding: 0 5px;
		width: calc(100% - 60px);
	}
	.detail_content {
		-webkit-overflow-scrolling: touch;
	}
	.infinite-container{
	  width: 100%;
	  height: 100%;
	  overflow: auto;
	  -webkit-overflow-scrolling: touch;
	  border: 0px;
		margin-top: 5px;
	}
	.infinite-container img{
		width:40%;
		float: left;
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
		color:#555;
	}
	.video_desc .desc_time{
		width:50%;
		color:#999;
		float: left;
	}
	.video_desc .desc_time img{
		width:20px;
		margin-right:5px;
		margin-top:2px;
	}
	.video_desc .desc_comment{
		width:50%;
		color:#999;
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
	div#nomoredate, .mu-circular-progress{
		position: absolute;
		top:calc(50% - 20px);
		left:calc(50% - 20px);
	}

</style>
