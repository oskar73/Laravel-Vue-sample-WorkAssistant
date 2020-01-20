<template>
  <div class="bz-el-warning-root">
    <error-outline-icon fill-color="rgb(255, 133, 15)" :size="18" />
    <div class="ml-2" style="padding-top: 1px">
      <slot />
    </div>
  </div>
</template>

<script>
import ErrorOutlineIcon from '../icons/ErrorOutline.vue'
export default {
  name: 'BzWarning',
  components: { ErrorOutlineIcon }
}
</script>
<style lang="scss">
.bz-el-warning-root {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  font-size: 0.9em;
}
</style>
