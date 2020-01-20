import axios from 'axios'
import { cloneDeep, merge } from 'lodash'
import defaultTemplateData from '../../data/defaultTemplateData'
import { getTemplateData } from '../../apis'
import eventBus from '@/public/eventBus'

export const builderMutation = {
  setTemplate(state, { websiteId }) {
    state.loadingData = true
    getTemplateData(websiteId)
      .then((res) => {
        if (res.data.status) {
          console.log('Template data loaded', cloneDeep(res.data))
          state.countries = res.data.data.countries
          state.allCategories = res.data.data.allCategories
          state.modules = res.data.data.modules

          state.systemPalettes = res.data.data.systemPalettes
          state.userPalettes = res.data.data.userPalettes

          state.themeCategories = res.data.data.themeCategories
          state.themes = res.data.data.themes
          state.templateCategories = res.data.data.templateCategories

          // state.template = res.data.data.template

          this.commit('updateTemplate', res.data.data.template)
        } else {
          window.LOG.error("pageMutation: can't get template data", res)
        }
      })
      .catch((err) => {
        window.LOG.error("pageMutation: can't get template data", err)
        state.loadingData = false
      })
  },
  updateTemplate(state, newTemplate) {
    if (!newTemplate) {
      newTemplate = state.template
    }
    newTemplate.data = merge(defaultTemplateData, newTemplate.data ?? {})
    state.template = cloneDeep(newTemplate)

    let url = state.template.domain + window.location.pathname
    if (window.location.host !== state.template.domain) {
      const publicIndex = window.location.pathname.split('/').findIndex((s) => s === 'editContent' || s === 'editPages')
      url =
        '/' +
        window.location.pathname
          .split('/')
          .filter((_, i) => i > publicIndex + 1)
          .join('/')
    }

    // set active page index, because the first page may be a module page which should not be editable here for now.
    for (let i = 0; i < state.template.pages.length; i++) {
      if (state.template.pages[i].type !== 'module' && state.template.pages[i].url && url.includes(state.template.pages[i].url)) {
        state.indexOfActivePage = i
      }
    }
    eventBus.$emit('template:update')
    eventBus.$emit('refresh:sections')
  },
  setActiveViewPage(state, payload) {
    if (typeof payload.index === 'number' && payload.index > -1 && payload.index < state.themePreviewPages.length) {
      state.indexOfActiveViewPage = payload.index
    } else {
      console.error('Invalid page index', payload.index)
    }
  },
  setActivePage(state, payload) {
    if (payload.hasOwnProperty('index')) {
      if (typeof payload.index === 'number' && payload.index > -1 && payload.index < state.template.pages.length) {
        state.indexOfActivePage = payload.index
      } else {
        console.error('Invalid page index', payload.index)
      }
    } else if (payload.hasOwnProperty('id')) {
      // eslint-disable-next-line
      const index = state.template.pages.findIndex((page) => page.id == payload.id)
      console.log(index)
      state.indexOfActivePage = index
    }
  },
  updatePageStatus(state, pageId) {},
  updatePagesOrder(state) {
    const pageIds = (state.template.pages || []).map((page) => page.id)
    let updateOrderUrl = window.route('admin.template.page.updateOrder')
    if (state.template.domain) {
      updateOrderUrl = window.route('user.website.updatePagesOrder')
    }
    axios
      .post(updateOrderUrl, { ids: pageIds.join(',') })
      .then((res) => {
        if (res.data.status) {
          window.LOG.success('update page order success')
        } else {
          window.LOG.error('update page order failed', res.data)
        }
      })
      .catch((err) => {
        window.LOG.error('update page order failed', err)
      })
  },
  loadStockFavicons(state) {
    axios
      .get(window.route('api.v1.loadStockFavicons'))
      .then((res) => {
        if (res.data.status) {
          window.LOG.success('Load Stack Favicons', res.data.data)
          if (state.stockFavicons && Array.isArray(state.stockFavicons)) {
            state.stockFavicons = state.stockFavicons.concat(res.data.data.stockFavicons)
          } else {
            state.stockFavicons = res.data.data.stockFavicons
          }
        } else {
          window.LOG.error('Failed Loading Stack Favicons', res.data.data)
          state.stockFavicons = []
        }
      })
      .catch((err) => {
        window.LOG.error('Load Stack Favicons', err.message)
        state.stockFavicons = []
      })
  },
  loadUserFavicons(state) {
    axios
      .get(window.route('api.v1.loadUserFavicons'))
      .then((res) => {
        if (res.data.status) {
          window.LOG.success('Load Stack Favicons', res.data.data)
          if (state.userFavicons && Array.isArray(state.userFavicons)) {
            state.userFavicons = state.userFavicons.concat(res.data.data.userFavicons)
          } else {
            state.userFavicons = res.data.data.userFavicons
          }
        } else {
          window.LOG.error('Failed Loading Stack Favicons', res.data.data)
          state.userFavicons = []
        }
      })
      .catch((err) => {
        window.LOG.error('Load Stack Favicons', err.message)
        state.userFavicons = []
      })
  },
  loadStockLogos(state, page = 0) {
    axios
      .get(window.route('api.v1.loadStockLogos', { page }))
      .then((res) => {
        if (res.data.status) {
          window.LOG.success('Load Stock Logos', res.data.data)
          if (state.stockLogos && Array.isArray(state.stockLogos)) {
            state.stockLogos = state.stockLogos.concat(res.data.data.stockLogos)
          } else {
            state.stockLogos = res.data.data.stockLogos
          }
        } else {
          window.LOG.error('Failed Loading Stock Logos', res.data.data)
          state.stockLogos = []
        }
      })
      .catch((err) => {
        window.LOG.error('Load Stack Favicons', err.message)
        state.stockLogos = []
      })
  },
  loadUserLogos(state) {
    axios
      .get(window.route('api.v1.loadUserLogos'))
      .then((res) => {
        if (res.data.status) {
          window.LOG.success('Load User Logos', res.data.data)
          state.userLogos = res.data.data.userLogos
        } else {
          window.LOG.error('Failed Loading User Logos', res.data.data)
          state.userLogos = []
        }
      })
      .catch((err) => {
        window.LOG.error('Load User Logos', err.message)
        state.userLogos = []
      })
  },
  updateActiveModule(state, module) {
    if (!state.modules.activeModules.includes(module)) {
      state.modules.activeModules = [...state.modules.activeModules, module]
    }
  },
  setUserTemplates(state, payload) {
    state.userTemplates = payload
  }
}
