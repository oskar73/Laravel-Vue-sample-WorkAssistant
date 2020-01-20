<template>
  <div class="w-100">
    <hr class="my-3" />
    <template v-if="activeSection.category.slug === 'header'">
      <div class="px-2 my-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <span class="element_item_label">Connect to section below</span>
          <label class="custom_switch">
            <input v-model="backgroundData.connectToSectionBelow" type="checkbox" />
            <span />
          </label>
        </div>
      </div>
    </template>
    <template v-if="!backgroundData.connectToSectionBelow">
      <div class="p-2">
        <div class="btn-group w-100 background-type">
          <button type="button" class="btn bg-white p-2" :class="{ active: backgroundData.type === 'color' }" @click.prevent="backgroundData.type = 'color'">Color</button>
          <button type="button" class="btn bg-white p-2" :class="{ active: backgroundData.type === 'image' }" @click.prevent="backgroundData.type = 'image'">Image</button>
          <button type="button" class="btn bg-white p-2" :class="{ active: backgroundData.type === 'video' }" @click.prevent="backgroundData.type = 'video'">Video</button>
        </div>
      </div>
      <div v-if="backgroundData.type === 'color'" class="w-100">
        <div class="p-2 no-move">
          <div class="d-flex align-items-center mb-4 px-2">
            <span style="white-space: nowrap">Use Custom Color</span>
            <bz-switch v-model="useCustomColor" @update:modelValue="handleBackgroundColorModeChange" />
          </div>
          <div class="w-100 d-flex justify-content-center">
            <sketch v-if="useCustomColor && backgroundData.color" :model-value="backgroundData.color" :disable-alpha="true" @update:modelValue="handleBackgroundColorChange" />
          </div>
        </div>
        <div class="background-effect d-flex justify-content-between align-items-center mb-2">
          <span class="element_item_label">Pattern</span>
          <label class="custom_switch">
            <input v-model="backgroundData.pattern" type="checkbox" />
            <span />
          </label>
        </div>
        <div v-if="backgroundData.pattern" class="w-100 p-4 background-pattern">
          <div class="row">
            <div v-for="pattern of bgPatterns" :key="pattern.name" class="col-3">
              <bz-aspect-view>
                <img
                  :src="s3_asset(pattern.url)"
                  class="bz-img-full pattern-item"
                  :class="{ active: backgroundData.patternName === pattern.name }"
                  :alt="pattern.name"
                  @click="backgroundData.patternName = pattern.name"
                />
              </bz-aspect-view>
            </div>
          </div>

          <div class="row">
            <div class="col-6 d-flex align-items-center">
              <span class="element_item_label">Strength</span>
            </div>
            <div class="col-6 no-move">
              <slider v-model="backgroundData.patternStrength" :min="0" :max="100" thumb-label />
            </div>
            <div class="col-6 d-flex align-items-center">
              <span class="element_item_label">Animation</span>
            </div>
            <div class="col-6">
              <bz-select v-model="backgroundData.animation" :options="animations" />
            </div>
          </div>
        </div>
      </div>

      <div v-if="backgroundData.type === 'image'" class="w-100">
        <div class="w-100 p-4">
          <template v-if="backgroundData.image">
            <bz-aspect-view :ratio="3 / 5">
              <img :src="backgroundData.image" class="bz-img-full" alt="" />
            </bz-aspect-view>
            <button class="w-100 btn bz-btn-default mt-2" @click="openImageSelector">Replace</button>
          </template>
          <template v-else>
            <button class="w-100 btn bz-btn-default mt-3" @click="openImageSelector">Select Image</button>
          </template>
          <div class="w-100 mt-3">
            <div class="row">
              <div class="col-6 d-flex align-items-center">
                <span class="element_item_label">Animation</span>
              </div>
              <div class="col-6">
                <bz-select v-model="backgroundData.animation" :options="animations" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Background Video Section -->
      <div v-if="backgroundData.type === 'video'" class="w-100 background-video">
        <div class="w-100 p-2 mt-2">
          <button class="w-100 btn bz-btn-default mt-3" @click="openVideoSelector">Select Video</button>
          <div class="col-12">
            <div class="row">
              <div v-for="video of bgVideos" :key="video.name" class="col-6">
                <bz-aspect-view :ratio="1 / 2">
                  <img
                    :src="s3_asset(video.url)"
                    class="bz-img-full video-item"
                    :class="{ active: backgroundData.video?.includes(video.name) }"
                    :alt="video.name"
                    @click="handleVideoItemClick(video.name)"
                  />
                </bz-aspect-view>
              </div>
            </div>
          </div>
        </div>
      </div>
      <template v-if="backgroundData.type === 'image' || backgroundData.type === 'video'">
        <div class="background-effect d-flex justify-content-between align-items-center mb-2 mt-2">
          <span class="element_item_label">Overlay</span>
          <label class="custom_switch">
            <input v-model="backgroundData.overlay" type="checkbox" />
            <span />
          </label>
        </div>
        <div v-if="backgroundData.overlay" class="w-100 p-4 background-pattern no-move">
          <bz-color-set v-model="backgroundData.overlayColor" />
          <div class="row mt-3">
            <div class="col-4 d-flex align-items-center">
              <span class="element_item_label">Opacity</span>
            </div>
            <div class="col-8 no-move">
              <slider v-model="backgroundData.overlayOpacity" :min="0" :max="100" thumb-label />
            </div>
          </div>
        </div>
      </template>
    </template>
  </div>
