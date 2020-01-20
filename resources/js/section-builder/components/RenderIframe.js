import { h, ref, createApp, onMounted, onBeforeUpdate, resolveComponent } from 'vue'
import ShopPageDev from '@/section-builder/modules/ecommerce/ShopPage.dev.vue'
import ProductDetailDev from '@/section-builder/modules/ecommerce/ProductDetail.dev.vue'
import CartPageDev from '@/section-builder/modules/ecommerce/CartPage.dev.vue'
import CheckoutPageDev from '@/section-builder/modules/ecommerce/CheckoutPage.dev.vue'
import CheckoutSuccessPageDev from '@/section-builder/modules/ecommerce/CheckoutSuccessPage.dev.vue'
import CheckoutCanceledPageDev from '@/section-builder/modules/ecommerce/CheckoutCanceledPage.dev.vue'
import ModulePageDev from '@/section-builder/modules/ModulePage.dev.vue'
import BlogDetailPageDev from '@/section-builder/modules/blog/BlogDetailPage.dev.vue'
import PageContent from '@/section-builder/components/page/PageContent.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Directives from '@/section-builder/utils/directives'
import applyTheme from '@/section-builder/custom/utils/applyTheme'
import eventBus from '@/public/eventBus'

export default {
  name: 'RenderIframe',
  props: {
    css: {
      type: String,
      default: ''
    },
    store: {
      type: Object,
      required: true
    }
  },
  setup(props, { slots, emit }) {
    const iframeRef = ref(null)
    const iframeBody = ref(null)
    const iframeHead = ref(null)
    const iframeStyle = ref(null)
    const iframeHtml = ref(null)
    let iframeApp = null

    onMounted(() => {
      iframeBody.value = iframeRef.value.contentDocument.body
      iframeHead.value = iframeRef.value.contentDocument.head
      iframeHtml.value = iframeRef.value.contentDocument.documentElement
      iframeHtml.value.classList.add('tw-overflow-hidden')
      iframeRef.value.id = 'bz-page-content-frame'
      const el = document.createElement('div')
      el.classList.add('tw-overflow-hidden')
      el.classList.add('tw-bg-white')
      iframeBody.value.appendChild(el)
      iframeBody.value.classList.add('tw-overflow-hidden')
      iframeStyle.value = document.createElement('style')
      iframeStyle.value.innerHTML = props.css
      iframeHead.value.appendChild(iframeStyle.value)

      iframeHead.value.innerHTML = window.document.head.innerHTML

      iframeApp = createApp({
        name: 'IframeRender',
        setup() {
          return () => h(resolveComponent('router-view'))
        },
        mounted: () => {
          console.log('Builder iframe mounted')
          const iframeDocument = iframeRef.value.contentDocument
          applyTheme(props.store.state.template.data.theme, iframeDocument)
          eventBus.$on('TemplateThemeUpdated', (newTheme) => {
            console.log('TemplateThemeUpdatedEvent')
            applyTheme(newTheme || props.store.state.template.data.theme, iframeDocument)
          })

          iframeDocument.defaultView.addEventListener('resize', () => {
            console.log('IframeResizeEvent')
            eventBus.$emit('IframeResized')
          })
        }
      })

      iframeApp.mixin({
        methods: {
          asset: window.Vapor.asset
        }
      })

      iframeBody.value.addEventListener('scroll', (e) => {
        emit('scroll', e)
      })

      async function registerComponentsFromContext(app, context) {
        const promises = Object.keys(context).map((path) => {
          const componentName = path.split('/').pop().split('.')[0]
          return context[path]().then((component) => {
            app.component(componentName, component.default)
          })
        })
        await Promise.all(promises)
      }

      async function registerComponents(app) {
        const sections = import.meta.glob('../sections/**/*.vue')
        await registerComponentsFromContext(app, sections)

        const pages = import.meta.glob('../pages/**/*.dev.vue')
        await registerComponentsFromContext(app, pages)

        const components = import.meta.glob('../components/**/*.dev.vue')
        await registerComponentsFromContext(app, components)
      }

      iframeApp.config.globalProperties.$config = window.config

      Directives.init(iframeApp)

      registerComponents(iframeApp).then(() => {
        const basePath = window.config.basePath

        const pages = props.store.state.template.pages

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
              case 'market_place': {
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
            routes.push({
              path: page.url,
              name: page.name,
              component: PageContent
            })
          }
        }
        const router = createRouter({
          mode: 'history', // remove hashbang (#) from url
          history: createWebHistory(basePath),
          routes
        })

        iframeApp.use(router).use(props.store).mount(el)
      })
    })
    onBeforeUpdate(() => {
      if (!iframeApp || !iframeRef.value) {
        return
      }
      if (props.css) {
        iframeStyle.value.innerHTML = props.css
      }
    })
    return () => h('iframe', { ref: iframeRef })
  }
}
