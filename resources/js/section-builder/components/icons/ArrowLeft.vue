<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path d="M15.41 16.59 10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z">
        {{ title }}
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'BzArrowLeftIcon',
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
