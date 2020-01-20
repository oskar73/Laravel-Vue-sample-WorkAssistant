<script>
import { defineComponent } from 'vue'
import SelectFont from '@/svg-editor/components/elements/select-font.vue'
import InputRange from '@/svg-editor/components/elements/input-range.vue'
import appMixin from '@/svg-editor/mixins/app-mixin'
import eventBus from '@/public/eventBus'
import editorMixin from '@/svg-editor/mixins/editor-mixin'

export default defineComponent({
  name: 'RightSideBar',
  components: {
    InputRange,
    SelectFont
  },
  mixins: [appMixin, editorMixin],
  mounted() {
    eventBus.$on('background.selected', () => {
      this.setPropertiesSame()
      this.updatePanel()
      this.initHoverHandler()
      this.updateGlobalTextInput()
      this.backgroundSelected = true
    })
    eventBus.$on('background.cleared', () => {
      this.backgroundSelected = false
    })
  },
  methods: {
    onChangeRangeField(structure, evt, key = 'value') {
      // Set custom opacity value from input change
      if (evt.target) {
        const value = parseFloat(evt.target.value)

        // Check value the presence in the right range
        if (this.inRange(value, structure.min, structure.max)) {
          structure[key] = value
        }
      }
    },
    toFront() {
      if (!this.backgroundSelected) {
        this.canvas.moveUpDownSelected('Up')
      }
    },
    toBack() {
      if (!this.backgroundSelected) {
        this.canvas.moveUpDownSelected('Down')
      }
    },
    canGroup() {
      return this.selected.length > 1 && this.selected[0] !== null
    },
    canUngroup() {
      if (this.selected.length === 1) {
        const item = this.selected[0]

        if (item && item.tagName === 'g') {
          return true
        }
      }

      return false
    },
    undoAction() {
      this.canvas.undoMgr.undo()
    },
    redoAction() {
      this.canvas.undoMgr.redo()
    },
    remove() {
      if (!this.backgroundSelected && this.isSelected) {
        this.canvas.deleteSelectedElements()
      }
    },
    alignVertical() {
      this.canvas.alignSelectedElements('m', 'page')
    },
    alignHorizontal() {
      this.canvas.alignSelectedElements('c', 'page')
    },
    onChangeFontSize(evt) {
      // Change <input> near input-range
      this.onChangeRangeField(this.attributes.text.font, evt, 'size')

      // Set value
      this.canvas.setFontSize(this.attributes.text.font.size)
    },
    onChangeLetterSpacing(evt) {
      // Change <input> near input-range
      this.onChangeRangeField(this.attributes.text.letterSpacing, evt)

      let value = this.attributes.text.letterSpacing.value

      let isNormalSpacing = false

      // If the value has not changed, then set "normal" spacing
      if (value === 0) {
        isNormalSpacing = true
      }

      // Define value value
      if (isNormalSpacing) {
        value = 'normal'
      }

      // Set value
      this.canvas.setLetterSpacing(value)
    },
    // On change properties
    onChangeOpacity(evt) {
      // Change <input> near input-range
      this.onChangeRangeField(this.attributes.opacity, evt)

      // Set value
      this.canvas.changeSelectedAttribute('opacity', this.attributes.opacity.value)
    },
    onChangeBlur(evt) {
      // Change <input> near input-range
      this.onChangeRangeField(this.attributes.blur, evt)

      // Set value
      this.canvas.setBlur(this.attributes.blur.value, true)
    },
    onChangeStroke(evt) {
      // Change <input> near input-range
      this.onChangeRangeField(this.attributes.stroke, evt)

      this.canvas.setStrokeWidth(this.attributes.stroke.value)
    },
    onChangeFont() {
      this.canvas.setFontFamily(this.attributes.text.font.name)
    },
    onChangeText() {
      // Set text
      this.setText(this.attributes.text.value)

      // Check/UnCheck [AA]
      this.attributes.text.isUpperCase = this.textIsInUpperCase()
    },
    onChangeBold() {
      this.canvas.setBold(!this.attributes.text.font.bold)
    },
    onChangeItalic() {
      this.canvas.setItalic(!this.attributes.text.font.italic)
    },
    onChangeRegister() {
      // Get selected element
      const element = this.getFirstSelected()

      // Get text value
      const text = this.getText(element)

      // Selected text to upper/lower case
      const updatedText = text === text.toUpperCase() ? text.charAt(0).toUpperCase() + text.slice(1).toLowerCase() : text.toUpperCase()

      // Update selected text
      return this.setText(updatedText)
    }
  }
})
</script>

