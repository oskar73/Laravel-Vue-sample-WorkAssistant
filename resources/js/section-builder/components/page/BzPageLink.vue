<template>
  <a :href="edit ? 'javascript:void(0)' : href">
    <slot />
  </a>
</template>

<script>
import elementMixin from '../../mixins/elementMixin'

export default {
  name: 'BzPageLink',
  mixins: [elementMixin],
  props: {
    href: {
      type: String,
      default: '/'
    }
  }
}
</script>
