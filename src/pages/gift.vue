<template>
	<div class="gift">
		<Headbar :account="true" ></Headbar>
		<ScrollNav></ScrollNav>
		<div class="list">
			<mu-list>
		 	 	<div v-for="vlist in giftlist" @click="getlottery">
		 		 	<mu-list-item>
	 				 	<img src="../assets/images/placeholder.gif" :data-src="vlist.adv" class="lazyload" >
	 				 	<div class="giftlist_desc">
	 					 	<p>礼包奖品 : {{vlist.name}}</p>
	 					 	<p>活动时间 : {{vlist.endon}}</p>
	 				 	</div>
		 		 	</mu-list-item>
		 	 	</div>
		  	</mu-list>
		</div>
		<Foot></Foot>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
import Foot from '../components/foot'
export default{
	name: 'gift',
	components: {
		Headbar,
		ScrollNav,
		Foot
	},
	mounted () {
		this.$store.dispatch('giftlist')
	},
	computed: {
		giftlist () {
			return this.$store.state.giftList
		}
	}
}
</script>
<style>
@import '../css/login-regist.css';
.list{
	padding-top:95px;
}
.mu-item-content{
	border-bottom: 1px solid #f3f3f3;
}
.mu-item-content img{
	width:100%;
	border-radius: 10px;
}
.giftlist_desc{
	width:100%;
	float:left;
	margin-left:2%;
	font-size:12px;
	margin-bottom: 10px;
}
.giftlist_desc p{
	color:#999999;
	line-height: 10px;
}
</style>
