<script>
import { defineComponent } from 'vue'
import builderMixin from '@/section-builder/mixins/builderMixin'

export default defineComponent({
  name: 'BzHeader',
  mixins: [builderMixin]
})
</script>

<template>
  <section data-position="header">
    <component :is="header.name" position="header" :properties="header" />
  </section>
</template>

<style scoped lang="scss">
</style>
