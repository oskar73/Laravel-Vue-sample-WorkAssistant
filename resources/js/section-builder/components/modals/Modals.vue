<template>
  <div>
    <!--    <navigation-builder-modal v-if="openNavigationBuilderModal" />-->
    <manage-markers-modal v-if="openManageMarkers" />
    <embed-link-modal v-if="openEmbedLink" />
    <page-setting-modal v-if="$store.state.modals.openPageSettingModal" :open="true" />
    <image-select-modal :open="openImageSelector" />
    <video-select-modal v-if="openVideoEditor" />
    <attach-link-modal v-if="openAttachLink" :open="openAttachLink" @confirm="$store.state.modals.basic.onChange" />
    <alt-text-modal v-if="openAltText" />
    <icon-selector-modal v-if="openIconSelector" />
    <contact-form-setting-modal v-if="openContactFormSetting" />
    <bz-time-picker v-if="openTimePicker" />
    <promotion-modal v-if="openPromotionModal" />
    <subscribe-modal v-if="openSubscribeModal" />
    <video-modal v-if="openVideoModal" />
    <preset-theme-name-modal v-if="openThemeNameModal" />
    <preset-palette-name-modal :open="openPaletteNameModal" />
    <update-theme-modal v-if="checkOpenModal('updateThemeModal')" />
    <new-page-modal v-if="checkOpenModal('newPageModal')" />
    <new-section-modal v-if="checkOpenModal('newSectionModal')" />
    <new-appointment-modal v-if="checkOpenModal('newAppointmentModal')" />
    <template-switch-modal v-if="checkOpenModal('templateSwitchModal')" />
    <user-template-preview-modal v-if="checkOpenModal('UserTemplatePreviewModal')" :template-name="$store.state.modals.basic.templateName" :template-id="$store.state.modals.basic.templateId" />
    <template-switch-confirm-modal v-if="checkOpenModal('templateSwitchConfirmModal')" :pages="$store.state.modals.basic.pages" @confirm="$store.state.modals.basic.onConfirm" />
    <new-template-modal v-if="checkOpenModal('newTemplateModal')" @confirm="$store.state.modals.basic.onConfirm" />
  </div>
</template>

<script>
import ImageSelectModal from './ImageSelectModal.vue'
import VideoSelectModal from './VideoSelectModal.vue'
import EmbedLinkModal from './EmbedLinkModal.vue'
import AttachLinkModal from './AttachLinkModal.vue'
import AltTextModal from './AltTextModal.vue'
import PageSettingModal from './PageSettingModal.vue'
import IconSelectorModal from './IconSelectorModal.vue'
import ContactFormSettingModal from './ContactFormSettingModal.vue'
import ManageMarkersModal from './ManageMarkersModal.vue'
import PromotionModal from './PromotionModal.vue'
import SubscribeModal from './SubscribeModal.vue'
import VideoModal from './VideoModal.vue'
import BzTimePicker from '../page/BzTimePicker.vue'
import UserTemplatePreviewModal from './UserTemplatePreviewModal.vue'
// import NavigationBuilderModal from './NavigationBuilderModal'
import builderMixin from '../../mixins/builderMixin'
import PresetThemeNameModal from './PresetThemeNameModal.vue'
import PresetPaletteNameModal from './PresetPaletteNameModal.vue'
import UpdateThemeModal from './UpdateThemeModal.vue'
import NewPageModal from '@/section-builder/components/modals/NewPageModal.vue'
import NewSectionModal from '@/section-builder/components/modals/NewSectionModal.vue'
import NewAppointmentModal from '@/section-builder/components/modals/NewAppointmentModal.vue'
import TemplateSwitchModal from '@/section-builder/components/modals/TemplateSwitchModal.vue'
import TemplateSwitchConfirmModal from '@/section-builder/components/modals/TemplateSwitchConfirmModal.vue'
import NewTemplateModal from '@/section-builder/components/modals/NewTemplateModal.vue'

export default {
  components: {
    TemplateSwitchConfirmModal,
    TemplateSwitchModal,
    NewAppointmentModal,
    NewSectionModal,
    NewPageModal,
    UpdateThemeModal,
    PresetPaletteNameModal,
    PresetThemeNameModal,
    // NavigationBuilderModal,
    VideoModal,
    SubscribeModal,
    PromotionModal,
    ManageMarkersModal,
    EmbedLinkModal,
    ContactFormSettingModal,
    IconSelectorModal,
    PageSettingModal,
    AltTextModal,
    AttachLinkModal,
    ImageSelectModal,
    VideoSelectModal,
    BzTimePicker,
    NewTemplateModal,
    UserTemplatePreviewModal
  },
  mixins: [builderMixin],
  computed: {
    modals: {
      get() {
        return this.$store.state.modals
      }
    },
    openImageSelector() {
      return this.modals.basic.name === 'selectImage'
    },
    openVideoEditor() {
      return this.checkOpenModal('selectVideo')
    },
    openAttachLink() {
      return this.checkOpenModal('attachLinkModal')
    },
    openAltText() {
      return this.checkOpenModal('altText')
    },
    openIconSelector() {
      return this.checkOpenModal('iconSelector')
    },
    openContactFormSetting() {
      return this.$store.state.modals.contactFormSetting.open
    },
    openTimePicker() {
      return this.$store.state.modals.timePicker.open
    },
    openEmbedLink() {
      return this.$store.state.modals.embedLink.open
    },
    openManageMarkers() {
      return this.$store.state.modals.manageMarkers.open
    },
    openPromotionModal() {
      return this.$store.state.modals.promotion.open
    },
    openSubscribeModal() {
      return this.$store.state.modals.subscribe.open
    },
    openVideoModal() {
      return this.$store.state.modals.youtubeVideo.open
    },
    openNavigationBuilderModal() {
      return this.checkOpenModal('navigationBuilder')
    },
    openThemeNameModal() {
      return this.$store.state.modals.themeName.open
    },
    openPaletteNameModal() {
      return this.$store.state.modals.paletteName.open
    }
  },
  methods: {
    checkOpenModal(modalName) {
      return this.$store.state.modals.openedModals.some((item) => item.name === modalName) || this.$store.state.modals.basic.name === modalName
    }
  }
}
</script>
