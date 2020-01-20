<template>
  <div class="new-theme-area mt-3">
    <bz-input v-model="themeName" :disabled="!createMode" label="Theme Name" :required="true" />

    <div v-if="createMode" class="mt-3">
      <bz-select v-if="step >= steps.category" v-model="themeCategory" label="Theme Category" :options="categories" :auto-select="true">
        <template #selected="{ selected, placeholder }">
          <span>{{ selected?.name || placeholder }}</span>
        </template>
        <template #option="{ option }">
          <span>{{ option.name }}</span>
        </template>
      </bz-select>
    </div>

    <color-setting v-if="step >= steps.color" ref="colorSettingRef" :edit-theme="editTheme" @expand="closeAllPanel" />
    <font-setting v-if="step >= steps.font && showFonts" ref="fontSettingRef" :edit-theme="editTheme" @expand="closeAllPanel" />
    <link-setting v-if="step >= steps.link && showLink" ref="linkSettingRef" :edit-theme="editTheme" @expand="closeAllPanel" />
    <button-setting v-if="step >= steps.button && showButton" ref="buttonSettingRef" :edit-theme="editTheme" @expand="closeAllPanel" />
    <social-icons-setting v-if="step >= steps.socialIcon && showSocialIcon" ref="socialIconSettingRef" :edit-theme="editTheme" @expand="closeAllPanel" />

    <div class="w-100 d-flex align-items-center justify-content-end mt-2 mb-2">
      <div v-if="showSkipButton" class="mr-2 d-flex align-items-center">
        <b class="text-danger btn-skip" @click="skip">Skip</b>
      </div>
      <button v-if="step === steps.font && !showFonts" class="btn btn-success mr-2" @click="handleChangeFontsClick">Change Fonts</button>
      <button v-if="step === steps.link && !showLink" class="btn btn-success mr-2" @click="handleChangeLinksClick">Change Links</button>
      <button v-if="step === steps.button && !showButton" class="btn btn-success mr-2" @click="handleChangeButtonClick">Change Buttons</button>
      <button v-if="step === steps.socialIcon && !showSocialIcon" class="btn btn-success mr-2" @click="handleChangeSocialIconClick">Change Social Icons</button>
      <div v-if="showCancelChangeButton" class="mr-2 d-flex align-items-center">
        <b class="btn-cancel" @click="handleCancelChange">Cancel Changes</b>
      </div>
      <button v-if="showNextAndSaveButton" class="btn btn-primary" @click="saveAndNext">
        <span v-if="step === steps.final">Save Theme & Exit</span>
        <span v-else>Save & Next</span>
      </button>
      <button v-if="Boolean(editTheme) && step === steps.final && !createMode" class="btn btn-primary" @click="$emit('update')">Update & Apply</button>
    </div>
  </div>
</template>

