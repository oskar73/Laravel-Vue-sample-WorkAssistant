<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'PersonalVideoIcon',
  props: {
    title: {
      type: String,
      default: 'Theme Color'
    },
    decorative: {
      type: Boolean,
      default: false
    },
    fillColor: {
      type: String,
      default: 'currentColor'
    },
    size: {
      type: Number,
      default: 24
    }
  }
})
</script>

<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path
        d="M21 3H3c-1.11 0-2 .89-2 2v12c0 1.1.89 2 2 2h5v2h8v-2h5c1.1 0 1.99-.9 1.99-2L23 5c0-1.11-.9-2-2-2zm0 14H3V5h18v12z"
      >
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<style scoped lang="scss">

</style>
