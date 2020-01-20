<template>
  <div class="font-setting">
    <div class="card mt-2">
      <div class="d-flex px-3 align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleExpand">
        Links Change
        <bz-arrow-up-icon v-if="expand" />
        <bz-arrow-down-icon v-else />
      </div>
      <div v-if="expand">
        <div class="btn-group w-100 font-types" role="group">
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: linkType === linkTypes.header }" @click.prevent="linkType = linkTypes.header">Header</button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: linkType === linkTypes.footer }" @click.prevent="linkType = linkTypes.footer">Footer</button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: linkType === linkTypes.main }" @click.prevent="linkType = linkTypes.main">Main</button>
        </div>
        <div class="p-3">
          <div v-if="linkType !== linkTypes.main">
            <div class="mb-2 w-full text-center">Choose Link Type</div>
            <div class="d-flex justify-content-center">
              <button class="btn" :class="{ 'btn-info': linkForm === linkForms.text }" @click="linkForm = linkForms.text">Text</button>
              <button class="btn ml-3" :class="{ 'btn-info': linkForm === linkForms.button }" @click="linkForm = linkForms.button">Button</button>
            </div>
          </div>
          <!--    Text/Link Style    -->
          <div class="mt-3">
            <label class="mb-1">{{ linkForm === linkForms.button ? 'Text' : 'Links' }} Font Style</label>
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
            <label class="mb-1">{{ linkForm === linkForms.button ? 'Text' : 'Links' }} Font Elements</label>
            <div class="row w-full justify-content-center mt-1">
              <label for="bold" class="font-element" :class="{ active: linkData[linkType][linkForm].bold }">
                <span><b>B</b></span>
                <input id="bold" v-model="linkData[linkType][linkForm].bold" type="checkbox" hidden />
              </label>
              <label class="font-element" :class="{ active: linkData[linkType][linkForm].italic }">
                <span class="font-italic">I</span>
                <input v-model="linkData[linkType][linkForm].italic" type="checkbox" hidden />
              </label>
              <label class="font-element" :class="{ active: linkData[linkType][linkForm].underline }">
                <span><u>U</u></span>
                <input v-model="linkData[linkType][linkForm].underline" type="checkbox" hidden />
              </label>
            </div>
          </div>
          <div class="mt-3">
            <label class="mb-1">{{ linkForm === linkForms.button ? 'Text' : 'Links' }} Font Adjustment</label>
            <div class="mt-1">
              <div v-if="linkType !== linkTypes.main">
                <label>Font Size</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType][linkForm].fontSize }}</div>
                  <slider v-model="linkData[linkType][linkForm].fontSize" :tooltips="false" :min="0" :max="50" :step="1" />
                </div>
              </div>
              <div class="mt-2">
                <label>Letter Space</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType][linkForm].letterSpace }}</div>
                  <slider v-model="linkData[linkType][linkForm].letterSpace" :tooltips="false" :min="1" :max="50" :step="1" />
                </div>
              </div>
              <div class="mt-2">
                <label>Opacity</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType][linkForm].opacity }}</div>
                  <slider v-model="linkData[linkType][linkForm].opacity" :tooltips="false" :min="0" :max="100" :step="1" />
                </div>
              </div>
            </div>
          </div>
          <!--    Links Font Color    -->
          <div class="mt-3">
            <label class="mb-1">{{ linkForm === linkForms.button ? 'Text' : 'Links' }} Font Color</label>
            <div class="d-flex align-items-center justify-content-center">
              <div class="font-color" :style="{ background: linkData[linkType][linkForm].color || originColor }" @click="openPicker('openColorPicker')">
                <span class="text-invert">{{ (linkData[linkType][linkForm].color || originColor).toUpperCase() }}</span>
              </div>
              <button
                :style="{ visibility: linkData[linkType][linkForm].color !== initialLinkData[linkType][linkForm].color && !openColorPicker ? 'visible' : 'hidden' }"
                class="text-danger p-2 ml-2"
                @click="linkData[linkType][linkForm].color = initialLinkData[linkType][linkForm].color"
              >
                <i class="fa fa-times"></i>
              </button>
            </div>
            <div v-if="openColorPicker">
              <Sketch :model-value="linkData[linkType][linkForm].color || originColor" class="font-color-picker" @update:modelValue="updateFontColor" />
              <div class="d-flex justify-content-center">
                <button class="btn btn-primary mr-2 btn-sm" @click="openColorPicker = false">Save Color</button>
                <button class="btn btn-danger btn-sm" @click="closeColorPicker">Cancel Changes</button>
              </div>
            </div>
          </div>
          <!--    Hover Links Font Color    -->
          <div class="mt-3">
            <label class="mb-1">Hover Links Font Color</label>
            <div class="d-flex align-items-center justify-content-center">
              <div class="font-color" :style="{ background: linkData[linkType][linkForm].hoverColor || originHoverColor }" @click="openPicker('openHoverColorPicker')">
                <span class="text-invert">{{ (linkData[linkType][linkForm].hoverColor || originHoverColor).toUpperCase() }}</span>
              </div>
              <button
                :style="{ visibility: linkData[linkType][linkForm].hoverColor !== initialLinkData[linkType][linkForm].hoverColor && !openColorPicker ? 'visible' : 'hidden' }"
                class="text-danger p-2 ml-2"
                @click="linkData[linkType][linkForm].hoverColor = originHoverColor"
              >
                <i class="fa fa-times"></i>
              </button>
            </div>
            <div v-if="openHoverColorPicker">
              <Sketch :model-value="linkData[linkType][linkForm].hoverColor || originHoverColor" class="font-color-picker" @update:modelValue="updateHoverColor" />
              <div class="d-flex justify-content-center">
                <button class="btn btn-primary mr-2 btn-sm" @click="openHoverColorPicker = false">Save Color</button>
                <button class="btn btn-danger btn-sm" @click="closeHoverColorPicker">Cancel Changes</button>
              </div>
            </div>
          </div>
          <!--    Hover Options    -->
          <div v-if="linkForm === linkForms.text" class="mt-3">
            <label class="mb-1">Hover Options</label>
            <bz-select v-model="hoverOption" :options="hoverOptions" :auto-select="true">
              <template #selected="{ selected, placeholder }">
                <span :style="{ fontFamily: selected?.name }">{{ selected?.label || placeholder }}</span>
              </template>
              <template #option="{ option }">
                <span :style="{ fontFamily: option.name }">{{ option.label }}</span>
              </template>
            </bz-select>
          </div>

          <!--   Button Style    -->
          <div v-if="linkForm === linkForms.button">
            <hr class="mt-3" />
            <label class="mb-1">Button Adjustment</label>
            <div class="mt-1">
              <div>
                <label>Button Corners</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType].button.round }}</div>
                  <slider v-model="linkData[linkType].button.round" :tooltips="false" :min="0" :max="50" :step="1" />
                </div>
              </div>
              <div class="mt-2">
                <label>Button Size</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType].button.padding }}</div>
                  <slider v-model="linkData[linkType].button.padding" :tooltips="false" :min="0" :max="50" :step="1" />
                </div>
              </div>

              <!--   Button Color    -->
              <div v-if="linkForm === linkForms.button" class="mt-3">
                <label class="mb-1">Button Color</label>
                <div class="ml-3 my-1 d-flex align-items-center">
                  <input v-model="linkData[linkType].button.outline" type="checkbox" /> <label class="pl-2">Outline Only</label>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                  <div class="font-color" :style="{ backgroundColor: linkData[linkType].button.bgColor || originButtonBgColor }" @click="openPicker('openButtonBgColorPicker')">
                    <span class="text-invert">{{ (linkData[linkType][linkForm].bgColor || originButtonBgColor).toUpperCase() }}</span>
                  </div>
                  <button
                    :style="{ visibility: linkData[linkType].button.bgColor !== initialLinkData[linkType].button.bgColor && !openButtonBgColorPicker ? 'visible' : 'hidden' }"
                    class="text-danger p-2 ml-2"
                    @click="linkData[linkType].button.bgColor = originButtonBgColor"
                  >
                    <i class="fa fa-times"></i>
                  </button>
                </div>
                <div v-if="openButtonBgColorPicker">
                  <Sketch :model-value="linkData[linkType].button.bgColor || originButtonBgColor" class="font-color-picker" @update:modelValue="updateButtonBgColor" />
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mr-2 btn-sm" @click="openButtonBgColorPicker = false">Save Color</button>
                    <button class="btn btn-danger btn-sm" @click="closeButtonBgColorPicker">Cancel Changes</button>
                  </div>
                </div>
              </div>

              <!--   Hover Button Color    -->
              <div class="mt-3">
                <label class="mb-1">Button Hover Color</label>
                <div class="ml-3 my-1 d-flex align-items-center">
                  <input v-model="linkData[linkType].button.hoverOutline" type="checkbox" /> <label class="pl-2">Outline Only</label>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                  <div
                    class="font-color"
                    :style="{ backgroundColor: linkData[linkType].button.hoverBgColor || originButtonHoverBgColor }"
                    @click="openPicker('openButtonHoverBgColorPicker')"
                  >
                    <span class="text-invert">{{ (linkData[linkType][linkForm].hoverBgColor || originButtonHoverBgColor).toUpperCase() }}</span>
                  </div>
                  <button
                    :style="{
                      visibility: linkData[linkType].button.hoverBgColor !== initialLinkData[linkType].button.hoverBgColor && !openButtonHoverBgColorPicker ? 'visible' : 'hidden'
                    }"
                    class="text-danger p-2 ml-2"
                    @click="linkData[linkType].button.hoverBgColor = initialLinkData[linkType].button.hoverBgColor"
                  >
                    <i class="fa fa-times"></i>
                  </button>
                </div>
                <div v-if="openButtonHoverBgColorPicker">
                  <Sketch :model-value="linkData[linkType].button.hoverBgColor || originButtonHoverBgColor" class="font-color-picker" @update:modelValue="updateButtonHoverBgColor" />
                  <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mr-2 btn-sm" @click="openButtonHoverBgColorPicker = false">Save Color</button>
                    <button class="btn btn-danger btn-sm" @click="closeButtonHoverBgColorPicker">Cancel Changes</button>
                  </div>
                </div>
              </div>

              <div class="mt-2">
                <label>Hover Button Opacity</label>
                <div class="slider-element slider-blue">
                  <div class="value">{{ linkData[linkType].button.hoverOpacity }}</div>
                  <slider v-model="linkData[linkType].button.hoverOpacity" :tooltips="false" :min="0" :max="100" :step="1" />
                </div>
              </div>
            </div>
            <hr />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import BzSelect from '@/public/BzSelect.vue'
