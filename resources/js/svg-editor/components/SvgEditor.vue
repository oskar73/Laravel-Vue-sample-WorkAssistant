<template>
  <div id="svg_editor" class="svg-editor" :class="eyedrop === 0 ? '' : 'eye_drop_selected'">
    <template v-if="!isMobile()">
      <div id="workarea" ref="workArea" class="workarea" :class="{ backgroundSelected }">
        <div id="svgcanvas" ref="svgcanvas" @click="eyeDropAction"></div>
        <span class="helper z-index-100000" @click.prevent="getTutorial">
          <i class="mdi mdi-help tw-text-white"></i>
        </span>
        <!--        <div-->
        <!--          class="tw-absolute tw-border tw-pointer-events-none"-->
        <!--          :style="boundaryStyle"-->
        <!--          style="outline: solid 10000px #ebeced"-->
        <!--        ></div>-->
        <div v-if="pairs.length > 0" class="tw-absolute tw-bottom-[20px] tw-left-[120px]">
          <Menu as="div" class="tw-relative tw-inline-block tw-text-left">
            <menu-button class="tw-flex tw-items-center tw-w-full tw-justify-center tw-gap-x-1.5 tw-px-1 tw-py-1 tw-text-sm tw-text-gray-900">
              <div class="tw-bg-white tw-px-5 tw-py-3 tw-rounded tw-shadow-lg">
                {{ graphic.title }}
                <i class="mdi mdi-chevron-up"></i>
              </div>
            </menu-button>

            <transition
              enter-active-class="tw-transition tw-ease-out tw-duration-100"
              enter-from-class="tw-transform tw-opacity-0 tw-scale-95"
              enter-to-class="tw-transform tw-opacity-100 tw-scale-100"
              leave-active-class="tw-transition tw-ease-in tw-duration-75"
              leave-from-class="tw-transform tw-opacity-100 tw-scale-100"
              leave-to-class="tw-transform tw-opacity-0 tw-scale-95"
            >
              <menu-items
                class="tw-absolute tw-bottom-full tw-mb-1 tw-z-50 tw-py-2 tw-w-40 tw-origin-bottom-left tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-focus:outline-none"
              >
                <div v-for="pair in pairs" :key="pair.hash" class="hover:tw-bg-gray-100 tw-cursor-pointer tw-px-2 tw-py-1 tw-text-sm" @click="handlePairClick(pair)">
                  {{ pair.graphic.title }}
                </div>
                <div class="hover:tw-bg-gray-100 tw-cursor-pointer tw-px-2 tw-py-1 tw-text-sm" @click="handlePairClick(ownerDesign)">
                  {{ ownerDesign.graphic.title }} <small class="tw-text-green-500">(main)</small>
                </div>
              </menu-items>
            </transition>
          </Menu>
        </div>
        <span class="zoom-toolbar">
          <span class="zoom-button-dec" @click="changeZoom('decrement', $event)">-</span>
          <span class="zoom-value">{{ zoom.toFixed(0) }}%</span>
          <span class="zoom-button-inc" @click="changeZoom('increment', $event)">+</span>
        </span>
      </div>

      <div v-if="groups.length" class="top_pan_area d-flex justify-content-center align-items-center">
        <a href="#" class="similar_next_btn mr-3" title="View Similar Logo" @click.prevent="goSimilarLogo('prev')"><i class="el-icon-arrow-left"></i></a>
        <button class="btn btn-outline-info compact_view text-uppercase" title="view similar logos" @click.prevent="modalOpen = true">
          <i class="el-icon-menu"></i> view similar logos <span>({{ groups.length }})</span>
        </button>
        <a href="#" class="similar_next_btn ml-3" title="View Similar Logo" @click.prevent="goSimilarLogo('next')"><i class="el-icon-arrow-right"></i></a>
      </div>

      <span v-if="is_premium" class="btn btn-theme premium_btn premium">Premium</span>
      <span v-else class="btn btn-theme premium_btn">Free</span>
      <input id="zoom" ref="zoom" size="3" value="100%" type="hidden" readonly="readonly" />
      <div :class="icons.states.isVisible ? 'show' : ''" class="overlay"></div>
    </template>

    <template v-else>
      <div class="mobile-editor">
        <div class="mobile-not-supported-message">Mobile version in development.</div>
        <button class="edit-on-desktop-link" icon="el-icon-share" @click="sendLinkToEditor()">Send link to email</button>
        <p class="edit-on-desktop-text">and continue editing on my desktop</p>
      </div>
    </template>

    <input id="text" type="text" />

    <div v-if="tutorial_on" class="tutorial_popup_overlay" @click="tutorial_on = false">
      <div class="tutorial_popup_content" v-html="tutorial_content"></div>
    </div>
    <div v-if="modalOpen" class="c_modal">
      <div class="c_modal_overlay" @click="modalOpen = !modalOpen"></div>
      <div class="c_modal_bg">
        <span class="c_modal_close" @click="modalOpen = !modalOpen">×</span>

        <div class="container custom_container custom-scroll-h position-relative">
          <div class="modal_area row">
            <div v-for="(gl_item, gl_key) in groups" :key="gl_key" class="col-md-2 mb-3">
              <div :class="{ 'active-preview': gl_item.hash === currentHash }" class="modal_logo_preview logoItemContainer" @click.prevent="groups(gl_item.id)">
                <item-on-modal :url="gl_item.preview" :hash="gl_item.hash" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import svgEditor from '../editor/svg-editor'
