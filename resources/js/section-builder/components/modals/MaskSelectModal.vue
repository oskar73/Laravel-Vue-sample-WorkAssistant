<template>
  <modal :show="open">
    <div class="bz-modal tw-bg-white tw-rounded tw-w-full tw-max-w-7xl tw-h-full">
      <div class="bz-modal-header">
        <div v-if="backPage != null" class="back-icon cursor-pointer" @click="handleBack()">
          <BackIcon />
        </div>
        <div class="close-icon cursor-pointer">
          <div class="cursor-pointer" @click="onClose">
            <i class="mdi mdi-close tw-text-2xl"></i>
          </div>
        </div>
      </div>
      <div class="bz-modal-body">
        <div class="bz-modal-section-1">
          <h2 class="bz-text-black">Mask Selection</h2>
        </div>
        <div class="bz-modal-section-2 tw-overflow-y-auto">
          <div class="tw-grid tw-grid-cols-6 tw-gap-2 tw-mt-3">
            <div class="tw-min-h-[78px] tw-relative tw-cursor-pointer" :style="{ paddingTop: graphic.width / graphic.height + '%' }" @click="addMask(null)">
              <div class="tw-absolute tw-w-full tw-h-full tw-top-0 tw-left-0 tw-flex tw-justify-center tw-items-center tw-border">
                <i class="mdi mdi-cancel tw-text-gray-200 tw-text-xl"></i>
              </div>
            </div>
            <div v-for="(mask, index) in graphicMasks" :key="index" class="tw-w-full tw-cursor-pointer tw-border tw-border-solid" @click="addMask(mask)">
              <img :src="mask.original_url" class="tw-w-full" alt="Mask Svg" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import BackIcon from '../icons/Back.vue'
import modalMixin from '../../mixins/modalMixin'
import Modal from '@/public/Modal.vue'

export default {
  name: 'MaskSelectModal',
  components: {
    Modal,
    BackIcon
  },
  mixins: [modalMixin],
  props: {
    open: {
      type: Boolean,
      default: false
    },
    graphicMasks: {
      type: Array,
      required: true
    },
    addMask: {
      type: Function,
      required: true
    },
    graphic: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      modalName: 'selectImage'
    }
  },
  watch: {
    sizing() {
      this.$refs.cropper.reset()
    }
  },
  methods: {}
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
      display: block;
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
