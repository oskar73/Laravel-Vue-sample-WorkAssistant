<template>
  <div v-click-outside="handleOutsideClick" :style="{ left: left + 'px', top: top + 'px' }" class="image-editor-root">
    <div class="more-menu">
      <div class="d-flex flex-wrap">
        <div class="mask-item" :style="{ backgroundImage: `url(${asset('assets/img/masks/none.svg')})` }" @click="addMask(null)"></div>
        <template v-for="(maskName, index) in maskNames" :key="index">
          <div style="margin-right: 3px" :style="{ border: imageData.maskImage === asset('assets/img/masks/' + maskName) ? 'solid 1px #0076df' : 'solid 1px #00000000' }">
            <div class="mask-item" :style="{ backgroundImage: `url(${asset('assets/img/masks/' + maskName)})` }" @click="addMask(asset('assets/img/masks/' + maskName))"></div>
          </div>
        </template>
      </div>
    </div>
    <div class="tool-bar">
      <template v-if="!noZoom">
        <div class="zoom-in-out-bar tw-items-center">
          <i class="mdi mdi-magnify-minus-outline tw-text-gray-500 tw-text-xl" @click.prevent="zoomOut"></i>
          <div class="tw-px-4 tw-flex tw-flex-1">
            <slider v-model="imageData.transform.scale" :lazy="false" :tooltips="false" :min="1" :max="3" :step="0.1" />
          </div>
          <i class="mdi mdi-magnify-plus-outline tw-text-gray-500 tw-text-xl" @click.prevent="zoomIn"></i>
        </div>
        <div class="divider"></div>
      </template>

      <div class="item-wrapper" @click.prevent="openSelectImage()">
        <i class="mdi mdi-image tw-text-gray-500 tw-text-xl"></i>
      </div>
      <div class="item-wrapper" @click.prevent="openAttachLinkModal()">
        <i class="mdi mdi-link tw-text-gray-500 tw-text-xl"></i>
      </div>
    </div>
  </div>
</template>
<script>
import Slider from '@vueform/slider'
import { mapMutations } from 'vuex'
import eventBus from '@/public/eventBus'

export default {
  name: 'ImageEditor',
  components: { Slider },
  props: {
    left: {
      type: Number,
      default: 0
    },
    top: {
      type: Number,
      default: 0
    },
    modelValue: {
      type: Object,
      required: true
    },
    noZoom: {
      type: Boolean,
      default: false
    }
  },
  data: function () {
    return {
      maskNames: ['pentagon.svg', 'triangle.svg', 'star.svg', 'hart.svg', 'nonagon.svg', 'top-left-rounded.svg', 'circle.svg'],
      imageData: {
        maskImage: '',
        transform: {
          scale: 1
        }
      },
      viewMore: false
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      handler() {
        this.imageData = this.modelValue
      }
    },
    imageData: {
      deep: true,
      handler(v) {
        eventBus.$emit('image:update')
        this.$emit('update:modelValue', v)
      }
    }
  },
  methods: {
    handleOutsideClick(event) {
      const target = event.target
      if (!target.closest('.bz-element') && !target.classList.contains('bz-element')) {
        if (window.bzElementEditor) {
          window.bzElementEditor.unmount()
          window.bzElementEditor = null
        }
      }
    },
    handleChange(v) {
      this.$emit('update:scale', v)
    },
    addMask(url) {
      this.imageData.maskImage = url
    },
    openSelectImage() {
      this.openModal({
        name: 'selectImage',
        onChange: (data) => {
          this.imageData.src = data.url
          this.imageData.thumb = data.thumb
          this.imageData.thumbWidth = data.width
          this.imageData.thumbHeight = data.height
          this.$store.commit('closeModal')
        }
      })
    },
    openAttachLinkModal() {
      this.openModal({
        name: 'attachLinkModal',
        data: this.imageData.link,
        onChange: (link) => {
          this.imageData.link = link
          this.$store.commit('closeModal')
        }
      })
    },
    openAltTextModal() {
      this.openModal({
        name: 'altText',
        data: {
          altText: this.imageData.altText
        },
        onChange: (data) => {
          this.imageData.altText = data.altText
        }
      })
    },
    zoomIn() {
      this.imageData.transform.scale = Number(this.imageData.transform.scale || 0) + 0.1
    },
    zoomOut() {
      const scale = Number(this.imageData.transform.scale || 0)
      if (scale > 1) {
        this.imageData.transform.scale = scale - 0.1
      }
    },
    ...mapMutations(['openModal'])
  }
}
</script>

<style lang="scss">
.image-editor-root {
  position: fixed;
  z-index: 1000;
  width: 305px;

  .mask-item {
    width: 30px;
    height: 30px;
    opacity: 0.1;
    cursor: pointer;

    &.active {
      border: solid 1px #0076df;
    }
  }

  .divider {
    width: 1px;
    background-color: #777777;
    height: 18px;
    margin: 0 4px;
  }

  .tool-bar {
    background-color: white;
    border-radius: 2px;
    box-shadow: 0 0 2px 1px #00000012;
    display: flex;
    align-items: center;
    justify-content: space-around;
    width: max-content;

    .zoom-in-out-bar {
      display: flex;
      justify-content: space-between;
      width: 200px;
      padding: 4px;
    }

    .item-wrapper {
      padding: 4px;
      cursor: pointer;

      &:hover:not(.disabled) {
        background-color: #8080803f;
      }
    }
  }

  .more-menu {
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 6px;
    z-index: 9999999;
    background-color: white;
    box-shadow: 0 0 2px 1px #00000012;
    margin-bottom: 2px;
  }
}
</style>