import Driver from 'driver.js'
import appMixin from '../mixins/app-mixin'
import notification from '../mixins/notifications'
import downloadProduct from '../mixins/download-product'
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css'
import eventBus from '@/public/eventBus'
import axios from 'axios'
import ItemOnModal from './ItemOnModal.vue'
import editorMixin from '@/svg-editor/mixins/editor-mixin'
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { getDesignData } from '@/svg-editor/api'

export default {
  name: 'SvgEditor',
  components: {
    MenuItems,
    Menu,
    MenuButton,
    ItemOnModal
  },
  mixins: [appMixin, notification, downloadProduct, editorMixin],
  data() {
    return {
      backgroundSelected: false,
      defaultGradient: {
        type: 'linear',
        degree: 0,
        points: [
          {
            left: 0,
            red: 0,
            green: 0,
            blue: 0,
            alpha: 1
          },
          {
            left: 100,
            red: 255,
            green: 0,
            blue: 0,
            alpha: 1
          }
        ]
      },
      fill_type: 'solid',
      stroke_type: 'solid',
      background_type: 'solid',
      fill_gradient: {
        type: 'linear',
        degree: 0,
        points: []
      },
      stroke_gradient: {
        type: 'linear',
        degree: 0,
        points: []
      },
      modalOpen: false,
      groups: [],
      unsavedChanges: true,
      svgData: null,
      currentHash: null,
      is_premium: false,
      tutorial: null,
      tutorial_on: false,
      tutorial_content: '',
      states: {
        is_edited: false,
        is_loaded: false,
        isSaving: false
      },
      hash: null,
      saveTimer: 10000,
      eyedrop: 0,
      icons: {
        states: {
          isVisible: false
        }
      },
      attributes: {
        background: null,
        opacity: {
          value: 1,
          min: 0,
          max: 1,
          interval: 0.01
        },

        blur: {
          value: 0,
          min: 0,
          max: 10,
          interval: 0.01
        },
        color: {
          fill: '#FFFFFF',
          stroke: null
        },
        stroke: {
          value: 1,
          min: 0,
          max: 10,
          interval: 0.01,
          color: '#000000'
        },

        // Text attributes
        text: {
          value: null,
          font: {
            name: null,
            size: 24,
            bold: false,
            italic: false,
            min: 1,
            max: 200,
            interval: 1
          },
          letterSpacing: {
            value: 0,
            min: 0,
            max: this.isFirefox() ? 1000 : 100,
            interval: this.isFirefox() ? 5 : 1
          },
          isUpperCase: false
        }
      },
      navigator: {
        driver: new Driver({
          allowClose: true,
          opacity: 0.5
        }),
        demonstrations: {
          panel: {
            text: false
          }
        }
      },
      zoom: 100,
      logoSaveCallbackRoute: '',
      favicon: null,
      logo: null,

      // for ruler
      workAreaWidth: null,
      workAreaHeight: null
    }
  },
  computed: {
    activeFaviconGroup() {
      return false
    },
    boundaryStyle() {
      const _style = {
        width: (this.graphic.width * this.zoom) / 100 + 'px',
        height: (this.graphic.height * this.zoom) / 100 + 'px'
      }

      if (this.backgroundSelected) {
        _style.border = 'dashed 2px #00000080'
      }

      return _style
    }
  },
  created() {
    this.backgrond_gradient = Object.assign({}, this.defaultGradient)
    this.stroke_gradient = Object.assign({}, this.defaultGradient)
    this.fill_gradient = Object.assign({}, this.defaultGradient)
  },
  mounted() {
    this.initializeSvgEditor()
    eventBus.$on('editor-design-changed', () => {
      this.unsavedChanges = true
      // window.onbeforeunload = (e) => {
      //     e.preventDefault();
      //     e.returnValue = "You have attempted to leave this page. Are you sure?";
      // }
    })
    eventBus.$on('tools.set', (data) => {
      if (data.tool === 'eyedrop') {
        this.eyedrop = data.eyedrop
      } else {
        this.eyedrop = 0
      }
    })
    this.workAreaWidth = this.$refs.workArea.clientWidth
    this.workAreaHeight = this.$refs.workArea.clientHeight
  },
  methods: {
    handlePairClick(pair) {
      if (pair.hash !== this.designHash) {
        this.loadingSvgData = true
        this.saveDesign().then((success) => {
          if (success) {
            getDesignData(pair.hash, this.ownerDesign.hash).then((data) => {
              console.log(data)
              this.designData = data
              this.loadingSvgData = false
            })
          } else {
            this.loadingSvgData = false
          }
        })
      }
    },
    initializeSvgEditor() {
      svgEditor.init({
        dimensions: [this.graphic.width, this.graphic.height]
      })

      if (this.isMobile()) {
        return
      }

      const canvas = svgEditor.canvas

      this.downloadProtection()
      this.setCanvasWorker(canvas)
        .then(() => {
          this.listen()
          this.setDesign().then(() => {
            this.alignDesign().then(() => {
              this.actualizeLetterSpacing()
              this.clearSelected()
              // this.initTippy()
              this.initTippyForRotate()
              this.fitDesignWindow()
            })
            this.states.is_loaded = true
          })
        })
        .catch((error) => {
          console.log(error)
        })

      // Save logo after closing the tab
      window.addEventListener('beforeunload', () => {
        // this.saveDesign();
      })
    },
    fitDesignWindow() {
      const screen = this.$refs.workArea
      const screenWidth = screen.clientWidth
      const screenHeight = screen.clientHeight

      let zoom = 1
      const viewAreaRatio = 0.8

      if (this.graphic.width > this.graphic.height) {
        if (screenWidth * viewAreaRatio < this.graphic.width) {
          zoom = (screenWidth * viewAreaRatio) / this.graphic.width
        }
      } else {
        if (screenHeight * viewAreaRatio < this.graphic.height) {
          zoom = (screenHeight * viewAreaRatio) / this.graphic.height
        }
      }
      this.canvas.changeZoom({ value: zoom * 100 })
      this.zoom = zoom * 100
    },
    saveAlertInit() {
      // window.onbeforeunload = () => (this.unsavedChanges ? true : null);
    },
    getFontFileName(css, fontname) {
      let nameIdx = css.indexOf("'" + fontname + "'")
      if (nameIdx === -1) return null
      let tempStr = css.slice(nameIdx)
      nameIdx = tempStr.indexOf('url("../')
      if (nameIdx === -1) return null
      nameIdx += 8

      tempStr = tempStr.slice(nameIdx)
      const lastIdx = tempStr.indexOf('"')
      if (lastIdx === -1) return null

      return tempStr.slice(0, lastIdx)
    },
    loadFont() {
      return new Promise((resolve) => {
        const svgElem = this.$refs.svgcanvas.getElementsByTagName('text')
        const styles = this.$refs.svgcanvas.getElementsByTagName('style')
        const fontList = []
        for (const node of svgElem) {
          const fontFamily = node.getAttribute('font-family')
          if (fontFamily) {
            let bFindFont = false
            for (const style of styles) {
              if (style.innerText.indexOf(fontFamily) >= 0) {
                bFindFont = true
                break
              }
            }
            if (!bFindFont) fontList.push(fontFamily)
          }
        }
        let css = ''
        let loaded = 0
        const _this = this
        if (fontList.length === 0) return resolve()

        axios.get(this.fontUrl).then(function (response) {
          const fontcss = response.data

          fontList.forEach((fontname) => {
            const filename = _this.getFontFileName(fontcss, fontname)
            const url = _this.fontUrl.replace('css/fonts.css', '') + filename
            const request = new XMLHttpRequest()
            request.open('get', url)
            request.responseType = 'blob'
            request.onloadend = () => {
              const reader = new FileReader()
              reader.onloadend = () => {
                css = css + "@font-face {font-family: '" + fontname + "';src: url(" + reader.result + ") format('truetype');}"
                loaded++
                if (loaded >= fontList.length) {
                  window.$('svg').append(`<style>${css}</style>`)
                  return resolve()
                }
              }
              reader.readAsDataURL(request.response)
            }
            request.send()
          })
        })
      })
    },
    eyeDropAction(e) {
      this.saveAlertInit()

      if (this.eyedrop > 0) {
        const x = e.pageX - this.$refs.svgcanvas.getBoundingClientRect().left
        const y = e.pageY - this.$refs.svgcanvas.getBoundingClientRect().top
        const _eyedrop = this.eyedrop

        this.loadFont().then(() => {
          this.$html2canvas(this.$refs.svgcanvas).then((canvas) => {
            const canvasCtx = canvas.getContext('2d')
            const img_data = canvasCtx.getImageData(x, y, 1, 1).data
            const hex = this.rgbToHex(img_data[0], img_data[1], img_data[2])
            const color = '#' + hex

            if (_eyedrop === 1) {
              this.attributes.color.fill = color
              this.onChangeFillColor()
            } else if (_eyedrop === 2) {
              this.attributes.color.fill = color
              this.onChangeFillColor()
            } else if (_eyedrop === 3) {
              this.attributes.stroke.color = color
              this.onChangeStrokeColor()
            }
            this.toolSelect()
            this.eyedrop = 0
          })
        })
      }
    },
    toolSelect() {
      this.canvas.setMode('select')
      eventBus.$emit('tools.set', {
        tool: 'select'
      })
    },
    goSimilarLogo(obj) {
      const currentHash = this.currentHash
      const length = this.groups.length
      let currentLogoKey
      let nextKey
      window.$.each(this.groups, function (key, item) {
        if (item.hash === currentHash) {
          currentLogoKey = key
        }
      })
      if (obj === 'next') {
        nextKey = length + currentLogoKey + 1
      } else {
        nextKey = length + currentLogoKey - 1
      }
      const final = nextKey % length
      window.location.href = '/step/choose/' + this.groups[final].id
    },
    getTutorial() {
      // axios.get(this.route(this.editorType + '.getTutorial', this.tutorial)).then((response) => {
      //   this.tutorial_on = true;
      //   this.tutorial_content = response.data.data;
      // });
      const screenWidth = window.innerWidth
      const screenHeight = window.innerHeight

      const left = screenWidth * 0.2
      const top = screenHeight * 0.2
      const width = screenWidth * 0.6
      const height = screenHeight * 0.6

      window.open('/videos', '_blank ', `menubar=1,resizable=1,width=${width},height=${height},left=${left},top=${top}`)
    },
    startLogoSaver() {
      // let self = this;
      // let saveDesignClosure = setInterval(function () {
      //     self.saveDesignWithOldDefs().catch(error => {
      //         clearInterval(saveDesignClosure);
      //         //document.location.reload(true);
      //     });
      // }, this.saveTimer);
    },
    saveDesignWithOldDefs() {
      return new Promise((resolve, reject) => {
        return axios
          .post(this.route('graphics.save', this.designHash), {
            svgData: this.rot13(this.getLogoWithOldDefs(), true)
          })
          .then(() => {
            return resolve()
          })
          .catch((e) => {
            return reject(e)
          })
      })
    },
    alignDesign() {
      return new Promise((resolve) => {
        setTimeout(() => {
          // If logo is not editable then align of center workarea
          this.canvas.clearSelection(true)
          this.canvas.selectAllInCurrentLayer()
          this.canvas.groupSelectedElements()
          this.canvas.alignSelectedElements('m', 'page')
          this.canvas.alignSelectedElements('c', 'page')
          this.canvas.ungroupSelectedElement()
          this.canvas.ungroupSelectedElement()
          this.canvas.ungroupSelectedElement()
          this.canvas.ungroupSelectedElement()
          this.canvas.clearSelection(true)
          this.canvas.undoMgr.resetUndoStack()

          return resolve()
        }, 100)
      })
    },
    getLogoWithOldDefs() {
      return this.canvas.svgCanvasToString(true)
    },
    setDesign() {
      return new Promise((resolve) => {
        const data = this.rot13(this.designData.content)
        this.svgData = data
        this.tutorial = this.designData.tutorial
        this.currentHash = this.designData.hash
        this.is_premium = this.designData.is_premium
        this.states.is_edited = this.designData.is_edited

        this.canvas.setSvgString(data, {
          is_edited: this.states.is_edited
        })

        return resolve()
      })
    },

    updateBackground() {
      this.attributes.background = this.canvas.getBackgroundColor()
    },
    isTextSelected() {
      return this.selected[0] && this.selected[0].nodeName === 'text' && this.elementsAreSame()
    },
    onChangeFillColor() {
      const color = this.prepareColor(this.attributes.color.fill)
      this.canvas.setColor('fill', color)
    },
    onChangeStrokeColor() {
      const color = this.prepareColor(this.attributes.stroke.color)
      this.canvas.setColor('stroke', color)
    },
    listen() {
      // Selected event for re-rendering Edit panel
      eventBus.$on('leave.window.allow', () => {
        this.unsavedChanges = false
      })

      eventBus.$on('selected.changed', () => {
        this.selected = this.getSelected()
      })

      eventBus.$on('background.selected', () => {
        this.backgroundSelected = true
      })

      eventBus.$on('background.cleared', () => {
        this.backgroundSelected = false
      })

      // Get logotype preview
      eventBus.$on('design.preview.get', () => {
        this.saveDesign().then(() => {
          axios.get(this.route('graphics.download', this.designHash)).then((response) => {
            eventBus.$emit('design.preview.set', {
              preview: response.data.logotype.content,
              forDownload: true
            })
          })
        })
      })

      eventBus.$on('editor.panel.text.input.navigator', (data) => {
        // Force show navigator for dbl-click on text
        if (data && data.force_show) {
          this.navigator.demonstrations.panel.text = false
        }
        this.showTextInputNavigator()
      })

      eventBus.$on('zoom.updated', () => {
        this.updateZoom()
      })
    },

    setCanvasWorker(canvas) {
      return new Promise((resolve, reject) => {
        this.canvas.bind('selected', () => {
          eventBus.$emit('selected.changed')
        })
        return resolve()
      })
    },
    setText(text) {
      // Set model
      this.attributes.text.value = text

      // Change text
      this.canvas.setTextContent(text)
    },
    group() {
      this.canvas.groupSelectedElements()
    },

    ungroup() {
      this.canvas.ungroupSelectedElement()
    },
    clearSelected() {
      this.canvas.clearSelection()
      this.selected = []
    },
    changeZoom(operation, event) {
      const evt = document.createEvent('MouseEvents')
      evt.initEvent('mousewheel', true, true)
      evt.wheelDelta = 120

      if (operation === 'decrement') {
        evt.wheelDelta = -120
      }

      evt.forceZoom = true

      this.canvas.getSvgRoot().dispatchEvent(evt)

      // Actualize zoom value
      this.updateZoom()
    },
    focusOnTextInput() {
      setTimeout(() => {
        // Focus
        this.$refs.text.focus()

        // Fix for focus on end text
        const value = this.$refs.text.value
        this.$refs.text.value = ''
        this.$refs.text.value = value
      }, 200)
    },

    showTextInputNavigator() {
      if (this.isTextSelected() && !this.navigator.demonstrations.panel.text) {
        // Show user navigator
        this.navigator.driver.highlight({
          element: '.text-edit',
          popover: {
            title: 'Text control panel',
            description: 'Here you can change the inserted text to fit your needs'
          }
        })

        // Set the flag to show navigation
        this.navigator.demonstrations.panel.text = true
      }
    },

    getDomLogo() {
      return document.getElementById('svgcontent')
    },

    isFirefox() {
      return navigator.userAgent.toLowerCase().indexOf('firefox') > -1
    },

    actualizeLetterSpacing() {
      if (this.isFirefox()) {
        const logo = this.getDomLogo()
        const htmlCollection = logo.getElementsByTagName('text')
        const texts = Array.prototype.slice.call(htmlCollection)

        for (const item of texts) {
          const value = item.getAttribute('letter-spacing')

          if (value) {
            const canvas = document.createElement('canvas')
            const context = canvas.getContext('2d')
            context.font = parseFloat(item.getAttribute('font-size')) + 'px' + ' ' + item.getAttribute('font-family')

            let px = parseFloat(value)
            let length = px

            // If unit === em
            const unit = value.substr(-2)
            if (unit === 'em') {
              px = parseFloat(value) * parseFloat(item.getAttribute('font-size'))
            }

            // Define text length
            length = this.fillTextWithSpacing(context, item.textContent, 0, 0, px)

            // Set text length attribute
            item.setAttribute('textLength', length)
          }
        }
      }
    },

    fillTextWithSpacing(context, text, x, y, spacing) {
      text = text.trim()

      let wordWidth = context.measureText(text).width
      let char = ''
      let widthShorter = 0
      let charWidth = 0

      do {
        char = text.substr(0, 1)
        text = text.substr(1)
        context.fillText(char, x, y)

        if (text === '') {
          widthShorter = 0
        } else {
          widthShorter = context.measureText(text).width
        }

        charWidth = wordWidth - widthShorter

        x += charWidth + spacing
        wordWidth = widthShorter
      } while (text !== '')

      if (spacing < 0) {
        x = -1 * x
      }

      return x
    },

    sendLinkToEditor() {
      axios.get(this.route('graphics.send-editor-link', this.designHash)).then((response) => {
        this.notification({
          title: response.data.status,
          type: response.data.status,
          message: response.data.message
        })
      })
    },

    initTippyForRotate() {
      const button = document.getElementById('selectorGrip_rotate')
      tippy(button, {
        trigger: 'click',
        theme: 'light',
        animation: 'fade'
      })
    },
    updateZoom() {
      if (this.$refs.zoom) {
        this.zoom = this.$refs.zoom.value
      }
    }
  }
}
</script>

