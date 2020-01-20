<template>
  <sweet-modal ref="modal" @close="handleClose">
    <design-preview v-show="isOpenPreview" ref="preview" />
  </sweet-modal>
</template>

<script>
import { SweetModal } from 'sweet-modal-vue-3'
import DesignPreview from './DesignPreview.vue'
import appMixin from '@/svg-editor/mixins/app-mixin'

export default {
  name: 'PreviewPopup',
  components: {
    DesignPreview,
    SweetModal
  },
  mixins: [appMixin],
  watch: {
    isOpenPreview() {
      if (this.isOpenPreview) {
        this.$refs.modal.open()
        // Rerender swiper component
        this.$refs.preview.swiper.update()
      } else {
        this.$refs.modal.close()
      }
    }
  },
  methods: {
    handleClose() {
      this.isOpenPreview = false
    }
  }
}
</script>

<style lang="scss">
.sweet-modal {
  overflow: hidden;
}
.sweet-action-close {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
