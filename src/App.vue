<template>
  <div id="app">
    <Headbar ></Headbar>
    <Index></Index>
    <Tools :Showtools="tools"></Tools>
    <Foot></Foot>
  </div>
</template>

<script>
import Headbar from './components/Headbar'
import Index from './components/Index'
import Tools from './components/Tools'
import Foot from './components/foot'

export default{
	name: 'index',
	components: {Headbar, Index, Tools, Foot},
	data () {
		return {tools: false}
	},
	methods: {
	}
}
</script>
