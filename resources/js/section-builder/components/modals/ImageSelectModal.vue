<template>
  <modal :show="open">
    <div class="bz-modal tw-bg-white tw-rounded tw-w-full tw-max-w-7xl tw-h-full">
      <div class="bz-modal-header">
        <div v-if="backPage != null" class="back-icon cursor-pointer" @click="handleBack()">
          <BackIcon />
        </div>
        <div class="d-flex">
          <button class="btn" :class="{ 'bz-btn-default': currentPage === 'unsplash' }" @click="currentPage = 'unsplash'">Images</button>
          <button class="btn ml-1" :class="{ 'bz-btn-default': currentPage === 'logos' }" @click="currentPage = 'logos'">Logos</button>
        </div>
        <div class="close-icon cursor-pointer">
          <button v-if="deleteItems.length > 0" class="delete-button mr-2 tw-cursor-pointer" @click="toggleDeleteConfirmModal()">
            <i class="mdi mdi-delete"></i>
            <b class="ml-2">Delete ({{ deleteItems.length }})</b>
          </button>
          <div class="cursor-pointer" @click="handleClose()">
            <i class="mdi mdi-close tw-text-2xl"></i>
          </div>
        </div>
      </div>
      <div v-if="currentPage === 'unsplash'" class="bz-modal-body">
        <div class="bz-modal-section-1">
          <h2 class="bz-text-black tw-text-2xl mr-3">Image library</h2>
          <div class="pull-left d-flex">
            <button class="btn bz-btn-default tw-flex tw-items-center tw-gap-x-2 mr-3 tw-rounded-0" data-current-page="upload" data-back-page="unsplash" @click="handlePageSwitch">
              <i class="mdi mdi-upload-outline tw-text-2xl"></i>
              Upload image
            </button>
            <button
              :class="`btn tw-flex tw-items-center tw-gap-x-2 mr-3 tw-rounded-0 ${library === 'stockgraphix' ? 'tw-bg-gray-300' : 'bz-btn-default'}`"
              :disabled="true"
              @click="library = 'stockgraphix'"
            >
              Stockgraphix
            </button>
            <button
              :class="`btn tw-flex tw-items-center tw-gap-x-2 mr-3 tw-rounded-0 ${library === 'unsplash' ? 'tw-bg-gray-300' : 'bz-btn-default'}`"
              @click="library = 'unsplash'"
            >
              Unsplash
            </button>
          </div>
          <div class="input-group pull-right" style="width: 350px">
            <input
              v-model="search"
              type="text"
              class="form-control h-100 tw-min-h-[37px]"
              name="search"
              :placeholder="'Search images in ' + library"
              @input="handleSearchUnsplashImage"
            />
            <div class="input-group-append">
              <button @click="getUnsplashFiles()" class="btn btn-outline-secondary" type="button">
                <SearchIcon />
              </button>
            </div>
          </div>
        </div>
        <div class="bz-modal-section-2 d-flex flex-column position-relative tw-overflow-y-auto">
          <div v-if="search" class="tw-w-full tw-h-full tw-overflow-y-auto tw-flex tw-flex-wrap tw-space-y-2 tw-space-x-2 tw-justify-between" @scroll="handleImageScroll">
            <div
              v-for="(item, index) in libraryImages"
              :key="index"
              class="tw-h-[150px] tw-cursor-pointer tw-border tw-border-transparent hover:tw-border-blue-500"
              @click="updateImage(item)"
            >
              <img :src="item.thumb" class="tw-h-full" />
            </div>
            <div class="tw-flex-1"></div>
            <div class="tw-h-[150px] tw-w-full tw-flex tw-items-center tw-justify-center">
              <Spinner v-if="loadingLibrary" class="!tw-w-8 !tw-h-8" />
            </div>
          </div>
          <div v-else class="tw-w-full tw-h-full tw-overflow-y-auto">
            <MasonryWall v-if="!loading" :items="myImages" :ssr-columns="1" :column-width="250" :gap="4" class="tw-w-full tw-h-full">
              <template #default="{ item }">
                <div class="position-relative my-image-container" :class="{ delete: deleteItems.includes(item.id) }" @click="updateImage(item)">
                  <div class="control">
                    <button :class="{ active: deleteItems.includes(item.id) }" @click.stop="deleteItems.toggle(item.id)">
                      <i class="mdi mdi-delete"></i>
                    </button>
                  </div>
                  <img :src="item.url" alt="Stock Image" />
                </div>
              </template>
            </MasonryWall>
          </div>
        </div>
      </div>
      <div v-if="currentPage === 'upload'" class="bz-modal-body">
        <Dropzone :added="handleFilesAdded" />
        <div class="w-100 h-100 d-flex align-items-center justify-content-center position-relative">
          <div class="image-link-form" :class="{ active: fromLink }">
            <div>Enter image link</div>
            <div class="d-flex align-items-center">
              <input class="form-control" placeholder="Add from link" />
              <button class="btn bz-btn-default ml-3 py-2 px-3">Save</button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="currentPage === 'uploading'" class="bz-modal-body tw-overflow-hidden tw-relative">
        <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-absolute bg-purple-100">
          <Spinner v-if="uploading" class="!tw-w-8 !tw-h-8" />
        </div>
        <MasonryWall :items="uploadingImages" :ssr-columns="1" :column-width="100" :gap="8" class="tw-w-full tw-h-full">
          <template #default="{ item }">
            <img :src="item.url" class="uploading-image tw-blur-sm" :class="{ uploading }" />
          </template>
        </MasonryWall>
      </div>
      <div v-if="currentPage === 'logos'" class="bz-modal-body">
        <div class="bz-modal-section-1">
          <h2 class="bz-text-black bz-fw-700">My Logos</h2>
          <div class="pull-right d-flex">
            <a href="https://bizinabox.com/logotyes" target="_blank" class="btn bz-btn-default mr-4" data-current-page="upload" data-back-page="unsplash" @click="handlePageSwitch">
              New Logo
            </a>
          </div>
        </div>
        <div class="d-flex flex-wrap position-relative">
          <template v-for="(item, index) in myLogos" :key="index">
            <img :src="item.previewContent" alt="Logo Image" />
          </template>
        </div>
      </div>
    </div>
    <image-delete-con-firm-modal :open="openDeleteConfirmModal" :on-confirm="deleteMyMedia" :on-close="toggleDeleteConfirmModal" />
  </modal>
