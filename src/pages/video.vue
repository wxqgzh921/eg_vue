<template>
	<div class="list">
		<Headbar :account="true" ></Headbar>
		<ScrollNav ></ScrollNav>
		<detailcontent :selectedClass="selectedClass"></detailcontent>
	</div>
</template>

<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
import detailcontent from '../components/detail_content'

export default{
	name: 'list',
	components: {
		Headbar,
		ScrollNav,
		detailcontent
	},
	computed: {
		// 当前选中的菜单项
		selectedClass () {
			return this.$route.name
		}
	}
}
</script>
