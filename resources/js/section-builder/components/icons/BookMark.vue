<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 64 64">
      <path d="M51,59l-17.137-9.019c-1.166-0.614-2.56-0.614-3.726,0L13,59V7h38V59z">
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'BzBookmark',
  props: {
    title: {
      type: String,
      default: 'Theme Color'
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
