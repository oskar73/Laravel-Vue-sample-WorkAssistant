<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z">
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'HomeIcon',
  props: {
    title: {
      type: String,
      default: 'Home icon'
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