<style lang="scss">
.label_txt {
  font-weight: bold !important;
  font-size: 14px !important;
  color: #000 !important;
}

.preset_item_area {
  margin-top: 5px;
}

.toggle_header {
  border: 1px solid #4aa0e6;
  color: #4aa0e6;
  padding: 5px 10px;

  margin-top: 0.5rem;
  cursor: pointer;

  &:hover {
    background-color: #4aa0e6;
    color: #fff;
  }
}

.h-cursor:hover {
  cursor: pointer;
}

input.bg_type {
  width: auto !important;
  box-shadow: none !important;
}

.eye_drop_selected #svgcanvas svg * {
  cursor: crosshair !important;
}

.modal_logo_preview {
  cursor: pointer !important;
}

.custom-scroll-h {
  overflow: auto;

  &::-webkit-scrollbar {
    width: 10px;
    cursor: pointer;
  }

  &::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  &::-webkit-scrollbar-thumb {
    background: #888;
  }

  &::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
}

.c_modal_bg {
  position: fixed;
  top: 10px;
  left: 50%;
  bottom: 10px;
  border: 1px solid #4d8ac9;
  box-shadow: 1px 3px 5px #3333;
  transform: translate(-50%, 0);
  width: 100%;
  max-width: 1350px;

  background-color: #fff;
  z-index: 99999;
  padding-top: 100px;
  padding-bottom: 100px;
  margin: auto;
  display: flex;
  justify-content: center;
  transition: all 0.5s;

  .c_modal_close {
    position: fixed;
    font-size: 40px;
    color: #000;
    top: 20px;
    right: 20px;
    z-index: 9999;
    line-height: 25px;
    cursor: pointer !important;
  }

  .modal_logo_preview {
    background-color: #fff;
    cursor: default;
    box-shadow: 0 0 15px -3px #cfcfcf;
    padding: 10px;
    border: 1px solid #4d8ac9;
  }
}