<script>
import BzInput from '../BzInput.vue'
import BzSelect from '@/public/BzSelect.vue'
import builderMixin from '../../../mixins/builderMixin'
import { cloneDeep } from 'lodash'
import FontSetting from './FontSetting.vue'
import LinkSetting from './LinkSetting.vue'
import ButtonSetting from './ButtonSetting.vue'
import SocialIconsSetting from './SocialIconsSetting.vue'
import ColorSetting from './ColorSetting.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  name: 'CreateNewTheme',
  components: {
    ColorSetting,
    SocialIconsSetting,
    ButtonSetting,
    LinkSetting,
    FontSetting,
    BzSelect,
    BzInput
  },
  mixins: [builderMixin],
  props: {
    modelValue: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      steps: {
        name: 0,
        category: 1,
        color: 2,
        font: 3,
        link: 4,
        button: 5,
        socialIcon: 6,
        final: 7
      },
      createMode: false,
      themeName: '',
      themeId: null,
      newTheme: null,
      themeCategory: null,
      step: 0,
      showPalettes: null,
      showFonts: false,
      showLink: false,
      showButton: false,
      showSocialIcon: false,

      // variables to check theme item changes.
      fontHasChanges: false,
      linkHasChanges: false,
      buttonHasChanges: false,
      socialIconHasChanges: false
    }
  },
  computed: {
    theme: {
      get() {
        return this.$store.state.theme || this.template.data.theme
      },
      set(value) {
        this.$store.commit('updateTheme', value)
      }
    },
    editTheme: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      }
    },
    categories() {
      if (this.isWebsite) {
        return this.$store.state.themeCategories.filter(({ user_id }) => user_id)
      } else {
        return this.$store.state.themeCategories.filter(({ user_id }) => !user_id)
      }
    },
    showSkipButton() {
      return (
        (this.step === this.steps.font && (!this.showFonts || !this.fontHasChanges)) ||
        (this.step === this.steps.link && (!this.showLink || !this.linkHasChanges)) ||
        (this.step === this.steps.button && (!this.showButton || !this.buttonHasChanges)) ||
        (this.step === this.steps.socialIcon && (!this.showSocialIcon || !this.socialIconHasChanges))
      )
    },
    showCancelChangeButton() {
      return (
        (this.step === this.steps.font && this.showFonts && this.fontHasChanges) ||
        (this.step === this.steps.link && this.showLink && this.linkHasChanges) ||
        (this.step === this.steps.button && this.showButton && this.buttonHasChanges) ||
        (this.step === this.steps.socialIcon && this.showSocialIcon && this.socialIconHasChanges) ||
        (this.editTheme && this.step === this.steps.final)
      )
    },
    showNextAndSaveButton() {
      const _palettes = this.theme?.palettes || []
      return (
        (!this.editTheme || this.step <= this.steps.final) &&
        ((this.step === this.steps.category && this.themeCategory) ||
          (this.step === this.steps.color && _palettes?.length) ||
          (this.step === this.steps.name && this.themeName) ||
          (this.step === this.steps.font && this.showFonts) ||
          (this.step === this.steps.link && this.showLink) ||
          (this.step === this.steps.button && this.showButton) ||
          (this.step === this.steps.socialIcon && this.showSocialIcon) ||
          (this.step === this.steps.final && this.createMode))
      )
    }
  },
  watch: {
    'theme.font': {
      handler() {
        this.fontHasChanges = true
      }
    },
    'theme.link': {
      handler() {
        this.linkHasChanges = true
      }
    },
    'theme.button': {
      handler() {
        this.buttonHasChanges = true
      }
    },
    'theme.socialIcon': {
      handler() {
        this.socialIconHasChanges = true
      }
    },
    editTheme: {
      immediate: true,
      deep: true,
      handler(value) {
        if (value) {
          this.themeName = value.name
          this.themeId = value.id
          this.themeCategory = this.categories.find((item) => item.id === value.category_id)
          this.theme = cloneDeep({
            ...value.data,
            id: value.id,
            category_id: value.category_id,
            name: value.name
          })
          this.step = value.data.step ?? this.steps.final
          this.showLink = this.step > this.steps.link
          this.showFonts = this.step > this.steps.font
          this.showButton = this.step > this.steps.button
          this.showSocialIcon = this.step > this.steps.socialIcon
        } else {
          this.createMode = true
          this.themeName = ''
          this.themeCategory = null
          this.step = 0
          this.showPalettes = null
          this.showFonts = false
          this.showLink = false
          this.showButton = false
          this.showSocialIcon = false

          // variables to check theme item changes.
          this.fontHasChanges = false
          this.linkHasChanges = false
          this.buttonHasChanges = false
          this.socialIconHasChanges = false
        }
      }
    }
  },
  created() {
    // this.themePreview = true
    if (this.editTheme) {
      if ((this.editTheme.data.step ?? this.steps.final) < this.steps.final) {
        this.createMode = true
        this.step = this.editTheme.data.step
      } else {
        this.createMode = false
      }
    } else {
      this.createMode = true
      this.newTheme = cloneDeep(this.theme)
    }
  },
  mounted() {
    this.appliedTo = 'website'
    this.paletteAppliedPages = []
    this.paletteAppliedSections = {}
  },
  destroyed() {
    this.appliedTo = 'website'
  },
  methods: {
    getNewTheme() {
      if (!this.themeName || !this.themeCategory) {
        return null
      }
      return {
        id: this.themeId,
        name: this.themeName,
        category_id: this.themeCategory.id,
        data: {
          ...this.theme,
          category_id: this.themeCategory.id,
          name: this.themeName,
          palettes: this.theme.newTheme ? this.theme.palettes : [],
          step: this.step
        }
      }
    },
    saveAndNext() {
      if (this.step === this.steps.name && this.themeName) {
        if (this.themes.some((_t) => _t.name === this.themeName)) {
          return toast.error('Theme name is taken, please choose another name')
        } else {
          this.step = this.steps.category
        }
      } else if (this.step === this.steps.category && this.themeCategory) {
        this.step = this.steps.color
      } else if (this.step === this.steps.color) {
        if (this.editTheme && (this.theme?.palettes || []).length === 0) {
          return toast.error('Please select at least one palette.')
        } else if (!this.theme.newTheme) {
          return toast.error('Please select at least one palette.')
        } else {
          this.step = this.steps.font
        }
      } else if (this.step === this.steps.font) {
        this.step = this.steps.link
      } else if (this.step === this.steps.link) {
        this.step = this.steps.button
      } else if (this.step === this.steps.button) {
        this.step = this.steps.socialIcon
      } else if (this.step === this.steps.socialIcon) {
        this.step = this.steps.final
      } else {
        const newTheme = this.getNewTheme()
        this.$emit('create', newTheme)
      }
      this.closeAllPanel()
    },
    skip() {
      if (this.editTheme && this.step === this.steps.final) {
        return false
      }
      if (this.step === this.steps.font) {
        this.showFonts = true
        this.step = this.steps.link
      } else if (this.step === this.steps.link) {
        this.showLink = true
        this.step = this.steps.button
      } else if (this.step === this.steps.button) {
        this.showButton = true
        this.step = this.steps.socialIcon
      } else if (this.step === this.steps.socialIcon) {
        this.showSocialIcon = true
        this.step = this.steps.final
      }
    },
    handleCancelChange() {
      if (this.step === this.steps.font && this.$refs.fontSettingRef) {
        this.$refs.fontSettingRef.cancelChanges()
        this.$nextTick(() => {
          this.fontHasChanges = false
        })
        this.step = this.steps.link
      } else if (this.step === this.steps.link && this.$refs.linkSettingRef) {
        this.$refs.linkSettingRef.cancelChanges()
        this.$nextTick(() => {
          this.linkHasChanges = false
        })
        this.step = this.steps.button
      } else if (this.step === this.steps.button && this.$refs.buttonSettingRef) {
        this.$refs.buttonSettingRef.cancelChanges()
        this.$nextTick(() => {
          this.buttonHasChanges = false
        })
        this.step = this.steps.socialIcon
      } else if (this.step === this.steps.socialIcon && this.$refs.socialIconSettingRef) {
        this.$refs.socialIconSettingRef.cancelChanges()
        this.$nextTick(() => {
          this.socialIconHasChanges = false
        })
        this.step = this.steps.final
      } else {
        this.$emit('cancel')
      }
    },
    closeAllPanel() {
      if (this.$refs.colorSettingRef) {
        this.$refs.colorSettingRef.closePanel()
      }
      if (this.$refs.fontSettingRef) {
        this.$refs.fontSettingRef.closePanel()
      }
      if (this.$refs.linkSettingRef) {
        this.$refs.linkSettingRef.closePanel()
      }
      if (this.$refs.buttonSettingRef) {
        this.$refs.buttonSettingRef.closePanel()
      }
      if (this.$refs.socialIconSettingRef) {
        this.$refs.socialIconSettingRef.closePanel()
      }
    },
    handleChangeFontsClick() {
      this.closeAllPanel()
      this.showFonts = true
      this.$nextTick(() => {
        if (this.$refs.fontSettingRef) {
          this.$refs.fontSettingRef.expandPanel()
        }
      })
    },
    handleChangeLinksClick() {
      this.closeAllPanel()
      this.showLink = true
      this.closeAllPanel()
      this.$nextTick(() => {
        if (this.$refs.linkSettingRef) {
          this.$refs.linkSettingRef.expandPanel()
        }
      })
    },
    handleChangeButtonClick() {
      this.closeAllPanel()
      this.showButton = true
      this.closeAllPanel()
      this.$nextTick(() => {
        if (this.$refs.buttonSettingRef) {
          this.$refs.buttonSettingRef.expandPanel()
        }
      })
    },
    handleChangeSocialIconClick() {
      this.closeAllPanel()
      this.showSocialIcon = true
      this.closeAllPanel()
      this.$nextTick(() => {
        if (this.$refs.socialIconSettingRef) {
          this.$refs.socialIconSettingRef.expandPanel()
        }
      })
    }
  }
}
</script>

<style lang="scss">
.new-theme-area {
  padding-bottom: 100px;

  .card {
    box-shadow: 0 0 2px 4px rgb(0 0 0 / 7%);
    background-color: white;
    border: solid 1px #8080803f;
    border-radius: 4px;
    padding: 4px;
  }

  .apply-to {
    margin: 4px 0;
  }

  .btn-delete {
    text-decoration: underline;
    cursor: pointer;
    color: var(--bz-primary-color);
    margin-left: 8px;
  }

  .btn-skip,
  .btn-cancel {
    color: #e3342f;
    border-bottom: solid 1px #e3342f;
    height: max-content;
    cursor: pointer;
    width: max-content;

    &:hover {
      border-color: red;
      color: red;
    }
  }
}
</style>