</template>

<script>
import BackIcon from '../icons/Back.vue'
import SearchIcon from '../icons/Search.vue'
import MasonryWall from '@yeger/vue-masonry-wall'
import axios from 'axios'
import ImageDeleteConFirmModal from './ImageDeleteConFirmModal.vue'
import modalMixin from '../../mixins/modalMixin'
import Modal from '@/public/Modal.vue'
import Dropzone from '../elements/Dropzone.vue'
import Spinner from '@/public/Spinner.vue'
import { loadImage } from '@/section-builder/utils/helper'
import BzSelect from '@/public/BzSelect.vue'

export default {
  name: 'ImageSelectModal',
  components: {
    Spinner,
    Modal,
    ImageDeleteConFirmModal,
    Dropzone,
    MasonryWall,
    SearchIcon,
    BackIcon,
    BzSelect
  },
  mixins: [modalMixin],
  props: {
    open: {
      type: Boolean,
      default: false
    },
    confirmAction: {
      type: [Function, null],
      default: null
    },
    customClose: {
      type: [Function, null],
      default: null
    }
  },
  data() {
    return {
      library: 'unsplash',
      modalName: 'selectImage',
      currentPage: 'unsplash', // home, search, edit, uplo
      backPage: null,
      disabledClick: true,
      fromLink: false,
      uploadingImages: [],
      uploading: false,
      tab: 'images',
      tabs: {
        images: 'images',
        logos: 'logos'
      },
      dropzoneOptions: {
        url: '/',
        maxFilesize: 0.5
      },
      deleteItems: [],
      openDeleteConfirmModal: false,
      croppedImage: null,
      search: '',
      searchPage: 1,
      unsplashMinPage: 1,
      libMaxPage: 1,
      maxPage: 'Max page',
      zoom: 1,
      rotate: 0,
      zoomInput: false,
      initialScale: 1,
      saturation: 1,
      brightness: 1,
      contrast: 1,
      hue: 3.14,
      intensity: 1,
      timer: null,
      loadingLibrary: false,
      // loadingLibrary: true,
      loading: false,
      myImages: [],
      libraryImages: [],
      myLogos: [],
      loadUnsplashImageDeboucingTimer: null
    }
  },
  watch: {
    sizing() {
      this.$refs.cropper.reset()
    },
    library() {
      this.libraryImages = []
    },
    async open(val) {
      if (val) {
        await this.getMyFiles()
      }
    }
  },
  mounted() {
    this.getMyFiles()
  },
  methods: {
    handleClose() {
      if (this.customClose) {
        this.customClose()
      }
      this.onClose()
    },
    updateImage(item) {
      if (this.deleteItems.length === 0) {
        if (!this.uploading) {
          if (this.confirmAction) {
            this.confirmAction(item)
            this.$emit('close')
          } else {
            this.onConfirm(item)
          }
        }
      } else {
        this.deleteItems.toggle(item.id)
      }
    },
    handleImageScroll(event) {
      const element = event.target
      if (element.scrollHeight - element.scrollTop < element.clientHeight + 150 && !this.loadingLibrary) {
        if (this.libMaxPage > this.searchPage) {
          this.searchPage++
          this.getUnsplashFiles()
        }
      }
    },
    handlePageSwitch(event) {
      this.currentPage = event.target.dataset.currentPage
      this.backPage = event.target.dataset.backPage
    },
    async getMyFiles() {
      if (window.user) {
        const self = this
        this.loading = true
        axios.get('/account/getStockFiles').then(async ({ data }) => {
          if (data.status) {
            this.myImages = data.data.images
            this.myLogos = data.data.logos
          }
          self.loading = false
        })
      }
    },
    async getUnsplashFiles() {
      if (!this.search) return
      if (this.library === 'unsplash') {
        const unsplashApiKey = this.$config?.unsplashApiKey || window.unsplashApiKey
        if (unsplashApiKey && !this.loadingLibrary) {
          this.loadingLibrary = true
          axios
            .get('https://api.unsplash.com/search/photos', {
              params: {
                query: this.search,
                page: this.searchPage,
                per_page: 50,
                client_id: unsplashApiKey
              }
            })
            .then(async ({ data }) => {
              if (data.results.length) {
                this.deleteItems = []
                this.libMaxPage = data.total_pages
                this.maxPage = 'Min page 1 & Max page: ' + data.total_page
                for (const item of data.results) {
                  const newImage = {
                    url: item.urls.raw,
                    thumb: item.urls.thumb
                  }
                  loadImage(item.urls.thumb).then((img) => {
                    newImage.width = img.width
                    newImage.height = img.height
                    this.libraryImages.push(newImage)
                  })
                }
                this.libraryImages = [...this.libraryImages]
              }
              this.loadingLibrary = false
            })
            .catch((err) => {
              console.error(err)
              this.loadingLibrary = false
            })
        } else {
          console.error('Unsplash Key does not exist')
        }
      } else {
        this.loadingLibrary = true
        axios
          .post('/api/media/stockgraphix', {
            query: this.search,
            page: this.searchPage,
            per_page: 50
          })
          .then((res) => {
            this.deleteItems = []
            this.libMaxPage = res.data.total_pages
            this.maxPage = 'Min page 1 & Max page: ' + res.data.total_page
            for (const item of res.data.data) {
              loadImage(item.thumb).then((img) => {
                item.width = img.width
                item.height = img.height
                this.libraryImages.push(item)
              })
            }
            this.libraryImages = [...this.libraryImages]
            this.loadingLibrary = false
          })
          .catch((err) => {
            console.error(err)
            this.loadingLibrary = false
          })
      }
    },
    handleBack() {
      this.currentPage = this.backPage
      switch (this.backPage) {
        case 'home': {
          this.backPage = null
          break
        }
        default: {
          this.backPage = null
        }
      }
    },
    handleSearchUnsplashImage() {
      if (this.search) {
        if (this.loadUnsplashImageDeboucingTimer !== null) {
          clearTimeout(this.loadUnsplashImageDeboucingTimer)
        }
        this.loadUnsplashImageDeboucingTimer = setTimeout(() => {
          this.searchPage = 1
          this.libraryImages = []
          this.getUnsplashFiles()
          this.loadUnsplashImageDeboucingTimer = null
        }, 500)
      } else {
        clearTimeout(this.loadUnsplashImageDeboucingTimer)
        this.loadingLibrary = false
        this.libraryImages = []
      }
    },
    async handleFilesAdded(files) {
      this.uploading = true
      this.loadingLibrary = true
      this.currentPage = 'uploading'
      this.uploadingImages = []
      let images = []
      for (let i = 0; i < files.length; i++) {
        if (i > 10) break
        images.push(this.getImageFromFile(files[i]))
      }
      images = await Promise.all(images)
      this.uploadingImages = images.filter((img) => img)
      axios.post('/account/uploadStockFiles', { images: this.uploadingImages }).then((res) => {
        if (res.data.status) {
          const newImages = this.uploadingImages.map((image, index) => {
            this.uploading = false
            return {
              ...image,
              url: res.data.data[index]
            }
          })
          this.uploadingImages = Object.assign([], newImages)
          if (this.uploadingImages.length === 1) {
            this.updateImage(this.uploadingImages[0])
          }
          this.loadingLibrary = false
          this.currentPage = 'unsplash'
        }
      })
    },
    deleteMyMedia() {
      axios.post('/account/deleteStockFiles', { ids: this.deleteItems }).then(({ data }) => {
        if (data.status) {
          this.toggleDeleteConfirmModal()
          this.myImages = this.myImages.filter((item) => !this.deleteItems.includes(item.id))
          this.myImages = Object.assign([], this.myImages)
          this.deleteItems = []
        }
      })
    },
    toggleDeleteConfirmModal() {
      this.openDeleteConfirmModal = !this.openDeleteConfirmModal
    },
    async getImageFromFile(file) {
      return new Promise((resolve) => {
        const img = new Image()
        img.onload = function () {
          const oFReader = new FileReader()
          oFReader.readAsDataURL(file)
          oFReader.onload = function () {
            resolve({
              width: img.width,
              height: img.height,
              url: this.result
            })
          }
        }
        img.onerror = function () {
          resolve(false)
        }
        img.src = window.URL.createObjectURL(file)
      })
    }
  }
}
</script>

