<template>
  <a class="bz-facebook-icon-root" :style="socialItemStyle" @click="goToUrl">
    <facebook-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import FacebookIcon from '../icons/Facebook.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzFacebookIcon',
  components: { FacebookIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'facebook'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-facebook-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