.c_modal_overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #333a;
  z-index: 9998;
}

.similar_next_btn {
  padding: 3px 9px;
  border-radius: 50%;
  font-size: 20px;
  border: 1px solid #4d8ac9;
  font-weight: 600;
  background-color: #fff;
  box-shadow: 1px 3px 5px #3333;

  &:hover {
    color: #fff;
    background-color: #4d8ac9;
  }
}

.compact_view {
  border-radius: 0;
  color: #4d8ac9;
  border-color: #4d8ac9;
  box-shadow: 1px 3px 5px #3333;
  transition: all 0.2s;

  &:hover,
  &:focus,
  &:active,
  &:visited {
    box-shadow: 1px 5px 8px #3333;
    background-color: #4d8ac9;
    color: #fff;
    outline: none !important;
    border: 1px solid #4d8ac9;
  }

  &:focus {
    box-shadow: none !important;
    background-color: #4d8ac9;
    color: #fff;
    outline: none !important;
    border: 1px solid #4d8ac9;
  }
}

.solid_item_span {
  width: 25px;
  height: 25px;
  display: inline-block;

  &:hover,
  &.active {
    border: 2px solid #4aa0e6;
  }
}

.gradient_preset_area {
  padding-left: 0;
  list-style: none;
  max-height: 250px;
  overflow-y: auto;
  padding-top: 0;
  padding-bottom: 10px;

  .folder_item {
    border-bottom: 1px solid #eee;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;

    &:hover {
      background-color: #eee;
    }

    .folder_img {
      width: 20px;
      margin-right: 10px;
    }
  }
}

