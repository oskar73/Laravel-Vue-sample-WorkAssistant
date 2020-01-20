<template>
  <div class="w-100">
    <component :is="section.name" :position="0"></component>
  </div>
</template>
<script>
export default {
  props: ['section'],
  created() {
    this.$store.commit('setSections', [this.section])
    this.$store.commit('enableEdit')
  }
}
</script>
