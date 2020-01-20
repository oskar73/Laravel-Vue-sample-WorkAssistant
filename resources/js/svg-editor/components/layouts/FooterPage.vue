<template>
  <transition name="slideUp">
    <footer id="preview-footer" :class="{ editor: !liveView }">
      <template v-if="liveView">
        <!-- To Home button-->
        <button class="animated-button back-button" title="home page" itemprop="url" @click="handleBack">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span class="arrow-icon arrow-left-icon"></span>
          Back
        </button>
        <!-- To Home button-->

        <!-- Choose logo button-->
        <transition name="bounceUp">
          <button class="animated-button animate-button choose-logo-button" title="choose logo" @click="getNextStep">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
            Edit Design
            <span class="arrow-icon arrow-right-icon"></span>
          </button>
        </transition>
        <!-- Choose logo button -->
      </template>

      <template v-else>
        <button :disabled="isSavingDesign" class="button button-preview tw-mr-1" @click="showPreview">
          <spinner v-if="isSavingDesign" />
          <i v-else class="mdi mdi-eye tw-mr-1"></i>
          Preview
        </button>

        <button :disabled="isSavingDesign" class="button button-preview" @click="handleDesignSave">
          <spinner v-if="isSavingDesign" />
          <i v-else class="mdi mdi-content-save tw-mr-1"></i>
          Save
        </button>

        <button :disabled="isSavingDesign" class="button button-preview" @click="handleSaveDesignAndExit">
          <spinner v-if="isSavingDesign" />
          <i v-else class="mdi mdi-content-save tw-mr-1"></i>
          Save & Exit
        </button>

        <Menu v-if="auth" as="div" class="tw-relative tw-inline-block tw-text-left">
          <div>
            <menu-button :disabled="isDownloadingDesign" class="button button-download">
              <spinner v-if="isDownloadingDesign" />
              <i v-else class="mdi mdi-chevron-down"></i>
              Download
            </menu-button>
          </div>
          <transition
            enter-active-class="tw-transition tw-ease-out tw-duration-100"
            enter-from-class="tw-transform tw-opacity-0 tw-scale-95"
            enter-to-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-active-class="tw-transition tw-ease-in tw-duration-75"
            leave-from-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-to-class="tw-transform tw-opacity-0 tw-scale-95"
          >
            <MenuItems
              class="tw-absolute tw-bottom-full tw-right-0 tw-z-10 tw-mb-2 tw-w-56 tw-origin-bottom-right tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-black tw-ring-opacity-5 tw-focus:outline-none"
            >
              <div class="py-1">
                <MenuItem>
                  <button class="tw-block tw-px-4 tw-py-3 tw-text-sm hover:tw-bg-gray-100 tw-w-full tw-text-left" @click="clickDownload(products.image)">
                    <i class="mdi mdi-image" aria-hidden="true"></i> {{ graphic.title }}
                  </button>
                </MenuItem>
                <MenuItem>
                  <button class="tw-block tw-px-4 tw-py-3 tw-text-sm hover:tw-bg-gray-100 tw-w-full tw-text-left" @click="clickDownload(products.package)">
                    <i class="mdi mdi-archive" aria-hidden="true"></i> Full {{ graphic.title }} package
                  </button>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>
        <button v-else :disabled="isDownloadingDesign" class="button button-download" @click="clickDownload(products.image)">
          <spinner v-if="isDownloadingDesign" />
          <i v-else class="mdi mdi-download tw-mr-1"></i>
          Download
        </button>

        <save-design-modal :open="modalOpen" @close="modalOpen = false" @save="saveDesignWindow" />
        <preview-popup />
      </template>
    </footer>
  </transition>
</template>

<script>
import stepsNavigation from '../../mixins/steps-navigation'
import downloadProduct from '../../mixins/download-product'
import notification from '../../mixins/notifications'
import eventBus from '@/public/eventBus'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import SaveDesignModal from '@/svg-editor/components/modals/SaveDesignModal.vue'
import Spinner from '@/public/Spinner.vue'
import editorMixin from '../../mixins/editor-mixin'
import PreviewPopup from '@/svg-editor/components/elements/preview-popup.vue'
import axios from 'axios'

