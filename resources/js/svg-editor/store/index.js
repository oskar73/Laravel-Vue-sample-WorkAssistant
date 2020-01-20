import { createStore } from 'vuex'
import mutations from './mutations'

export const store = createStore({
  state: {
    modals: {
      basic: {
        name: null,
        value: null,
        onChange: null
      }
    },
    loadingSvgData: true,
    designData: window.svgData,
    liveView: window.svgData.liveView,
    unsplashApiKey: window.unsplashApiKey,
    isSavingDesign: false,
    isOpenPreview: false
  },
  mutations
})
