<template>
  <span :aria-hidden="decorative" :aria-label="title" class="material-design-icon delete-icon" role="img">
    <div class="d-block" :style="{ width: size + 'px', height: size + 'px', padding: '2px' }">
      <svg :fill="fillColor" style="width: 100%; height: 100%" viewBox="0 0 2859 3333">
        <path
          d="M2081 0c55 473 319 755 778 785v532c-266 26-499-61-770-225v995c0 1264-1378 1659-1932 753-356-583-138-1606 1004-1647v561c-87 14-180 36-265 65-254 86-398 247-358 531 77 544 1075 705 992-358V1h551z"
        >
          <title>{{ title }}</title>
        </path>
      </svg>
    </div>
  </span>
</template>

<script>
export default {
  name: 'TikTokIcon',
  props: {
    title: {
      type: String,
      default: 'tiktok icon'
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
