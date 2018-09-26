<template>
	<mu-bottom-sheet :open="bottomSheet" sheetClass="install_box" @close="closeBottomSheet">
		<a :href="install">
			<img src="../assets/images/flag.png" />
			<div class="message">安装客户端，节目看不停！</div>
			<mu-flat-button label="立即安装" class="install-button" backgroundColor="#ff3d00" color="#FFF"/>
		</a>
	</mu-bottom-sheet>
</template>
<script>
	export default{
		name: 'install',
		data () {
			let isAndroid = Boolean(navigator.userAgent.match(/android/ig))
			let isIphone = Boolean(navigator.userAgent.match(/iphone|ipod/ig))
			let isIpad = Boolean(navigator.userAgent.match(/ipad/ig))
			// let isWeixin = Boolean(navigator.userAgent.match(/MicroMessenger/ig))
			let install = ''
			let bottomSheet = false
			if (isAndroid) {
				install = 'http://android.gamefy.cn/download.php'
			} else if (isIphone || isIpad) {
				install = 'https://itunes.apple.com/cn/app/id641402211'
			} else {
				install = ''
				bottomSheet = true
			}

			setTimeout(() => {
				this.$store.state.showInstall = false
				this.bottomSheet = false
			}, 3000)

			return {
				bottomSheet,
				install
			}
		},
		methods: {
			closeBottomSheet () {
				this.bottomSheet = false
			}
		},
		mounted () {
			this.bottomSheet = this.$store.state.showInstall
		}
	}
</script>

<style>
.install_box{
	height: 50px;
}
.install_box img {
	height: 50px;
	width: 50px;
	position: relative;
	float: left;
}
.install_box .message {
	float: left;
	position: relative;
	line-height: 32px;
	margin: 10px;
	margin-right: 0px;
	color: #121212;
	font-size: 12px;
}
.install_box .install-button {
	margin-top: 8px;
	font-style: 14px;
	float: right;
	margin-right: 8px;
}
</style>