</template>

<script>
import BzColorSet from './BzColorSet.vue'
import BzAspectView from '../section/BzAspectView.vue'
import bgVideos from '../../data/bgVideos'
import bgPatterns from '../../data/bgPatterns'
import BzSelect from './BzSelect.vue'
import { cloneDeep, merge } from 'lodash'
import { Sketch } from '@lk77/vue3-color'
import builderMixin from '../../mixins/builderMixin'
import BzSwitch from './BzSwitch.vue'
import Slider from '@vueform/slider'

export default {
  name: 'SettingBackground',
  components: { BzSwitch, BzSelect, BzAspectView, BzColorSet, Sketch, Slider },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      animations: ['none', 'parallax', 'fixed', 'contain', 'animate'],
      bgVideos,
      bgPatterns,
      backgroundData: {},
      useCustomColor: false
    }
  },
  watch: {
    modelValue: {
      immediate: true,
      handler() {
        if (this.backgroundData !== this.modelValue) {
          this.backgroundData = cloneDeep(
            merge(
              {
                type: 'color',
                patternStrength: 100,
                image: null,
                color: null,
                video: null,
                patternName: null,
                pattern: false,
                overlay: false,
                isYoutube: false,
                isS3: false
              },
              this.modelValue ?? {}
            )
          )
          this.useCustomColor = Boolean(this.backgroundData.color)
        }
      }
    },
    backgroundData: {
      deep: true,
      handler(val) {
        this.$emit('update:modelValue', val)
      }
    }
  },
  methods: {
    handleBackgroundColorModeChange(bgMode) {
      if (bgMode) {
        this.backgroundData.color = this.backgroundColor
      } else {
        this.backgroundData.color = null
      }
      this.useCustomColor = bgMode
    },
    handleBackgroundColorChange(color) {
      this.backgroundData.color = color.hex
    },
    handleVideoItemClick(videoName) {
      this.backgroundData.video = this.s3_asset(`builder/videos/${videoName}.mp4`)
      this.backgroundData.isYoutube = false
    },
    openImageSelector() {
      this.$store.commit('openModal', {
        name: 'selectImage',
        onChange: ({ url }) => {
          this.backgroundData.image = url
          this.$store.commit('closeModal')
        }
      })
    },
    openVideoSelector() {
      this.$store.commit('openModal', {
        name: 'selectVideo',
        onChange: ({ url, isYoutube = false, isS3 = false }) => {
          this.backgroundData.video = url
          this.backgroundData.isYoutube = isYoutube
          this.backgroundData.isS3 = isS3
        }
      })
    }
  }
}
</script>

<style scoped>
.vc-sketch {
  width: 100% !important;
  box-shadow: none !important;
  border: solid 1pc #00000012;
}
</style>
