<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path d="M7.41 15.41 12 10.83l4.59 4.58L18 14l-6-6-6 6z">
        {{ title }}
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'BzArrowUpIcon',
  props: {
    title: {
      type: String,
      default: 'Arrow icon'
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
