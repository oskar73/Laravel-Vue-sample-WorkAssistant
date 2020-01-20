<template>
  <div>
    <div class="build-palette-modal">
      <div class="w-100 h-100">
        <div class="col-12 h-100">
          <p>Build your color template by selecting one from the following colors?</p>
          <div class="h-100 mb-4">
            <p>what would you like to name this palette?</p>
            <div class="w-100 h-100 mb-4">
              <bz-input v-model="paletteName" label="Palette Name" />
              <div v-if="submit && !paletteName" class="text-danger mt-1">Please insert the palette name</div>
            </div>
            <p>Category</p>
            <div class="w-100 h-100 mb-2">
              <bz-select v-model="category" :options="paletteCategories" />
            </div>
          </div>
          <theme-panel v-model="theme" :enable-save="false" @randomize="$emit('randomize')" @setActiveColor="setActiveColor" />
          <div class="mt-2">
            <div class="d-flex pt-3 justify-content-between">
              <md-radio v-model="pickerType" value="swatch" class="mb-2">Color Swatch</md-radio>
              <md-radio v-model="pickerType" value="image" class="mb-2">Color Image</md-radio>
            </div>
          </div>
          <div v-if="pickerType === 'swatch'" class="w-100">
            <div class="colors-wrap" :class="{ loading: (loading && theme.paletteImage) || processing }">
              <div
                v-for="(color, index) of theme.paletteColors"
                :key="index"
                class="color-item"
                :style="{ backgroundColor: color }"
                :class="{ active: isActiveColor(color) }"
                @click="handleColorItemClick(color)"
              ></div>
              <div v-if="(loading && theme.paletteImage) || processing" class="loading-spinner">
                <bz-spinner class="position-absolute" style="margin-right: 5px" />
              </div>
            </div>
          </div>
          <div v-else class="tw-flex tw-flex-col">
            <image-selector v-model="theme.paletteImage" :preview="false" :crop="false" :from-modal="true" :encode-base64="false" />
            <div class="d-flex justify-content-center align-items-center position-relative mt-2">
              <canvas
                v-show="theme.paletteImage"
                ref="canRef"
                class="canvas"
                :style="{ opacity: (loading && theme.paletteImage) || processing ? 0.3 : 1 }"
                style="cursor: crosshair"
                @mousedown="mouseDownHandler"
                @mouseup="mouseDown = false"
                @mousemove="pickCanvasColor"
              ></canvas>
              <bz-spinner v-if="loading && theme.paletteImage" class="position-absolute" style="margin-right: 5px" />
            </div>
          </div>
        </div>
      </div>
      <hr style="margin-top: auto" />
      <div class="w-100 d-flex justify-content-end pb-2">
        <button class="btn bz-btn-default mr-2 d-flex align-items-center" @click="onSave">
          <b>Save</b>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import BzSpinner from '../page/BzSpinner'
import BzInput from '../page/BzInput.vue'
import BzSelect from '../page/BzSelect.vue'
import ImageSelector from '../elements/ImageSelector'
import ThemePanel from '../page/Theme/ThemePanel'
import builderMixin from '../../mixins/builderMixin'
import { cloneDeep } from 'lodash'
import ColorThief from 'colorthief'
import { mapMutations } from 'vuex'

