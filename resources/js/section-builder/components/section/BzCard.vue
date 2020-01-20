<template>
  <div
    class="bz-card-root"
    :class="{ shadow }"
    :style="{
      backgroundColor: cardBgColor,
      padding: padding + 'px',
      borderRadius: rounded + 'px'
    }"
  >
    <slot />
  </div>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'

export default {
  name: 'BzCard',
  mixins: [elementMixin],
  props: {
    shadow: {
      type: Boolean,
      default: true
    },
    padding: {
      type: Number,
      default: 10
    },
    rounded: {
      type: Number,
      default: 0
    },
    bgColor: {
      type: String,
      default: ''
    }
  },
  computed: {
    cardBgColor() {
      return this.bgColor || this.boxColor
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-card-root {
  width: 100%;
  &.shadow {
    box-shadow: rgb(0 0 0 / 24%) 0 0.5rem 1rem -0.25rem !important;
  }
}
</style>