.gradient_preset_item {
  width: 25px;
  height: 25px;
  float: left;
  display: inline-block;
  margin: 2px;

  &.active {
    border: 2px solid #4aa0e6;
    width: 26px;
    height: 26px;
  }
}

.color-preview-area {
  fragment {
    display: inherit;
  }

  .input {
    width: 100% !important;
    outline: 0 !important;
    color: #1f2667 !important;
    border-radius: inherit !important;
    border: 1px solid #bbbfc5 !important;
    height: 24px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    padding: 0 6px !important;
  }
}

.ui-color-picker {
  padding: 10px;
  margin: 0 !important;
  background-color: transparent !important;

  p {
    margin-top: auto !important;
    margin-bottom: auto !important;
  }
}

select.bg_type {
  height: 30px !important;
  width: 130px !important;
}

.tutorial_popup_overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 9001;
  font-size: 14px;
  -webkit-font-smoothing: antialiased;
  background: #3338;
  transition: opacity 0.3s;
  transform: translate3D(0, 0, 0);
  -webkit-perspective: 500px;

  .tutorial_popup_content {
    transform: translate(-50%, -50%);
    position: absolute;
    top: 50%;
    left: 50%;
    width: 80%;
    max-width: 640px;
    max-height: 100vh;
    overflow-y: auto;
    border-radius: 2px;
    transition-property: transform, opacity;
    transition-duration: 0.3s;
    transition-delay: 0.05s;
    transition-timing-function: cubic-bezier(0.52, 0.02, 0.19, 1.02);
  }
}

