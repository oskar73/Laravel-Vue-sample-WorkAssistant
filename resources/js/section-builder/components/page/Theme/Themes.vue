<template>
  <div>
    <div class="py-4">
      <bz-select v-model="selectedCategory" :options="categories" :auto-select="true">
        <template #selected="{ selected, placeholder }">
          <span>{{ selected?.name || placeholder }}</span>
        </template>
        <template #option="{ option }">
          <span>{{ option.name }}</span>
        </template>
      </bz-select>
      <hr />
      <template v-for="themeItem in categoryThemes" :key="themeItem.id">
        <theme-item
          :theme-item="themeItem"
          :active="themeItem.id === selectedTheme?.id"
          @select="$emit('select', $event)"
          @input="$emit('update:modelValue', $event)"
          @update:modelValue="$emit('edit', $event)"
          :showControl="showControl"
        />
      </template>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../../mixins/builderMixin'
import ThemeItem from './ThemeItem.vue'
import _ from 'lodash'
import BzSelect from '@/public/BzSelect.vue'

export default {
  name: 'Themes',
  components: { ThemeItem, BzSelect },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: [Object, undefined],
      default: undefined
    },
    type: {
      type: String,
      default: 'system'
    },
    showControl: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      list: [],
      selectedCategory: null
    }
  },
  computed: {
    selectedTheme() {
      return this.modelValue
    },
    categories() {
      if (this.type === 'user') {
        return this.$store.state.themeCategories.filter(({ user_id }) => user_id)
      } else {
        return this.$store.state.themeCategories.filter(({ user_id }) => !user_id)
      }
    },
    categoryThemes() {
      if (this.type === 'user') {
        return this.$store.state.themes.filter(({ user_id, category_id }) => user_id && category_id === this.selectedCategory?.id)
      } else {
        return this.$store.state.themes.filter(({ user_id, category_id }) => !user_id && category_id === this.selectedCategory?.id)
      }
    }
  },
  methods: {
    /**
     * @deprecated
     * @param theme
     */
    previewTheme(theme) {
      this.$emit('update:modelValue', theme)

      this.clearPageThemes()
      this.clearSectionThemes()
      const colors = theme.data.colors
      for (const color of colors) {
        if (color.appliedTo === 'website') {
          this.template.data.theme.colors = color.colors
        } else if (color.appliedTo === 'page') {
          for (const pageIndex of color.applies) {
            const page = this.allPages[pageIndex]
            if (page) {
              _.set(page, 'data.theme.colors', color.colors)
            }
          }
        } else if (color.appliedTo === 'section') {
          for (const pageIndex in color.applies) {
            const page = this.allPages[pageIndex]
            if (page && color.applies.hasOwnProperty(pageIndex)) {
              for (const sectionIndex of color.applies[pageIndex]) {
                const section = page.sections[sectionIndex]
                if (section) {
                  _.set(section, 'data.theme.colors', color.colors)
                }
              }
            }
          }
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped></style>
