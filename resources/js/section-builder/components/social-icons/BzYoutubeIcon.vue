<template>
  <a class="bz-youtube-icon-root" :style="socialItemStyle" @click="goToUrl">
    <youtube-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import YoutubeIcon from '../icons/Youtube.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzYoutubeIcon',
  components: { YoutubeIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'youtube'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-youtube-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
