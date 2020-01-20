<template>
  <div class="bz-section-container bz-sec--sectionName-root" :class="{ [breakPoint]: true }"></div>
</template>

<script>
import sectionMixin from '../mixins/sectionMixin'

export default {
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--sectionName-root {
}
</style>
