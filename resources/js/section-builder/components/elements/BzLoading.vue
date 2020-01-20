<template>
  <div class="tw-absolute tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center">
    <svg :width="size" :height="size" viewBox="0 0 8 10" xmlns="http://www.w3.org/2000/svg">
      <rect x="0" y="3" width="2" height="4" :fill="fill">
        <animate attributeName="height" attributeType="XML" values="4; 10; 4" begin="0s" dur="0.6s" repeatCount="indefinite" />
        <animate attributeName="y" attributeType="XML" values="3; 0; 3" begin="0s" dur="0.6s" repeatCount="indefinite" />
      </rect>
      <rect x="3" y="2.25" width="2" height="5.5" :fill="fill">
        <animate attributeName="height" attributeType="XML" values="4; 10; 4" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
        <animate attributeName="y" attributeType="XML" values="3; 0; 3" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
      </rect>
      <rect x="6" y="1.5" width="2" height="7" :fill="fill">
        <animate attributeName="height" attributeType="XML" values="4; 10; 4" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
        <animate attributeName="y" attributeType="XML" values="3; 0; 3" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
      </rect>
    </svg>
  </div>
</template>

<script>
export default {
  name: 'BzLoading',
  props: {
    size: {
      type: Number,
      default: 24
    },
    fill: {
      type: String,
      default: 'blue'
    }
  }
}
</script>

<style scoped></style>
