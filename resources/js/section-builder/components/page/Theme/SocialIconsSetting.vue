<template>
  <div class="social-icons-setting-root">
    <div class="card mt-2">
      <div class="d-flex align-items-center justify-content-between py-2 cursor-pointer px-3" @click.prevent="toggleExpand">
        Social Icons Change
        <bz-arrow-up-icon v-if="expand" />
        <bz-arrow-down-icon v-else />
      </div>
      <div v-if="expand">
        <div class="btn-group w-100 font-types" role="group">
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: iconType === iconTypes.header }" @click.prevent="iconType = iconTypes.header">Header</button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: iconType === iconTypes.main }" @click.prevent="iconType = iconTypes.main">Main</button>
          <button type="button" class="btn btn-light p-2 w-100" :class="{ active: iconType === iconTypes.footer }" @click.prevent="iconType = iconTypes.footer">Footer</button>
        </div>

        <div class="p-3">
          <div class="d-flex align-items-center justify-content-around">
            <label class="tw-flex tw-gap-x-2 tw-items-center">
              <input v-model="socialIconData[iconType].individual" type="radio" value="false" class="mb-2" />
              Group
            </label>
            <label class="tw-flex tw-gap-x-2 tw-items-center">
              <input v-model="socialIconData[iconType].individual" type="radio" value="true" class="mb-2" />
              Individual
            </label>
          </div>

          <div v-if="socialIconData[iconType].individual" class="mt-3">
            <label>Select Social Icon(s)</label>
            <div class="row social-icons mt-2">
              <div v-if="templateSetting.socialAccounts.bizinabox.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('bizinabox'), active: selectedIcons.bizinabox }" @click="handleIconItemClick('bizinabox')">
                  Bizinabox
                  <div v-if="selectedIcons.bizinabox" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.facebook.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('facebook'), active: selectedIcons.facebook }" @click="handleIconItemClick('facebook')">
                  Facebook
                  <div v-if="selectedIcons.facebook" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.instagram.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('instagram'), active: selectedIcons.instagram }" @click="handleIconItemClick('instagram')">
                  Instagram
                  <div v-if="selectedIcons.instagram" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.linkedin.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('linkedin'), active: selectedIcons.linkedin }" @click="handleIconItemClick('linkedin')">
                  Linkedin
                  <div v-if="selectedIcons.linkedin" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.pinterest.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('pinterest'), active: selectedIcons.pinterest }" @click="handleIconItemClick('pinterest')">
                  Pinterest
                  <div v-if="selectedIcons.pinterest" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.reddit.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('reddit'), active: selectedIcons.reddit }" @click="handleIconItemClick('reddit')">
                  Reddit
                  <div v-if="selectedIcons.reddit" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.tiktok.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('tiktok'), active: selectedIcons.tiktok }" @click="handleIconItemClick('tiktok')">
                  TikTok
                  <div v-if="selectedIcons.tiktok" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.twitter.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('twitter'), active: selectedIcons.twitter }" @click="handleIconItemClick('twitter')">
                  Twitter
                  <div v-if="selectedIcons.twitter" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
              <div v-if="templateSetting.socialAccounts.youtube.show" class="col-4">
                <div class="icon-item" :class="{ selected: selectedIcons.hasOwnProperty('youtube'), active: selectedIcons.youtube }" @click="handleIconItemClick('youtube')">
                  Youtube
                  <div v-if="selectedIcons.youtube" class="completed">
                    <check-circle-rounded-icon :size="18" fill-color="green" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <template v-if="showIconsSettingPanel">
            <div class="d-flex align-items-center justify-end mt-4 mb-2">
              <bz-switch v-model="iconsSetting.noOutline" />
              <span class="pl-2 whitespace-no-wrap" style="white-space: nowrap">No Outline</span>
            </div>

            <div v-if="!iconsSetting.noOutline" class="mt-2">
              <label>Outline Corners</label>
              <div class="slider-element slider-blue mt-1">
                <div class="value">{{ iconsSetting.outlineCorner }}</div>
                <slider v-model="iconsSetting.outlineCorner" :tooltips="false" :min="0" :max="100" :step="1" />
              </div>
            </div>

            <div class="mt-2">
              <label>Icon Size</label>
              <div class="slider-element slider-blue mt-1">
                <div class="value">{{ iconsSetting.iconSize }}</div>
                <slider v-model="iconsSetting.iconSize" :tooltips="false" :min="12" :max="100" :step="1" />
              </div>
            </div>

            <div class="mt-3 color-element">
              <div>
                <label class="mb-1">Icon Color</label>
                <div class="d-flex align-items-center justify-content-center mt-1">
                  <div class="color" :style="{ background: iconsSetting.iconColor || '#ffffff' }" @click="openPicker('openColorPicker')">
                    <span class="text-invert">{{ (iconsSetting.iconColor || '#ffffff').toUpperCase() }}</span>
                  </div>
                  <button
                    :style="{ visibility: iconsSetting.iconColor !== iconsSettingBackup?.iconColor && !openColorPicker ? 'visible' : 'hidden' }"
                    class="text-danger p-2 ml-2"
                    @click="iconsSetting.iconColor = iconsSettingBackup.iconColor"
                  >
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div v-if="openColorPicker">
                <Sketch :value="iconsSetting.iconColor || '#ffffff'" class="picker" @update:modelValue="updateIconColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="openColorPicker = false">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeIconColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>

            <div v-if="!iconsSetting.noOutline" class="mt-3 color-element">
              <div>
                <label class="mb-1">Outline Color</label>
                <div class="ml-3 my-1 d-flex align-items-center">
                  <input v-model="iconsSetting.outlineOnly" type="checkbox" />
                  <label class="pl-2">Outline Only</label>
                </div>
                <div class="d-flex align-items-center justify-content-center mt-1">
                  <div class="color" :style="{ background: iconsSetting.outlineColor || '#ffffff' }" @click="openPicker('openOutlineColorPicker')">
                    <span class="text-invert">{{ (iconsSetting.outlineColor || '#ffffff').toUpperCase() }}</span>
                  </div>
                  <button
                    :style="{ visibility: iconsSetting.outlineColor !== iconsSettingBackup.outlineColor && !openOutlineColorPicker ? 'visible' : 'hidden' }"
                    class="text-danger p-2 ml-2"
                    @click="iconsSetting.outlineColor = iconsSettingBackup.outlineColor"
                  >
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div v-if="openOutlineColorPicker">
                <Sketch :value="iconsSetting.outlineColor || '#ffffff'" class="picker" :disable-alpha="true" @update:modelValue="updateOutlineColor" />
                <div class="d-flex justify-content-center">
                  <button class="btn btn-primary mr-2 btn-sm" @click="openOutlineColorPicker = false">Save Color</button>
                  <button class="btn btn-danger btn-sm" @click="closeOutlineColorPicker">Cancel Changes</button>
                </div>
              </div>
            </div>

            <div class="mt-2">
              <label>Hover Opacity</label>
              <div class="slider-element slider-blue mt-1">
                <div class="value">{{ iconsSetting.hoverOpacity }}</div>
                <slider v-model="iconsSetting.hoverOpacity" :tooltips="false" :min="0" :max="100" :step="1" />
              </div>
            </div>

            <div v-if="socialIconData[iconType].individual && showButtons" class="mt-2 d-flex justify-content-center">
              <button class="btn bz-btn-default" @click="saveIconSetting">Save</button>
              <button class="btn bz-btn-danger ml-4" @click="discardIconSetting">Discard</button>
            </div>
          </template>
          <div v-else class="text-gray text-center py-4">Select Icons</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BzArrowDownIcon from '../../icons/ArrowDown.vue'
