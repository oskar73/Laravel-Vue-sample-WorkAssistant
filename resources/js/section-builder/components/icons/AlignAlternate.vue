<template>
  <span :aria-label="title" class="material-design-icon delete-icon tw-inline-block" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path
        id="Combined-Shape"
        d="M1,3 L11,3 L11,11 L1,11 L1,3 Z M13,3 L23,3 L23,5 L13,5 L13,3 Z M13,7 L19,7 L19,9 L13,9 L13,7 Z M23,13 L23,21 L13,21 L13,13 L23,13 Z M11,13 L11,15 L1,15 L1,13 L11,13 Z M11,17 L11,19 L5,19 L5,17 L11,17 Z"
      />
    </svg>
  </span>
</template>

<script>
export default {
  name: 'AlignAlternateIcon',
  props: {
    title: {
      type: String,
      default: 'Align alternate icon'
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