export default {
  name: 'FooterPage',
  components: {
    PreviewPopup,
    Spinner,
    SaveDesignModal,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
  },
  mixins: [stepsNavigation, downloadProduct, notification, editorMixin],
  data() {
    return {
      modalOpen: false,
      auth: window.user,
      product: null,
      isDownloadingDesign: false,
      buttons: {
        nextButton: {
          isVisible: true
        }
      },
      exit: 0
    }
  },
  created() {
    // eslint-disable-next-line
    eventBus.$on('footer.button-next.hide', () => {
      this.buttons.nextButton.isVisible = false
    })

    // eslint-disable-next-line
    eventBus.$on('footer.button-next.show', () => {
      this.buttons.nextButton.isVisible = true
    })
  },

  beforeDestroy() {
    eventBus.$off('design.preview.set')
  },

  methods: {
    handleDesignSave() {
      this.isSavingDesign = true
      this.saveDesign().then((success) => {
        this.isSavingDesign = false
        if (success) {
          this.notification({
            title: 'Success!',
            type: 'success',
            message: 'Successfully saved!'
          })
        }
      })
    },
    async saveDesignFinal(option) {
      return new Promise((resolve) => {
        axios
          .post(this.route('graphics.saveFinal', this.ownerDesign.hash), {
            svgData: this.rot13(this.getDesign(), true),
            version_name: option.version_name,
            version_type: option.version_type,
            owner_hash: option.owner_hash,
            exit: option.exit
          })
          .then((response) => {
            return resolve(response)
          })
          .catch((err) => {
            console.error('saveDesignFinal  Error', err)
            return resolve(false)
          })
      })
    },
    saveDesignWindow(option) {
      this.modalOpen = false
      this.isSavingDesign = true
      const { version_type, version_name } = option
      const data = {
        exit: this.exit,
        version_type,
        version_name
      }
      this.saveDesignFinal(data).then((res) => {
        this.isSavingDesign = false
        this.notification({
          title: 'Success!',
          type: 'success',
          message: 'Successfully saved!'
        })
        if (this.exit) {
          window.location.href = this.route('user.graphics.index')
        }
      })
    },
    handleSaveDesignAndExit() {
      this.exit = 1
      this.isSavingDesign = true
      this.saveDesign().then(() => {
        if (this.original_version === 'first_version') {
          const option = {
            version_type: 'create',
            version_name: 'default'
          }
          this.saveDesignWindow(option)
        } else {
          this.modalOpen = true
        }
      })
    },
    showPreview() {
      this.isSavingDesign = true
      this.saveDesign().then((svgData) => {
        eventBus.$emit('design.preview.set', {
          preview: `data:image/svg+xml;base64, ${window.btoa(unescape(encodeURIComponent(svgData)))}`,
          modal: true
        })
        this.isSavingDesign = false
        this.isOpenPreview = true
      })
    },
    handleBack() {
      if (this.route().current() === 'graphics.edit') {
        this.liveView = false
      } else {
        window.location.href = this.route('user.graphics.index')
      }
    },
    getNextStep() {
      if (this.route().current() === 'graphics.edit') {
        this.liveView = false
      } else {
        window.location.href = this.route('graphics.index')
      }
    },
    clickDownload(product) {
      this.isDownloadingDesign = true
      this.saveDesign().then((success) => {
        if (success) {
          switch (product) {
            case this.products.image:
              this.downloadDesign()
              break
            case this.products.package:
              this.downloadPackage()
              break
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
footer.editor {
  left: 320px;
  right: 345px;
  width: auto !important;
}

.svg-editor {
  .mobile-editor {
    footer {
      display: none;
    }
  }
}

.error_div {
  border: 1px solid red;
  margin-bottom: 10px;
  padding: 5px;
}

footer {
  width: 100%;
  height: 70px;
  position: fixed;
  bottom: 0;
  background: #fff;
  box-shadow: 0 -6px 14px rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  padding: 0 20px;
  z-index: 40;
  justify-content: flex-end;
}

.button {
  display: flex;
  height: 45px;
  min-width: 120px;
  justify-content: center;
  align-items: center;
  font-size: 14px;
  font-weight: normal;
  outline: none;
  box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.14), 0 3px 3px -2px rgba(0, 0, 0, 0.2), 0 1px 8px 0 rgba(0, 0, 0, 0.12);

  &-download {
    background: #45ca82;
    border: #43c250 1px solid;

    &:hover {
      background: #4aa976;
    }
  }

  &-preview {
    margin-right: 15px;
    padding: 0 15px;
    color: #ffffff;
    border: #6319a5;
    background: #7719be;

    &:hover {
      background: #8b19d1;
    }
  }
}

.previous-step {
  width: 160px;
  height: 45px;
  border: 1px solid rgba(58, 88, 249, 0.2);
  border-radius: 4px;
  font-family: 'Poppins', sans-serif;
  font-style: normal;
  font-weight: 300;
  line-height: normal;
  font-size: 16px;
  text-transform: capitalize;
  display: flex;
  color: #3a58f9;
  align-items: center;
  transition: all 0.2s ease;
  cursor: pointer;

  @media (max-width: 767px) {
    width: 150px;
  }
}

.next-step {
  width: 160px;
  height: 45px;
  border: 1px solid rgba(58, 88, 249, 0.2);
  border-radius: 4px;
  font-family: 'Poppins', sans-serif;
  font-style: normal;
  font-weight: 300;
  line-height: normal;
  font-size: 16px;
  text-transform: capitalize;
  display: flex;
  color: #3a58f9;
  align-items: center;
  transition: all 0.2s ease;
  cursor: pointer;
  box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.14), 0 3px 3px -2px rgba(0, 0, 0, 0.2), 0 1px 8px 0 rgba(0, 0, 0, 0.12);

  &.active {
    background-color: #2743de;
    border: 1px solid rgba(58, 88, 249, 0.2);
    color: #fff;

    &:hover span {
      background: rgba(255, 255, 255, 0.1);
      border-color: rgba(255, 255, 255, 0.1);
    }

    span svg path {
      stroke: #fff;
    }
  }

  @media (max-width: 767px) {
    width: 150px;
  }
}

.previous-step span,
.next-step span {
  width: 45px;
  height: 45px;
  border: 1px solid #3a58f9;
  border-radius: 4px;
  display: flex;
}

.previous-step span svg,
.next-step span svg {
  margin: auto;
}

.previous-step span svg path,
.next-step span svg path {
  stroke: #3a58f9;
}

.previous-step span {
  margin-right: 18px;
}

.next-step {
  flex-direction: row-reverse;

  span {
    margin-left: 18px;
  }
}

.previous-step:hover,
.next-step:hover {
  background-color: #2743de;
  border: 1px solid rgba(58, 88, 249, 0.2);
  color: #fff;
}

.previous-step:hover span,
.next-step:hover span {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.1);
}

.previous-step:hover span svg path,
.next-step:hover span svg path {
  stroke: #fff;
}

a {
  text-decoration: none;
}

.animated-button {
  text-transform: none;
  letter-spacing: 0;
  display: flex;
  align-items: center;

  @media (max-width: 768px) {
    margin-bottom: 0;
  }
}

.back-button {
  margin-left: 0;
  margin-right: auto;
}
</style>
