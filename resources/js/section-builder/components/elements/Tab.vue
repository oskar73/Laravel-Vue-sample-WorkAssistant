<template>
  <div v-show="isActive" class="bz-tab-root">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'BzTab',
  props: {
    label: {
      type: String,
      default: 'Tab Name'
    }
  },
  data() {
    return {
      isActive: true
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-tab-root {
  padding: 20px;
}
</style>
