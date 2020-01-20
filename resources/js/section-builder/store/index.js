import { createStore } from 'vuex'
import mutations from './mutations'
import actions from './actions'

export const store = createStore({
  state: {
    template: window.config.template,
    userTemplates: [],
    templateCategories: [],
    isBuilder: true,
    allCategories: [],
    systemPalettes: {
      advanced: [],
      simple: []
    },
    userPalettes: {
      advanced: [],
      simple: []
    },
    indexOfActivePage: 0,
    indexOfActiveViewPage: 0,
    activePosition: 'header',
    addPosition: null,
    viewMode: 'desktop',
    edit: false,
    activeSlider: null,
    showEmptySection: false,
    activeTab: 0,
    activeSubTab: '',
    panelArrow: '',
    activeCompanyIndex: 0,
    loading: false,
    loadingData: true,
    stockImages: null,
    userFavicons: null,
    stockFavicons: null,
    userLogos: null,
    stockLogos: null,
    refreshEditor: false,
    countries: [],
    theme: null,
    themePreview: false,
    themePreviewPages: [],
    previewPalette: null, // refer themeMixin
    modules: null,
    modals: {
      openPageSettingModal: false,
      basic: {
        name: null,
        value: null,
        onChange: null,
        onClose: null
      },
      embedLink: {
        open: false,
        value: '',
        onChange: function (value) {
          console.log('embed link', value)
        }
      },
      manageMarkers: {
        open: false,
        value: [],
        onChange: function (value) {
          console.log('manage markers', value)
        }
      },
      promotion: {
        open: false,
        value: {
          successMessage: {
            title: '',
            message: '',
            footNote: ''
          },
          permissionMessage: 'By submitting your information, you are granting us permission to email you. You may unsubscribe at any time'
        },
        onChange: function (value) {
          console.log('promotion', value)
        }
      },
      subscribe: {
        open: false,
        value: {
          formAddress: '',
          successMessage: {
            title: '',
            message: '',
            footNote: ''
          },
          permissionMessage: 'By submitting your information, you are granting us permission to email you. You may unsubscribe at any time'
        },
        onChange: function (value) {
          console.log('subscribe', value)
        }
      },
      youtubeVideo: {
        open: false,
        value: {
          source: '',
          setting: {
            autoPlay: false,
            loop: false,
            controls: true
          }
        },
        onChange: null
      },
      openedModals: [],
      contactFormSetting: {
        open: false,
        form: {
          formFields: {
            firstName: {
              label: 'First Name',
              enabled: true
            },
            lastName: {
              label: 'Last Name',
              enabled: true
            },
            subject: {
              label: 'Subject',
              enabled: true
            },
            message: {
              label: 'Message',
              enabled: true
            },
            email: {
              label: 'Email',
              enabled: true
            },
            phone: {
              label: 'Phone',
              enabled: true
            },
            date: {
              label: 'Date',
              enabled: true
            },
            address: {
              label: 'Address',
              enabled: true
            }
          },
          formAddress: '',
          successMessage: {
            title: 'Message Sent!',
            message: 'Your message has been sent successfully, I hope to respond within 24 hours. You can also contact us through social media, links can be found below!'
          },
          permissionMessage: 'By checking this box and submitting your information, you are granting us permission to email you. You may unsubscribe at any time.'
        },
        onChange: null
      },
      timePicker: {
        open: false,
        time: '00:00 am',
        onChange: null
      },
      themeName: {
        open: false,
        value: null,
        onConfirm: null
      },
      paletteName: {
        open: false,
        onConfirm: null
      }
    },
    // if section appliedTo is "section", all sections should be inactive unless user selects one section
    isActiveSection: true,
    themeCategories: [],
    themes: [],

    // Create/Add Palette
    isNewPaletteMode: false,
    appliedTo: 'website',
    // page index list applied to current new palette
    paletteAppliedPages: [],
    // section index list applied to current new palette
    paletteAppliedSections: {},

    settingEditor: 'section',
    isOpenSettingPanel: true,
    isFixedSettingPanel: true
  },
  mutations,
  actions
})
