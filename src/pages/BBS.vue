<template>
	<div class="bbs">
		<Headbar :account="true" :menu="true"  ></Headbar>
		<div class="main">
			<iframe :src="iframe" ></iframe>
			<mu-paper class="button-paper" :zDepth="1"  >
				<img src="../assets/icon/back.png" @touchstart="gotoBACK()" alt="返回" title="返回">
			</mu-paper>
		</div>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'

export default{
	name: 'bbs',
	components: {
		Headbar,
		ScrollNav
	},
	computed: {
		user () {
			return this.$store.state.user
		},
		iframe () {
			let bbsID = this.$route.params.id === undefined ? 'gamefy-review' : 'nview-' + this.$route.params.id
			return '#/changyan/' + bbsID
		}
	},
	methods: {
		signout () {
			this.$store.dispatch('sign_btn')
		},
		gotoBACK () {
			this.$router.back()
		}
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';

div.bbs{
	height: 100%;
}
div.main{
	top: 35px;
	display: block;
	position: relative;
	height: 100%;
	width: 100%;
}
iframe{
	width: 100%;
	height: 100%;
	border: 0;
}
div.button-paper {
	position: absolute;
  bottom: 50px;
  width: 32px;
  height: 32px;
  right: 20px;
  border-radius: 4px;
	background-color: rgba(255, 255, 255, 0.8);
}
div.button-paper img {
	width: 28px;
	height: 28px;
	margin: 2px;
}
</style>
