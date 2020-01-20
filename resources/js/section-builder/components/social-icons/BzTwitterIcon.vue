<template>
  <a class="bz-twitter-icon-root" :style="socialItemStyle" @click="goToUrl">
    <twitter-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import TwitterIcon from '../icons/Twitter.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzTwitterIcon',
  components: { TwitterIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'twitter'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-twitter-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
