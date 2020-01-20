<template>
  <span class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path fill="rgb(255, 255, 255)" d="M0 20h24v4H0z">
        <title>{{ title }}</title>
      </path>
      <rect width="24" height="4" x="0" y="20" style="stroke-width: 1; stroke: rgba(0, 0, 0, 0.15)" :fill="color"></rect>
      <path d="M11 3L5.5 17h2.25l1.12-3h6.25l1.12 3h2.25L13 3h-2zm-1.38 9L12 5.67 14.38 12H9.62z"></path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'BzFormatColorTextIcon',
  props: {
    title: {
      type: String,
      default: 'Format color text icon'
    },
    decorative: {
      type: Boolean,
      default: false
    },
    fillColor: {
      type: String,
      default: 'currentColor'
    },
    color: {
      type: String,
      default: '#ffffff'
    },
    size: {
      type: Number,
      default: 24
    }
  }
}
</script>
