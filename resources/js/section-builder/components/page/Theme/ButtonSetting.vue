<template>
  <div class="button-setting-root">
    <div class="card mt-2">
      <div class="d-flex px-3 align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleExpand">
        Buttons Change
        <bz-arrow-up-icon v-if="expand" />
        <bz-arrow-down-icon v-else />
      </div>
      <div v-if="expand">
        <hr style="margin: 0" />
        <div class="p-3">
          <div class="mt-3">
            <!--    Font Family    -->
            <div class="mt-3">
              <label class="mb-1"> Text Font Style</label>
              <bz-select v-model="fontFamily" :options="fontFamilies" :auto-select="true">
                <template #selected="{ selected, placeholder }">
                  <span :style="{ fontFamily: selected?.name }">{{ selected?.label || placeholder }}</span>
                </template>
                <template #option="{ option }">
                  <span :style="{ fontFamily: option.name }">{{ option.label }}</span>
                </template>
              </bz-select>
            </div>
            <div class="mt-3">
              <label class="mb-1 mt-1">Text Font Elements</label>
              <div class="row w-full justify-content-center">
                <label for="bold" class="font-element" :class="{ active: buttonData.bold }">
                  <span><b>B</b></span>
                  <input id="bold" v-model="buttonData.bold" type="checkbox" hidden />
                </label>
                <label class="font-element" :class="{ active: buttonData.italic }">
                  <span class="font-italic">I</span>
                  <input v-model="buttonData.italic" type="checkbox" hidden />
                </label>
                <label class="font-element" :class="{ active: buttonData.underline }">
                  <span><u>U</u></span>
                  <input v-model="buttonData.underline" type="checkbox" hidden />
                </label>
              </div>
            </div>
            <div class="mt-3">
              <label class="mb-1">Text Font Adjustment</label>
              <div class="mt-1">
                <div>
                  <label>Font Size</label>
                  <div class="slider-element slider-blue">
                    <div class="value">{{ buttonData.fontSize }}</div>
                    <slider v-model="buttonData.fontSize" :tooltips="false" :min="8" :max="72" :step="1" />
                  </div>
                </div>
                <div class="mt-2">
                  <label>Letter Space</label>
                  <div class="slider-element slider-blue">
                    <div class="value">{{ buttonData.letterSpace }}</div>
                    <slider v-model="buttonData.letterSpace" :tooltips="false" :min="0" :max="50" :step="1" />
                  </div>
                </div>
                <div class="mt-2">
                  <label>Opacity</label>
                  <div class="slider-element slider-blue">
                    <div class="value">{{ buttonData.fontOpacity }}</div>
                    <slider v-model="buttonData.fontOpacity" :tooltips="false" :min="0" :max="100" :step="1" />
                  </div>
                </div>
              </div>
            </div>
            <!--    Button Text Font Color    -->
            <div class="mt-3">
              <label class="mb-1">Text Font Color</label>
              <div class="d-flex align-items-center justify-content-center">
                <div class="font-color" :style="{ background: buttonData.color || '#ffffff' }" @click="openColorPicker('openTextColorPicker')">
                  <span class="text-invert">{{ (buttonData.color || '#ffffff').toUpperCase() }}</span>
                </div>
                <button
                  :style="{ visibility: buttonData.color !== originColor && !openTextColorPicker ? 'visible' : 'hidden' }"
                  class="text-danger p-2 ml-2"
                  @click="buttonData.color = initialButtonData.color"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="openTextColorPicker">
                <Sketch :model-value="buttonData.color || '#ffffff'" class="font-color-picker" @update:modelValue="updateFontColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="saveTextColor">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeFontColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>

            <!--    Hover Text Font Color    -->
            <div class="mt-3">
              <label class="mb-1">Hover Text Font Color</label>
              <div class="d-flex align-items-center justify-content-center">
                <div class="font-color" :style="{ background: buttonData.hoverColor || buttonData.color || '#ffffff' }" @click="openColorPicker('openHoverColorPicker')">
                  <span class="text-invert">{{ (buttonData.hoverColor || buttonData.color || '#ffffff').toUpperCase() }}</span>
                </div>
                <button
                  :style="{ visibility: buttonData.hoverColor !== originHoverColor && !openHoverColorPicker ? 'visible' : 'hidden' }"
                  class="text-danger p-2 ml-2"
                  @click="buttonData.hoverColor = initialButtonData.hoverColor"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="openHoverColorPicker">
                <Sketch :model-value="buttonData.hoverColor || buttonData.color || '#ffffff'" class="font-color-picker" @update:modelValue="updateFontHoverColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="saveTextHoverColor">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeHoverColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>
          </div>
          <hr />
          <label>Button Adjustment</label>
          <div class="mt-3">
            <div>
              <label>Button Corners</label>
              <div class="slider-element slider-blue">
                <div class="value">{{ buttonData.round }}</div>
                <slider v-model="buttonData.round" :min="1" :max="50" :tooltips="false" :thumb-label="true" :step="1" />
              </div>
            </div>
            <div class="mt-2">
              <label>Button Size</label>
              <div class="slider-element slider-blue">
                <div class="value">{{ buttonData.padding }}</div>
                <slider v-model="buttonData.padding" :min="1" :max="50" :tooltips="false" thumb-label :step="1" />
              </div>
            </div>

            <!--   Button Color    -->
            <div class="mt-3">
              <label class="mb-1">Button Color</label>
              <div class="ml-3 my-1 d-flex align-items-center"><input v-model="buttonData.outline" type="checkbox" /> <label class="pl-2">Outline Only</label></div>
              <div class="d-flex align-items-center justify-content-center">
                <div class="font-color" :style="{ background: buttonData.bgColor }" @click="openColorPicker('openButtonColorPicker')">
                  <span class="text-invert">{{ (buttonData.bgColor || '').toUpperCase() }}</span>
                </div>
                <button
                  :style="{ visibility: buttonData.bgColor !== originButtonBgColor && !openButtonColorPicker ? 'visible' : 'hidden' }"
                  class="text-danger p-2 ml-2"
                  @click="buttonData.bgColor = originButtonBgColor"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="openButtonColorPicker">
                <Sketch :model-value="buttonData.bgColor" class="font-color-picker" @update:modelValue="updateButtonColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="saveButtonColor">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeButtonColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>

            <!--   Hover Button Color    -->
            <div class="mt-3">
              <label class="mb-1">Button Hover Color</label>
              <div class="ml-3 my-1 d-flex align-items-center">
                <input v-model="buttonData.hoverOutline" type="checkbox" />
                <label class="pl-2">Outline Only</label>
              </div>
              <div class="d-flex align-items-center justify-content-center">
                <div class="font-color" :style="{ background: buttonData.hoverBgColor }" @click="openColorPicker('openButtonHoverColorPicker')">
                  <span class="text-invert">{{ (buttonData.hoverBgColor || '').toUpperCase() }}</span>
                </div>
                <button
                  :style="{ visibility: buttonData.hoverBgColor !== originButtonHoverColor && !openButtonHoverColorPicker ? 'visible' : 'hidden' }"
                  class="text-danger p-2 ml-2"
                  @click="buttonData.hoverBgColor = originButtonHoverColor"
                >
                  <i class="fa fa-times"></i>
                </button>
              </div>
              <div v-if="openButtonHoverColorPicker">
                <Sketch :model-value="buttonData.hoverBgColor" class="font-color-picker" @update:modelValue="updateButtonHoverColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="saveButtonHoverColor">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeButtonHoverColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>

            <div class="mt-2">
              <label>Hover Button Opacity</label>
              <div class="slider-element slider-blue">
                <div class="value">{{ buttonData.hoverOpacity }}</div>
                <slider v-model="buttonData.hoverOpacity" :tooltips="false" :min="0" :max="100" :step="1" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import { Sketch } from '@lk77/vue3-color'
