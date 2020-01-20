<template>
  <div class="font-setting">
    <div class="card mt-2">
      <div class="px-3 d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleExpand">
        Font Change
        <bz-arrow-up-icon v-if="expand" />
        <bz-arrow-down-icon v-else />
      </div>
      <div v-if="expand">
        <div class="btn-group w-100 font-types" role="group">
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: fontType === fontTypes.title }" @click.prevent="fontType = fontTypes.title">Title</button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: fontType === fontTypes.subtitle }" @click.prevent="fontType = fontTypes.subtitle">
            Subtitle
          </button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: fontType === fontTypes.description }" @click.prevent="fontType = fontTypes.description">
            Description
          </button>
        </div>
        <div class="p-3">
          <div>
            <label class="mb-1">Font Style</label>
            <bz-select v-model="fontFamily" :options="fontFamilies">
              <template #selected="{ selected, placeholder }">
                <span :style="{ fontFamily: selected?.name }">{{ selected?.label || placeholder }}</span>
              </template>
              <template #option="{ option }">
                <span :style="{ fontFamily: option.name }">{{ option.label }}</span>
              </template>
            </bz-select>
          </div>
          <div class="mt-3">
            <label class="mb-1">Font Elements</label>
            <div class="row w-full justify-content-center mt-1">
              <label
                for="bold"
                class="tw-p-1 border rounded tw-flex tw-items-center tw-cursor-pointer"
                :class="{ 'tw-bg-blue-500 border-tw-bg-500 tw-text-white': fontData[fontType].bold }"
              >
                <i class="mdi mdi-format-bold tw-text-base"></i>
                <input id="bold" v-model="fontData[fontType].bold" type="checkbox" hidden />
              </label>
              <label
                class="tw-p-1 border rounded tw-flex tw-items-center tw-cursor-pointer ml-2"
                :class="{ 'tw-bg-blue-500 border-tw-bg-500 tw-text-white': fontData[fontType].italic }"
              >
                <i class="mdi mdi-format-italic"></i>
                <input v-model="fontData[fontType].italic" type="checkbox" hidden />
              </label>
              <label
                class="tw-p-1 border rounded tw-flex tw-items-center tw-cursor-pointer ml-2"
                :class="{ 'tw-bg-blue-500 border-tw-bg-500 tw-text-white': fontData[fontType].underline }"
              >
                <i class="mdi mdi-format-underline"></i>
                <input v-model="fontData[fontType].underline" type="checkbox" hidden />
              </label>
            </div>
          </div>
          <div class="mt-3">
            <label class="mb-1">Font Adjustment</label>
            <div class="mt- slider-blue">
              <div>
                <label>Font Size</label>
                <div class="slider-element">
                  <div class="value">{{ fontData[fontType].fontSize }}</div>
                  <slider v-model="fontData[fontType].fontSize" :tooltips="false" :min="8" :max="100" :step="1" />
                </div>
              </div>
              <div class="mt-2">
                <label>Letter Space</label>
                <div class="slider-element">
                  <div class="value">{{ fontData[fontType].letterSpace }}</div>
                  <slider v-model="fontData[fontType].letterSpace" :tooltips="false" :min="1" :max="50" :step="1" />
                </div>
              </div>
              <div class="mt-2">
                <label>Opacity</label>
                <div class="slider-element">
                  <div class="value">{{ fontData[fontType].opacity }}</div>
                  <slider v-model="fontData[fontType].opacity" :tooltips="false" :min="0" :max="100" :step="1" />
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <div>
              <label class="mb-1">Font Color</label>
              <div class="d-flex align-items-center justify-content-center mt-1">
                <div class="font-color" :style="{ background: fontData[fontType].color || originColor }" @click="openColorPicker = true">
                  <span class="text-invert">{{ (fontData[fontType].color || originColor).toUpperCase() }}</span>
                </div>
                <button
                  v-if="fontData[fontType].color !== initialFontData[fontType].color && !openColorPicker"
                  class="text-danger p-2 ml-2"
                  @click="fontData[fontType].color = initialFontData[fontType].color"
                >
                  <i class="mdi mdi-close"></i>
                </button>
              </div>
            </div>
            <div v-if="openColorPicker">
              <Sketch :model-value="fontData[fontType].color || originColor" class="font-color-picker" @update:modelValue="updateFontColor" />
              <div class="d-flex justify-content-center">
                <button class="btn btn-primary mr-2 btn-sm" @click="openColorPicker = false">Save Color</button>
                <button class="btn btn-danger btn-sm" @click="closeColorPicker">Cancel Changes</button>
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
import fontFamilies from '../../../data/fonts'
import BzSelect from '@/public/BzSelect.vue'
import { Sketch } from '@lk77/vue3-color'
import { cloneDeep, merge } from 'lodash'
import builderMixin from '../../../mixins/builderMixin'
import Slider from '@vueform/slider'

export default {
  name: 'FontSetting',
  components: { BzSelect, BzArrowDownIcon, BzArrowUpIcon, Sketch, Slider },
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
      fontType: 'title',
      fontFamilies: fontFamilies.title,
      fontTypes: {
        title: 'title',
        subtitle: 'subtitle',
        description: 'description'
      },
      fontData: {
        title: {
          fontFamily: 'Roboto',
          bold: false,
          italic: false,
          underline: false,
          opacity: 100,
          color: null,
          fontSize: 20,
          letterSpace: 1
        },
        subtitle: {
          fontFamily: 'Roboto',
          bold: false,
          italic: false,
          underline: false,
          opacity: 100,
          color: null,
          fontSize: 18,
          letterSpace: 1
        },
        description: {
          fontFamily: 'Roboto',
          bold: false,
          italic: false,
          underline: false,
          opacity: 100,
          color: null,
          fontSize: 14,
          letterSpace: 1
        }
      },
      initialFontData: null,
      openColorPicker: false
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
    originColor() {
      return this.getColor(this.mainPalette.backgroundColor)
    },
    fontFamily: {
      get() {
        return this.fontFamilies.find((item) => item.name.toLowerCase() === this.fontData[this.fontType]?.fontFamily?.toLowerCase())
      },
      set(value) {
        this.fontData[this.fontType].fontFamily = value.name
      }
    }
  },
  watch: {
    fontData: {
      deep: true,
      handler(value) {
        this.theme = { ...this.theme, font: value }
      }
    },
    editTheme: {
      deep: true,
      handler() {
        this.updateFontData()
      }
    }
  },
  created() {
    this.updateFontData()
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
    updateFontData() {
      if (this.editTheme) {
        this.fontData = merge(this.fontData, this.editTheme.data.font ?? {})
      } else {
        this.fontData = merge(this.fontData, this.theme.font ?? {}, {
          title: {
            color: null
          },
          subtitle: {
            color: null
          },
          description: {
            color: null
          }
        })
      }
      this.fontData = cloneDeep(this.fontData)
      this.initialFontData = cloneDeep(this.fontData)
    },
    updateFontColor(color) {
      this.fontData[this.fontType].color = color.hex8
    },
    closeColorPicker() {
      this.openColorPicker = false
      this.fontData[this.fontType].color = this.initialFontData.color
    },
    expandPanel() {
      this.expand = true
    },
    closePanel() {
      this.expand = false
    },
    cancelChanges() {
      this.fontData = this.initialFontData
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
.font-setting {
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
