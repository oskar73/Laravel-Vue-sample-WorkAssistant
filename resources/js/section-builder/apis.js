import axios from 'axios'

const bzApi = axios.create({
  baseURL: window.config.baseUrl
})

const bzApiV1 = axios.create({
  baseURL: '/api/v1/'
})

const route = window.route
window.websiteId = window.config.websiteId

// Get Admin Template
export const getTemplate = (templateId) => {
  return axios.get(route('user.template.item.getTemplate', templateId))
}

// Get Admin Template/User Website data for builder
export const getTemplateData = (templateId) => {
  if (window.config.isTemplate) {
    return bzApi.get('/template/item/getTemplateData/' + templateId)
  } else {
    return bzApi.get('/website/getWebsiteData/' + templateId + '?templateId=' + window.config.userTemplateId)
  }
}

// Template APIs
export const updateTemplateTheme = (templateId, theme) => {
  return bzApi.post('/template/item/update-theme/' + templateId, { theme })
}

export const publishTemplate = () => {
  if (window.config.isTemplate) {
    return bzApi.post('/template/item/publishContent/' + window.websiteId)
  } else {
    return bzApiV1.post(route('api.v1.website.publish', { website: window.websiteId }))
  }
}

export const saveTemplate = (template) => {
  if (window.config.isTemplate) {
    if (template.image.includes('blob:')) {
      return axios.get(template.image, { responseType: 'blob' }).then((blobResponse) => {
        const file = new File([blobResponse.data], 'preview.png', { type: 'image/png' })
        const form = new FormData()
        form.append('image', file)

        return Promise.all([
          bzApi.post('/template/item/edit/' + window.websiteId, template),
          bzApi.post('/template/item/uploadPreviewUrl/' + window.websiteId, form, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
        ])
      })
    } else {
      return bzApi.post('/template/item/edit/' + window.websiteId, template)
    }
  } else {
    return bzApiV1.put(route('api.v1.website.save', { website: window.websiteId }), template)
  }
}

export const createUserTemplate = (website) => {
  return bzApi.post(route('user.website.user-template.create', { webId: window.websiteId }), website)
}

export const getUserTemplate = (id) => {
  return bzApi.get(route('user.website.user-template.get', { webId: window.websiteId, id }))
}

export const getUserTemplates = () => {
  return bzApi.get(route('user.website.user-template.list', { webId: window.websiteId }))
}

export const deleteUserTemplate = (id) => {
  return bzApi.delete(route('user.website.user-template.delete', { webId: window.websiteId, id }))
}

export const addNewAppointment = (data) => {
  if (!window.config.isTemplate) {
    return axios.post('/account/appointment/listing/store', { ...data })
  }
}

export const addNewPage = (pageName, requestUrl) => {
  if (window.config.isTemplate) {
    return bzApi.post('/template/page/add', {
      web_id: window.websiteId,
      page_name: pageName
    })
  } else {
    return axios.post(requestUrl, {
      web_id: window.websiteId,
      page_name: pageName,
      parent_id: 0
    })
  }
}

export const addNewSectionToTemplate = (payload) => {
  return bzApi.post('/template/section/add', payload)
}

export const duplicatePage = (pageId) => {
  if (window.config.isTemplate) {
    return bzApi.post('/template/page/clone/' + pageId)
  } else {
    return bzApi.post('/website/duplicatePage/' + pageId)
  }
}

export const deletePage = (pageId) => {
  if (window.config.isTemplate) {
    return bzApi.delete('/template/page/' + pageId)
  } else {
    return bzApi.post('/website/deletePage', { pageId })
  }
}

// Theme APIs
export const storeTheme = (newTheme) => {
  return bzApi.post('/theme/item/', newTheme)
}

export const updateTheme = (newTheme) => {
  return bzApi.put('/theme/item', newTheme)
}

export const deleteTheme = (themeId) => {
  return bzApi.post('/theme/item/delete', { themeId })
}

export const savePalette = (newPalette) => {
  return bzApi.post('/palettes/advanced/palette', newPalette)
}

export const deletePalette = (paletteId) => {
  return bzApi.delete('/palettes/advanced/palette', {
    data: {
      id: paletteId
    }
  })
}

// Module Ecommerce API
export const getEcommerceModuleCategories = () => {
  return bzApiV1.get(route('api.v1.module.ecommerce.categories', { website: window.websiteId }))
}

export const getEcommerceProducts = () => {
  return bzApiV1.get(route('api.v1.module.ecommerce.products', { website: window.websiteId }))
}

// Module Directory API
export const getDirectoryModuleCategories = () => {
  return bzApiV1.get(route('api.v1.module.directory.categories', { website: window.websiteId }))
}

export const getDirectoryListings = () => {
  if (window.config.isTemplate) {
    // TODO:
  } else {
    return bzApiV1.get(route('api.v1.module.directory.listings', { website: window.websiteId }))
  }
}

export const getPortfolioItems = () => {
  if (window.config.isTemplate) {
    // TODO:
  } else {
    return bzApiV1.get(route('api.v1.module.portfolio.items', { website: window.websiteId }))
  }
}

export const getEcommerceProduct = (productId) => {
  return bzApiV1.get(
    route('api.v1.module.ecommerce.product.get', {
      website: window.websiteId,
      product: productId
    })
  )
}

export const checkoutItems = (cart, type) => {
  return bzApiV1.post(route('api.v1.module.ecommerce.checkout', { website: window.websiteId }), {
    cart,
    type,
    url: window.location.href
  })
}

export const checkoutSuccess = (session, cart) => {
  return bzApiV1.post(route('api.v1.module.ecommerce.checkout.success', { website: window.websiteId }), {
    session,
    cart
  })
}

export const getWebsitePage = (pageId) => {
  if (window.location.pathname.includes('template') && window.location.pathname.includes('preview')) {
    return bzApiV1.get(
      route('api.v1.template.get', {
        page: pageId,
        website: window.websiteId
      })
    )
  }

  return bzApiV1.get(
    route('api.v1.page.get', {
      page: pageId,
      website: window.websiteId
    })
  )
}

export const uploadMediaToS3 = (file) => {
  const formData = new FormData()
  formData.append('image', file)
  return bzApiV1.post(route('api.media.uploadToS3'), formData)
}
