<template>
  <div>
    <bz-select v-model="activeCategoryIndex" :options="paletteCategories" />
    <div class="color-panel">
      <template v-if="colors.length">
        <template v-for="(color, index) of colors" :key="index">
          <div>
            <div>{{ color.name }}</div>
            <div class="color-group" :class="{ active: isActiveColor(color.palette) }" @click="setThemeColors(color.palette, color.name)">
              <div class="color-item" :style="{ backgroundColor: color.palette.backgroundColor }" />
              <div class="color-item" :style="{ backgroundColor: color.palette.buttonColor }" />
              <div class="color-item" :style="{ backgroundColor: color.palette.socialIconColor }" />
              <div class="color-item" :style="{ backgroundColor: color.palette.headingColor }" />
              <div class="color-item" :style="{ backgroundColor: color.palette.boxColor }" />
              <div class="color-item" :style="{ backgroundColor: color.palette.secondaryColor }" />
            </div>
          </div>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
import BzSelect from './BzSelect'
import builderMixin from '../../mixins/builderMixin'

export default {
  name: 'SystemThemeColors',
  components: { BzSelect },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    user: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      activeCategoryIndex: 0,
      colors: []
    }
  },
  computed: {
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
    theme: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    }
  },
  watch: {
    activeCategoryIndex: {
      immediate: true,
      handler() {
        this.getSystemColors()
      }
    },
    themeMode: {
      immediate: true,
      handler() {
        this.getSystemColors()
      }
    }
  },
  created() {
    this.themePreview = false
  },
  methods: {
    getSystemColors() {
      if (this.user) {
        if (this.userPalettes.advanced.length > 0) {
          return (this.colors = this.userPalettes.advanced[this.activeCategoryIndex]?.palettes?.[this.themeMode] || [])
        }
      } else {
        if (this.systemPalettes.advanced.length > 0) {
          return (this.colors = this.systemPalettes.advanced[this.activeCategoryIndex].palettes[this.themeMode] || [])
        }
      }
      this.colors = []
    },
    /**
     * Sets Theme colors from the preset color list when one of them is clicked.
     * @param color
     */
    setThemeColors(color, name) {
      this.theme[this.themeMode] = {
        ...this.theme[this.themeMode],
        name,
        ...color
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.color-panel {
  max-height: 400px;
  overflow-y: auto;
  padding-top: 20px;
  padding-bottom: 20px;
}
</style>
