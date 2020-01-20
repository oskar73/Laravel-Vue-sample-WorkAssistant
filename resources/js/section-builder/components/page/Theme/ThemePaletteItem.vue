<template>
  <div class="w-100 mb-2">
    <div v-click-outside="closeEditor" class="w-100">
      <div>{{ palette.name }} ({{ palette.appliedTo }}) {{ palette.applies }}</div>
      <div class="theme-colors-wrap">
        <draggable-item :value="advancedThemeColors" draggable=".theme-color-item" class="theme-colors" @end="handlePageDragEnd">
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.backgroundColor }" @click.prevent="openSketch('background')" />
          </div>
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.buttonColor }" @click.prevent="openSketch('button')" />
          </div>
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.socialIconColor }" @click.prevent="openSketch('socialIcon')" />
          </div>
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.headingColor }" @click.prevent="openSketch('heading')" />
          </div>
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.boxColor }" @click.prevent="openSketch('box')" />
          </div>
          <div class="theme-color-item">
            <div :style="{ backgroundColor: palette.colors.secondaryColor }" @click.prevent="openSketch('secondary')" />
          </div>
        </draggable-item>
      </div>
      <div v-if="openColorPicker" class="position-fixed" :style="sketchStyle">
        <Sketch :value="colorValue" @input="updateThemeColor" />
      </div>
    </div>
  </div>
</template>

<script>
import { Sketch } from '@lk77/vue3-color'

export default {
  components: {
    Sketch
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      editor: 'background',
      openColorPicker: false
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
          this.palette.colors.backgroundColor,
          this.palette.colors.buttonColor,
          this.palette.colors.socialIconColor,
          this.palette.colors.headingColor,
          this.palette.colors.boxColor,
          this.palette.colors.secondaryColor
        ]
      },
      set(newColors) {
        this.palette.colors.backgroundColor = newColors[0]
        this.palette.colors.buttonColor = newColors[1]
        this.palette.colors.socialIconColor = newColors[2]
        this.palette.colors.headingColor = newColors[3]
        this.palette.colors.boxColor = newColors[4]
        this.palette.colors.secondaryColor = newColors[5]
      }
    },
    colorValue() {
      switch (this.editor) {
        case 'primary':
          return this.palette.colors.primaryColor
        case 'secondary':
          return this.palette.colors.secondaryColor
        case 'background':
          return this.palette.colors.backgroundColor
        case 'button':
          return this.palette.colors.buttonColor
        case 'socialIcon':
          return this.palette.colors.socialIconColor
        case 'heading':
          return this.palette.colors.headingColor
        case 'box':
          return this.palette.colors.boxColor
        default:
          return '#000000'
      }
    }
  },
  methods: {
    openSketch(editor) {
      this.editor = editor
      this.openColorPicker = true
      this.$emit('setActiveColor', editor)
    },
    updateThemeColor(color) {
      switch (this.editor) {
        case 'primary': {
          this.palette.colors.primaryColor = color.hex8
          break
        }
        case 'secondary': {
          this.palette.colors.secondaryColor = color.hex8
          break
        }
        case 'background': {
          this.palette.colors.backgroundColor = color.hex8
          break
        }
        case 'button': {
          this.palette.colors.buttonColor = color.hex8
          break
        }
        case 'socialIcon': {
          this.palette.colors.socialIconColor = color.hex8
          break
        }
        case 'heading': {
          this.palette.colors.headingColor = color.hex8
          break
        }
        case 'box': {
          this.palette.colors.boxColor = color.hex8
          break
        }
      }
      this.$emit('changedPaletteColor', this.palette)
    },
    handlePageDragEnd(event) {
      const tempColor = this.advancedThemeColors[event.newIndex]
      this.advancedThemeColors[event.newIndex] = this.advancedThemeColors[event.oldIndex]
      this.advancedThemeColors[event.oldIndex] = tempColor
      this.advancedThemeColors = Object.assign([], this.advancedThemeColors)
      this.$emit('changedPaletteColor', this.palette)
    },
    closeEditor() {
      if (this.openColorPicker) {
        this.openColorPicker = false
      }
    }
  }
}
</script>

<style scoped></style>
