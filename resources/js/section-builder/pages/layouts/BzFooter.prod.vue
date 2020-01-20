<script>
import { defineComponent } from 'vue'
import builderMixin from '@/section-builder/mixins/builderMixin'

export default defineComponent({
  name: 'BzFooter',
  mixins: [builderMixin]
})
</script>

<template>
  <component :is="footer.name" position="footer" :properties="footer" />
</template>

<style scoped lang="scss">
</style>
