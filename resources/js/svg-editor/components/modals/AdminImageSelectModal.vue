<script>
import Modal from '@/public/Modal.vue'
import MasonryWall from '@yeger/vue-masonry-wall'
import appMixin from '../../mixins/app-mixin'

export default {
  name: 'AdminImageSelectModal',
  components: {
    Modal,
    MasonryWall
  },
  mixins: [appMixin],
  props: {
    show: {
      type: [Boolean],
      required: true
    },
    option: {
      type: [String, null],
      required: true
    }
  },
  data() {
    return {
      modalName: 'selectAdminImage',
      uploading: false,
      loading: false,
      backPage: null
    }
  },
  computed: {
    myImages() {
      if (this.option === 'image') {
        return this.graphicImages
      } else {
        return this.graphicIcons
      }
    }
  },
  watch: {
    sizing() {
      this.$refs.cropper.reset()
    }
  },
  methods: {
    updateImage(item) {
      this.$emit('confirm', { url: item.original_url })
    },
    titleCase(string) {
      return string[0].toUpperCase() + string.slice(1)
    }
  }
}
</script>

<template>
  <modal :show="show">
    <div class="bz-modal tw-bg-white tw-rounded tw-w-full tw-max-w-7xl tw-h-full">
      <div class="bz-modal-header">
        <div class="d-flex tw-text-xl">Select {{ titleCase(option) }}</div>
        <div class="close-icon cursor-pointer">
          <div class="tw-cursor-pointer" @click="$emit('close')">
            <i class="mdi mdi-close tw-text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bz-modal-body">
        <div class="bz-modal-section-1">
          <h2 class="bz-text-black bz-fw-700">{{ titleCase(option) }} library</h2>
        </div>
        <MasonryWall v-if="myImages.length" :items="myImages" :ssr-columns="1" :column-width="200" :gap="4" class="tw-w-full tw-h-full">
          <template #default="{ item }">
            <div class="position-relative my-image-container" @click="updateImage(item)">
              <img :src="item.original_url" alt="Stock Image" />
            </div>
          </template>
        </MasonryWall>
        <div v-else>
          <div class="tw-text-center">No items found!</div>
        </div>
      </div>
    </div>
  </modal>
</template>

<style lang="scss">
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
.vm--modal.select-image {
  max-width: 1480px !important;
  align-self: center;
  height: 80vh !important;
  overflow: hidden;

  .justified-container {
    width: 100%;
    height: 100% !important;
    background-color: white;
    overflow-y: auto;
    overflow-x: hidden;

    .justified-item {
      height: 200px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
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
        margin-top: 20px;
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

    &.delete {
      border: solid 3px crimson;
    }

    .control {
      position: absolute;
      right: 0;
      top: 0;
      align-items: center;
      justify-content: space-between;
      margin-top: 5px;
      display: flex;

      button {
        margin: 4px 4px;
        border: none;
        outline: none;
        border-radius: 4px;
        box-shadow: 0 0 4px 2px #00000012;
        align-items: center;
        justify-content: space-between;
        background-color: white;
        padding: 4px 10px;

        &:not(.active) {
          display: none;
        }

        &:hover {
          background-color: wheat;
        }

        b {
          padding-left: 4px;
          color: $activeColor;
        }
      }
    }

    &:hover {
      .img-over-layer {
        height: inherit;
        width: 100%;
        position: absolute;
        background-color: #00000034;

        button {
          display: flex !important;
        }
      }

      &:not(.delete) {
        img {
          border: solid 3px #0076df;
          cursor: pointer;
        }
      }
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
