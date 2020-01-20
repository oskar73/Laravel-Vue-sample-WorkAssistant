<template>
  <modal v-model="showModal" :classes="['template-switch-confirm-modal']" name="template-switch-confirm-modal"
    @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
      <h5 class="tw-text-lg">New Template Information</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="tw-flex-col px-3 tw-flex justify-content-center">
      <div class="my-2">You should input new template information.</div>
      <div class="tw-font-bold mb-2">
        <bz-input v-model="name" label="Name" placeholder="Input name" required />
      </div>
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end py-2">
      <button class="btn bz-btn-default mr-4 tw-flex tw-items-center" @click="handleConfirm">
        <spinner v-if="saveLoading" />
        Confirm
      </button>
    </div>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import { createUserTemplate, getUserTemplate } from '../../apis'
import Spinner from '@/public/Spinner.vue'
import { cloneDeep } from 'lodash'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

import helpers from '../../helpers'

export default {
  components: { Spinner, BzInput },
  props: {
    onConfirm: {
      type: Function,
      default: () => { }
    }
  },
  data() {
    return {
      showModal: true,
      name: '',
      saveLoading: false
    }
  },
  computed: {
    userTemplatesCount() {
      return this.$store.state.userTemplates.length
    }
  },
  mounted() {
    this.$modal.show('new-template-save-modal')
  },
  methods: {
    onClose() {
      this.$store.commit('closeModal')
    },
    handleConfirm: async function () {
      this.saveLoading = true
      const currentWebsite = cloneDeep(this.$store.state.template)

      /** TODO: We should compare the origin data and new data */
      const {
        data: { data: originUserTemplate }
      } = await getUserTemplate(window.config.userTemplateId)
      const extractKeys = ['web_id', 'created_at', 'name', 'id', 'template_id', 'updated_at']
      const hasDifference = !helpers.compareObjects(originUserTemplate, currentWebsite, extractKeys)
      if (!hasDifference) {
        toast.error("There are no changes on this template. You don't need to create new template")
      } else if (this.userTemplatesCount >= 5) {
        // if (this.userTemplatesCount >= 5) {
        toast.error(
          'You have reached the maximum amount of templates that can be saved.\n' +
          'If you want to save this template, please delete one of your previously saved templates then come back and save this one.\n' +
          'Thank you.'
        )
      } else {
        currentWebsite.web_id = window.websiteId
        currentWebsite.name = this.name

        currentWebsite.pages = currentWebsite.pages?.map(page => ({
          ...page,
          id: null
        }))

        const {
          data: { status: saveSuccess }
        } = await createUserTemplate(currentWebsite)
        console.log(currentWebsite, 'currentWebsite')

        this.onConfirm({ name: this.name, saveSuccess })
      }
      this.saveLoading = false
    }
  }
}
</script>
<style lang="scss">
.template-switch-confirm-modal {
  height: min-content;
  max-height: 500px;
  margin: auto;
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