export default {
  name: 'BuildPaletteModal',
  components: { ThemePanel, ImageSelector, BzSpinner, BzInput, BzSelect },
  mixins: [builderMixin],
  props: {
    user: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      activeColor: 'backgroundColor',
      pickerType: 'swatch',
      mouseDown: false,
      canvas: null,
      context: null,
      // rendering image to canvas
      loading: false,
      processing: false,
      // swatching color palette is in progress
      openThemeNameModal: false,
      paletteName: '',
      submit: false,
      category: 0
    }
  },
  computed: {
    paletteImage() {
      return this.theme.paletteImage
    },
    paletteCategories() {
      if (this.user) {
        return this.userPalettes.advanced.map((category) => ({
          label: category.name,
          value: category.category_id
        }))
      } else {
        return this.systemPalettes.advanced.map((category) => ({
          label: category.name,
          value: category.category_id
        }))
      }
    },
    theme: {
      get() {
        return this.$store.state.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    }
  },
  watch: {
    pickerType(value) {
      if (value === 'image' && this.theme.paletteImage) {
        this.$nextTick(() => {
          this.renderImage()
        })
      }
    },
    paletteImage(value) {
      if (this.pickerType === 'image') {
        this.renderImage()
      }
      this.$emit('swatch', value)
    }
  },
  created() {
    this.themePreview = true
    this.theme = cloneDeep(this.theme)
  },
  mounted() {
    if (!this.theme.paletteImage) {
      this.pickerType = 'image'
    }
    this.category = this.paletteCategories[0]?.value
    this.refreshTheme()
  },
  methods: {
    ...mapMutations(['openModal']),
    onSave() {
      if (this.paletteName) {
        this.$emit('save', {
          name: this.paletteName,
          category_id: this.category,
          description: '',
          status: 1,
          image: this.theme.paletteImage
        })
        this.$emit('close')
        this.themePreview = false
      } else this.submit = true
    },
    renderImage() {
      try {
        this.loading = true
        const img = new Image()
        img.crossOrigin = 'Anonymous'
        const canvas = this.$refs.canRef
        const context = canvas.getContext('2d')

        this.canvas = canvas
        this.context = context
        const self = this
        img.onload = function () {
          canvas.width = this.width
          canvas.height = this.height

          const oldWidth = canvas.width
          context.drawImage(this, 0, 0)
          canvas.width = 370
          canvas.height = (canvas.height * canvas.width) / oldWidth

          context.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height)
          self.loading = false
        }
        img.onerror = function (error) {
          console.error('Palette Image rendering Error: ', error)
          console.error('Palette Image Url', this.theme.paletteImage)
        }
        img.src = this.theme.paletteImage + '?1'
        this.getColorPaletteFromImage(img.src)
      } catch (error) {
        console.error("Build Palette Modal: couldn't render image", error)
      }
    },
    setActiveColor(activeColor) {
      this.activeColor = activeColor + 'Color'
    },
    handleColorItemClick(color) {
      this.theme[this.themeMode][this.activeColor] = color
    },
    isActiveColor(color) {
      if (this.activeColor === 'modeColor') {
        return this.modeColor === color
      }
      return this.theme[this.activeColor] === color
    },
    mouseDownHandler(e) {
      this.mouseDown = true
      this.performAction(e)
    },
    pickCanvasColor(e) {
      if (!this.mouseDown) return false
      this.performAction(e)
    },
    performAction(e) {
      const x = ((e.pageX - e.target.getBoundingClientRect().left - window.pageXOffset) * this.canvas.width) / e.target.getBoundingClientRect().width
      const y = ((e.pageY - e.target.getBoundingClientRect().top - window.pageYOffset) * this.canvas.height) / e.target.getBoundingClientRect().height
      const imageData = this.context.getImageData(x, y, 1, 1).data
      const r = imageData[0]
      const g = imageData[1]
      const b = imageData[2]
      const color = '#' + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1)
      this.handleColorItemClick(color)
    },
    /**
     * get color palette from image
     * @param src
     * @returns {Promise<void>}
     */
    async getColorPaletteFromImage(src) {
      this.processing = true
      try {
        const colorThief = new ColorThief()
        const img = new Image()
        img.crossOrigin = 'anonymous'
        img.onload = () => {
          const result = colorThief.getPalette(img, 8)
          const colors = result.map((rgb) => {
            function componentToHex(c) {
              const hex = c.toString(16)
              return hex.length === 1 ? '0' + hex : hex
            }

            return '#' + componentToHex(rgb[0]) + componentToHex(rgb[1]) + componentToHex(rgb[2])
          })
          this.theme.paletteColors = colors
          this.setAdvancedColors(colors)
          this.processing = false
        }
        img.src = src
      } catch (error) {
        this.processing = false
        console.error('getColorPaletteFromImage Error: ', error)
      }
    }
  }
}
</script>
<style lang="scss">
.build-palette-modal {
  display: flex;
  flex-direction: column;

  &.vm--modal {
    width: calc(100% - 6px) !important;
  }

  .colors-wrap {
    margin-top: 20px;
    display: grid;
    grid-gap: 4px;
    grid-template-columns: repeat(auto-fill, minmax(35px, 1fr));
    position: relative;

    &.loading {
      min-height: 150px;
    }

    .color-item {
      width: 100%;
      height: 35px;
      border-radius: 2px;
      border: solid 1px #80808078;
      cursor: pointer;

      &.active {
        outline: solid 2px var(--bz-edit-active);
      }
    }
    .loading-spinner {
      position: absolute;
      justify-content: center;
      align-items: center;
      display: flex;
      top: 0;
      height: 100%;
      width: 100%;
      background-color: #cccccc7f;
      pointer-events: none;
    }
  }
}
</style>
