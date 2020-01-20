<template>
  <a class="bz-bizinabox-icon-root" :style="socialItemStyle" @click="goToUrl">
    <bizinabox-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import BizinaboxIcon from '../icons/Bizinabox.vue'
import socialIconMixin from '../../mixins/socialIconMixin'

export default {
  name: 'BzBizinaboxIcon',
  components: { BizinaboxIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'bizinabox'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-bizinabox-icon-root {
  cursor: pointer;
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