<template>
  <div id="right-sidebar">
    <div class="item top">
      <span class="item-group">
        <span title="Move element back" class="placeholder" @click="toBack">
          <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="18" height="18" rx="4" fill="#9f9f9f" />
            <rect x="7.39746" y="7.0874" width="18" height="18" rx="3" fill="white" />
            <rect x="7.89746" y="7.5874" width="17" height="17" rx="2.5" stroke="#060C3F" stroke-opacity="0.1" />
          </svg>
        </span>
        <span title="Move element forward" class="placeholder" @click="toFront">
          <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="8.23804" y="7.0874" width="18" height="18" rx="3" fill="white" />
            <rect x="8.73804" y="7.5874" width="17" height="17" rx="2.5" stroke="#060C3F" stroke-opacity="0.1" />
            <rect x="0.840332" width="18" height="18" rx="4" fill="#9f9f9f" />
          </svg>
        </span>
      </span>

      <span class="item-group">
        <span title="Undo action" class="placeholder" @click="undoAction">
          <i class="mdi mdi-arrow-u-left-top tw-text-3xl"></i>
        </span>
        <span title="Redo action" class="placeholder" @click="redoAction">
          <i class="mdi mdi-arrow-u-right-top tw-text-3xl"></i>
        </span>
      </span>

      <span title="Delete" :class="{ 'hover:tw-bg-red-500': isSelected }" @click="remove">
        <i class="mdi mdi-trash-can-outline tw-text-3xl" :class="{ 'tw-text-gray-400': !isSelected, 'hover:tw-text-white': isSelected }"></i>
      </span>
    </div>

    <div v-show="isSelected">
      <!--                    align items -->
      <div class="item">
        <label class="label_txt">ALIGN ITEMS</label>
        <div class="tw-w-full tw-flex">
          <button class="tw-flex tw-px-2 tw-py-1 tw-border tw-rounded tw-mr-4" @click="alignHorizontal()">
            <i class="mdi mdi-format-horizontal-align-center tw-mr-2"></i>
            HORIZONTAL
          </button>
          <button class="tw-flex tw-px-2 tw-py-1tw-border tw-rounded" @click="alignVertical()">
            <i class="mdi mdi-format-vertical-align-center tw-mr-2"></i>
            VERTICAL
          </button>
        </div>
      </div>
      <!--                    text editor -->
      <div v-show="isTextSelected()" class="item text-edit">
        <label for="text-input" class="label_txt">text</label>
        <span class="text-type">
          <input id="text-input" ref="text" v-model="attributes.text.value" type="text" @keyup="onChangeText" />
          <label :class="{ active: attributes.text.font.bold }" class="placeholder" title="Bold">
            <input v-model="attributes.text.font.bold" class="checkbox" type="checkbox" @click="onChangeBold" />
            <span class="radio-custom"><strong>B</strong></span>
          </label>
          <label :class="{ active: attributes.text.font.italic }" class="placeholder" title="Italic">
            <input v-model="attributes.text.font.italic" class="checkbox" type="checkbox" @click="onChangeItalic" />
            <span class="radio-custom"><em>I</em></span>
          </label>
          <label :class="{ active: attributes.text.isUpperCase }" class="placeholder" title="Upper case">
            <input v-model="attributes.text.isUpperCase" class="checkbox" type="checkbox" @click="onChangeRegister" />
            <span class="radio-custom"><span>AA</span></span>
          </label>
        </span>
      </div>
    </div>

    <div v-show="isTextSelected()">
      <div class="font-item">
        <label class="label_txt">font</label>
        <select-font v-model="attributes.text.font.name" @input="onChangeFont"></select-font>
      </div>

      <div class="item">
        <label class="label_txt">font size</label>
        <span class="size-input">
          <input :value="attributes.text.font.size" type="text" @keypress="isNumber($event)" @keyup="onChangeFontSize($event)" />
          <span>
            <input-range
              v-model="attributes.text.font.size"
              :min="attributes.text.font.min"
              :max="attributes.text.font.max"
              :interval="attributes.text.font.interval"
              @update:modelValue="onChangeFontSize"
            ></input-range>
          </span>
        </span>
      </div>

      <div class="item">
        <label class="label_txt">letter spacing</label>
        <span class="size-input">
          <input :value="attributes.text.letterSpacing.value" type="text" @keypress="isNumber($event)" @keyup="onChangeLetterSpacing($event)" />
          <span>
            <input-range
              v-model="attributes.text.letterSpacing.value"
              :min="attributes.text.letterSpacing.min"
              :max="attributes.text.letterSpacing.max"
              :interval="attributes.text.letterSpacing.interval"
              @update:modelValue="onChangeLetterSpacing"
            ></input-range>
          </span>
        </span>
      </div>
    </div>
    <!--                stroke, blur, opacity-->
    <div v-show="isSelected">
      <!--                    opacity-->
      <div class="item">
        <label class="label_txt">opacity</label>
        <span class="size-input">
          <input :value="attributes.opacity.value" type="text" @keypress="isNumber($event)" @keyup="onChangeOpacity($event)" />
          <span>
            <input-range
              ref="slider-opacity"
              v-model="attributes.opacity.value"
              :value="attributes.opacity.value"
              :min="attributes.opacity.min"
              :max="attributes.opacity.max"
              :interval="attributes.opacity.interval"
              @update:modelValue="onChangeOpacity"
            ></input-range>
          </span>
        </span>
      </div>
      <!--                    blur-->
      <div class="item">
        <label class="label_txt">blur</label>
        <span class="size-input">
          <input :value="attributes.blur.value" type="text" @keypress="isNumber($event)" @keyup="onChangeBlur($event)" />
          <span>
            <input-range
              ref="slider-blur"
              v-model="attributes.blur.value"
              :value="attributes.blur.value"
              :min="attributes.blur.min"
              :max="attributes.blur.max"
              :interval="attributes.blur.interval"
              @update:modelValue="onChangeBlur"
            ></input-range>
          </span>
        </span>
      </div>
      <!--                    stroke-->
      <div class="item">
        <label class="label_txt">stroke</label>
        <span class="size-input">
          <input :value="attributes.stroke.value" type="text" @keypress="isNumber($event)" @keyup="onChangeStroke($event)" />
          <span>
            <input-range
              ref="slider-stroke"
              v-model="attributes.stroke.value"
              :value="attributes.stroke.value"
              :min="attributes.stroke.min"
              :max="attributes.stroke.max"
              :interval="attributes.stroke.interval"
              @update:modelValue="onChangeStroke"
            ></input-range>
          </span>
        </span>
      </div>
    </div>
    <div v-show="!isSelected" class="select-item-to-edit">
      <img :src="asset('assets/img/icons/select-item-to-edit.svg')" alt="" />
      <span>Select an item to edit</span>
    </div>
  </div>
</template>

<style scoped lang="scss"></style>
