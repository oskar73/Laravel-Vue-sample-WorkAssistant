import { createStore } from 'vuex'
import mutations from '../store/mutations'

export const store = createStore({
  state: {
    template: window.template,
    modules: window.modules,
    allCategories: [],
    edit: false,
    setting: null,
    activeCompanyIndex: 0,
    indexOfActivePage: 0,
    loadingData: true,
    stockImages: null,
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
    }
  },
  mutations
})
