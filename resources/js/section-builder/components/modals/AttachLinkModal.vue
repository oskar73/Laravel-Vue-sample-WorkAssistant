<template>
  <!-- <modal :classes="['attach-link-modal']" name="attachLinkModal" @closed="onClose()"> -->
  <modal :show="open" @closed="onClose()">
    <div class="attach-link-modal tw-bg-white tw-w-[40vw]">
      <div style="background-color: rgb(246, 246, 246)" class="p-3">
        <h5 class="tw-text-xl tw-font-bold">Attach link</h5>
      </div>
      <div class="d-flex flex-column pt-3 px-4 tw-my-1">
        <label class="tw-flex tw-gap-x-2 tw-items-center">
          <input v-model="data.type" type="radio" checked value="page" class="mb-2" />
          Page
        </label>
        <label class="tw-flex tw-gap-x-2 tw-items-center">
          <input v-model="data.type" type="radio" value="web-address" class="mb-2" />
          Web address
        </label>
        <label class="tw-flex tw-gap-x-2 tw-items-center">
          <input v-model="data.type" type="radio" value="email-address" class="mb-2" />
          Email address
        </label>
        <label class="tw-flex tw-gap-x-2 tw-items-center">
          <input v-model="data.type" type="radio" value="phone-number" class="mb-2" />
          Phone Number
        </label>
      </div>
      <div v-if="data.type === 'page'" class="p-4">
        <bz-select v-model="data.page" :options="pageLinks" />
        <div class="d-flex justify-content-between align-items-center mt-3 px-1">
          <span class="element_item_label">Open in a new tab</span>
          <bz-switch v-model="data.target" />
        </div>
      </div>
      <div v-if="data.type === 'web-address'" class="p-4">
        <input v-model="data.webAddress" class="form-control" placeholder="Web address" />
        <div class="d-flex justify-content-between align-items-center mt-3 px-1">
          <span class="element_item_label">Open in a new tab</span>
          <bz-switch v-model="data.target" />
        </div>
      </div>

      <div v-if="data.type === 'email-address'" class="p-4">
        <input v-model="data.email" class="form-control" placeholder="Enter an email address" />
        <input v-model="data.subject" class="form-control mt-3" placeholder="Enter an email subject (optional)" />
      </div>

      <div v-if="data.type === 'phone-number'" class="p-4">
        <input v-model="data.phoneNumber" class="form-control" placeholder="Enter a phone number" />
      </div>

      <div v-if="data.type === 'document'" class="p-4">
        <label class="btn bz-btn-default-outline" for="uploadDocument">
          <b>Upload document</b>
          <input id="uploadDocument" type="file" hidden />
        </label>
      </div>
      <div class="tw-py-2 tw-px-4">
        <p v-if="error" class="tw-border tw-rounded tw-border-red-400 tw-py-1 tw-px-4 tw-text-left tw-text-red-400">
          {{ error }}
        </p>
      </div>
      <hr class="my-1" />
      <div class="w-100 d-flex justify-content-end">
        <button class="btn bz-btn-default-outline mr-2" @click="onClose">
          <b>Cancel</b>
        </button>
        <button class="btn btn-danger mr-4 d-flex align-items-center" @click="handleLinkSave">
          <b>Save</b>
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
import BzSelect from '../page/BzSelect.vue'
import BzSwitch from '../page/BzSwitch.vue'
import modalMixin from '../../mixins/modalMixin'
import appMixin from '../../mixins/appMixin'
import Modal from '../../../public/Modal.vue'

export default {
  components: { BzSwitch, BzSelect, Modal },
  mixins: [modalMixin, appMixin],
  data() {
    return {
      loading: false,
      data: {
        type: 'page'
      },
      error: null
    }
  },
  computed: {
    pageLinks() {
      const links = []
      for (const page of (this.allPages ?? []).filter((page) => page.type !== 'new-page')) {
        links.push({
          label: page.name,
          value: `${page.url || ''}`
        })
      }

      if (this.isWebsite) {
        links.push({
          label: 'Login',
          value: 'login'
        })
        links.push({
          label: 'Register',
          value: 'register'
        })
      }

      return links
    }
  },
  mounted() {
    this.$watch('$store.state.modals.basic.name', (modalName) => {
      if (modalName === 'attachLinkModal') {
        this.data.type = 'page'
      }
    }, { immediate: true, deep: true })
  },
  methods: {
    handleLinkSave() {
      const linkData = this.data
      this.error = null
      if (linkData.type === 'page' && !linkData.page) {
        this.error = 'Please select a page.'
        return
      }

      console.log('AttachLinkSave: data', linkData)
      this.onConfirm(linkData)
      this.onClose()
    }
  }
}
</script>

<style lang="scss">
@import 'style';

.attach-link-modal {
  max-width: 450px;
  height: min-content !important;
  padding-bottom: 15px;

  .md-radio {
    margin: 0;

    .md-radio-label {
      line-height: 22px;
    }
  }

  .element_item_label {
    width: 170px;
  }
}
</style>
