<template>
  <nav class="z-99 w-100 bz-navbar tw-shadow-sm tw-bg-white">
    <div class="tw-grid tw-w-full tw-grid-cols-3 tw-px-3">
      <div class="tw-flex tw-flex-nowrap tw-items-center">
        <div class="nav-item">
          <button class="btn bz-btn-default-outline mr-1" @click="handleBack">Back</button>
        </div>
        <Menu as="div" class="tw-relative tw-inline-block tw-text-left">
          <menu-button
            class="tw-flex tw-items-center tw-w-full tw-justify-center tw-gap-x-1.5 tw-px-2 tw-text-sm tw-border tw-bg-gray-50 tw-text-gray-900 tw-py-1">
            Page: <b class="tw-text-capitalize">{{ activePage?.name }}</b>
            <i class="mdi mdi-chevron-down tw-text-lg"></i>
          </menu-button>
          <transition enter-active-class="tw-transition tw-ease-out tw-duration-100"
            enter-from-class="tw-transform tw-opacity-0 tw-scale-95"
            enter-to-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-active-class="tw-transition tw-ease-in tw-duration-75"
            leave-from-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-to-class="tw-transform tw-opacity-0 tw-scale-95">
            <menu-items
              class="tw-absolute tw-top-full tw-mt-1 tw-z-50 tw-w-48 origin-top-left tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-focus:outline-none">
              <template v-for="(page, index) of allPages" :key="page.id">
                <router-link v-if="isActivePage(page)" :to="page.url">
                  <div :key="index" class="tw-px-4 tw-py-2 hover:tw-gray-100 tw-cursor-pointer"
                    @click="setActivePage({ index })">
                    {{ page.name }}
                  </div>
                </router-link>
              </template>
              <hr />
              <div class="tw-px-4 tw-py-2 hover:tw-gray-100">
                <a href="javascript:void(0)" @click.prevent.stop="openPageSlider()">Manage pages</a>
              </div>
            </menu-items>
          </transition>
        </Menu>
        <div class="nav-item tw-flex px-2">
          <div
            class="mx-1 tw-h-7 tw-w-7 tw-rounded tw-bg-gray-100 tw-flex tw-justify-center tw-items-center tw-text-gray-400 tw-cursor-pointer"
            :class="{ 'tw-text-white !tw-bg-blue-400': canUndo }" @click.prevent="undo()">
            <i class="mdi mdi-undo tw-text-lg"></i>
          </div>
          <div
            class="mx-1 tw-h-7 tw-w-7 tw-rounded tw-bg-gray-100 tw-flex tw-justify-center tw-items-center tw-text-gray-400 tw-cursor-pointer"
            :class="{ 'tw-text-white !tw-bg-blue-400': canRedo }" @click.prevent="redo()">
            <i class="mdi mdi-redo tw-text-lg"></i>
          </div>
          <span class="active d-md-inline-block mx-1" @click.prevent="refresh">
            <i class="mdi mdi-refresh tw-text-lg"></i>
          </span>
        </div>
        <div class="nav-item d-flex align-items-center">
          <div @click="handleSave">
            <button class="btn bz-btn-default-outline d-flex align-items-center">
              Save
              <bz-spinner v-if="savingAllPages" style="margin-left: 5px" />
            </button>
          </div>
        </div>
      </div>
      <div class="tw-flex tw-justify-center tw-items-center tw-space-x-2">
        <button class="tw-rounded tw-p-0.5 tw-border" :class="{ 'tw-border-blue-500': viewMode === 'desktop' }"
          @click="viewMode = 'desktop'">
          <personal-video-icon :class="{ 'tw-text-blue-500': viewMode === 'desktop' }" />
        </button>
        <button class="tw-rounded tw-p-0.5 tw-border" :class="{ 'tw-border-blue-500': viewMode === 'tablet' }"
          @click="viewMode = 'tablet'">
          <tablet-android-icon :class="{ 'tw-text-blue-500': viewMode === 'tablet' }" />
        </button>
        <button class="tw-rounded tw-p-0.5 tw-border" :class="{ 'tw-border-blue-500': viewMode === 'mobile' }"
          @click="viewMode = 'mobile'">
          <phone-android-icon :class="{ 'tw-text-blue-500': viewMode === 'mobile' }" />
        </button>
      </div>
      <div class="tw-flex tw-justify-end">
        <ul class="mb-0 pl-0 list-style-none d-none d-md-flex flex-row justify-content-end">
          <li class="nav-item">
            <a class="btn btn-outline-info m-1" :href="previewUrl" target="_blank"> Preview </a>
          </li>
          <li v-if="isWebsite" class="nav-item">
            <button class="btn btn-info m-1 text-white d-flex align-items-center" @click="handlePublish">
              Publish
              <bz-spinner v-if="publishingContent" style="margin-left: 5px" />
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import BzSpinner from './BzSpinner.vue'
import builderMixin from '../../mixins/builderMixin'
import { publishTemplate, saveTemplate } from '../../apis'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import historyMixin from '../../mixins/historyMixin'
import PersonalVideoIcon from '@/section-builder/components/icons/PersonalVideoIcon.vue'
import TabletAndroidIcon from '@/section-builder/components/icons/TabletAndroidIcon.vue'
import PhoneAndroidIcon from '@/section-builder/components/icons/PhoneAndroidIcon.vue'
import eventBus from '@/public/eventBus'

