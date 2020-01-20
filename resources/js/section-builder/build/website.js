import { createApp } from 'vue'
import { Carousel, Slide } from 'vue3-carousel'

import tinycolor from 'tinycolor2'
import Vapor from 'laravel-vapor'

import { vfmPlugin } from 'vue-final-modal'
import '@vueform/slider/themes/default.css'

import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

import { createRouter, createWebHistory } from 'vue-router'

import PageView from '@/section-builder/build/components/PageView.vue'
import WebsiteView from '@/section-builder/build/components/WebsiteView.vue'
import HeaderView from '@/section-builder/build/components/HeaderView.vue'
import FooterView from '@/section-builder/build/components/FooterView.vue'
import Plugins from '@/section-builder/plugins'
import Utils from '@/section-builder/custom/utils'
import Directives from '@/section-builder/utils/directives'

import '../../bootstrap'

import { store } from './store'
import ShopPageProd from '@/section-builder/modules/ecommerce/ShopPage.prod.vue'
import ProductDetailProd from '@/section-builder/modules/ecommerce/ProductDetail.prod.vue'
import BlogPageProd from '@/section-builder/modules/blog/BlogPage.prod.vue'
import NotFoundView from '@/section-builder/build/components/NotFoundView.vue'
import CartPageProd from '@/section-builder/modules/ecommerce/CartPage.prod.vue'
import CheckoutPageProd from '@/section-builder/modules/ecommerce/CheckoutPage.prod.vue'
import CheckoutSuccessPageProd from '@/section-builder/modules/ecommerce/CheckoutSuccessPage.prod.vue'
import CheckoutCanceledPageProd from '@/section-builder/modules/ecommerce/CheckoutCanceledPage.prod.vue'

window.tinycolor = tinycolor
window.Vapor = Vapor
if (import.meta.env) {
  window.Vapor.withBaseAssetUrl(import.meta.env.VITE_VAPOR_ASSET_URL)
} else {
  window.Vapor.withBaseAssetUrl(process.env.VITE_VAPOR_ASSET_URL)
}

Utils.init()
if (document.getElementById('app')) {
  const app = createApp(WebsiteView)

  app.mixin({
    methods: {
      asset: window.Vapor.asset
    }
  })

  app.use(Plugins)

  // Initialize Util functions
  window.applyTheme(window.template.data.theme)
  Directives.init(app)

  app.use(
    vfmPlugin({
      key: '$modal',
      componentName: 'Modal',
      dynamicContainerName: 'ModalsContainer'
    })
  )

  app.component('DatePicker', VueDatePicker)
  app.component('VueCarousel', Carousel)
  app.component('VueSlide', Slide)

  const sections = require.context('../sections', true, /\.vue$/i)
  sections.keys().map((key) => app.component(key.split('/').pop().split('.')[0], sections(key).default))

  const pages = require.context('../pages', true, /\.prod.vue$/i)
  pages.keys().map((key) => app.component(key.split('/').pop().split('.')[0], pages(key).default))

  const components = require.context('../components', true, /\.prod.vue$/i)
  components.keys().map((key) => app.component(key.split('/').pop().split('.')[0], components(key).default))

  app.config.globalProperties.$config = window.config

  let routes = [{ path: '/:catchAll(.*)', name: 'NotFound', component: NotFoundView }]
  window.websiteId = window.template.id
  for (const page of window.template.pages) {
    if (page.type === 'module') {
      switch (page.module_name) {
        case 'ecommerce': {
          const ecommerceRoutes = [
            {
              name: 'shop',
              path: page.url,
              component: ShopPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            },
            {
              name: 'product-detail',
              path: '/product/:product',
              component: ProductDetailProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            },
            {
              name: 'cart',
              path: '/cart',
              component: CartPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            },
            {
              name: 'checkout',
              path: '/checkout',
              component: CheckoutPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            },
            {
              name: 'checkout-success',
              path: '/checkout/success',
              component: CheckoutSuccessPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            },
            {
              name: 'checkout-canceled',
              path: '/checkout/canceled',
              component: CheckoutCanceledPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            }
          ]
          routes = routes.concat(ecommerceRoutes)
          break
        }
        case 'simple_blog':
        case 'advanced_blog':
        case 'blog': {
          const blogRoutes = [
            {
              name: 'BlogPage',
              path: page.url,
              component: BlogPageProd,
              meta: {
                title: page.name
              },
              props: {
                pageId: page.id
              }
            }
          ]
          routes = routes.concat(blogRoutes)
          break
        }
        case 'market_place':
        case 'marketplace':
      }
    } else {
      routes.push({
        name: page.name,
        path: page.url,
        meta: {
          title: page.name
        },
        props: {
          pageId: page.id
        },
        component: PageView
      })
    }
  }

  const router = createRouter({
    base: window.config?.basePath || '',
    mode: 'history', // remove hashbang (#) from url
    history: createWebHistory(window.config?.basePath || ''),
    routes
  })

  app.use(store).use(router).mount('#app')
} else {
  const routes = [{ path: '/:catchAll(.*)', name: 'NotFound', component: NotFoundView }]
  const router = createRouter({
    base: '',
    mode: 'history', // remove hashbang (#) from url
    history: createWebHistory(),
    routes
  })

  const headerApp = createApp(HeaderView)
  headerApp.use(Plugins)
  const headerSections = require.context('../sections/header', true, /\.vue$/i)
  headerSections.keys().forEach((key) => {
    headerApp.component(key.split('/').pop().split('.')[0], headerSections(key).default)
  })
  headerApp.use(store).use(router).mount('#header')
  const components = require.context('../components', true, /\.prod.vue$/i)
  components.keys().map((key) => headerApp.component(key.split('/').pop().split('.')[0], components(key).default))

  const footerApp = createApp(FooterView)
  footerApp.use(Plugins)
  const footerSections = require.context('../sections/footer', true, /\.vue$/i)
  footerSections.keys().forEach((key) => {
    footerApp.component(key.split('/').pop().split('.')[0], footerSections(key).default)
  })
  components.keys().map((key) => footerApp.component(key.split('/').pop().split('.')[0], components(key).default))

  footerApp.use(store).use(router).mount('#footer')
}