<style lang="scss">
@import 'style';

.unsplash-loading-container {
  position: absolute;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #ffffff34;
  z-index: 2;
  top: 0;
  left: 0;
}

$activeColor: #0076df;
.select-image {
  max-width: 1480px !important;
  align-self: center;
  height: 80vh !important;
  overflow: hidden;
}

.bz-modal {
  .bz-modal-header {
    width: 100%;
    height: 60px;
    box-shadow: 0 0 8px 4px #00000034;
    align-items: center;
    display: flex;
    padding: 5px 20px;

    .close-icon {
      display: flex;
      margin-left: auto;
      align-items: center;

      .delete-button {
        margin: 4px 4px;
        border: none;
        outline: none;
        border-radius: 4px;
        box-shadow: 0 0 4px 2px #00000012;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #ff850f;
        padding: 7px 15px;

        &:hover {
          background-color: #d93f02;
        }
      }
    }
  }

  .bz-modal-body {
    width: 100%;
    height: calc(100% - 60px);
    padding: 20px;

    .bz-modal-section-1 {
      width: 100%;
      align-items: center;
      display: flex;

      .pull-right {
        margin-left: auto;
      }
    }

    .bz-modal-section-2 {
      margin-top: 10px;
      width: 100%;
      align-items: center;
      display: flex;
      justify-content: center;
      height: calc(100% - 70px);
    }

    .bz-dropzone {
      width: 100%;
      height: 100%;
      border: none;
      box-shadow: none;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;

      .dz-message {
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
      }
    }

    &.disabledClick {
      &:hover {
        pointer-events: none;
      }
    }

    .select-photo-container {
      width: 100%;
      height: 100%;
      border: 2px dashed rgb(204, 204, 204);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      max-width: 600px;
      max-height: min(300px, 100% - 200px);
    }

    .image-link-form {
      bottom: -100px;
      width: 100%;
      position: absolute;
      left: 0;

      &.active {
        bottom: 0;
        transition: 0.5s;
      }
    }
  }
}

