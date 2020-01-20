<template>
  <Modal :show="open" name="page-setting-modal" @closed="onClose()">
    <div class="tw-bg-white tw-w-full tw-max-w-[600px] tw-rounded">
      <div style="background-color: rgb(246, 246, 246)" class="p-3">
        <h5 class="tw-text-left tw-text-xl tw-font-bold">{{ activePage.name }}</h5>
      </div>
      <div>
        <tabs>
          <tab name="Properties">
            <div class="tw-p-4">
              <bz-input v-model="pageName" label="Page Name" :height="40" :required="true" :invalid="!pageName" />
              <bz-input v-show="activePage.url" v-model="page_url" label="Page Url" :height="40" class="mt-4" prefix="/" />
              <small v-show="!uniqueUrl" style="text-transform: lowercase; color: #ff1744">This url is already used</small>
            </div>
          </tab>
          <tab name="Seo">
            <div class="seo-container tw-p-4 tw-overflow-auto tw-max-h-[calc(100vh-180px)]">
              <p class="tw-border tw-mb-4">Improve the way this page shows up in search results by setting a good title and description.</p>
              <bz-input v-model="seoTitle" label="Title" :height="40" />
              <bz-input v-model="seoTags" label="Tags" :height="40" class="mt-3" />
              <bz-input v-model="seoDescription" label="Description" :multiple="true" class="mt-3" :rows="3" />
              <image-selector v-model="seoImage" />
            </div>
          </tab>
        </tabs>
      </div>
      <hr style="margin-top: auto" />
      <div class="tw-w-full tw-flex tw-justify-end tw-py-4">
        <button class="btn bz-btn-default-outline mr-3" @click="onClose()">
          <b>Cancel</b>
        </button>
        <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm()">
          <b>Apply</b>
        </button>
      </div>
    </div>
  </Modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import { cloneDeep } from 'lodash'
import ImageSelector from '../elements/ImageSelector.vue'
import builderMixin from '../../mixins/builderMixin'
import Modal from '@/public/Modal.vue'
import eventBus from '@/public/eventBus'

export default {
  components: {
    Modal,
    ImageSelector,
    BzInput
  },
  mixins: [builderMixin],
  props: {
    open: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
      pageName: '',
      page_url: '',
      seoTitle: '',
      seoTags: '',
      seoDescription: '',
      seoImage: ''
    }
  },
  computed: {
    uniqueUrl () {
      if (!this.activePage.url) return true
      if (this.page_url && this.allPages.map(({ url }) => url).includes(this.page_url) && this.page_url !== this.activePage.url) return false
      return !!this.page_url
    }
  },
  mounted () {
    console.log('OpenPageSettingModal')
    this.pageName = this.activePage.name
    this.page_url = this.activePage.url
    if (this.activePage.data?.seo) {
      this.seoTitle = this.activePage.data.seo.title
      this.seoTags = this.activePage.data.seo.tags
      this.seoDescription = this.activePage.data.seo.description
      this.seoImage = this.activePage.data.seo.image
    }
  },
  methods: {
    onClose () {
      this.$store.commit('setStore', {
        path: 'modals.openPageSettingModal',
        value: false
      })
    },
    onConfirm () {
      if (!this.uniqueUrl) return

      if (!this.activePage) {
        console.warn('PageSettingModal.onConfirm: active page is undefined.')
        return
      }

      // Update Page Name
      this.activePage.name = this.pageName

      if (!this.activePage.data) {
        this.activePage.data = {}
      }

      this.activePage.data.seo = {
        title: this.seoTitle,
        keywords: this.seoTags,
        description: this.seoDescription,
        image: this.seoImage
      }

      // Update Page URL
      this.activePage.url = this.page_url

      // Update browser url
      this.$router.push({
        path: this.page_url
      })

      this.onClose()
    }
  }
}
</script>
<style lang="scss">
@import 'style';

.page-setting-modal {
  display: flex;
  flex-direction: column;
  height: auto !important;
  top: 100px !important;

  .md-tabs {
    .md-button-content {
      text-transform: capitalize;
    }

    .md-tabs-navigation.md-elevation-0 {
      background-color: rgb(246, 246, 246) !important;
      padding: 0 20px;
    }
  }

  .md-field .md-input,
  .md-field .md-textarea {
    height: 32px;
    padding: 0;
    display: block;
    flex: 1;
    border: none;
    background: none;
    transition: 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-property: font-size, padding-top, color;
    font-family: inherit;
    font-size: 16px;
    line-height: 32px;
  }

  .seo-container {
    max-height: 60vh;
    overflow-y: auto;
  }
}
</style>
