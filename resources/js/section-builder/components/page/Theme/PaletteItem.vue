<template>
  <div class="w-100">
    <div v-click-outside="closeEditor" class="w-100">
      <div class="theme-colors-wrap">
        <div class="theme-color-labels">
          <div><small>Background</small></div>
          <div><small>Buttons</small></div>
          <div><small>Social Icon</small></div>
          <div><small>Headings</small></div>
          <div><small>Boxes</small></div>
          <div><small>Secondary</small></div>
        </div>
        <draggable-item :value="advancedThemeColors" draggable=".theme-color-item" class="theme-colors" @end="handlePageDragEnd">
          <div class="theme-color-item">
            <div :class="{ active: editor === 'background' }" :style="{ backgroundColor: backgroundColor }" @click.prevent="openSketch('background')" />
          </div>
          <div class="theme-color-item">
            <div :class="{ active: editor === 'button' }" :style="{ backgroundColor: buttonColor }" @click.prevent="openSketch('button')" />
          </div>
          <div class="theme-color-item">
            <div :class="{ active: editor === 'socialIcon' }" :style="{ backgroundColor: socialIconColor }" @click.prevent="openSketch('socialIcon')" />
          </div>
          <div class="theme-color-item">
            <div :class="{ active: editor === 'heading' }" :style="{ backgroundColor: headingColor }" @click.prevent="openSketch('heading')" />
          </div>
          <div class="theme-color-item">
            <div :class="{ active: editor === 'box' }" :style="{ backgroundColor: boxColor }" @click.prevent="openSketch('box')" />
          </div>
          <div class="theme-color-item">
            <div :class="{ active: editor === 'secondary' }" :style="{ backgroundColor: secondaryColor }" @click.prevent="openSketch('secondary')" />
          </div>
        </draggable-item>
      </div>
      <div v-if="openColorPicker" class="position-fixed" :style="sketchStyle">
        <Sketch :value="colorValue" @input="updateThemeColor" />
      </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-2">
      <template v-if="palette.paletteImage && !disableRandomize">
        <div class="p-2 theme-control-item" @click="$emit('randomize')">
          <i class="fa fa-refresh"></i>
          Randomize
        </div>
      </template>
      <div v-if="enableSave" class="p-2 theme-control-item" @click="$emit('save', $event)">
        <i class="fa fa-save"></i>
        Save
      </div>
    </div>
  </div>
</template>

<script>
import { Sketch } from '@lk77/vue3-color'
import themeMixin from '../../../mixins/themeMixin'
export default {
  name: 'PaletteItemOld',
  components: {
    Sketch
  },
  mixins: [themeMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    disableRandomize: {
      type: Boolean,
      default: false
    },
    enableSave: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      editor: null,
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
    colorMode() {
      return 'light'
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
    simpleThemeColors: {
      get() {
        return [this.palette[this.colorMode].backgroundColor, this.palette[this.colorMode].primaryColor, this.palette[this.colorMode].secondaryColor]
      },
      set(newColors) {
        this.palette[this.colorMode].backgroundColor = newColors[0]
        this.palette[this.colorMode].primaryColor = newColors[1]
        this.palette[this.colorMode].secondaryColor = newColors[2]
      }
    },
    advancedThemeColors: {
      get() {
        return [
          this.palette[this.colorMode].backgroundColor,
          this.palette[this.colorMode].buttonColor,
          this.palette[this.colorMode].socialIconColor,
          this.palette[this.colorMode].headingColor,
          this.palette[this.colorMode].boxColor,
          this.palette[this.colorMode].secondaryColor
        ]
      },
      set(newColors) {
        this.palette[this.colorMode].backgroundColor = newColors[0]
        this.palette[this.colorMode].buttonColor = newColors[1]
        this.palette[this.colorMode].socialIconColor = newColors[2]
        this.palette[this.colorMode].headingColor = newColors[3]
        this.palette[this.colorMode].boxColor = newColors[4]
        this.palette[this.colorMode].secondaryColor = newColors[5]
      }
    },
    colorValue() {
      switch (this.editor) {
        case 'primary':
          return this.palette[this.colorMode].primaryColor
        case 'secondary':
          return this.palette[this.colorMode].secondaryColor
        case 'background':
          return this.palette[this.colorMode].backgroundColor
        case 'button':
          return this.palette[this.colorMode].buttonColor
        case 'socialIcon':
          return this.palette[this.colorMode].socialIconColor
        case 'heading':
          return this.palette[this.colorMode].headingColor
        case 'box':
          return this.palette[this.colorMode].boxColor
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
          this.palette[this.colorMode].primaryColor = color.hex8
          break
        }
        case 'secondary': {
          this.palette[this.colorMode].secondaryColor = color.hex8
          break
        }
        case 'background': {
          this.palette[this.colorMode].backgroundColor = color.hex8
          break
        }
        case 'button': {
          this.palette[this.colorMode].buttonColor = color.hex8
          break
        }
        case 'socialIcon': {
          this.palette[this.colorMode].socialIconColor = color.hex8
          break
        }
        case 'heading': {
          this.palette[this.colorMode].headingColor = color.hex8
          break
        }
        case 'box': {
          this.palette[this.colorMode].boxColor = color.hex8
          break
        }
      }
    },
    handlePageDragEnd(event) {
      const tempColor = this.advancedThemeColors[event.newIndex]
      this.advancedThemeColors[event.newIndex] = this.advancedThemeColors[event.oldIndex]
      this.advancedThemeColors[event.oldIndex] = tempColor
      this.advancedThemeColors = Object.assign([], this.advancedThemeColors)
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