.link {
  color: #0076df;
  text-decoration: underline;
  padding: 10px 5px;
  border-radius: 4px;

  &:hover {
    background-color: rgba(0, 118, 223, 0.04);
    cursor: pointer;
  }
}

.uploading-image {
  &.uploading {
    pointer-events: none;
  }

  &:not(.uploading) {
    &:hover {
      border: solid 4px #0076df;
      cursor: pointer;
    }
  }
}

.my-image-container {
  height: inherit;
  border: solid 2px transparent;

  img {
    width: 100%;
  }

  &:hover {
    border-color: #0076df;
  }

  &.delete {
    border-color: crimson;
  }

  .control {
    position: absolute;
    right: 0;
    top: 0;
    align-items: center;
    justify-content: space-between;
    margin-top: 5px;
    display: flex;
  }
}

.image-editor {
  .side-bar {
    width: 280px;
    display: flex;

    hr {
      margin-top: 0;
      margin-bottom: 0;
    }

    .side-menu {
      background-color: rgb(246, 246, 246);
      width: 80px;
      height: 100%;
      padding-top: 30px;
      box-shadow: inset 0 0 0 1px #8080803f;

      .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        padding: 14px 0;
        cursor: pointer;

        &.active {
          color: $activeColor;
          background-color: white;
        }
      }
    }

    .side-panel {
      width: 200px;
      background-color: white;
      display: none;
      flex-direction: column;
      padding: 5px;

      &.active {
        display: flex;
      }

      .slider-horizontal {
        height: 10px;
        min-width: 190px;
        padding: 0;
        margin: 0;

        .slider-wrapper {
          top: 5px;
        }

        .slider-thumb,
        .slider-thumb-label,
        .slider-track-fill {
          background-color: $activeColor;
        }
      }

      .label {
        padding-left: 7px;
        margin-top: 20px;
      }

      .row {
        margin-right: -5px;
        margin-left: -5px;
        flex: unset;
      }

      .col-4 {
        padding-right: 6px;
        padding-left: 6px;
      }

      .crop-item {
        padding: 6px;
        border-radius: 4px;

        &.active,
        &:hover {
          background-color: rgba(0, 118, 223, 0.1);
        }
      }
    }
  }

  .editor-body {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgb(240, 240, 240);
    width: calc(100% - 280px);
    height: 100%;
    position: relative;
    overflow: hidden;

    .zoomArea {
      position: absolute;
      width: 100%;
      top: calc(10% - 30px);
      left: 0;
      z-index: 99999999;
      display: flex;
      align-items: center;
      justify-content: center;

      input {
        width: 50%;
      }
    }

    .rotateArea {
      position: absolute;
      width: 100%;
      bottom: calc(10% - 30px);
      left: 0;
      z-index: 99999999;
      display: flex;
      align-items: center;
      justify-content: center;

      input {
        width: 50%;
      }
    }

    .cropper {
      height: 100%;
      width: 100%;

      .vue-advanced-cropper__image-wrapper {
        overflow: unset !important;
      }

      .vue-advanced-cropper__background {
        background-color: transparent !important;
      }

      img {
        object-fit: contain !important;
      }
    }

    &.crop {
      .vue-advanced-cropper__foreground {
        opacity: 0.7;
        background-color: white !important;
      }
    }

    &.tune {
      .vue-advanced-cropper__foreground {
        opacity: 1;
        background-color: rgb(240, 240, 240) !important;
      }
    }
  }
}

.bz-btn-default {
  background-color: #0076df !important;
  border: solid 1px #0076df !important;
  border-radius: 4px;
  color: white !important;
  cursor: pointer;

  &:hover {
    background-color: white;
    color: #0076df;
  }
}
</style>
