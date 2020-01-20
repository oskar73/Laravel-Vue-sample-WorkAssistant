<template>
  <modal v-model="showModal" :classes="['template-switch-modal', 'w-full']" name="template-switch-modal" @closed="onClose()">
    <template v-if="!isPreview">
      <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
        <h5 class="tw-text-xl tw-font-bold">Switch Template</h5>
        <div class="cursor-pointer" @click.prevent="onClose()">
          <i class="mdi mdi-close"></i>
        </div>
      </div>
      <div class="tw-flex tw-mx-auto tw-max-w-screen-2xl tw-gap-2 tw-w-full tw-p-2 tw-h-full">
        <div class="tw-w-80 tw-p-1">
          <div class="tw-relative tw-flex">
            <bz-input v-model="search" placeholder="Search..." :height="42" inputClassName="tw-pr-10" />
            <search-icon class="tw-absolute tw-top-2 tw-right-2" />
          </div>

          <div class="tw-mt-6 tw-text-base tw-font-medium">Filter by</div>
          <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-4 tw-mt-1">
            <button
              class="tw-rounded-full tw-border tw-border-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white tw-py-1 tw-px-2"
              :class="{ 'tw-bg-[#86bc42] tw-text-white': filter === 'featured', 'tw-text-[#86bc42]': filter !== 'featured' }"
              @click="handleSelectFilter('featured')"
            >
              Featured
            </button>
            <button
              class="tw-rounded-full tw-border tw-border-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white tw-py-1 tw-px-2"
              :class="{ 'tw-bg-[#86bc42] tw-text-white': filter === 'new', 'tw-text-[#86bc42]': filter !== 'new' }"
              @click="handleSelectFilter('new')"
            >
              New
            </button>
            <button
              class="tw-rounded-full tw-border tw-border-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white tw-py-1 tw-px-2"
              :class="{ 'tw-bg-[#86bc42] tw-text-white': filter === 'most_popular', 'tw-text-[#86bc42]': filter !== 'most_popular' }"
              @click="handleSelectFilter('most_popular')"
            >
              Most Popular
            </button>
          </div>

          <div class="tw-mt-6 tw-text-base tw-font-medium">Filter by Category</div>
          <ul class="tw-mb-4 tw-mt-1 tw-list-[circle] tw-pl-4 tw-text-base tw-space-y-2">
            <li :class="{ 'tw-text-[#86bc42]': !category }" class="hover:tw-text-[#86bc42] tw-cursor-pointer" @click="category = null">All Categories</li>
            <li
              :class="{ 'tw-text-[#86bc42]': category === cat.id }"
              class="hover:tw-text-[#86bc42] tw-cursor-pointer"
              @click="category = cat.id"
              v-for="cat in categories"
              :key="cat.id"
            >
              {{ cat.name }}
            </li>
          </ul>
        </div>
        <div class="tw-w-full tw-p-1 tw-overflow-y-auto tw-overflow-x-hidden tw-pb-[20px] tw-mb-[45px]">
          <div class="tw-grid tw-grid-cols-3 tw-gap-2" v-if="templates.length">
            <div
              class="tw-w-full tw-shadow-lg tw-text-[#5e6973] tw-rounded"
              :class="{ 'tw-border-2 tw-border-[#86bc42]': $store.state.template.template_id === template.id }"
              v-for="template in templates"
              :key="template.id"
            >
              <div class="tw-bg-gray-200 tw-w-full tw-pt-[66%] tw-flex tw-relative">
                <img :src="template.image" class="tw-rounded tw-absolute top-0 tw-w-full tw-h-full" :alt="template.name" v-if="template.image" />
              </div>
              <div class="tw-p-1.5 tw-mt-auto">
                <div class="tw-font-bold tw-text-lg">{{ template.name }}</div>
                <div class="tw-text-sm tw-text-gray-500">By Bizinabox</div>
                <div class="tw-flex tw-items-center tw-justify-end tw-gap-2" v-if="$store.state.template.template_id !== template.id">
                  <button
                    @click="useTemplate(template.id)"
                    :disabled="saveLoading"
                    class="tw-flex tw-items-center tw-text-sm tw-border tw-border-[#86bc42] tw-text-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white disabled:hover:tw-bg-[#86bc42] disabled:hover:tw-text-white tw-px-2 tw-py-1 tw-rounded"
                  >
                    <spinner v-if="saveLoading" />
                    Use Template
                  </button>
                  <button
                    @click="handlePreview(template.id)"
                    class="tw-flex tw-items-center tw-text-sm tw-border tw-border-[#86bc42] tw-text-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white disabled:hover:tw-bg-[#86bc42] disabled:hover:tw-text-white tw-px-2 tw-py-1 tw-rounded"
                  >
                    <spinner v-if="saveLoading" />
                    Preview
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="tw-text-base tw-text-center tw-py-12">No Templates</div>
        </div>
      </div>
    </template>

    <div v-else class="tw-w-full tw-h-full" style="background-color: rgb(246, 246, 246)">
      <div class="tw-flex tw-px-3 justify-content-between tw-bg-white">
        <div class="tw-flex tw-items-center tw-p-4 tw-gap-5 tw-ml-8">
          <div class="cursor-pointer" :class="{ 'tw-text-[#86bc42]': viewMode === 'desktop' }" @click="setViewMode('desktop')">
            <svg width="28" height="22" viewBox="0 0 28 22" xmlns="http://www.w3.org/2000/svg">
              <g fill="currentColor" fill-rule="evenodd">
                <path d="M11 18h1v4h-1z"></path>
                <path d="M9 21h10v1H9z"></path>
                <path d="M16 18h1v4h-1z"></path>
                <path
                  d="M1 3v13c0 1.11.891 2 1.996 2h22.008A2.004 2.004 0 0 0 27 16V3c0-1.11-.891-2-1.996-2H2.996A2.004 2.004 0 0 0 1 3zM0 3c0-1.657 1.35-3 2.996-3h22.008A2.994 2.994 0 0 1 28 3v13c0 1.657-1.35 3-2.996 3H2.996A2.994 2.994 0 0 1 0 16V3z"
                ></path>
              </g>
            </svg>
          </div>
          <div class="cursor-pointer" :class="{ 'tw-text-[#86bc42]': viewMode === 'tablet' }" @click="setViewMode('tablet')">
            <svg width="20" height="28" viewBox="0 0 20 28" xmlns="http://www.w3.org/2000/svg">
              <g fill="currentColor" fill-rule="evenodd">
                <path
                  d="M1 2.996v22.008C1 26.1 1.897 27 2.994 27h14.012c1.1 0 1.994-.895 1.994-1.996V2.996A2.001 2.001 0 0 0 17.006 1H2.994C1.894 1 1 1.895 1 2.996zm-1 0A2.997 2.997 0 0 1 2.994 0h14.012A3.001 3.001 0 0 1 20 2.996v22.008A2.997 2.997 0 0 1 17.006 28H2.994A3.001 3.001 0 0 1 0 25.004V2.996z"
                ></path>
                <path d="M9 23h2v2H9z"></path>
              </g>
            </svg>
          </div>
          <div class="cursor-pointer" :class="{ 'tw-text-[#86bc42]': viewMode === 'mobile' }" @click="setViewMode('mobile')">
            <svg width="12" height="22" viewBox="0 0 12 22" xmlns="http://www.w3.org/2000/svg">
              <g fill="currentColor" fill-rule="evenodd">
                <path
                  d="M1 3.001V19C1 20.105 1.894 21 2.997 21h6.006A2 2 0 0 0 11 18.999V3A1.999 1.999 0 0 0 9.003 1H2.997A2 2 0 0 0 1 3.001zm-1 0A3 3 0 0 1 2.997 0h6.006A2.999 2.999 0 0 1 12 3.001V19A3 3 0 0 1 9.003 22H2.997A2.999 2.999 0 0 1 0 18.999V3z"
                ></path>
                <path d="M5 18h2v2H5z"></path>
              </g>
            </svg>
          </div>
        </div>
        <div class="tw-flex tw-items-center tw-gap-4 tw-relative tw-z-50">
          <div class="tw-flex tw-gap-4 tw-items-center px-4">
            <div class="tw-text-lg">Do you want to keep your current theme?</div>
            <div class="tw-w-fit">
              <bz-switch :model-value="themeChange" @change="keepCurrentTheme" />
            </div>

            <div :key="system.id" v-for="system in themes" class="tw-group tw-inline-block tw-relative tw-flex tw-flex-col tw-gap-2 tw-py-2 tw-min-w-[192px] tw-w-fit">
              <bz-select v-model="selectedCategory[system.id]" :options="system.items" :placeholder="system.title" @update:modelValue="onChangeCategory($event, system.id)">
                <template #selected="{ selected, placeholder }">
                  <span v-if="selected?.title" class="tw-font-semibold">{{ selected.title }}</span>
                  <span v-else class="tw-text-gray-500">{{ placeholder }}</span>
                </template>
                <template #option="{ option }">
                  <span>{{ option.title }}</span>
                </template>
              </bz-select>
              <bz-select v-model="selectedTheme[system.id]" :options="selectedCategory[system.id]?.items || []" :search="true" @update:modelValue="onChangeTheme($event)">
                <template #selected="{ selected, placeholder }">
                  <span v-if="selected?.name" class="tw-font-semibold">{{ selected.name }}</span>
                  <span v-else class="tw-text-gray-500">{{ placeholder }}</span>
                </template>
                <template #option="{ option }">
                  <span>{{ option.name }}</span>
                </template>
              </bz-select>
            </div>
          </div>
          <button class="tw-border tw-border-[#17a2b8] tw-text-[#17a2b8] hover:tw-bg-[#17a2b8] hover:tw-text-white tw-px-2 tw-py-1 tw-rounded" @click="handleBack">Back</button>
          <button
            class="tw-flex tw-items-center tw-border tw-border-[#86bc42] tw-text-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white disabled:hover:tw-bg-white disabled:hover:tw-text-[#86bc42] tw-px-2 tw-py-1 tw-rounded"
            @click="confirmTemplate"
            :disabled="saveLoading"
          >
            <spinner v-if="saveLoading" />
            Use This Template
          </button>
        </div>
      </div>
      <div
        class="tw-mx-auto tw-w-full"
        :class="{ 'tw-max-w-screen-2xl': viewMode === 'desktop', 'tw-max-w-[1106px]': viewMode === 'tablet', 'tw-max-w-[400px]': viewMode === 'mobile' }"
      >
        <div class="tw-w-full tw-h-16 tw-bg-white tw-rounded-t-[46px] tw-shadow-lg tw-mt-2 tw-z-10 tw-relative">
          <div class="tw-w-6 tw-h-[3px] tw-rounded-[1.5px] tw-bg-[#eff1f2] tw-absolute tw-top-[23px] tw-left-[calc(50%-12px)]" />
        </div>
        <div
          class="tw-w-full tw-overflow-y-auto tw-overflow-x-hidden"
          :class="{ 'tw-h-[calc(100vh-208px)]': viewMode === 'desktop', 'tw-h-[580px]': viewMode === 'tablet', 'tw-h-[500px]': viewMode === 'mobile' }"
        >
          <page-layout viewOnly>
            <div class="tw-flex-1">
              <div v-if="loading" class="tw-flex tw-justify-center tw-items-center tw-h-36">
                <spinner class="tw-w-8 tw-h-8" />
              </div>
              <template v-for="(section, index) of sections">
                <component :is="section.name" v-if="section.data.setting.visible" :key="index" :properties="section" view-only :position="index" />
              </template>
            </div>
          </page-layout>
        </div>
        <div class="tw-w-full tw-h-16 tw-bg-white tw-rounded-b-[46px] tw-shadow-lg tw-mb-2 tw-z-10 tw-relative" />
      </div>
    </div>
  </modal>
