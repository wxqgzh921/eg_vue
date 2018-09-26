<template>
	<div class="foot">
		<mu-row gutter class="foot_box" >
			<mu-col width="100" tablet="100" desktop="100">Copyright©2017 游戏风云 <a href="www.gamefy.cn">gamefy.com</a> 版权所有</mu-col>
			<mu-col width="100" tablet="100" desktop="100">沪ICP备12018235号</mu-col>
		</mu-row>
	</div>
</template>
<script>
export default{
	name: 'foot'
}
</script>
<style>
	.foot{
		padding: 20px 0;
	}
	.row .col-100{
		text-align: center;
	}
</style>
