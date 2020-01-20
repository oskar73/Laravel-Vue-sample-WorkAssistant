<template>
  <div class="w-100 h-100 tw-relative">
    <router-view :key="$route.path"></router-view>
    <modal-view />
  </div>
</template>

<script>
import ModalView from './ModalView.vue'
import templateMixin from '@/section-builder/mixins/templateMixin'
export default {
  name: 'WebsiteView',
  components: {
    ModalView
  },
  mixins: [templateMixin]
}
</script>
