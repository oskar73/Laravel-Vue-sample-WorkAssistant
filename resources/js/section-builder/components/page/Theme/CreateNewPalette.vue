<template>
  <div>
    <div class="create-new-palette">
      <div class="w-100">
        <div v-if="step >= 0" class="mb-4">
          <p>What would you like to name this palette?</p>
          <div class="w-100 tw-mt-2">
            <bz-input v-model="paletteName" label="Palette Name" :required="true" />
          </div>
        </div>
        <div v-if="step >= 1" class="mb-4">
          <div>Category</div>
          <div class="mt-1">
            <bz-select v-model="category" :options="paletteCategories" />
          </div>
        </div>
        <div v-if="step >= 2" class="mb-4">
          <color-mode v-model="colorMode" />
          <new-theme-palette-item
            ref="paletteItemRef"
            v-model="newPalette.colors"
            :enable-save="false"
            :enable-lock="colorCreator === 'random'"
            @changedPaletteColor="changedPaletteColor"
            @setActiveColor="setActiveColor"
          />
          <div class="card mt-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleColorCreator('random')">
              Random Colors
              <bz-arrow-up-icon v-if="colorCreator === 'random'" />
              <bz-arrow-down-icon v-else />
            </div>
            <div v-if="colorCreator === 'random'" class="d-flex justify-content-end">
              <button v-if="randomBackupColors.length" class="btn bz-btn-danger mr-2 p-1" @click="onClickUndoRandomize">Undo</button>
              <button class="btn bz-btn-default p-1" @click="onClickRandomize">Randomize</button>
            </div>
          </div>
          <div class="card mt-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleColorCreator('image')">
              Use a Color Image
              <bz-arrow-up-icon v-if="colorCreator === 'image'" />
              <bz-arrow-down-icon v-else />
            </div>
            <div v-show="colorCreator === 'image'">
              <div class="tw-flex tw-flex-col">
                <image-selector v-model="newPalette.paletteImage" :preview="false" :crop="false" :from-modal="true" :encode-base64="false" />
                <div class="d-flex justify-content-center align-items-center position-relative mt-2">
                  <canvas
                    v-show="newPalette.paletteImage"
                    ref="canRef"
                    class="canvas"
                    style="cursor: crosshair; width: 100% !important"
                    @mousedown="mouseDownHandler"
                    @mouseup="imageMouseDown = false"
                    @mousemove="pickCanvasColor"
                  ></canvas>
                  <bz-spinner v-if="imageLoading && newPalette.paletteImage" class="position-absolute" style="margin-right: 5px" />
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleColorCreator('swatch')">
              Color Swatch Creator
              <bz-arrow-up-icon v-if="colorCreator === 'swatch'" />
              <bz-arrow-down-icon v-else />
            </div>
            <div v-show="colorCreator === 'swatch'">
              <div class="tw-flex tw-flex-col">
                <image-selector v-model="newPalette.paletteImage" :preview="false" :crop="false" :from-modal="true" :encode-base64="false" />
                <div class="colors-wrap" :class="{ loading: (imageLoading && newPalette.paletteImage) || imageProcessing }">
                  <div
                    v-for="(color, index) of newPalette.paletteColors"
                    :key="index"
                    class="color-item"
                    :style="{ backgroundColor: color }"
                    :class="{ active: isActiveColor(color) }"
                    @click="handleColorItemClick(color)"
                  ></div>
                  <div v-if="(imageLoading && newPalette.paletteImage) || imageProcessing" class="loading-spinner">
                    <bz-spinner class="position-absolute" style="margin-right: 5px" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleColorCreator('hex')">
              Use Color Hex Codes
              <bz-arrow-up-icon v-if="colorCreator === 'hex'" />
              <bz-arrow-down-icon v-else />
            </div>
            <div v-if="colorCreator === 'hex'">
              <table>
                <tbody>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.backgroundColor" @input="onChangeHexColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.backgroundColor }" />
                    </td>
                    <td>Background</td>
                  </tr>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.buttonColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.buttonColor }" />
                    </td>
                    <td>Buttons</td>
                  </tr>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.socialIconColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.socialIconColor }" />
                    </td>
                    <td>Social Icons</td>
                  </tr>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.headingColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.headingColor }" />
                    </td>
                    <td>Headings</td>
                  </tr>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.boxColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.boxColor }" />
                    </td>
                    <td>Boxes</td>
                  </tr>
                  <tr class="hex-color-wrap">
                    <td>
                      <bz-input v-model="newPalette.colors.secondaryColor" />
                    </td>
                    <td>
                      <div class="color-item" :style="{ backgroundColor: newPalette.colors.secondaryColor }" />
                    </td>
                    <td>Secondary</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <hr style="margin-top: auto" />
      <div class="w-100 d-flex justify-content-end pb-2 tw-mt-4">
        <button class="btn bz-btn-default mr-2 d-flex align-items-center" :disabled="saving" @click="saveAndNext">
          {{ saveBtnTitle }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import BzSpinner from '../../page/BzSpinner.vue'
import BzInput from '../../page/BzInput.vue'
import BzSelect from '../../page/BzSelect.vue'
import ImageSelector from '../../elements/ImageSelector.vue'
import builderMixin from '../../../mixins/builderMixin'
import { cloneDeep } from 'lodash'
import NewThemePaletteItem from './NewThemePaletteItem.vue'
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import ColorMode from './ColorMode.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import ColorThief from 'colorthief'

export default {
  name: 'CreateNewPalette',
  components: {
    ColorMode,
    BzArrowDownIcon,
    BzArrowUpIcon,
    NewThemePaletteItem,
    ImageSelector,
    BzSpinner,
    BzInput,
    BzSelect
  },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: [Object, undefined],
      default: undefined
    },
    user: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      activeColor: 'backgroundColor',
      step: 0,
      paletteName: '',
      category: 0,
      colorCreator: '',
      randomBackupColors: [],
      imageLoading: false,
      imageProcessing: false,
      imageMouseDown: false,
      saving: false,
      colorMode: 'light',
      newPalette: {
        paletteImage: '',
        paletteColors: null,
        colors: {
          backgroundColor: '#ffffff',
          primaryColor: '#E07F10',
          buttonColor: '#E07F10',
          socialIconColor: '#11638F',
          headingColor: '#1e7730',
          boxColor: '#939393',
          secondaryColor: '#11638F'
        }
      }
    }
  },
  computed: {
    theme: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
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
    saveBtnTitle() {
      if (this.step === 2) {
        return 'Save'
      } else {
        return 'Save & Next'
      }
    }
  },
  watch: {
    'newPalette.paletteImage': {
      handler(value) {
        if (value) {
          if (this.colorCreator === 'image') {
            this.renderImage().then((url) => {
              this.getColorPaletteFromImage(url)
            })
          } else if (this.colorCreator === 'swatch') {
            this.getColorPaletteFromImage(value)
            this.$emit('swatch', value)
          }
        }
      }
    },
    colorCreator(value) {
      if (value === 'image' && this.newPalette.paletteImage) {
        this.$nextTick(() => {
          this.renderImage()
        })
      }
    },
    newPalette: {
      deep: true,
      handler(v) {
        this.setPreviewPalette(v)
      }
    }
  },
  methods: {
    saveAndNext() {
      if (this.step === 0 && this.paletteName) {
        // Palette name check.
        const isNameTaken = this.systemPalettes.advanced.some((sp) => {
          return [...(sp.palettes.dark || []), ...(sp.palettes.light || [])].some((_k) => {
            return _k.name === this.paletteName
          })
        })
        if (isNameTaken) {
          return toast.error('The palette name is taken. please choose another name.')
        } else {
          this.step++
        }
      } else if (this.step === 1 && this.category) {
        this.step++
        if (this.$store.state.previewPalette) {
          this.newPalette = cloneDeep(this.$store.state.previewPalette)
        } else {
          this.newPalette.colors = cloneDeep(this.mainPalette)
        }
        this.setPreviewPalette(this.newPalette)
      } else if (this.step === 2 && this.paletteName && this.category) {
        this.saving = true
        const data = {
          name: this.paletteName,
          category_id: this.category,
          data: this.newPalette.colors,
          mode: this.colorMode,
          image: this.newPalette.paletteImage
        }
        this.$emit('save', data)
      }
    },
    toggleColorCreator(mode) {
      if (this.colorCreator === mode) {
        this.colorCreator = ''
      } else {
        this.colorCreator = mode
      }
    },
    onClickRandomize() {
      this.randomBackupColors.push(cloneDeep(this.newPalette.colors))
      const colors = cloneDeep(this.newPalette.colors)
      for (const key of Object.keys(colors)) {
        if (!this.$refs.paletteItemRef.isLocked(key.replace('Color', ''))) {
          colors[key] = this.getRandomColor()
        }
      }
      this.newPalette.colors = colors
    },
    onClickUndoRandomize() {
      this.newPalette.colors = this.randomBackupColors.pop()
    },
    getRandomColor() {
      const letters = '0123456789ABCDEF'
      let color = ''
      for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)]
      }
      return '#' + color
    },
    mouseDownHandler(e) {
      this.imageMouseDown = true
      this.performAction(e)
    },
    pickCanvasColor(e) {
      if (!this.imageMouseDown) return false
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
    isActiveColor(color) {
      return this.newPalette.colors[this.activeColor] === color
    },
    async renderImage() {
      return new Promise((resolve) => {
        try {
          this.imageLoading = true
          const img = new Image()
          img.crossOrigin = 'Anonymous'
          const canvas = this.$refs.canRef
          const context = canvas.getContext('2d')

          this.canvas = canvas
          this.context = context
          const self = this
          img.onload = function () {
            canvas.width = 370
            canvas.height = (canvas.width * this.height) / this.width
            context.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height)
            self.imageLoading = false
          }
          img.onerror = function (error) {
            console.error('Palette Image rendering Error: ', error)
          }
          let url = this.newPalette.paletteImage
          if (url.includes('amazonaws.com')) {
            url += '?v=canvas'
          }
          img.src = url
          resolve(url)
        } catch (error) {
          console.error("Build Palette Modal: couldn't render image", error)
          resolve(false)
        }
      })
    },
    async getColorPaletteFromImage(src) {
      this.imageProcessing = true
      try {
        // const options = {
        //   crossOrigin: 'anonymous'
        // }
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
          this.newPalette.paletteColors = colors
          this.setPaletteColors(colors)
          this.imageProcessing = false
        }
        img.src = src
      } catch (error) {
        this.imageProcessing = false
        console.error('getColorPaletteFromImage Error: ', error)
      }
    },
    setPaletteColors(colors) {
      this.newPalette.colors.backgroundColor = colors[0]
      this.newPalette.colors.buttonColor = colors[1]
      this.newPalette.colors.socialIconColor = colors[2]
      this.newPalette.colors.headingColor = colors[3]
      this.newPalette.colors.boxColor = colors[4]
      this.newPalette.colors.secondaryColor = colors[5]
    },
    handleColorItemClick(color) {
      this.newPalette.colors[this.activeColor] = color
    },
    setActiveColor(activeColor) {
      this.activeColor = activeColor + 'Color'
    },
    changedPaletteColor() {
      if (this.appliedTo !== 'website') {
        this.colors = cloneDeep(this.colors)
      }
    },
    onChangeHexColor() {
      this.colors = cloneDeep(this.colors)
    }
  }
}
</script>
<style lang="scss">
.create-new-palette {
  display: flex;
  flex-direction: column;
  padding: 5px;

  .theme-mode {
    display: flex;
    align-items: center;
    justify-content: space-between;

    div {
      padding: 1px;
      border-radius: 5px;
      width: 48%;
      height: 38px;

      &.active {
        border: solid 2px #0076df;
      }

      button {
        width: 100%;
        height: 100%;
        border-radius: 4px;
        border: solid 1px #8080803f;
      }
    }
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

  .hex-color-wrap {
    .color-item {
      height: 40px;
      min-width: 40px;
      border-radius: 3px;
      border: solid 1px #80808078;
      cursor: pointer;
      margin: 3px 5px;
    }
  }
}
</style>