import fontFamilies from '../../../data/fonts'
import { Sketch } from '@lk77/vue3-color'
import { cloneDeep, merge } from 'lodash'
import builderMixin from '../../../mixins/builderMixin'
import Slider from '@vueform/slider'
import eventBus from '@/public/eventBus'

export default {
  name: 'LinkSetting',
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
      linkType: 'header',
      linkTypes: {
        header: 'header',
        footer: 'footer',
        main: 'main'
      },
      linkForms: {
        text: 'text',
        button: 'button'
      },
      hoverOptions: [
        {
          label: 'Color Change Only',
          value: 'color_change_only'
        },
        {
          label: 'Hover Underline',
          value: 'hover_underline'
        },
        {
          label: 'Hover Overline',
          value: 'hover_overline'
        },
        {
          label: 'Hover Both Lines',
          value: 'hover_both_lines'
        }
      ],
      linkData: {
        header: {
          type: 'text',
          text: {
            fontFamily: 'Roboto',
            bold: false,
            italic: false,
            underline: false,
            fontSize: 14,
            letterSpace: 1,
            color: '',
            hoverColor: '',
            opacity: 100,
            hoverOption: 'hover_underline'
          },
          button: {
            bgColor: '',
            hoverBgColor: '',
            fontFamily: 'Roboto',
            color: '',
            round: 4,
            size: 2,
            fontSize: 14,
            letterSpace: 1,
            opacity: 100,
            hoverOpacity: 100,
            outline: false,
            hoverOutline: false
          }
        },
        footer: {
          type: 'text',
          text: {
            type: 'text',
            fontFamily: 'Roboto',
            bold: false,
            italic: false,
            underline: false,
            fontSize: 14,
            letterSpace: 1,
            color: '',
            hoverColor: '',
            opacity: 100,
            hoverOption: 'hover_underline'
          },
          button: {
            bgColor: '',
            hoverBgColor: '',
            fontFamily: 'Roboto',
            color: '',
            round: 4,
            size: 2,
            opacity: 100,
            hoverOpacity: 100,
            outline: false,
            hoverOutline: false,
            fontSize: 14,
            letterSpace: 1
          }
        },
        main: {
          text: {
            type: 'text',
            fontFamily: 'Roboto',
            bold: false,
            italic: false,
            underline: false,
            fontSize: 14,
            letterSpace: 1,
            color: '',
            hoverColor: '',
            opacity: 100,
            hoverOption: 'hover_underline'
          },
          button: {
            bgColor: '',
            hoverBgColor: '',
            fontFamily: 'Roboto',
            color: '',
            round: 4,
            size: 2,
            opacity: 100,
            hoverOpacity: 100,
            outline: false,
            hoverOutline: false,
            fontSize: 14,
            letterSpace: 1
          }
        }
      },
      initialLinkData: null,
      openColorPicker: false,
      openHoverColorPicker: false,
      openButtonBgColorPicker: false,
      openButtonHoverBgColorPicker: false,
      fontFamilies: fontFamilies.paragraph
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
    linkForm: {
      get() {
        return this.linkData[this.linkType].type || 'text'
      },
      set(v) {
        this.linkData[this.linkType].type = v
      }
    },
    originColor() {
      return '#555555'
    },
    originHoverColor() {
      // default hover color, see website.scss
      return '#0076df'
    },
    originButtonBgColor() {
      return '#555555'
    },
    originButtonHoverBgColor() {
      return '#555555'
    },
    fontFamily: {
      get() {
        return this.fontFamilies.find((item) => item.name.toLowerCase() === this.linkData[this.linkType][this.linkForm]?.fontFamily?.toLowerCase())
      },
      set(value) {
        this.linkData[this.linkType][[this.linkForm]].fontFamily = value.name
      }
    },
    hoverOption: {
      get() {
        return this.hoverOptions.find((item) => item.value.toLowerCase() === this.linkData[this.linkType][this.linkForm]?.hoverOption?.toLowerCase())
      },
      set(value) {
        this.linkData[this.linkType][this.linkForm].hoverOption = value.value
      }
    }
  },
  watch: {
    linkData: {
      deep: true,
      handler(value) {
        const newTheme = { ...this.theme, link: value }
        this.theme = newTheme
        eventBus.$emit('TemplateThemeUpdated', newTheme)
      }
    },
    editTheme: {
      deep: true,
      handler(v) {
        this.updateLinkData()
      }
    }
  },
  created() {
    this.updateLinkData()
  },
  methods: {
    openPicker(pickerName) {
      this.backupButtonData()
      this[pickerName] = true
    },
    toggleExpand() {
      if (this.expand) {
        this.expand = false
      } else {
        this.$emit('expand')
        this.expand = true
      }
    },
    updateLinkData() {
      if (this.editTheme) {
        this.linkData = merge(this.linkData, this.editTheme.data.link ?? {})
      } else {
        this.linkData = merge(this.linkData, this.theme.link ?? {})
      }

      for (const linkType in this.linkTypes) {
        // sync with button settings because it is used as default
        if (!this.linkData[linkType].button.bgColor) {
          this.linkData[linkType].button.bgColor = this.theme.button.bgColor
        }
        if (!this.linkData[linkType].button.hoverBgColor) {
          this.linkData[linkType].button.hoverBgColor = this.theme.button.hoverBgColor
        }
        if (!this.linkData[linkType].button.color) {
          this.linkData[linkType].button.color = this.theme.button.color
        }
        if (!this.linkData[linkType].button.hoverColor) {
          this.linkData[linkType].button.hoverColor = this.theme.button.hoverColor || this.originHoverColor
        }

        if (!this.linkData[linkType].text.hoverColor) {
          // default link hover color, see website.scss
          this.linkData[linkType].text.hoverColor = this.originHoverColor
        }

        // sync with palette color
        if (!this.linkData[linkType].button.bgColor) {
          this.linkData[linkType].button.bgColor = this.mainPalette.buttonColor
        }
      }

      if (!this.linkData.main.text.color) {
        // default link hover color, see website.scss
        this.linkData.main.text.color = '#0076df'
      }

      this.linkData = cloneDeep(this.linkData)

      this.backupButtonData()
    },
    backupButtonData() {
      this.initialLinkData = cloneDeep(this.linkData)
    },
    updateFontColor(color) {
      this.linkData[this.linkType][this.linkForm].color = color.hex8
    },
    closeColorPicker() {
      this.openColorPicker = false
      this.linkData[this.linkType][this.linkForm].color = this.initialLinkData[this.linkType][this.linkForm].color
    },
    updateHoverColor(color) {
      this.linkData[this.linkType][this.linkForm].hoverColor = color.hex8
    },
    closeHoverColorPicker() {
      this.openHoverColorPicker = false
      this.linkData[this.linkType][this.linkForm].hoverColor = this.initialLinkData[this.linkType][this.linkForm].hoverColor
    },
    updateButtonBgColor(color) {
      this.linkData[this.linkType].button.bgColor = color.hex8
    },
    closeButtonBgColorPicker() {
      this.openButtonBgColorPicker = false
      this.linkData[this.linkType].button.bgColor = this.initialLinkData[this.linkType].button.bgColor
    },
    updateButtonHoverBgColor(color) {
      this.linkData[this.linkType].button.hoverBgColor = color.hex8
    },
    closeButtonHoverBgColorPicker() {
      this.openButtonHoverColorPicker = false
      this.linkData[this.linkType].button.hoverBgColor = this.initialLinkData[this.linkType].button.hoverBgColor
    },
    expandPanel() {
      this.expand = true
    },
    closePanel() {
      this.expand = false
    },
    cancelChanges() {
      this.linkData = this.initialLinkData
      this.theme.link = this.initialLinkData
      this.theme = { ...this.theme }
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
