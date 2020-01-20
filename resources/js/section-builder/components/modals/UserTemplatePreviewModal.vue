<template>
  <modal v-model="showModal" :classes="['template-switch-modal', 'w-full']" name="template-switch-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
      <h5 class="tw-text-xl tw-font-bold">{{ templateName }} Template Preview</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="tw-h-full">
      <iframe :src="iframeSrc" title="iframe Example 1" width="100%" height="100%"></iframe>
    </div>
  </modal>
</template>

<script>
import templateMixin from '@/section-builder/mixins/templateMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'

export default {
  components: {},
  mixins: [templateMixin, rerenderMixin],
  props: {
    templateName: {
      type: String,
      default: ''
    },
    templateId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      showModal: false
    }
  },
  computed: {
    iframeSrc() {
      return `${window.config.baseUrl}/website/preview/${window.config.websiteId}?templateId=${this.templateId}/`
    }
  },
  mounted() {
    this.$modal.show('template-switch-modal')
  },
  methods: {
    onClose() {
      this.$store.commit('closeModal')
    }
  }
}
</script>
<style lang="scss">
.vfm__content {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start !important;
  background-color: white !important;
  gap: 0 !important;
}
</style>
