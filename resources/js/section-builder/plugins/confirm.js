import { createApp } from 'vue'
import ConfirmDialog from './ConfirmDialog.vue'
import ClickOutside from '@/public/directives/ClickOutside'
export default function confirm({ title, description, action }) {
  return new Promise((resolve) => {
    const ConfirmDialogApp = createApp(ConfirmDialog, {
      title,
      description,
      action
    })

    const rootEl = document.createElement('div')
    ConfirmDialogApp.mixin({
      methods: {
        close(e) {
          if (e.target.closest('.bz-confirm-dialog')) {
            this.$el.remove()
            resolve(false)
          }
        },
        confirm() {
          this.$el.remove()
          resolve(true)
        }
      }
    })

    ConfirmDialogApp.directive('click-outside', ClickOutside)

    ConfirmDialogApp.mount(rootEl)

    document.body.appendChild(rootEl)
  })
}
