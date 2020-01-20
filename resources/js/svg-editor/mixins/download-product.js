import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css'
import appMixin from './app-mixin'
import eventBus from '@/public/eventBus'
import axios from 'axios'

export default {
  mixins: [appMixin],

  data() {
    return {
      purchases: {
        types: {
          logotype: 'logotype',
          package: 'package'
        }
      }
    }
  },

  created() {
    this.$nextTick(() => {
      setTimeout(() => {
        this.placeholderForPackageButton()
        this.placeholderForLogotypeButton()
      }, 1000)
    })
  },

  methods: {
    async downloadDesign() {
      // this.downloadGaEvent(svgData, this.purchases.types.logotype)

      // Spinner for buttons
      axios.get(this.route('graphics.download', this.designHash)).then((response) => {
        console.log(response)
        if (response.data.type === this.http.statuses.error) {
          this.notification({
            title: response.data.error,
            type: response.data.status,
            message: response.data.error
          })
          return
        }

        if (response.data.isPurchased) {
          console.log('footer page downloadDesign response', response.data)
          const linkSource = response.data.content
          const downloadLink = document.createElement('a')
          const fileName = this.graphic.slug + '.png'

          downloadLink.href = linkSource
          downloadLink.download = fileName

          document.body.appendChild(downloadLink)
          downloadLink.click()
          document.body.removeChild(downloadLink)

          this.isDownloadingDesign = false
        } else {
          // Redirect to server for this purchase or downloading
          this.notification({
            title: 'Limited',
            type: 'info',
            message: response.data.message
          })
          eventBus.$emit('leave.window.allow')
          window.setTimeout(function () {
            window.location.href = response.data.redirect
          }, 1000)
        }
      })
    },

    downloadPackage(logotype) {
      // this.downloadGaEvent(logotype, this.purchases.types.package)

      // Spinner for buttons
      window.location.href = this.route('graphics.download.package', this.designHash)
    },

    placeholderForPackageButton() {
      // Show placeholder for editor page
      tippy('graphics.download-package', {
        placement: 'top',
        theme: 'light',
        animation: 'fade',
        trigger: 'mouseenter',
        content(reference) {
          return (
            '<ul class="benefits-on-purchase-button">' +
            '<li>High-quality logo images</li>' +
            '<li>Resizable vector SVG/PDF files</li>' +
            '<li>Social media logo (Facebook, Twitter, etc.)</li>' +
            '<li>Font names & color palette</li>' +
            '<li>Print-ready</li>' +
            '</ul>'
          )
        }
      })
    },

    placeholderForLogotypeButton() {
      tippy('graphics.download', {
        placement: 'top',
        theme: 'light',
        animation: 'fade',
        trigger: 'mouseenter',
        content(reference) {
          return '<ul class="benefits-on-purchase-button">' + '<li>High quality PNG logotype</li>' + '</ul>'
        }
      })
    }
  }
}
