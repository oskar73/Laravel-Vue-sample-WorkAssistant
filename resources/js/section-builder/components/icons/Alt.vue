<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <svg :fill="fillColor" class="material-design-icon__svg" :width="size" :height="size" viewBox="0 0 24 24">
      <path
        xmlns="http://www.w3.org/2000/svg"
        fill-rule="evenodd"
        clip-rule="evenodd"
        d="M5 5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19H15.9708C16.6167 19 17.2228 18.6881 17.5982 18.1625L22 12L17.5982 5.83752C17.2228 5.31193 16.6167 5 15.9708 5H5ZM7.6161 11.69L7.7211 12.11H6.7621L6.8671 11.69C6.92777 11.4613 6.98843 11.221 7.0491 10.969C7.10977 10.7123 7.1681 10.4627 7.2241 10.22H7.2521C7.31277 10.458 7.37344 10.7053 7.4341 10.962C7.49477 11.2187 7.55544 11.4613 7.6161 11.69ZM6.5521 12.915H7.9311L8.2041 14H9.2961L7.8751 9.436H6.6431L5.2221 14H6.2721L6.5521 12.915ZM10.9244 9.436H9.89537V14H12.9544V13.132H10.9244V9.436ZM12.8606 10.304H14.2256V14H15.2616V10.304H16.6266V9.436H12.8606V10.304Z"
        fill="black"
        fill-opacity="0.6"
      >
        <title>{{ title }}</title>
      </path>
    </svg>
  </span>
</template>

<script>
export default {
  name: 'AltIcon',
  props: {
    title: {
      type: String,
      default: 'Alt icon'
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
