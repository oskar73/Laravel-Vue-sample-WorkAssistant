<template>
  <div ref="root" class="tw-w-full tw-relative" :style="{ paddingTop: `${100 * ratio}%` }">
    <div class="tw-absolute tw-top-0 tw-left-0 tw-w-full tw-h-full">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'BzAspectView',
  props: {
    ratio: {
      type: Number,
      default: 1
    }
  }
}
</script>
