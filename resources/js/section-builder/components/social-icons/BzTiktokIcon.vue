<template>
  <a class="bz-tiktok-icon-root" :style="socialItemStyle" @click="goToUrl">
    <tik-tok-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import TikTokIcon from '../icons/TikTok.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzTiktokIcon',
  components: { TikTokIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'tiktok'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-tiktok-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
