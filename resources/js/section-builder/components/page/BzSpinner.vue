<template>
  <span class="bz-el-spinner" :class="{ loading }">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
  </span>
</template>

<script>
export default {
  name: 'BzSpinner',
  props: {
    variant: {
      type: [String, Number],
      default: 1
    },
    loading: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style scoped lang="scss">
.bz-el-spinner {
  opacity: 0;
  transition-property: padding, opacity;
  transition-duration: 0.2s, 0.2s;
  transition-timing-function: ease-in, ease;
  transition-delay: 0s, 0.2s;
  position: relative;

  &.loading {
    opacity: 1;
    display: block;
    width: 18px;
    height: 18px;
  }

  span {
    box-sizing: border-box;
    display: inline-block;
    position: absolute;
    right: 0;
    top: 0.15rem;
    width: 1rem;
    height: 1rem;
    opacity: 1;
    border: 3.4px solid;
    border-radius: 50%;
    animation: spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #aaa transparent transparent transparent;

    &:nth-child(1) {
      animation-delay: 0.45s;
    }

    &:nth-child(2) {
      animation-delay: 0.3s;
    }

    &:nth-child(1) {
      animation-delay: 0.15s;
    }
  }

  @keyframes spinner {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
}
</style>
