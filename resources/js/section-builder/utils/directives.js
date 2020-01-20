import ClickOutside from '../../public/directives/ClickOutside'
import Ripple from '@/public/directives/Ripple'

export default {
  init: function (app) {
    app.directive('tooltip', {
      bind: function (el, binding) {
        const { value } = binding
        const appendToolTip = () => {
          const rect = el.getBoundingClientRect()
          const tooltipContainerId = 'bz-tooltip-container'
          let toolTipContainer = document.getElementById(tooltipContainerId)
          if (toolTipContainer === null && value) {
            toolTipContainer = document.createElement('div')
            toolTipContainer.setAttribute('id', tooltipContainerId)
            toolTipContainer.style.cssText = `
            position: absolute;
            top: ${rect.bottom + 10}px;
            color: white;
            font-size: 14px;
            z-index: 10001;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 10px;
            background-color: #232E21;
            border-radius: 6px;
            height: 28px;
            box-shadow: 0 0 8px 4px #0000078;
            transform-origin: top center;
            transform: scale(0);
          `

            const tail = document.createElement('div')

            tail.style.cssText = `
            position: absolute;
            top: -5px;
            z-index: -1;
            background-color: #232E21;
            border-radius: 2px;
            height: 18px;
            width: 18px;
            transform: rotate(45deg);
            border-radius: 2px;
          `
            toolTipContainer.innerHTML = value

            toolTipContainer.prepend(tail)
            document.body.appendChild(toolTipContainer)

            setTimeout(() => {
              if (toolTipContainer) {
                toolTipContainer.style.cssText =
                  toolTipContainer.style.cssText +
                  `
                    left: ${rect.left + rect.width / 2 - toolTipContainer.clientWidth / 2}px;
                    transform: scale(1);
                    transition-delay: 500ms;
                    transition: all 200ms ease-out;
                  `
              }
            }, 10)

            function removeTooltip() {
              toolTipContainer?.remove()
              el.removeEventListener('mouseleave', removeTooltip)
            }

            el.addEventListener('mouseleave', removeTooltip)
          }
        }

        el.addEventListener('click', appendToolTip)
      }
    })

    app.directive('click-outside', ClickOutside)
    app.directive('ripple', Ripple)

    // Drop Down when clicking
    app.directive('dropdown', {
      bind: function (el, binding) {
        function doClose() {
          if (!isOpen) return
          isOpen = false
          el.classList.remove('show')
          document.removeEventListener('mousedown', onClose, false)
        }
        function onClose(e) {
          if (e && el.contains(e.target)) return
          doClose()
        }
        function onOpen(_e) {
          if (isOpen) return
          isOpen = true
          el.classList.add('show')
          document.addEventListener('mousedown', onClose, false)
        }
        function onToggle(_e) {
          isOpen ? onClose() : onOpen()
        }
        function onBlur(_e) {
          setTimeout(() => {
            const activeEl = document.activeElement
            if (activeEl !== document.body && !el.contains(activeEl)) {
              doClose()
            }
          })
        }
        let isOpen = false
        const toggle = el.querySelector('[dropdown-toggle]')
        const { value } = binding

        const { autoClose, click = 'toggle', focus = false } = value || {}
        if (click === 'toggle') {
          toggle.addEventListener('click', onToggle, false)
        } else if (click === 'open') {
          toggle.addEventListener('click', onOpen, false)
        }
        if (focus === 'open') {
          toggle.addEventListener('focus', onOpen, false)
          toggle.addEventListener('blur', onBlur, false)
        }

        autoClose && el.addEventListener('mouseup', doClose, false)

        el.classList.add('bz-dropdown')
      }
    })

    app.directive('drag-move', {
      bind: (el) => {
        this.translateX = 0
        this.translateY = 0
        this.enableMove = false

        this.mouseDown = (event) => {
          if (event.target.closest('.handler') && !event.target.closest('.no-move')) {
            this.enableMove = true
          }
        }

        this.mouseMove = (event) => {
          const rect = el.getBoundingClientRect()
          if (this.enableMove) {
            if ((event.movementY < 0 && rect.top < 10) || (event.movementY > 0 && rect.bottom > window.innerHeight - 10)) {
              return
            }

            this.translateX += event.movementX
            this.translateY += event.movementY
            el.style.transform = `translate(${this.translateX}px, ${this.translateY}px)`
          }
        }

        this.mouseUp = () => {
          this.enableMove = false
        }

        el.addEventListener('mousedown', this.mouseDown)
        document.body.addEventListener('mousemove', this.mouseMove)
        document.body.addEventListener('mouseup', this.mouseUp)

        new ResizeObserver((entries) => {
          const rect = entries[0].target.getBoundingClientRect()
          if (rect.top < 0) {
            this.translateY -= rect.top - 10
            el.style.transform = `translate(${this.translateX}px, ${this.translateY}px)`
          }

          if (rect.bottom > window.innerHeight - 10) {
            this.translateY -= rect.bottom - window.innerHeight + 10
            el.style.transform = `translate(${this.translateX}px, ${this.translateY}px)`
          }
        }).observe(el)
      },
      unbind: () => {
        document.body.removeEventListener('mousemove', this.mouseMove)
        document.body.removeEventListener('mouseup', this.mouseUp)
      }
    })
  }
}
