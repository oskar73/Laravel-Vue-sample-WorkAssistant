export const modalMutation = {
  // Embed Link Modal
  openEmbedLink(state, payload) {
    state.modals.embedLink.open = true
    state.modals.embedLink.onChange = payload.onChange
    state.modals.embedLink.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeEmbedLink(state) {
    state.modals.embedLink.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Manage Markers Modal
  openManageMarkers(state, payload) {
    state.modals.manageMarkers.open = true
    state.modals.manageMarkers.onChange = payload.onChange
    state.modals.manageMarkers.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeManageMarkers(state) {
    state.manageMarkers.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Page Setting Modal,
  openPageSetting: (state) => {
    state.modals.openPageSettingModal = true
  },
  closePageSetting: (state) => {
    state.modals.openPageSettingModal = false
  },

  // Contact Form Setting Modal
  openContactFormSetting(state, payload) {
    state.modals.contactFormSetting.form = payload.form
    state.modals.contactFormSetting.onChange = payload.onChange
    state.modals.contactFormSetting.open = true
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeContactFormSetting(state) {
    state.modals.contactFormSetting.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Time Picker Modal
  openTimePicker(state, payload) {
    state.modals.timePicker.open = true
    state.modals.timePicker.time = payload.time
    state.modals.timePicker.onChange = payload.onChange
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeTimePicker(state) {
    state.timePicker.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Promotion Modal
  openPromotion(state, payload) {
    state.modals.promotion.open = true
    state.modals.promotion.onChange = payload.onChange
    state.modals.promotion.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closePromotion(state) {
    state.modals.promotion.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Subscribe Modal
  openSubscribe(state, payload) {
    state.modals.subscribe.open = true
    state.modals.subscribe.onChange = payload.onChange
    state.modals.subscribe.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeSubscribe(state) {
    state.modals.subscribe.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },

  // Youtube Video Modal\
  openYoutubeVideo(state, payload) {
    state.modals.youtubeVideo.open = true
    state.modals.youtubeVideo.onChange = payload.onChange
    state.modals.youtubeVideo.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeYoutubeVideo(state) {
    state.modals.youtubeVideo.open = false
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  },
  openThemeNameModal(state, payload) {
    state.modals.themeName.open = true
    state.modals.themeName.onConfirm = payload.onConfirm
    state.modals.themeName.value = payload.value
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  openPaletteNameModal(state, payload) {
    state.modals.paletteName.open = true
    state.modals.paletteName.onConfirm = payload.onConfirm
  },

  // Basic Modal
  openModal(state, payload) {
    if (state.modals.basic.name !== null) {
      state.modals.openedModals.push(state.modals.basic)
    }
    state.modals.basic = payload
    window.document.getElementsByTagName('html')[0].style.overflow = 'hidden'
  },
  closeModal(state) {
    state.modals.basic = state.modals.openedModals.pop() || {
      name: null,
      data: null,
      onChange: null
    }
    window.document.getElementsByTagName('html')[0].style.overflow = ''
  }
}
