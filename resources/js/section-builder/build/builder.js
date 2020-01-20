import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import 'es6-promise/auto'
import { Vue3SlideUpDown } from 'vue3-slide-up-down'
import tinycolor from 'tinycolor2'
import Vapor from 'laravel-vapor'

import VueUUID from 'vue-uuid'
import { Tabs, Tab } from 'vue3-tabs-component'
import { Carousel, Slide } from 'vue3-carousel'

import { vfmPlugin } from 'vue-final-modal'
import '@vueform/slider/themes/default.css'

import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

import 'vue-progressive-image/dist/style.css'

import PageContent from '@/section-builder/components/page/PageContent.vue'
import ShopPageDev from '@/section-builder/modules/ecommerce/ShopPage.dev.vue'
import ProductDetailDev from '@/section-builder/modules/ecommerce/ProductDetail.dev.vue'
import ModulePageDev from '@/section-builder/modules/ModulePage.dev.vue'
import BlogDetailPageDev from '@/section-builder/modules/blog/BlogDetailPage.dev.vue'
import Plugins from '@/section-builder/plugins'
import Utils from '@/section-builder/custom/utils'
import Directives from '@/section-builder/utils/directives'
import EditPage from '../components/EditPage.vue'

import '../../bootstrap'

import { store } from '../store'
import CartPageDev from '@/section-builder/modules/ecommerce/CartPage.dev.vue'
import CheckoutPageDev from '@/section-builder/modules/ecommerce/CheckoutPage.dev.vue'
import CheckoutSuccessPageDev from '@/section-builder/modules/ecommerce/CheckoutSuccessPage.dev.vue'
import CheckoutCanceledPageDev from '@/section-builder/modules/ecommerce/CheckoutCanceledPage.dev.vue'

const app = createApp(EditPage)

window.tinycolor = tinycolor
window.Vapor = Vapor
if (import.meta.env) {
  window.Vapor.withBaseAssetUrl(import.meta.env.VITE_VAPOR_ASSET_URL)
} else {
  window.Vapor.withBaseAssetUrl(process.env.VITE_VAPOR_ASSET_URL)
}

app.use(
  vfmPlugin({
    key: '$modal',
    componentName: 'Modal',
    dynamicContainerName: 'ModalsContainer'
  })
)

app.use(VueUUID)

app.component('Tabs', Tabs)
app.component('Tab', Tab)
app.component('SlideUpDown', Vue3SlideUpDown)
app.component('DatePicker', VueDatePicker)

app.mixin({
  methods: {
    asset: window.Vapor.asset
  }
})

app.use(Plugins)

// Initialize Util functions
Utils.init()
Directives.init(app)

app.component('VueCarousel', Carousel)
app.component('VueSlide', Slide)

async function registerComponentsFromContext(app, context) {
  const promises = Object.keys(context).map((path) => {
    const componentName = path.split('/').pop().split('.')[0]
    return context[path]().then((component) => {
      app.component(componentName, component.default)
    })
  })
  await Promise.all(promises)
}

async function registerComponents() {
  const sections = import.meta.glob('../sections/**/*.vue')
  await registerComponentsFromContext(app, sections)

  const pages = import.meta.glob('../pages/**/*.dev.vue')
  await registerComponentsFromContext(app, pages)

  const components = import.meta.glob('../components/**/*.dev.vue')
  await registerComponentsFromContext(app, components)
}

// Vue configs
app.config.globalProperties.$config = window.config

registerComponents()
  .then(() => {
    const basePath = window.config.basePath

    const pages = window.config.template.pages

    let routes = []
    for (const page of pages) {
      if (page.type === 'module') {
        switch (page.module_name) {
          case 'ecommerce': {
            const ecommerceRouts = [
              {
                name: 'shop',
                path: page.url,
                component: ShopPageDev
              },
              {
                name: 'product-detail',
                path: '/product/:product',
                component: ProductDetailDev
              },
              {
                name: 'cart',
                path: '/cart',
                component: CartPageDev
              },
              {
                name: 'checkout',
                path: '/checkout',
                component: CheckoutPageDev
              },
              {
                name: 'checkout-success',
                path: '/checkout/success',
                component: CheckoutSuccessPageDev
              },
              {
                name: 'checkout-canceled',
                path: '/checkout/canceled',
                component: CheckoutCanceledPageDev
              },
              {
                name: 'directory',
                path: '/directory',
                component: ModulePageDev
              }
            ]

            routes = routes.concat(ecommerceRouts)
            break
          }
          case 'simple_blog':
          case 'advanced_blog':
          case 'blog': {
            const blogRoutes = [
              {
                name: 'blog',
                path: page.url,
                component: ModulePageDev
              },
              {
                name: 'blog-detail',
                path: '/blog/:slug',
                component: BlogDetailPageDev
              }
            ]
            routes = routes.concat(blogRoutes)
            break
          }
          case 'market_place':
          case 'marketplace': {
            const marketPlaceRoutes = [
              {
                name: 'marketplace',
                path: page.url,
                component: ModulePageDev
              }
            ]
            routes = routes.concat(marketPlaceRoutes)
            break
          }
        }
      } else {
        let pageUrl = '/'
        if (page.url) {
          if (!page.url.includes('/')) {
            pageUrl = '/' + page.url
          } else {
            pageUrl = page.url
          }
        }

        const isDuplicated = routes.some((route) => route.path === pageUrl)
        if (!isDuplicated) {
          routes.push({
            path: pageUrl,
            name: page.name,
            component: PageContent
          })
        }
      }
    }

    const router = createRouter({
      mode: 'history', // remove hashbang (#) from url
      history: createWebHistory(basePath),
      routes
    })

    app.use(store).use(router).mount('#app')
  })
  .catch((err) => {
    console.error('Build Error: ', err)
  })