.mobile-editor {
  font-family: Raleway Regular, sans-serif;
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100vh;
  justify-content: center;
  align-items: center;
  background: #fff;

  .mobile-not-supported-message {
    font-size: 17px;
    color: #acacac;
    padding-bottom: 20px;
  }

  .edit-on-desktop-link {
    width: 180px;
    margin-bottom: 7px;
  }

  .edit-on-desktop-text {
    color: #acacac;
    font-size: 13px;
    width: 150px;
    text-align: center;
  }
}

.workarea {
  background-color: #ededed !important;
  overflow: hidden !important;

  &.backgroundSelected {
    #selectorGrip_rotateconnector,
    #selectorGrip_rotate,
    [id^='selectorGrip_resize'] {
      display: none;
    }
  }
}

.editor .tutorial {
  position: fixed;
  width: 40px;
  height: 40px;
  display: flex;
  background-color: #7719be;
  border-radius: 10em;
  bottom: 105px;
  left: 160px;
  cursor: pointer;
  box-shadow: 0 4px 14px rgb(58 88 249 / 30%);
  transition: all 0.3s ease;
  padding: 7px;

  img {
    margin: auto;
    display: inline-block;
    width: 100%;
  }
}

.top_pan_area {
  position: fixed;
  top: 105px;
  right: 380px;
  left: 360px;
}

.premium_btn {
  position: fixed;
  background: transparent;
  border-radius: 0;
  border: 1px solid #4d8ac9;
  color: #4d8ac9;
  bottom: 105px;
  right: 550px;
  opacity: 0.4;
  padding: 5px 20px;

  &:hover {
    background: transparent;
    border: 1px solid #4d8ac9;
    color: #4d8ac9;
    cursor: default !important;
  }

  &.premium {
    border-color: #7719be;
    color: #7719be;

    &:hover {
      border-color: #7719be;
      color: #7719be;
    }
  }
}

@import 'color-gradient-picker-vue3/dist/style.css';
</style>
