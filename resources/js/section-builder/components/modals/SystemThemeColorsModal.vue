<template>
  <div>
    <preset-theme-name-modal v-if="openThemeNameModal" @close="openThemeNameModal = false" @confirm="onSave" />
    <div class="w-100 h-100">
      <div class="col-12 h-100">
        <p>Build your color template by selecting one from the following colors?</p>
        <!-- <theme-panel v-model="theme" :disable-randomize="true" @save="$emit('save')" /> -->
        <hr />
        <system-theme-colors v-model="theme" />
      </div>
    </div>
  </div>
</template>

<script>
import PresetThemeNameModal from './PresetThemeNameModal'
import SystemThemeColors from '../page/SystemThemeColors'
import { cloneDeep } from 'lodash'
import builderMixin from '../../mixins/builderMixin'
export default {
  name: 'SystemThemeColorsModal',
  components: { SystemThemeColors, PresetThemeNameModal },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      openThemeNameModal: false
    }
  },
  computed: {
    theme: {
      get() {
        return this.$store.state.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    }
  },
  created() {
    // this.themePreview = true
    this.theme = cloneDeep(this.theme)
  },
  mounted() {
    this.refreshTheme()
    this.$modal.show('system-theme-colors-modal')
  },
  methods: {
    onSave(themeName) {
      this.$emit('save', { ...this.theme, name: themeName, themeId: this.$uuid.v4() })
      this.$emit('close')
    },
    onClose() {
      this.syncTheme()
      this.$emit('close')
    }
  }
}
</script>

<style lang="scss">
.system-theme-colors-modal {
  display: flex;
  flex-direction: column;

  &.vm--modal {
    width: calc(100% - 6px) !important;
  }

  .md-tab {
    max-height: 250px;
    overflow-y: auto;
  }
}
</style>
