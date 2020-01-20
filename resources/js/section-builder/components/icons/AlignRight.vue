<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon tw-inline-block" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path d="M13,5 L23,5 L23,19 L13,19 L13,5 Z M1,5 L11,5 L11,7 L1,7 L1,5 Z M1,9 L7,9 L7,11 L1,11 L1,9 Z M1,13 L9,13 L9,15 L1,15 L1,13 Z">
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'AlignRightIcon',
  props: {
    title: {
      type: String,
      default: 'Align icon'
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
}
</script>
