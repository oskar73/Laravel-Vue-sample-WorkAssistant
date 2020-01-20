<template>
  <div class="my-1">
    <div class="d-flex justify-content-between">
      <div>{{ themeName || themeItem.name }}</div>
      <div v-if="$store.state.theme?.id !== themeItem.id && showControl" class="d-flex align-items-center">
        <div class="btn-delete" @click="handleEdit">Edit</div>
        <div class="btn-delete ml-2" @click="handleDelete">Delete</div>
      </div>
    </div>
    <div v-if="isExpanded">
      <div class="theme-colors-wrap">
        <div class="theme-color-labels mt-2 mb-2 !tw-text-xs">
          <div><small>Background</small></div>
          <div><small>Buttons</small></div>
          <div><small>Social Icon</small></div>
          <div><small>Headings</small></div>
          <div><small>Boxes</small></div>
          <div><small>Secondary</small></div>
        </div>
      </div>
      <div v-for="(color, index) in colors" :key="index">
        <theme-palette-item :value="color" @changedPaletteColor="onChangedColor" />
      </div>
      <div class="d-flex justify-content-end">
        <button v-if="isChanged" class="btn btn-danger" @click="cancelChange">Cancel Change</button>
        <button v-if="isChanged" class="btn btn-success ml-2" @click="handleSave">Save</button>
      </div>
    </div>
    <div v-else>
      <div v-if="showLabels" class="theme-colors-wrap">
        <div class="theme-color-labels mt-2 mb-2 !tw-text-sm">
          <div><small>Background</small></div>
          <div><small>Buttons</small></div>
          <div><small>Social Icon</small></div>
          <div><small>Headings</small></div>
          <div><small>Boxes</small></div>
          <div><small>Secondary</small></div>
        </div>
      </div>
      <div v-if="mainPalette" class="color-group" :class="{ active: active }" @click="preview">
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.backgroundColor }" />
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.buttonColor }" />
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.socialIconColor }" />
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.headingColor }" />
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.boxColor }" />
        <div class="color-item" :style="{ backgroundColor: mainPalette.colors.secondaryColor }" />
      </div>
    </div>
  </div>
</template>

<script>
import ThemePaletteItem from './ThemePaletteItem.vue'
import { deleteTheme, updateTheme, storeTheme } from '../../../apis'
import { cloneDeep } from 'lodash'

export default {
  components: { ThemePaletteItem },
  props: {
    themeName: {
      type: String,
      default: undefined
    },
    themeItem: {
      type: Object,
      required: true
    },
    showControl: {
      type: Boolean,
      default: true
    },
    showLabels: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isExpanded: false,
      isChanged: false,
      themeBackup: null
    }
  },
  computed: {
    active() {
      return this.$store.state.theme?.id === this.themeItem.id
    },
    mainPalette() {
      return this.themeItem.data.palettes?.find((p) => p.appliedTo === 'website')
    },
    colors() {
      return this.themeItem.data.palettes
    }
  },
  watch: {
    active(v) {
      if (v) {
        this.themeBackup = cloneDeep(this.themeItem)
      } else {
        this.isExpanded = false
        this.cancelChange()
      }
    }
  },
  methods: {
    cancelChange() {
      if (this.isChanged) {
        this.$store.commit('updateThemes', this.themeBackup)
        this.$store.commit('updateTheme', this.themeBackup.data)
        this.isChanged = false
      }
    },
    onChangedColor() {
      this.isChanged = true
      this.preview()
    },
    handleSave() {
      this.$store.commit('openThemeNameModal', {
        value: { themeName: this.themeItem.name },
        onConfirm: (value) => {
          const newTheme = { ...this.themeItem, name: value.name }
          if (value.override) {
            updateTheme(newTheme).then((res) => {
              this.$store.commit('updateThemes', res.data.data.theme)
              this.isChanged = false
            })
          } else {
            storeTheme(newTheme).then((res) => {
              this.$store.commit('addThemes', res.data.data.theme)
              this.$store.commit('updateTheme', res.data.data.theme)
              this.isChanged = false
            })
          }
        }
      })
    },
    preview() {
      this.$emit('select', this.themeItem)
    },
    handleEdit() {
      this.$emit('update:modelValue', this.themeItem)
    },
    handleDelete() {
      this.$dialog.confirm().then((res) => {
        if (res) {
          deleteTheme(this.themeItem.id).then(() => {
            this.$store.commit('removeTheme', this.themeItem.id)
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.btn-edit,
.btn-delete,
.btn-expand {
  text-decoration: underline;
  cursor: pointer;
  color: var(--bz-primary-color);
  margin-left: 8px;
}
</style>
