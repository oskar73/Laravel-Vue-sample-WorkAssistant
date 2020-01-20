<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon drag-vertical-icon" role="img" :style="{ pointerEvents: 'none' }">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path d="M20 9H4v2h16V9zM4 15h16v-2H4v2z">
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'DragHandleIcon',
  props: {
    title: {
      type: String,
      default: 'Drag Vertical icon'
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
