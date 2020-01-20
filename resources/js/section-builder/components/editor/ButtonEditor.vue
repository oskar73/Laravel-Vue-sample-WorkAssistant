<template>
  <div v-click-outside="handleOutsideClick" :style="{ left: left + 'px', top: top + 'px' }" class="bz-button-editor-root" @mousedown.prevent.stop="">
    <template v-if="link">
      <div class="item-wrapper">
        <div class="item link" @click="openLink">
          <i class="mdi mdi-link"></i>
        </div>
      </div>
      <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />
    </template>

    <div class="item-wrapper" @click.prevent="size = 's'">
      <div class="item size small" :class="{ selected: size === 's' }">S</div>
    </div>

    <div class="item-wrapper" @click.prevent="size = 'm'">
      <div class="item size medium" :class="{ selected: size === 'm' }">M</div>
    </div>

    <div class="item-wrapper" @click.prevent="size = 'l'">
      <div class="item size large" :class="{ selected: size === 'l' }">L</div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <div class="item-wrapper" @click.prevent="outline = false">
      <div class="item" :class="{ selected: !outline }">
        <div class="fill" />
      </div>
    </div>

    <div class="item-wrapper" @click.prevent="outline = true">
      <div class="item" :class="{ selected: outline }">
        <div class="outline" />
      </div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <div class="item-wrapper" @click.prevent.stop="handleClick">
      <div class="item color">
        <div class="before" :style="{ backgroundColor: colorItemOpacityBackground }"></div>
        <div class="color" :style="{ backgroundColor: data.backgroundColor || buttonColor }" />
      </div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <div class="item-wrapper">
      <div class="item" @click="handleClick2">
        <bz-format-color-text-icon :size="20" fill-color="#555555" :color="data.textColor || '#ffffff'" />
      </div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <div class="item-wrapper">
      <div class="item" @click="handleClear">
        <i class="mdi mdi-close"></i>
      </div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <div class="item-wrapper" @click="openIconSelector">
      <div class="item icon">
        <dvr-icon :size="16" :fill-color="'#555555'" />
      </div>
    </div>

    <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

    <template v-if="multiple">
      <div class="item-wrapper" @click="handleAddButton">
        <div class="item icon">
          <add-icon :size="16" :fill-color="'#555555'" />
        </div>
      </div>

      <div class="item-wrapper" @click="handleRemoveButton">
        <div class="item icon">
          <i class="mdi mdi-delete"></i>
        </div>
      </div>

      <bz-divider :background-color="'#fffff'" :vertical="true" :height="20" />

      <div class="item-wrapper" :class="{ disabled: start }" @click="moveToLeft">
        <div class="item icon">
          <arrow-back-icon :size="16" :fill-color="start ? '#8080807f' : '#555555'" />
        </div>
      </div>

      <div class="item-wrapper" :class="{ disabled: last }" @click="moveToRight">
        <div class="item icon">
          <arrow-forward-icon :size="16" :fill-color="last ? '#8080807f' : '#555555'" />
        </div>
      </div>
    </template>

    <div v-if="openSketch" class="color-sketch" :class="{'bottom': top < 360}">
      <Sketch :model-value="customColor" @update:modelValue="setCustomColor" />
    </div>

    <div v-if="openColorPanel" class="open-color-panel">
      <Sketch :model-value="data.textColor" @update:modelValue="setTextColor" />
    </div>
  </div>
</template>

<script>
import BzDivider from '../section/BzDivider.vue'
import DvrIcon from '../icons/Dvr.vue'
import AddIcon from '../icons/Add.vue'
import ArrowBackIcon from '../icons/ArrowBack.vue'
import ArrowForwardIcon from '../icons/ArrowForward.vue'
import { cloneDeep } from 'lodash'
import { mapMutations } from 'vuex'
import editorMixin from '../../mixins/editorMixin'
import { Sketch } from '@lk77/vue3-color'
import BzFormatColorTextIcon from '../icons/FormatColorText.vue'

