<template>
  <div id="ScrollNav" class="swiper-nav">
    	<nav class="swiper-nav-box">
				<div class="swiper-nav-list" style="overflow: hidden;" @touchmove="navMove" @touchstart="Navsave" @touchend="navStop">
    			<ul class="swiper-nav-pills" v-bind:style="{'width': pillsWidth, 'transition-property': 'transform', 'transform-origin': '0px 0px 0px', 'transform': 'translate(' + offsetposition + 'px, 0px) scale(1) translateZ(0px)'}">
						<li v-for="item in navs" :class="{current: item.link === current}">
							&nbsp;<router-link v-show="item.internal" :to="item.link" v-html="item.title" ></router-link>
							<a v-show="!item.internal" :href="item.link" v-html="item.title" :target="item.title" ></a>
					  </li>
    			</ul>
    		</div>
    	</nav>
  </div>
</template>
<script>
export default{
	name: 'ScrollNav',
	data () {
		let navlist = null
		if (this.$store.state.isAndroid) {
			navlist = [
				{title: '<img src="static/img/tv.png" class="icon"></img>主站', link: 'http://tv.gamefy.cn/', internal: false},
				{title: '<img src="static/img/game.png" class="icon"></img>风云水友赛', link: '/sygame', internal: true},
				{title: '<img src="static/img/gift.png" class="icon"></img>礼包领取', link: '/gift', internal: true}
			]
		} else {
			navlist = [
				{title: '<img src="static/img/tv.png" class="icon"></img>主站', link: 'http://tv.gamefy.cn/', internal: false},
				{title: '<img src="static/img/game.png" class="icon"></img>风云水友赛', link: '/sygame', internal: true},
				{title: '<img src="static/img/gift.png" class="icon"></img>礼包领取', link: '/gift', internal: true}
			]
		}

		return {
			// 滚动时用来保存起始位置
			startposition: 0,
			// 当前移动量
			offsetposition: 0,
			// 节流函数触发最小时间间隔
			handleSpace: 500,
			// 滑动速度
			speed: 0,
			// 滑动条的阻尼系数
			damp: 0.9,
			// 终点的阻力值
			endpoint: 10,
			// touch 事件状态
			// 1: 开始
			// 0: 结束
			touchStatus: 0,
			// 菜单项数组。
			// title: 菜单标题
			// link：菜单链接
			navs: navlist,
			// 初始菜单项长度，实际长度会动态计算
			pillsWidth: '16000px'
		}
	},
	computed: {
		// 当前选中的菜单项
		current () {
			// 对于安卓端的首页我们也需要选中首页
			return this.$route.path.replace('/android', '/index')
		},
		maxWidth () {
			const calcOffset = document.querySelector('.swiper-nav-pills').offsetWidth - document.querySelector('#ScrollNav').offsetWidth
			return calcOffset
		}
	},
	mounted: function () {
		let items = document.querySelectorAll('li')
		let width = 0
		for (var i = 0; i < items.length; i++) {
			width += items[i].offsetWidth
		}
		this.$data.pillsWidth = 394 + 'px'
		this.updateLocation(document.querySelector('#ScrollNav').offsetWidth, width)
	},
	methods: {
		updateLocation (windowWidth, wholeWidth) {
			let position = 0
			if (document.querySelector('li.current') !== null) {
				position = document.querySelector('li.current').offsetLeft * -1 + windowWidth / 2 - (document.querySelector('li.current').offsetWidth / 2)
				position = wholeWidth + position <= windowWidth ? (wholeWidth - windowWidth) * -1 : position
				position = position > 0 ? 0 : position
				this.$data.offsetposition = position
			}
		},
		Navsave (event) {
			this.$data.startposition = event.touches[0].clientX
			this.$data.touchStatus = 1
		},
		navMove (event) {
			// 通过节流函数，提高相应性能
			let lastTime = 0
			function throttle (from, e) {
				// 如果已经松开手指就不再处理
				if (from.$data.touchStatus === 0) return
				let now = new Date().getTime()
				if (now - lastTime <= from.$data.handleSpace) {
					return
				}
				// 更新节流时间
				lastTime = now
				// 计算速度
				from.$data.speed = e.touches[0].clientX - from.$data.startposition
				// 刷新起点
				from.$data.startposition = e.touches[0].clientX
				from.$data.offsetposition = from.$data.offsetposition + from.$data.speed
			}
			throttle(this, event)
			// 不再进行进一步的处理
			event.preventDefault()
		},
		navStop (event) {
			this.$data.touchStatus = 0
			function stoping (from) {
				// 计算变化后速度
				from.$data.speed = from.$data.speed * from.$data.damp
				// 最左端终点
				let endPointLeft = 1 * from.maxWidth + 20
				// 最右端终点
				let endPointRight = 0
				// 计算阻力影响后速度
				if (from.$data.offsetposition <= endPointLeft) {
					from.$data.speed = from.$data.speed + from.$data.endpoint
					from.$data.offsetposition = from.$data.offsetposition + from.$data.speed > endPointLeft ? endPointLeft : from.$data.offsetposition + from.$data.speed
					from.$data.speed = from.$data.offsetposition === endPointLeft ? 0 : from.$data.speed
				} else if (from.$data.offsetposition >= endPointRight) {
					from.$data.speed = from.$data.speed - from.$data.endpoint
					from.$data.offsetposition = from.$data.offsetposition + from.$data.speed < endPointRight ? endPointRight : from.$data.offsetposition + from.$data.speed
					from.$data.speed = from.$data.offsetposition === endPointRight ? 0 : from.$data.speed
				} else {
					from.$data.offsetposition = from.$data.offsetposition + from.$data.speed
				}
				// 进行停止动作
				if (Math.abs(from.$data.speed) < 1 && (from.$data.offsetposition <= endPointRight) && (from.$data.offsetposition >= endPointLeft)) {
					from.$data.speed = 0
				}
				// 继续进行运动，如果速度没有降为0的话
				if (from.$data.speed !== 0)	setTimeout(function () { stoping(from) }, 20)
			}
			stoping(this)
		}
	}
}
</script>

