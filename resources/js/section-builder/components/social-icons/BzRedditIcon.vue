<template>
  <a class="bz-reddit-icon-root" :style="socialItemStyle" @click="goToUrl">
    <reddit-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import RedditIcon from '../icons/Reddit.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzRedditIcon',
  components: { RedditIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'reddit'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-reddit-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