</template>

<script>
import { cloneDeep } from 'lodash'

import PageLayout from '@/section-builder/build/components/PageLayout.vue'
import Spinner from '@/public/Spinner.vue'
import BzSelect from '@/public/BzSelect.vue'
import BzInput from '@/section-builder/components/page/BzInput.vue'
import BzSwitch from '@/section-builder/components/page/BzSwitch.vue'
import Themes from '@/section-builder/components/page/Theme/Themes.vue'
import SearchIcon from '@/section-builder/components/icons/Search.vue'

import templateMixin from '@/section-builder/mixins/templateMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'

import { getTemplate, saveTemplate } from '../../apis'

export default {
  components: { Spinner, BzSelect, BzInput, BzSwitch, PageLayout, Themes, SearchIcon },
  mixins: [templateMixin, rerenderMixin],
  props: {
    id: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      categories: [],
      category: null,
      search: '',
      allTemplates: [],
      filter: '',
      isPreview: false,
      loading: false,
      showModal: false,
      themeChange: false,
      newWebsite: null,
      newTemplate: null,
      viewMode: 'desktop',
      newPages: [],
      saveLoading: false,
      websiteTheme: null,
      selectedCategory: {
        1: null,
        2: null
      },
      selectedTheme: {
        1: null,
        2: null
      },
      currentTheme: null,
      templateTheme: null
    }
  },
  mounted() {
    this.categories = this.$store.state.templateCategories
    this.allTemplates = this.categories.reduce((acc, cat) => [...acc, ...cat.templates], [])
    this.websiteTheme = this.$store.state.template.data.theme
    this.themes.forEach((system) => {
      system.items.forEach((category) => {
        if (category.id === this.websiteTheme.category_id) {
          const theme = category.items.find(({ name }) => name === this.websiteTheme.name)
          if (theme) {
            this.websiteTheme.id = theme.id
          }
          this.currentTheme = { theme: this.websiteTheme, category, system: system.id }
        }
      })
    })
    this.$modal.show('template-switch-modal')
  },
  computed: {
    templates() {
      let templates = this.allTemplates
      if (this.category) {
        const selectedCategory = this.$store.state.templateCategories.find((cat) => cat.id == this.category)
        if (selectedCategory) {
          templates = selectedCategory.templates
        }
      }

      if (this.filter && this.filter !== 'most_popular') {
        return templates.filter((t) => t[this.filter])
      }

      return templates.filter((template) => template.name.toLowerCase().includes(this.search.toLowerCase()))
    },
    sections() {
      return this.newWebsite?.pages?.[this.$store.state.indexOfActiveViewPage || 0]?.sections || []
    },
    themes() {
      return [
        {
          id: 1,
          title: 'Admin Themes',
          items: this.$store.state.themeCategories
            .filter(({ user_id }) => !user_id)
            .map(({ id, name: title }) => ({
              id,
              title,
              items: this.$store.state.themes.filter(({ category_id, user_id }) => !user_id && category_id === id)
            }))
        },
        {
          id: 2,
          title: 'My Themes',
          items: this.$store.state.themeCategories
            .filter(({ user_id }) => user_id)
            .map(({ id, name: title }) => ({
              id,
              title,
              items: this.$store.state.themes.filter(({ category_id, user_id }) => user_id && category_id === id)
            }))
        }
      ]
    }
  },
  methods: {
    onChangeTheme(theme) {
      if (this.currentTheme?.theme.id !== theme.id) this.themeChange = false
      this.$store.commit('updateThemePreview', theme.data)
    },
    onChangeCategory(value, id) {
      if (this.selectedTheme?.[id]?.category_id === value.id) return
      if (this.currentTheme?.category.id !== value.id) {
        this.themeChange = false
        this.$store.commit('updateThemePreview', this.newTemplate.data.theme)
      }

      const defaultValue = {
        1: null,
        2: null
      }

      this.selectedTheme = {
        ...defaultValue
      }

      this.selectedCategory = {
        ...defaultValue,
        [id]: value
      }
    },
    handleBack() {
      this.isPreview = false
      this.$store.commit('updateThemePreview', null)
    },
    handleSelectFilter(filter) {
      if (this.filter === filter) this.filter = ''
      else this.filter = filter
    },
    onClose() {
      this.$store.commit('closeModal')
    },
    mergeSections(sections, currentSections, pageIndex) {
      let paletteIndex = this.websiteTheme.palettes.findIndex((p) => p.appliedTo === 'section' && p.applies?.[pageIndex]?.length)
      const appliedPaletteSections = paletteIndex === -1 ? null : this.websiteTheme.palettes[paletteIndex].applies[pageIndex]
      const newSections = []
      const paletteSections = []

      const ids = []
      for (let i = 0; i < sections.length; i++) {
        let newSection,
          webSectionIndex = -1
        if (sections[i].data.setting.visible) {
          webSectionIndex = currentSections.findIndex(
            (cs) => !ids.includes(cs.id) && cs.category_id === sections[i].category_id && cs.data.setting.visible === sections[i].data.setting.visible
          )
        }
        if (webSectionIndex === -1) webSectionIndex = currentSections.findIndex((cs) => !ids.includes(cs.id) && cs.category_id === sections[i].category_id)
        if (webSectionIndex !== -1) {
          const webSection = currentSections[webSectionIndex]
          newSection = {
            ...webSection,
            name: sections[i].name,
            data: {
              ...sections[i].data,
              data: webSection.data.data,
              setting: {
                ...sections[i].data.setting,
                visible: sections[i].data.setting.visible || webSection.data.setting.visible
              }
            }
          }
          if (!webSection.data.setting.visible && sections[i].data.setting.visible) {
            newSection.data.setting.isNew = true
          }
          ids.push(webSection.id)

          if (appliedPaletteSections && appliedPaletteSections.includes(webSectionIndex)) {
            paletteSections.push(i)
          }
        } else {
          newSection = { ...sections[i] }
          if (newSection.data.setting.visible) newSection.data.setting.isNew = true
        }

        newSections.push(newSection)
      }

      if (appliedPaletteSections) {
        this.websiteTheme.palettes[paletteIndex].applies[pageIndex] = paletteSections
      }

      return newSections
    },
    mergeTemplate(template) {
      this.newPages = []
      const website = this.$store.state.template
      this.newWebsite = {
        ...website,
        template_id: template.id,
        data: {
          ...website.data,
          header: template.data.header,
          footer: template.data.footer
        }
      }

      let pages = []
      const websitePages = website.pages
      const templatePages = template.pages
      for (let i = 0; i < websitePages.length; i++) {
        if (websitePages[i].type === 'new-page') {
          const newPage = template.pages.find((page) => page.type === 'new-page')
          if (newPage) pages.push({ ...newPage, id: websitePages[i]?.id || null })
          else pages.push(websitePages[i])
          continue
        }

        const index = templatePages.findIndex((p) => p.url === websitePages[i].url)
        if (index === -1) {
          pages.push(websitePages[i])
          continue
        }

        const templatePage = templatePages[index]
        const sections = this.mergeSections(templatePage.sections, websitePages[i].sections, i)
        pages.push({
          ...websitePages[i],
          sections
        })
        templatePages.splice(index, 1)
      }
      this.newPages = templatePages.filter((page) => page.type !== 'new-page').map((page) => page.name)
      this.newWebsite.pages = [
        ...pages,
        ...templatePages
          .filter((page) => page.type !== 'new-page')
          .map((page) => ({
            ...page,
            id: null
          }))
      ]
    },
    async confirmTemplate() {
      const _this = this
      if (_this.newPages.length) {
        _this.$store.commit('openModal', {
          name: 'templateSwitchConfirmModal',
          pages: this.newPages,
          onConfirm: (canceledPages) => {
            canceledPages.forEach((page) => {
              const pageIndex = _this.newWebsite.pages.findIndex((p) => p.name === page)
              if (pageIndex) _this.newWebsite.pages.splice(pageIndex, 1)
            })
            _this.$store.commit('closeModal')
            _this.switchTemplate()
          }
        })
      } else {
        const willSaveUserTemplate = await this.$dialog.confirm({
          title: 'Save as a template?',
          description: 'Do you want to save this website as a template?'
        })
        if (willSaveUserTemplate) {
          this.$store.commit('openModal', {
            name: 'newTemplateModal',
            onConfirm: ({ saveSuccess }) => {
              if (saveSuccess) _this.switchTemplate()
            }
          })
        } else {
          _this.switchTemplate()
        }
      }
    },
    switchTemplate() {
      const index = window.location.href.split('/').findIndex((s) => s === 'editContent')
      window.location.href = window.location.href
        .split('/')
        .filter((_, i) => i < index + 2)
        .join('/')
    },
    setViewMode(mode) {
      this.viewMode = mode
    },
    setTemplateTheme() {
      const defaultValue = {
        1: null,
        2: null
      }
      this.selectedCategory = {
        ...defaultValue,
        ...(this.templateTheme?.category || {})
      }
      this.selectedTheme = {
        ...defaultValue,
        ...(this.templateTheme?.theme || {})
      }
    },
    getTemplateTheme(theme) {
      this.themes.forEach((system) => {
        system.items.forEach((category) => {
          if (category.id === theme.category_id) {
            const currentTheme = category.items.find(({ id }) => id === theme.id)
            this.templateTheme = {
              category: {
                [system.id]: category
              },
              theme: {
                [system.id]: currentTheme
              }
            }
            this.setTemplateTheme()
          }
        })
      })
    },
    keepCurrentTheme(keep) {
      if (typeof keep !== 'boolean') return

      this.themeChange = keep
      this.$store.commit('updateThemePreview', keep ? this.websiteTheme : this.newTemplate.data.theme)

      const defaultValue = {
        1: null,
        2: null
      }
      if (keep) {
        this.selectedCategory = {
          ...defaultValue,
          [this.currentTheme?.system || 1]: this.currentTheme?.category || null
        }
        this.selectedTheme = {
          ...defaultValue,
          [this.currentTheme?.system || 1]: this.currentTheme?.theme || null
        }
      } else {
        this.setTemplateTheme()
      }
    },
    async handlePreview(id) {
      this.isPreview = true
      this.loading = true
      this.themeChange = false
      this.selectedCategory = {
        1: null,
        2: null
      }
      this.selectedTheme = {
        1: null,
        2: null
      }
      this.templateTheme = null
      const res = await getTemplate(id)
      if (res.data.status) {
        const template = res.data.data.template
        this.newTemplate = cloneDeep(template)
        if (this.newTemplate.data.theme.id) {
          this.getTemplateTheme(this.newTemplate.data.theme)
        }
        this.$store.commit('updateThemePreview', this.newTemplate.data.theme)
        this.mergeTemplate(template)
        this.$store.commit(
          'updateThemePreviewPages',
          this.newWebsite.pages
            .filter((page) => page.type !== 'new-page')
            .map((page) => ({
              name: page.name,
              type: page.type,
              url: page.url
            }))
        )
        this.loading = false
      }
    },
    async useTemplate(id) {
      this.saveLoading = true
      const res = await getTemplate(id)

      if (res.data.status) {
        const template = res.data.data.template
        this.newTemplate = cloneDeep(template)
        this.$store.commit('updateThemePreview', this.newTemplate.data.theme)
        this.mergeTemplate(template)
        this.confirmTemplate()
      }
      this.saveLoading = false
    }
  }
}
</script>
<style lang="scss">
.vfm__content {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: white !important;
}
</style>
