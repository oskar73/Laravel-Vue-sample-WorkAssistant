<template>
  <div class="p-2">
    <bz-select v-model="activeCategoryIndex" :options="paletteCategories" />
    <div class="color-panel">
      <color-mode v-model="colorMode" />
      <div class="mt-1" style="overflow: auto; max-height: 250px">
        <div v-for="(palette, index) of palettes" :key="index" class="mt-2">
          <div :key="index">
            <div class="d-flex justify-content-between">
              {{ palette.name }}
              <div v-if="selectedPalette?.id === palette.id && showControl" class="d-flex align-items-center">
                <div class="btn-delete ml-2" @click="handleDelete">Delete</div>
              </div>
            </div>
            <div class="color-group" :class="{ active: selectedPalette?.id === palette.id }" @click="setThemeColors(palette)">
              <div class="color-item" :style="{ backgroundColor: palette.colors.backgroundColor }" />
              <div class="color-item" :style="{ backgroundColor: palette.colors.buttonColor }" />
              <div class="color-item" :style="{ backgroundColor: palette.colors.socialIconColor }" />
              <div class="color-item" :style="{ backgroundColor: palette.colors.headingColor }" />
              <div class="color-item" :style="{ backgroundColor: palette.colors.boxColor }" />
              <div class="color-item" :style="{ backgroundColor: palette.colors.secondaryColor }" />
            </div>
          </div>
        </div>
      </div>
      <div class="mt-2">
        <new-theme-palette-item v-if="selectedPalette" v-model="selectedPalette.colors" @changedPaletteColor="changedSelectedPaletteColor" @save="createNewPaletteWithSelected" />
      </div>
    </div>
    <div class="mt-1">
      <button v-if="selectedPalette" class="btn btn-primary" @click="choose">Choose</button>
    </div>
  </div>
</template>

<script>
import BzSelect from '../BzSelect.vue'
import builderMixin from '../../../mixins/builderMixin'
import NewThemePaletteItem from './NewThemePaletteItem.vue'
import { cloneDeep } from 'lodash'
import ColorMode from './ColorMode.vue'
import { deletePalette, savePalette } from '../../../apis'

export default {
  components: { ColorMode, NewThemePaletteItem, BzSelect },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    user: {
      type: Boolean,
      default: false
    },
    disableApplyTo: {
      type: Boolean,
      default: false
    },
    showControl: {
      type: Boolean,
      showControl: false
    }
  },
  data() {
    return {
      activeCategoryIndex: 0,
      selectedPalette: null,
      colorMode: 'light'
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
        return this.userPalettes.advanced.map((category, index) => ({
          label: category.name,
          value: index
        }))
      } else {
        return this.systemPalettes.advanced.map((category, index) => ({
          label: category.name,
          value: index
        }))
      }
    },
    palettes: {
      get() {
        if (this.user) {
          if (this.userPalettes.advanced.length > 0) {
            return this.userPalettes.advanced[this.activeCategoryIndex]?.palettes?.[this.colorMode] || []
          }
        } else {
          if (this.systemPalettes.advanced.length > 0) {
            return this.systemPalettes.advanced[this.activeCategoryIndex]?.palettes?.[this.colorMode] || []
          }
        }
        return []
      },
      set(value) {
        if (this.user) {
          if (this.userPalettes.advanced.length > 0) {
            this.userPalettes.advanced[this.activeCategoryIndex].palettes[this.colorMode] = value
          }
        } else {
          if (this.systemPalettes.advanced.length > 0) {
            this.systemPalettes.advanced[this.activeCategoryIndex].palettes[this.colorMode] = value
          }
        }
      }
    }
  },
  watch: {
    selectedPalette: {
      deep: true,
      handler(v) {
        if (v) {
          this.setPreviewPalette(v)
        }
      }
    }
  },
  methods: {
    /**
     * Sets Theme colors from the preset color list when one of them is clicked.
     * @param palette
     */
    setThemeColors(palette) {
      this.selectedPalette = cloneDeep(palette)
      this.setPreviewPalette(palette)
      this.refreshTheme()
    },
    changedSelectedPaletteColor(palette) {},
    createNewPaletteWithSelected(name, colors) {
      let selectedCategory = null
      if (this.user) {
        selectedCategory = this.userPalettes.advanced[this.activeCategoryIndex]
      } else {
        selectedCategory = this.systemPalettes.advanced[this.activeCategoryIndex]
      }

      const newPalette = {
        category_id: selectedCategory.category_id,
        name,
        data: cloneDeep(colors),
        status: true,
        mode: this.colorMode
      }
      savePalette(newPalette).then((res) => {
        if (res.data.status) {
          if (this.user) {
            this.userPalettes.advanced[this.activeCategoryIndex]?.palettes?.[this.colorMode].push(res.data.data.palette)
          } else {
            this.systemPalettes.advanced[this.activeCategoryIndex]?.palettes?.[this.colorMode].push(res.data.data.palette)
          }
        }
      })
    },
    choose() {
      this.$emit('choose', this.selectedPalette)
    },
    handleDelete() {
      this.$dialog.confirm().then((res) => {
        if (res) {
          deletePalette(this.selectedPalette.id).then(() => {
            this.palettes = this.palettes.filter((p) => p.id !== this.selectedPalette.id)
            this.selectedPalette = null
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.color-panel {
  overflow-y: auto;
  padding-top: 20px;
  padding-bottom: 20px;
}
</style>