import { cloneDeep, merge } from 'lodash'
import fontFamilies from '../../../data/fonts'
import BzSelect from '@/public/BzSelect.vue'
import builderMixin from '../../../mixins/builderMixin'
import Slider from '@vueform/slider'

export default {
  name: 'ButtonSetting',
  components: {
    BzSelect,
    BzArrowDownIcon,
    BzArrowUpIcon,
    Sketch,
    Slider
  },
  mixins: [builderMixin],
  props: {
    editTheme: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      expand: false,
      fontFamilies: fontFamilies.title,
      buttonData: {
        round: 4,
        padding: 1,
        outline: false,
        hoverOutline: false,
        bgColor: '',
        hoverBgColor: '#ffffff',
        color: '',
        hoverColor: '',
        hoverOpacity: 100,

        // button text style attributes
        fontFamily: 'Roboto',
        bold: false,
        italic: false,
        underline: false,
        fontSize: 14,
        letterSpace: 1,
        fontOpacity: 100
      },
      initialButtonData: null,
      openTextColorPicker: false,
      openHoverColorPicker: false,
      openButtonColorPicker: false,
      openButtonHoverColorPicker: false
    }
  },
  computed: {
    theme: {
      get() {
        return this.$store.state.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    },
    originButtonHoverColor() {
      return this.initialButtonData.hoverBgColor
    },
    originButtonBgColor() {
      return this.initialButtonData.bgColor
    },
    originColor() {
      return this.initialButtonData.color
    },
    originHoverColor() {
      return this.initialButtonData.hoverColor
    },
    fontFamily: {
      get() {
        return this.fontFamilies.find((item) => item.name.toLowerCase() === this.buttonData?.fontFamily?.toLowerCase())
      },
      set(value) {
        this.buttonData.fontFamily = value.name
      }
    }
  },
  watch: {
    buttonData: {
      deep: true,
      handler(value) {
        this.theme = { ...this.theme, button: value }
      }
    },
    editTheme: {
      deep: true,
      handler(v) {
        this.updateButtonData()
      }
    }
  },
  created() {
    this.updateButtonData()
  },
  methods: {
    toggleExpand() {
      if (this.expand) {
        this.expand = false
      } else {
        this.$emit('expand')
        this.expand = true
      }
    },
    openColorPicker(picker) {
      this.backupCurrentButtonData()
      this[picker] = true
    },
    updateButtonData() {
      if (this.editTheme) {
        this.buttonData = merge(this.buttonData, this.editTheme.data.button ?? {})
        if (!this.buttonData.bgColor) {
          this.buttonData.bgColor = this.mainPalette.buttonColor
        }
      } else {
        this.buttonData = merge(this.buttonData, this.theme.button ?? {})
        this.buttonData.bgColor = this.mainPalette.buttonColor
      }

      if (!this.buttonData.hoverBgColor) {
        this.buttonData.hoverBgColor = this.buttonData.bgColor
      }

      if (!this.buttonData.hoverColor) {
        this.buttonData.hoverColor = this.buttonData.color
      }

      this.buttonData = cloneDeep(this.buttonData)
      this.backupCurrentButtonData()
    },
    backupCurrentButtonData() {
      this.initialButtonData = cloneDeep(this.buttonData)
    },
    saveTextColor() {
      this.openTextColorPicker = false
    },
    saveButtonColor() {
      this.openButtonColorPicker = false
    },
    saveTextHoverColor() {
      this.openHoverColorPicker = false
    },
    updateButtonColor(color) {
      this.buttonData.bgColor = color.hex8
    },
    closeButtonColorPicker() {
      this.openButtonColorPicker = false
      this.buttonData.bgColor = this.initialButtonData.bgColor
    },
    saveButtonHoverColor() {
      this.openButtonHoverColorPicker = false
    },
    updateButtonHoverColor(color) {
      this.buttonData.hoverBgColor = color.hex8
    },
    closeButtonHoverColorPicker() {
      this.openButtonHoverColorPicker = false
      this.buttonData.hoverBgColor = this.initialButtonData.hoverBgColor
    },
    updateFontColor(color) {
      this.buttonData.color = color.hex8
    },
    closeFontColorPicker() {
      this.openTextColorPicker = false
      this.buttonData.color = this.initialButtonData.color
    },
    updateFontHoverColor(color) {
      this.buttonData.hoverColor = color.hex8
    },
    closeHoverColorPicker() {
      this.openHoverColorPicker = false
      this.buttonData.hoverColor = this.initialButtonData.hoverColor
    },
    expandPanel() {
      this.expand = true
    },
    closePanel() {
      this.expand = false
    },
    cancelChanges() {
      this.buttonData = this.initialButtonData
      this.expand = false
    },
    fontStyleOptionStyle(value) {
      return {
        fontFamily: value
      }
    }
  }
}
</script>

<style scoped lang="scss">
.button-setting-root {
  .card {
    box-shadow: 0 0 2px 4px rgb(0 0 0 / 7%);
    background-color: white;
    border: solid 1px #8080803f;
    border-radius: 4px;
    padding: 5px;

    .font-types {
      .active {
        background-color: #0076df;
        color: white;
      }
    }

    .font-element {
      padding: 5px;
      margin: 5px;
      min-width: 20px;
      border: 1px solid lightgrey;
      border-radius: 2px;
      text-align: center;
      cursor: pointer;

      &.active {
        background-color: #0076df;
        color: white;
        border-color: #0076df;
      }
    }
    .font-color {
      width: 100px;
      height: 30px;
      border-radius: 5px;
      cursor: pointer;
      border: solid 1px lightgrey;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .font-color-picker {
      margin: 5px auto;
    }
  }
}
</style>
