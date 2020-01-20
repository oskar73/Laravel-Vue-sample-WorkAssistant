<template>
  <modal v-model="showModal" :classes="['template-switch-confirm-modal']" name="template-switch-confirm-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
      <h5 class="tw-text-lg">Confirm Switching Template</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="tw-flex-col px-3 tw-flex justify-content-center">
      <div class="tw-text-base">There are {{ pages.length }} new pages in this template.</div>
      <div class="my-2">You can select the pages.</div>
      <div class="tw-font-bold mb-2">
        <bz-switch label="Select All Pages" v-model="selectAll" @change="handleChangeAll" />
      </div>
      <div v-for="page in pages" :key="page" class="my-1">
        <bz-switch :label="page" v-model="selectedValues[page]" @change="handleChange(page)" />
      </div>
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end py-2">
      <button class="btn bz-btn-default mr-4 tw-flex tw-items-center" @click="handleConfirm">Confirm</button>
    </div>
  </modal>
</template>

<script>
import BzSwitch from '../page/BzSwitch.vue'

export default {
  components: { BzSwitch },
  props: {
    pages: {
      type: Number,
      default: 0
    },
    onConfirm: {
      type: Function,
      default: () => {}
    }
  },
  data() {
    return {
      showModal: true,
      selectAll: true,
      selectedValues: {},
      showNewTemplateSaveModal: false
    }
  },
  mounted() {
    this.selectedValues = this.pages.reduce((acc, page) => ({ ...acc, [page]: true }), {})
    this.$modal.show('template-switch-confirm-modal')
  },
  methods: {
    closeNewTemplateModal() {
      this.showNewTemplateSaveModal = false
    },
    onClose() {
      this.$store.commit('closeModal')
    },
    async handleConfirm() {
      const canceledPages = this.pages.filter((page) => !this.selectedValues[page])
      this.$modal.hide('templateSwitchConfirmModal')
      const willSaveUserTemplate = await this.$dialog.confirm({
        title: 'Save as a template?',
        description: 'Do you want to save this website as a template?'
      })
      if (willSaveUserTemplate) {
        this.$store.commit('openModal', {
          name: 'newTemplateModal',
          onConfirm: ({ saveSuccess }) => {
            if (saveSuccess) this.onConfirm(canceledPages)
            this.onClose()
          }
        })
      } else {
        this.onConfirm(canceledPages)
      }
    },
    handleChange(page) {
      this.selectedValues[page] = !this.selectedValues[page]

      this.selectAll = Object.values(this.selectedValues).every((v) => v)
    },
    handleChangeAll() {
      if (this.selectAll) this.selectedValues = this.pages.reduce((acc, page) => ({ ...acc, [page]: true }), {})
      else this.selectedValues = {}
    }
  }
}
</script>
<style lang="scss">
.template-switch-confirm-modal {
  height: min-content;
  max-height: 500px;
  margin: 20px auto;
  max-width: 400px;
}

.vfm__content {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: white !important;
}
</style>
