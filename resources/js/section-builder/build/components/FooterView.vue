<template>
  <component :is="footer.name" :properties="footer"></component>
</template>

<script>
import templateMixin from '@/section-builder/mixins/templateMixin'

export default {
  name: 'FooterView',
  mixins: [templateMixin]
}
</script>
