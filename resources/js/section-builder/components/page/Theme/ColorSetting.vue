<template>
  <div class="color-setting-root">
    <div class="card mt-2">
      <div class="px-3 d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleExpand">
        Color Change
        <bz-arrow-up-icon v-if="expand" />
        <bz-arrow-down-icon v-else />
      </div>
      <div v-if="expand">
        <template v-if="colorPalettes">
          <div v-for="(palette, index) of colorPalettes" :key="index" class="mt-2">
            <div :key="index">
              <div class="d-flex justify-content-between">
                <div class="mb-1">
                  {{ palette.name }} ({{ palette.appliedTo }})
                  <!--                  <span v-if="palette.applies">({{ palette.applies }})</span>-->
                </div>
                <div v-if="palette.appliedTo !== 'website'" class="btn-delete border-b cursor-pointer" @click="handleDeletePalette(index)">Delete</div>
              </div>
              <new-theme-palette-item v-model="colorPalettes[index].colors" :show-labels="index === 0" @save="createNewPalette" />
            </div>
          </div>
        </template>

        <button v-if="isNewPaletteMode === false" class="btn btn-success mr-2 my-2" @click="onClickAddPalette">Add Palette</button>

        <template v-if="isNewPaletteMode">
          <div v-if="colorPalettes?.length > 0" class="d-flex tw-cursor-pointer flex-column mt-2 mb-2">
            <label class="tw-flex tw-gap-x-2 tw-items-center">
              <input v-model="appliedTo" type="radio" value="page" class="apply-to !tw-m-0" />
              Apply Palette to individual Pages
            </label>
            <label class="tw-flex tw-gap-x-2 tw-items-center">
              <input v-model="appliedTo" type="radio" value="section" class="apply-to !tw-m-0" />
              Apply Palette to Individual Sections
            </label>
          </div>
          <div class="card">
            <div class="d-flex align-items-center justify-content-between p-2 cursor-pointer" @click.prevent="toggleShowPalettes('system')">
              Bizinabox Color Palettes
              <bz-arrow-up-icon v-if="showPalettes === 'system'" />
              <bz-arrow-down-icon v-else />
            </div>
            <new-theme-palette-list v-if="showPalettes === 'system'" :value="theme" :show-control="true" :user="false" @choose="choosePalette" />
          </div>

          <div v-if="isWebsite" class="card mt-2">
            <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer" @click.prevent="toggleShowPalettes('user')">
              My Color Palettes
              <bz-arrow-up-icon v-if="showPalettes === 'user'" />
              <bz-arrow-down-icon v-else />
            </div>
            <new-theme-palette-list v-if="showPalettes === 'user'" :value="theme" :user="true" :show-control="true" @choose="choosePalette" />
          </div>

          <div class="card mt-2">
            <div class="d-flex align-items-center justify-content-between p-2 cursor-pointer" @click="toggleShowPalettes('new')">
              Create New Palette
              <bz-arrow-up-icon v-if="showPalettes === 'new'" />
              <bz-arrow-down-icon v-else />
            </div>
            <create-new-palette v-if="showPalettes === 'new'" v-model="theme" :user="isWebsite" @save="createNewPalette" />
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import { cloneDeep, merge } from 'lodash'
import builderMixin from '../../../mixins/builderMixin'
import NewThemePaletteList from './NewThemePaletteList.vue'
import CreateNewPalette from './CreateNewPalette.vue'
import { savePalette } from '../../../apis'
import NewThemePaletteItem from './NewThemePaletteItem.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'ColorSetting',
  components: {
    NewThemePaletteItem,
    CreateNewPalette,
    NewThemePaletteList,
    BzArrowDownIcon,
    BzArrowUpIcon
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
      expand: true,
      colorData: [],
      initialColorData: null,
      showPalettes: null
    }
  },
  computed: {
    theme: {
      get() {
        return this.$store.state.theme || this.template.data.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    },
    colorPalettes: {
      get() {
        if (this.editTheme) {
          return this.theme.palettes
        } else {
          if (this.theme?.newTheme) {
            return this.theme.palettes || []
          }
          return []
        }
      },
      set(v) {
        if (this.editTheme) {
          this.theme.palettes = v
        } else {
          this.colorData = v
        }
      }
    }
  },
  watch: {
    colorData: {
      deep: true,
      handler(value) {
        this.theme = { ...this.theme, palettes: value }
      }
    },
    editTheme: {
      deep: true,
      handler() {
        this.updateColorData()
      }
    }
  },
  created() {
    this.updateColorData()
  },
  methods: {
    updateColorData() {
      this.isNewPaletteMode = false
      this.setPreviewPalette(null)
    },
    toggleExpand() {
      if (this.expand) {
        this.expand = false
      } else {
        this.$emit('expand')
        this.expand = true
      }
      this.setPreviewPalette(null)
    },
    onClickAddPalette() {
      this.appliedTo = 'website'
      this.paletteAppliedPages = []
      this.paletteAppliedSections = {}
      this.isNewPaletteMode = true
    },
    onPaletteCreated() {
      this.isNewPaletteMode = false
      this.showPalettes = ''
      this.setPreviewPalette(null)
      this.refreshTheme()
    },
    choosePalette(palette) {
      this.addPaletteToTheme(palette.name, palette.colors)
    },
    addPaletteToTheme(name, colors) {
      if (this.appliedTo) {
        const palette = {
          name: name,
          colors: colors,
          appliedTo: this.appliedTo
        }
        if (this.appliedTo === 'page') {
          palette.applies = cloneDeep(this.paletteAppliedPages)
        } else if (this.appliedTo === 'section') {
          palette.applies = cloneDeep(this.paletteAppliedSections)
        }

        if (this.editTheme) {
          this.addPalette(palette)
        } else {
          if (this.theme?.newTheme) {
            this.addPalette(palette)
          } else {
            let newButtonSetting = cloneDeep(this.theme.button)
            newButtonSetting = merge(newButtonSetting, {
              outline: false,
              bgColor: ''
            })

            let newSocialIconSetting = cloneDeep(this.theme.socialIcon)
            newSocialIconSetting = merge(newSocialIconSetting, {
              header: {
                individual: false,
                group: {
                  iconColor: ''
                },
                bizinabox: {
                  iconColor: ''
                }
              },
              main: {
                individual: false,
                group: {
                  iconColor: ''
                }
              },
              footer: {
                individual: false,
                group: {
                  iconColor: ''
                }
              }
            })

            let newFontSetting = cloneDeep(this.theme.font)
            newFontSetting = merge(newFontSetting, {
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

            let newLinkSetting = cloneDeep(this.theme.font)
            newLinkSetting = merge(newLinkSetting, {
              header: {
                type: 'text',
                text: {
                  color: ''
                },
                button: {
                  bgColor: '',
                  color: ''
                }
              },
              footer: {
                type: 'text',
                text: {
                  color: ''
                },
                button: {
                  bgColor: '',
                  color: ''
                }
              },
              main: {
                text: {
                  color: ''
                },
                button: {
                  bgColor: '',
                  color: ''
                }
              }
            })

            this.theme = {
              ...this.theme,
              palettes: [palette],
              button: newButtonSetting,
              socialIcon: newSocialIconSetting,
              font: newFontSetting,
              link: newLinkSetting,
              newTheme: true
            }
          }
        }
        this.onPaletteCreated()
      } else {
        toast.error('Please select where to applied!')
      }
    },
    handleDeletePalette(index) {
      this.theme.palettes.splice(index, 1)
      this.theme = { ...this.theme }
    },
    createNewPalette(newPalette) {
      savePalette(newPalette).then((res) => {
        console.log('savePalette', res)
        const _np = cloneDeep(res.data.data.palette)
        const _catId = _np.category_id
        const _type = _np.type || 'advanced'
        const _mode = _np.mode || 'light'

        if (this.isWebsite) {
          // eslint-disable-next-line
          const index = this.userPalettes[_type].findIndex((p) => p.category_id == _catId)
          if (this.userPalettes[_type][index].palettes[_mode]) {
            this.userPalettes[_type][index].palettes[_mode].push(_np)
          } else {
            this.userPalettes[_type][index].palettes[_mode] = [_np]
          }

          // add new palette to system palette list in store.
          // this.$store.commit('updateUserPalettes', this.userPalettes)
        } else {
          // eslint-disable-next-line
          const index = this.systemPalettes[_type].findIndex((p) => p.category_id == _catId)
          if (this.systemPalettes[_type][index].palettes[_mode]) {
            this.systemPalettes[_type][index].palettes[_mode].push(_np)
          } else {
            this.systemPalettes[_type][index].palettes[_mode] = [_np]
          }

          // add new palette to system palette list in store.
          this.$store.commit('updateSystemPalettes', this.systemPalettes)
        }
        // apply changed palette to current editing/creating theme object.
        this.choosePalette(res.data.data.palette)
        this.onPaletteCreated()
      })
    },
    toggleShowPalettes(value) {
      this.showPalettes = this.showPalettes === value ? '' : value
    },
    cancelChanges() {
      this.colorData = this.initialColorData
      this.expand = false
    },
    expandPanel() {
      this.expand = true
    },
    closePanel() {
      this.expand = false
    }
  }
}
</script>

<style scoped lang="scss">
.color-setting-root {
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
  }
}
</style>
