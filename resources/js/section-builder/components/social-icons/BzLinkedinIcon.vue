<template>
  <a class="bz-linkedin-icon-root" :style="socialItemStyle" @click="goToUrl">
    <linkedin-icon :size="size" :fill-color="fill" />
  </a>
</template>

<script>
import LinkedinIcon from '../icons/Linkedin.vue'
import socialIconMixin from '../../mixins/socialIconMixin'
export default {
  name: 'BzLinkedinIcon',
  components: { LinkedinIcon },
  mixins: [socialIconMixin],
  data() {
    return {
      name: 'linkedin'
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-linkedin-icon-root {
  &:hover {
    opacity: var(--bz-hover-opacity, 1);
  }
}
</style>
