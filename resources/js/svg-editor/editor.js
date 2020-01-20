import './bootstrap'
import { createApp } from 'vue'
// It was important***
import 'babel-polyfill'
import VueHtml2Canvas from 'vue-html2canvas'

import notifications from './mixins/notifications'
import ClickOutside from '../public/directives/ClickOutside'
import DesignEditor from '@/svg-editor/components/DesignEditor.vue'
import { store } from './store'

// Polyfill URLSearchParams
window.URLSearchParams =
  window.URLSearchParams ||
  function (searchString) {
    const self = this
    self.searchString = searchString
    self.get = function (name) {
      const results = new RegExp('[?&]' + name + '=([^&#]*)').exec(self.searchString)
      if (results == null) {
        return null
      } else {
        return decodeURI(results[1]) || 0
      }
    }
  }

if (!SVGElement.prototype.contains) {
  SVGElement.prototype.contains = HTMLDivElement.prototype.contains
}

const app = createApp(DesignEditor)
app.mixin({
  methods: {
    asset: window.Vapor.asset,
    route: window.route
  }
})
app.mixin(notifications)

app.directive('click-outside', ClickOutside)

app.use(VueHtml2Canvas)

app.use(store)
  .mount('#app')
