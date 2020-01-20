<template>
  <component :is="header.name" v-if="header" :properties="header"></component>
</template>

<script>
import templateMixin from '@/section-builder/mixins/templateMixin'

export default {
  name: 'HeaderView',
  mixins: [templateMixin]
}
</script>

<style scoped></style>