export default {
  components: {
    PhoneAndroidIcon,
    TabletAndroidIcon,
    PersonalVideoIcon,
    BzSpinner,
    Menu,
    MenuButton,
    MenuItems
  },
  mixins: [builderMixin, historyMixin],
  data() {
    return {
      savingAllPages: false,
      publishingContent: false
    }
  },
  computed: {
    pages() {
      return this.allPages
    },
    previewUrl() {
      return this.$config.urls.websitePreviewURL
    }
  },
  watch: {
    viewMode() {
      eventBus.$emit('ReloadPageContent')
    }
  },
  methods: {
    isActivePage(page) {
      if (page.type === 'module') {
        return this.modules.activeModules.includes(page.module_name)
      }

      return page.type !== 'new-page' && page.url
    },
    refresh() {
      window.location.reload()
    },
    handleBack() {
      if (window.location.href.includes('/account/website/')) {
        window.location.href = '/account/website'
      } else if (window.location.href.includes('/account/template/item/')) {
        window.location.href = '/account/template/item'
      } else if (window.location.href.includes('/admin/website/')) {
        window.location.href = '/admin/website/list'
      } else {
        window.location.href = '/admin/template/item'
      }
    },
    handleSave() {
      this.savingAllPages = true
      saveTemplate(this.template).then(() => {
        toast.success('Successfully saved.')
        this.savingAllPages = false
        const index = window.location.href.split('/').findIndex((s) => s === 'editContent')
        window.location.href = window.location.href
          .split('/')
          .filter((_, i) => i < index + 2)
          .join('/')
      })
    },
    // after merged templates, check if the content has new sections(unchecked sections by owner), if has some, show confirm modal for that.
    handlePublish() {
      const noNewSections = this.template.pages.every((page) => page.sections.every((section) => !section?.data.setting.isNew || section?.category.module))
      if (noNewSections) {
        this.publishContent()
      } else {
        const _this = this
        _this.$dialog
          .confirm({
            title: 'Puglish Website',
            description: `
              <div>Are you sure you want to publish the website?</div>
              <div>Some sections haven't been edited since the template was merged.<div>
              <div class="tw-flex tw-justify-center tw-p-4">
                <img src="${_this.asset('assets/img/editor/images/new-section.png')}" width="480" class="tw-shadow" alt="New Section" />
              </div>
            `
          })
          .then(async (status) => {
            if (status) {
              _this.template.pages = _this.template.pages.map((page) => ({
                ...page,
                sections: page.sections.map((section) => {
                  if (section?.data.setting.isNew) delete section.data.setting.isNew
                  return section
                })
              }))
              this.publishingContent = true
              await saveTemplate(_this.template)
              _this.publishContent()
            }
          })
      }
    },
    async publishContent() {
      const _this = this
      _this.publishingContent = true

      try {
        const template = _this.template
        const hostName = new URL(window.location.href).hostname
        const isTemplate = window.config.isTemplate

        const redirectUrl = isTemplate ? '//' + template.slug + '.template.' + hostName : '//' + template.domain
        const status = isTemplate ? 1 : 'active'

        if (isTemplate && !template.image) {
          toast.warning('Please upload template preview image.')
          _this.openSettingSlider()
          _this.publishingContent = false
        } else {
          publishTemplate()
            .then((res) => {
              if (res.data.success) {
                _this.$store.state.template.status = status
                setTimeout(function () {
                  _this.publishingContent = false
                  window.open(redirectUrl, '_blank')
                }, 500)
              } else {
                toast.error('Failed to save!')
                _this.publishingContent = false
              }
            })
            .catch((err) => {
              _this.publishingContent = false
              console.log('updateContentUrl in NavBar.vue', err)
              toast.error('Failed to save!')
            })
        }
      } catch (error) {
        console.error(error)
        _this.publishingContent = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.bz-navbar {
  height: 60px;
  position: fixed;
  top: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}
</style>
