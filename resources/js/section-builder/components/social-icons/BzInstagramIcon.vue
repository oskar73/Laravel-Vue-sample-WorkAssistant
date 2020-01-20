<template>
  <a class="bz-instagram-icon-root" :style="socialItemStyle" @click="goToUrl">
    <instagram-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import InstagramIcon from '../icons/Instagram.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzInstagramIcon',
  components: { InstagramIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'instagram'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-instagram-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