export default {
  name: 'ButtonEditor',
  components: { BzFormatColorTextIcon, Sketch, ArrowForwardIcon, ArrowBackIcon, AddIcon, DvrIcon, BzDivider },
  mixins: [editorMixin],
  props: {
    multiple: {
      type: Boolean,
      default: false
    },
    last: {
      type: Boolean,
      default: false
    },
    start: {
      type: Boolean,
      default: false
    },
    index: {
      type: Number,
      default: 0
    },
    link: {
      type: Boolean,
      default: true
    },
    width: {
      type: String,
      default: ''
    },
    rounded: {
      type: [Boolean, String, Number],
      default: 4
    },
    colors: {
      type: [Object],
      required: true
    },
    button: {
      type: [HTMLElement],
      required: true
    },
    pageData: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      titleText: '',
      content: '',
      customColor: '#ffffff',
      openSketch: false,
      openColorPanel: false,
      data: {
        title: 'Button Title',
        size: 's',
        outline: false,
        textColor: '#ffffff'
      }
    }
  },
  computed: {
    outline: {
      get() {
        return this.data.outline
      },
      set(val) {
        this.data.outline = val
      }
    },
    size: {
      get() {
        return this.data.size
      },
      set(val) {
        this.data.size = val
      }
    },
    colorItemOpacityBackground() {
      const originalColor = window.tinycolor(this.data.backgroundColor || this.buttonColor)
      return originalColor.darken(30).toString()
    },
    buttonBackgroundColor() {
      return this.outline ? 'transparent' : this.data.backgroundColor || this.buttonColor || this.theme.colors.primaryColor
    },
    buttonSize() {
      return this.data.size || 's'
    },
    buttonStyle() {
      return {
        backgroundColor: this.buttonBackgroundColor,
        color: this.getColor(this.buttonBackgroundColor),
        borderStyle: 'solid',
        borderRadius: this.rounded === true ? '1000px' : this.rounded + 'px',
        borderColor: this.data.backgroundColor || this.theme.colors.primaryColor,
        ...(this.width ? { width: this.width } : {})
      }
    }
  },
  mounted() {
    if (this.colors.backgroundColor) {
      this.customColor = this.colors.backgroundColor
    }
    if (this.colors.textColor) {
      this.data.textColor = this.colors.textColor
    }
  },
  methods: {
    handleClick() {
      this.openSketch = !this.openSketch
      this.openColorPanel = false
    },
    handleClick2() {
      this.openColorPanel = !this.openColorPanel
      this.openSketch = false
    },
    handleClear() {
      this.openSketch = false
      this.openColorPanel = false
      this.$emit('clear')
    },
    setCustomColor(color) {
      this.customColor = color.hex8
      this.handleChangeColor(this.customColor)
    },
    setTextColor(color) {
      this.data.textColor = color.hex8
      this.data = cloneDeep(this.data)
    },
    openLink() {
      this.openModal({
        name: 'attachLinkModal',
        data: this.data.link,
        onChange: (link) => {
          this.data = { ...this.data, link }
          this.$store.commit('closeModal')
        }
      })
    },
    editColorItemStyle(color) {
      return {
        backgroundColor: color,
        border: 'solid 1px #8080803f'
      }
    },
    handleChangeColor(color) {
      this.data.backgroundColor = color
      this.data = cloneDeep(this.data)
    },
    closeEditor() {
      this.editing = false
    },
    openIconSelector() {
      this.$el.remove()
      this.$store.commit('openModal', {
        name: 'iconSelector',
        onChange: (icon) => {
          this.data.icon = icon
          this.data = cloneDeep(this.data)
        }
      })
    },
    moveToRight() {
      if (!this.last) {
        this.editing = false
        this.$emit('swap', this.index, this.index + 1)
      }
    },
    moveToLeft() {
      if (!this.first) {
        this.editng = false
        this.$emit('swap', this.index, this.index - 1)
      }
    },
    ...mapMutations(['openModal'])
  }
}
</script>

<style lang="scss" scoped>
.bz-button-editor-root {
  position: fixed;
  height: 30px;
  padding: 3px 5px;
  border-right: 4px;
  background-color: white;
  box-shadow: 0 0 20px 5px #00000012;
  display: flex;
  align-items: center;
  z-index: 10001;

  .item-wrapper {
    width: 24px;
    height: 24px;
    border-radius: 2px;
    padding: 4px;
    cursor: pointer;

    &:hover:not(.disabled) {
      background-color: #8080803f;
    }

    &.disabled {
      cursor: not-allowed;
    }

    .item {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;

      &.selected {
        background-color: #0a6aa13f;
      }

      &.size {
        text-transform: uppercase;
        font-size: 12px;
        color: #555555;
      }

      .outline,
      .fill {
        width: 10px;
        height: 10px;
        margin: 3px;
        padding: 0;
      }

      .fill {
        border-radius: 2px;
        background-color: var(--bz-section-edit-active-color);
      }

      .outline {
        border-radius: 2px;
        background-color: white;
        border: solid 1px #808080;
      }

      &.color {
        position: relative;
        .before {
          content: ' ';
          position: absolute;
          left: 0;
          top: 0;
          background-color: inherit;
          width: 100%;
          height: 100%;
          border-radius: 50%;
          filter: opacity(30%);
          z-index: 1;
        }
      }

      .color {
        width: 12px;
        height: 12px;
        margin: 2px;
        padding: 0;
        border-radius: 6px;
        z-index: 2;
      }
    }
  }
  .color-sketch {
    position: absolute;
    bottom: calc(100% + 4px);
    left: 0;

    &.bottom {
      bottom: unset;
      top: calc(100% + 4px);
    }
  }
  .open-color-panel {
    position: absolute;
    bottom: calc(100% + 4px);
    left: 27px;
  }
}
</style>