<style >
.swiper-nav{
  padding-left: 10px;
  padding-right: 10px;
	padding-top: 10px;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  outline: none;
	top: 35px;
	position: fixed;
	width: 100%;
	height: 50px;
	z-index: 10000;
	background-color: #fff;
}
.swiper-nav .swiper-nav-box {
  overflow: hidden;
  position: relative;
  min-width: 300px;
  margin: 0 auto;
	/*top: -10px;*/
	margin-bottom: 10pt;
	width: 100%;
	display: block;
}
.swiper-nav .swiper-nav-list {
  width: 100%;
  height: 36px;
  border-bottom: 3px solid #e6e6e6;
  -webkit-backface-visibility: hidden;
  -moz-backface-visibility: hidden;
  -ms-backface-visibility: hidden;
  -o-backface-visibility: hidden;
  backface-visibility: hidden;
}
.swiper-nav .swiper-nav-pills {
  position: absolute;
  top: 0;
  left: 0;
  list-style: none;
  height: 35px;
  margin: 0;
  padding: 0;
  padding-top: 7px;
}
.swiper-nav .swiper-nav-pills li {
  float: left;
  position: relative;
  height: 35px;
  line-height: 20px;
  padding-right: 62px;
  white-space: nowrap;
	display: block;
}
.swiper-nav .swiper-nav-pills li:nth-last-child(1){
	padding-right:0;
}
.swiper-nav .swiper-nav-pills li.current a, .swiper-nav .swiper-nav-pills a.current {
  color: #ff3d00;
	border-bottom: 3px solid #ff3d00;
}
.swiper-nav .swiper-nav-pills li a, .swiper-nav .swiper-nav-pills a {
  color: #616161;
	padding-bottom: 6px;
	display: inline-block;
	font-size: 17px;
	font-family: "Microsoft YaHei", SimHei, helvetica, arial, verdana, tahoma, sans-serif;
}
#ScrollNav .icon {
	width: 17px;
	height: auto;
	top: 1px;
	position: relative;
	margin-right: 3px;
}
</style>