import BzArrowUpIcon from '../../icons/ArrowUp.vue'
import { cloneDeep, merge } from 'lodash'
import BzSwitch from '../BzSwitch.vue'
import { Sketch } from '@lk77/vue3-color'
import CheckCircleRoundedIcon from '../../icons/CheckCircleRoundedIcon.vue'
import builderMixin from '../../../mixins/builderMixin'
import Slider from '@vueform/slider'

export default {
  name: 'SocialIconsSetting',
  components: { CheckCircleRoundedIcon, BzSwitch, BzArrowUpIcon, BzArrowDownIcon, Sketch, Slider },
  mixins: [builderMixin],
  props: {
    editTheme: {
      type: [Object, undefined],
      default: undefined
    }
  },
  data() {
    return {
      expand: false,
      selectedIcons: {},
      iconsSetting: {
        noOutline: false,
        outlineCorner: 4,
        iconSize: 24,
        iconColor: null,
        outlineColor: '#ffffff',
        outlineOnly: false,
        hoverOpacity: 100
      },
      iconsSettingBackup: null,
      socialIconData: {
        header: {
          individual: false,
          group: {
            noOutline: true,
            outlineCorner: 4,
            iconSize: 24,
            iconColor: null,
            outlineColor: '#ffffff',
            outlineOnly: false,
            hoverOpacity: 100
          }
        },
        main: {
          individual: false,
          group: {
            noOutline: true,
            outlineCorner: 4,
            iconSize: 24,
            iconColor: null,
            outlineColor: '#ffffff',
            outlineOnly: false,
            hoverOpacity: 100
          }
        },
        footer: {
          individual: false,
          group: {
            noOutline: true,
            outlineCorner: 4,
            iconColor: null,
            iconSize: 24,
            outlineColor: '#ffffff',
            outlineOnly: false,
            hoverOpacity: 100
          }
        }
      },
      initialSocialIconData: null,
      iconType: 'main',
      iconTypes: {
        header: 'header',
        main: 'main',
        footer: 'footer'
      },
      isGroup: true,
      openOutlineColorPicker: false,
      openColorPicker: false,
      showButtons: false,
      isSettingChanged: false
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
    },
    isIndividual() {
      return this.socialIconData[this.iconType].individual
    },
    completedIcons() {
      return Object.keys(this.selectedIcons).filter((key) => this.selectedIcons[key])
    },
    unCompletedIcons() {
      return Object.keys(this.selectedIcons).filter((key) => !this.selectedIcons[key])
    },
    showIconsSettingPanel() {
      return !this.socialIconData[this.iconType].individual || (this.socialIconData[this.iconType].individual && Object.keys(this.unCompletedIcons).length > 0)
    }
  },
  watch: {
    iconType: {
      immediate: true,
      handler(value) {
        this.isSettingChanged = false

        let _iconSetting = null
        if (this.socialIconData[value].individual) {
          _iconSetting = merge(this.iconsSetting, this.socialIconData[value].group)
        } else {
          _iconSetting = this.socialIconData[value].group
        }
        if (!_iconSetting.iconColor || !this.editTheme) {
          _iconSetting.iconColor = this.mainPalette.socialIconColor
        }
        // remove outline if outline is not defined
        if (!_iconSetting.outlineColor) {
          _iconSetting.noOutline = true
        }
        // default social icon outline color.
        if (!_iconSetting.outlineColor || !this.editTheme) {
          _iconSetting.outlineColor = '#ffffff'
        }

        this.selectedIcons = {}
        this.iconsSetting = cloneDeep(_iconSetting)
      }
    },
    selectedIcons() {
      if (this.isSettingChanged) {
        this.updateIndividualIconSettings()
      }
    },
    iconsSetting: {
      deep: true,
      handler() {
        if (this.isIndividual) {
          this.showButtons = true
        }
        if (this.isSettingChanged) {
          this.updateIndividualIconSettings()
        }
        this.isSettingChanged = true
      }
    },
    isIndividual(value) {
      this.isSettingChanged = false
      this.selectedIcons = {}
      const _iconSetting = cloneDeep(this.socialIconData[this.iconType].group)
      if (!_iconSetting.iconColor) {
        _iconSetting.iconColor = this.mainPalette.socialIconColor
      }
      // default social icon outline color.
      if (!_iconSetting.outlineColor) {
        _iconSetting.noOutline = true
      }
      this.iconsSetting = cloneDeep(_iconSetting)
      this.backupSocialIconData()
    },
    socialIconData: {
      deep: true,
      handler(v) {
        // No need to update with collapsed mode
        if (this.expand && this.isSettingChanged) {
          this.theme = { ...this.theme, socialIcon: v }
        }
      }
    },
    editTheme: {
      deep: true,
      handler() {
        if (this.expand) {
          this.updateSocialIconData()
        }
      }
    }
  },
  methods: {
    openPicker(pickerName) {
      this.backupSocialIconData()
      this[pickerName] = true
    },
    backupSocialIconData() {
      this.initialSocialIconData = cloneDeep(this.socialIconData)
      this.iconsSettingBackup = cloneDeep(this.iconsSetting)
    },
    toggleExpand() {
      if (this.expand) {
        this.expand = false
      } else {
        this.updateSocialIconData()
        this.$emit('expand')
        this.expand = true
      }
    },
    updateSocialIconData() {
      let _socialIconData = null
      if (this.editTheme) {
        _socialIconData = merge(this.socialIconData, this.editTheme.data.socialIcon ?? {})
      } else {
        _socialIconData = merge(this.socialIconData, this.theme.socialIcon ?? {})
      }
      // remove icon attributes from the setting for group setting
      for (const iconType of Object.keys(this.iconTypes)) {
        if (!_socialIconData[iconType].individual) {
          _socialIconData[iconType] = {
            individual: false,
            group: _socialIconData[iconType].group
          }
        }
      }
      this.socialIconData = cloneDeep(_socialIconData)
      this.backupSocialIconData()
    },
    updateIndividualIconSettings() {
      if (!this.isIndividual) {
        this.socialIconData[this.iconType].group = cloneDeep(this.iconsSetting)
      } else {
        for (const iconName of this.unCompletedIcons) {
          this.socialIconData[this.iconType][iconName] = cloneDeep(this.iconsSetting)
        }
        this.socialIconData = cloneDeep(this.socialIconData)
      }
    },
    expandPanel() {
      this.expand = true
    },
    closePanel() {
      this.expand = false
    },
    cancelChanges() {
      this.socialIconData = cloneDeep(this.initialSocialIconData)
      this.expand = false
    },
    updateIconColor(color) {
      this.iconsSetting.iconColor = color.hex8
    },
    closeIconColorPicker() {
      this.openColorPicker = false
      this.iconsSetting.iconColor = this.initialSocialIconData.iconColor
    },
    updateOutlineColor(color) {
      this.iconsSetting.outlineColor = color.hex
    },
    closeOutlineColorPicker() {
      this.openOutlineColorPicker = false
      this.iconsSetting.outlineColor = this.initialSocialIconData.outlineColor
    },
    saveIconSetting() {
      for (const key in this.selectedIcons) {
        if (this.selectedIcons[key] === false) {
          this.selectedIcons[key] = true
        }
      }
      this.showButtons = false
      this.iconsSetting = cloneDeep(this.socialIconData[this.iconType].group)
      this.backupSocialIconData()
    },
    discardIconSetting() {
      this.socialIconData = cloneDeep(this.initialSocialIconData)
      this.iconsSetting = cloneDeep(this.iconsSettingBackup)
      this.showButtons = false
    },
    handleIconItemClick(iconName) {
      let isSelect = true
      if (this.unCompletedIcons.includes(iconName)) {
        if (this.selectedIcons[iconName] === false) {
          delete this.selectedIcons[iconName]
          isSelect = false
        } else if (this.selectedIcons[iconName] === true) {
          this.selectedIcons[iconName] = false
        }
      } else {
        this.selectedIcons[iconName] = false
      }
      this.selectedIcons = cloneDeep(this.selectedIcons)

      if (isSelect && this.unCompletedIcons.length === 1) {
        if (this.socialIconData[this.iconType][iconName]) {
          this.iconsSetting = cloneDeep(this.socialIconData[this.iconType][iconName])
        } else {
          this.iconsSetting = cloneDeep(this.socialIconData[this.iconType].group)
        }
      }

      if (!isSelect) {
        if (this.initialSocialIconData[this.iconType][iconName]) {
          this.socialIconData[this.iconType][iconName] = cloneDeep(this.initialSocialIconData[this.iconType][iconName])
        } else {
          delete this.socialIconData[this.iconType][iconName]
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
.social-icons-setting-root {
  .card {
    box-shadow: 0 0 2px 4px rgb(0 0 0 / 7%);
    background-color: white;
    border: solid 1px #8080803f;
    border-radius: 4px;
    padding: 5px;

    .font-types {
      .active {
        background-color: #0076df;
        color: white;
      }
    }
  }
  .icon-item {
    border: solid 1px #00000055;
    padding: 4px;
    cursor: pointer;
    text-align: center;
    position: relative;

    &.selected {
      border: solid 1px #ff0000;
      outline: solid 1px #ff0000;
    }

    &.active {
      border: solid 1px green !important;
      outline: none !important;
    }

    div.completed {
      position: absolute;
      bottom: -9px;
      right: -9px;
      background-color: white;
    }
  }
}
</style>
