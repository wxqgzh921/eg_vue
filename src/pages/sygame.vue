<template>
	<div class="sygame">
		<Headbar :account="true" ></Headbar>
		<ScrollNav></ScrollNav>
		<div class="sy_content">
			<p>正在建设</p>
		</div>
	</div>
</template>
<script>
import Headbar from '../components/Headbar'
import ScrollNav from '../components/ScrollNav'
export default{
	name: 'sygame',
	components: {
		Headbar,
		ScrollNav
	}
}
</script>
<style scoped>
@import '../css/login-regist.css';

.sy_content{
	padding-top:95px;
}
.sy_content p{
	text-align: center;
	font-size: 20px;
	font-weight: bold;
}
</style>
