<template>
  <div class="w-100">
    <div v-click-outside="closeEditor" class="w-100">
      <div class="theme-colors-wrap">
        <div v-if="showLabels" class="theme-color-labels !tw-text-xs">
          <div><small>Background</small></div>
          <div><small>Buttons</small></div>
          <div><small>Social Icon</small></div>
          <div><small>Headings</small></div>
          <div><small>Boxes</small></div>
          <div><small>Secondary</small></div>
        </div>
        <draggable v-model="advancedThemeColors" class="theme-colors">
          <template #item="{ item, index }">
            <div class="theme-color-item">
              <div :class="{ active: editor === 'background' }" :style="{ backgroundColor: item }" @click.prevent="openSketch(getEditor(index))" />
            </div>
          </template>
        </draggable>
        <div v-if="enableLock" class="theme-color-labels mt-1">
          <div class="cursor-pointer" @click="handleLockClick('background')">
            <lock-outlined-icon v-if="isLocked('background')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
          <div class="cursor-pointer" @click="handleLockClick('button')">
            <lock-outlined-icon v-if="isLocked('button')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
          <div class="cursor-pointer" @click="handleLockClick('socialIcon')">
            <lock-outlined-icon v-if="isLocked('socialIcon')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
          <div class="cursor-pointer" @click="handleLockClick('heading')">
            <lock-outlined-icon v-if="isLocked('heading')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
          <div class="cursor-pointer" @click="handleLockClick('box')">
            <lock-outlined-icon v-if="isLocked('box')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
          <div class="cursor-pointer" @click="handleLockClick('secondary')">
            <lock-outlined-icon v-if="isLocked('secondary')" :size="18" />
            <lock-open-outlined-icon v-else :size="18" />
          </div>
        </div>
      </div>
      <div v-if="openColorPicker" class="position-fixed" :style="sketchStyle">
        <Sketch :model-value="colorValue" @update:modelValue="updateThemeColor" />
      </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-2">
      <div v-if="isChanged && enableSave" class="p-2 theme-control-item" @click="save">
        <i class="mdi mdi-content-save-outline"></i>
        Save
      </div>
    </div>
  </div>
</template>

<script>
import { Sketch } from '@lk77/vue3-color'
import LockOpenOutlinedIcon from '../../icons/LockOpenOutlined.vue'
import LockOutlinedIcon from '../../icons/LockOutlined.vue'
import Draggable from '@/public/draggable'

export default {
  components: {
    LockOutlinedIcon,
    LockOpenOutlinedIcon,
    Sketch,
    Draggable
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    enableRandomize: {
      type: Boolean,
      default: false
    },
    enableSave: {
      type: Boolean,
      default: true
    },
    showLabels: {
      type: Boolean,
      default: true
    },
    enableLock: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      editor: 'background',
      openColorPicker: false,
      isChanged: false,
      lockedIcons: []
    }
  },
  computed: {
    palette: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    },
    sketchStyle() {
      let paddingLeft = 0
      switch (this.editor) {
        case 'primary':
          paddingLeft = 0
          break
        case 'secondary':
          paddingLeft = 15
          break
        case 'lightMode':
        case 'darkMode':
          paddingLeft = 0
          break
        default:
          paddingLeft = 0
      }
      return {
        marginTop: '10px',
        paddingLeft: paddingLeft + 'px',
        zIndex: 999
      }
    },
    advancedThemeColors: {
      get() {
        return [
          this.palette.backgroundColor,
          this.palette.buttonColor,
          this.palette.socialIconColor,
          this.palette.headingColor,
          this.palette.boxColor,
          this.palette.secondaryColor
        ]
      },
      set(newColors) {
        if (!this.isLocked('background')) {
          this.palette.backgroundColor = newColors[0]
        }
        if (!this.isLocked('button')) {
          this.palette.buttonColor = newColors[1]
        }
        if (!this.isLocked('socialIcon')) {
          this.palette.socialIconColor = newColors[2]
        }
        if (!this.isLocked('heading')) {
          this.palette.headingColor = newColors[3]
        }
        if (!this.isLocked('box')) {
          this.palette.boxColor = newColors[4]
        }
        if (!this.isLocked('secondary')) {
          this.palette.secondaryColor = newColors[5]
        }
      }
    },
    colorValue() {
      switch (this.editor) {
        case 'primary':
          return this.palette.primaryColor
        case 'secondary':
          return this.palette.secondaryColor
        case 'background':
          return this.palette.backgroundColor
        case 'button':
          return this.palette.buttonColor
        case 'socialIcon':
          return this.palette.socialIconColor
        case 'heading':
          return this.palette.headingColor
        case 'box':
          return this.palette.boxColor
        default:
          return '#000000'
      }
    }
  },
  watch: {
    value(newValue, oldValue) {
      this.isChanged = false
    },
    enableLock() {
      this.lockedIcons = []
    }
  },
  methods: {
    getEditor(index) {
      return ['background', 'button', 'socialIcon', 'heading', 'box', 'secondary'][index]
    },
    handleLockClick(colorLabel) {
      const index = this.lockedIcons.indexOf(colorLabel)
      if (index > -1) {
        this.lockedIcons.splice(index, 1)
      } else {
        this.lockedIcons.push(colorLabel)
      }
      this.lockedIcons = [...this.lockedIcons]
      this.editor = null
      this.openColorPicker = false
    },
    openSketch(editor) {
      if (!this.isLocked(editor)) {
        this.editor = editor
        this.openColorPicker = true
        this.$emit('setActiveColor', editor)
      }
    },
    isLocked(colorLabel) {
      return this.lockedIcons.includes(colorLabel)
    },
    updateThemeColor(color) {
      if (this.isLocked(color)) {
        return
      }
      switch (this.editor) {
        case 'primary': {
          this.palette.primaryColor = color.hex8
          break
        }
        case 'secondary': {
          this.palette.secondaryColor = color.hex8
          break
        }
        case 'background': {
          this.palette.backgroundColor = color.hex8
          break
        }
        case 'button': {
          this.palette.buttonColor = color.hex8
          break
        }
        case 'socialIcon': {
          this.palette.socialIconColor = color.hex8
          break
        }
        case 'heading': {
          this.palette.headingColor = color.hex8
          break
        }
        case 'box': {
          this.palette.boxColor = color.hex8
          break
        }
      }
      this.isChanged = true
      this.$emit('changedPaletteColor', this.palette)
    },
    closeEditor() {
      if (this.openColorPicker) {
        this.openColorPicker = false
      }
    },
    save() {
      this.$store.commit('openPaletteNameModal', {
        onConfirm: (value) => {
          const newPalette = {
            name: value.name,
            category_id: value.categoryId,
            data: this.palette
          }
          this.$emit('save', newPalette)
          this.isChanged = false
        }
      })
    }
  }
}
</script>

<style lang="scss">
.theme-colors {
  div {
    display: flex!important;
    flex: 1!important;
  }
}
</style>
