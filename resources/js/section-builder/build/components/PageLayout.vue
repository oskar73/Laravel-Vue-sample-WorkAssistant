<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PageLayout',
  props: {
    preview: {
      type: Boolean,
      default: false
    },
    viewOnly: {
      type: Boolean,
      default: false
    }
  }
})
</script>

<template>
  <div class="tw-w-full tw-h-full tw-flex tw-flex-col">
    <bz-header :preview="preview" :viewOnly="viewOnly" />
    <slot />
    <bz-footer :preview="preview" :viewOnly="viewOnly" />
  </div>
</template>

<style scoped lang="scss">
</style>
