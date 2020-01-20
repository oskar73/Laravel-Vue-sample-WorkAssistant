<template>
  <a class="bz-pinterest-icon-root" :style="socialItemStyle" @click="goToUrl">
    <pinterest-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import PinterestIcon from '../icons/Pinterest.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzPinterestIcon',
  components: { PinterestIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'pinterest'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-pinterest-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
