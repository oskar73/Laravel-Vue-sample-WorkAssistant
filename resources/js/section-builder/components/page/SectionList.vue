<template>
  <div class="sections_area z-index-999" :class="{ active: activeSlider === 'sections' }">
    <div class="section_heading py-2 px-3">
      <div class="tw-flex tw-items-center">
        <div class="col-4">
          <h5 class="mb-0 text-dark">
            <b>Sections ({{ activeSlider }})</b>
          </h5>
        </div>
        <div class="col-6">
          <input type="text" class="form-control tw-w-full" placeholder="Search sections" />
        </div>
        <div class="col-2 text-right">
          <div class="text-dark cursor-pointer" @click.prevent="closeSlider()">
            <i class="mdi mdi-close tw-text-xl"></i>
          </div>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'sections'" class="section_content h-100 d-flex">
      <div class="section_category_area w-50 p-3 preview_content_ul custom-scroll-h scroll-w-5">
        <div v-if="moduleCategories && isWebsite">
          <p class="heading mb-0 fs-18px font-weight-bold pb-2">Modules</p>
          <ul class="ml-0 pl-2 list-style-none">
            <li v-for="(cat, key1) in moduleCategories" :key="key1" class="side_category_item">
              <a href="" class="mb-1" :class="{ active: activeCategory?.id === cat.id }"
                @click.prevent="handleCategoryClick(cat)">
                {{ cat.name }}
              </a>
            </li>
          </ul>
        </div>
        <div v-if="recommendedCategories.length">
          <p class="heading mb-0 fs-18px font-weight-bold pb-2">Recommended</p>
          <ul class="ml-0 pl-2 list-style-none">
            <li v-for="(cat, key1) in recommendedCategories" :key="key1" class="side_category_item">
              <a href="" class="mb-1" :class="{ active: activeCategory?.id === cat.id }"
                @click.prevent="handleCategoryClick(cat)">
                {{ cat.name }}
              </a>
            </li>
          </ul>
        </div>
        <p class="heading mb-0 fs-18px font-weight-bold pb-2">All categories</p>
        <ul class="ml-0 mb-0 pl-2 list-style-none">
          <template v-for="(cat, key2) in restCategories" :key="key2">
            <li class="side_category_item">
              <a href="" class="mb-1" :class="{ active: activeCategory?.id === cat.id }"
                @click.prevent="handleCategoryClick(cat)">{{ cat.name }}</a>
            </li>
          </template>
        </ul>
      </div>
      <div class="section_category_preview_area w-100 position-relative">
        <div
          v-if="!isModuleActive && ((activeCategory.slug === 'product' && !paymentRequired) || activeCategory.slug !== 'product')"
          class="p-2 text-center">
          <p>
            <b>{{ activeCategory.name }}</b> module is not published, please publish the module by clicking following
            link
          </p>
          <button class="btn bz-btn-default" @click="activateModule">Activate {{ activeCategory.name }} Module</button>
          <p class="pt-2">or Visit <a :href="`${domain}/admin/module`"
              class="tw-cursor-pointer tw-text-blue-500 tw-underline" target="_blank">Module Management </a></p>
        </div>
        <div class="p-2 text-center" v-else-if="paymentRequired">
          <p>
            You have to link your payment to activate this <b>{{ activeCategory.name }}</b>, please visit
            <a :href="`${domain}/admin/ecommerce/setting`" class="tw-cursor-pointer tw-text-blue-500 tw-underline"
              target="_blank"> Payment Setting Page</a>
          </p>
        </div>
        <div class="tw-flex tw-h-full" :style="sectionListStyle">
          <template v-if="categorySections.length">
            <div class="tw-absolute tw-w-[1440px] tw-space-y-5 tw-p-10 tw-overflow-y-auto"
              style="transform: scale(0.225); transform-origin: top left; height: 420%">
              <template v-for="(section, key3) in categorySections" :key="key3">
                <div class="cursor-pointer" :class="{ 'tw-border-4 tw-border-blue-500': isSectionActive(section) }"
                  @click.prevent.stop="handleSectionClick(section)">
                  <div class="tw-pointer-events-none">
                    <component :is="section.name" :preview="true" :position="activePosition" :page-data="activePageData"
                      :properties="section" />
                  </div>
                </div>
              </template>
            </div>
          </template>
          <template v-else>
            <li class="w-100">
              <span>No sections</span>
            </li>
          </template>
        </div>
        <div v-if="isLimitSection" class="p-2 text-center position-absolute w-100" style="top: 30%">
          <b>Maximum {{ activeCategory?.name }} sections added to your website, please add a different section.</b>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'
import axios from 'axios'
import eventBus from '@/public/eventBus'
import 'vue3-toastify/dist/index.css'

export default {
  mixins: [builderMixin],
  data() {
    return {
      recommendedCategories: [],
      restCategories: [],
      categorySections: [],
      activeCategory: null,
      dragData: {},
      prevActivePosition: null,
      selectedSection: {},
      availableSection: null,
      isLimitSection: false,
      paymentRequired: false
    }
  },
  computed: {
    isModuleActive() {
      if (this.activeCategory?.module) {
        return this.modules.activeModules.includes(this.activeCategory.module)
      }
      return true
    },
    // isReachedSectionLimit() {
    //   return this.activeSections.filter((s) => s && s.category_id === this.activeCategory?.id && !s.data.setting.visible).length === 0
    // },
    sectionListStyle() {
      if (!this.isModuleActive || this.isLimitSection) {
        return {
          opacity: 0.3,
          pointerEvents: 'none'
        }
      }
      return {}
    }
  },
  watch: {
    activeSlider() {
      if (typeof this.activePosition === 'number') {
        this.getCategorySections(this.activeCategory)
        this.isLimitSection =
          this.activeSections.filter((s) => s && s.category.slug === this.activeCategory?.slug && s.data.setting.visible).length >= this.activeCategory.limit_per_page
      }
    },
    activePosition(value) {
      if (typeof value === 'number') {
        this.getCategorySections(this.activeCategory)
        this.isLimitSection =
          this.activeSections.filter((s) => s && s.category.slug === this.activeCategory?.slug && s.data.setting.visible).length >= this.activeCategory.limit_per_page
      }
    }
  },
  mounted() {
    const recommendedCategories = []
    const restCategories = []

    for (const category of this.allCategories) {
      if (!category.module) {
        if (category.recommended) {
          recommendedCategories.push(category)
        } else {
          restCategories.push(category)
        }
      }
    }
    this.recommendedCategories = recommendedCategories
    this.restCategories = restCategories
    this.activeCategory = this.restCategories[2]
    this.categorySections = this.getCategorySections(this.activeCategory)
  },
  methods: {
    getCategorySections(category) {
      if (category) {
        if (category.slug === 'header') {
          return category.sections.map((section) => {
            return {
              category_id: category.id,
              category_slug: category.slug,
              category,
              page_id: this.activePage.id,
              name: section.name,
              data: {
                setting: section.data.setting,
                data: this.header.data.data,
                background: this.header.data.background
              }
            }
          })
        } else if (category.slug === 'footer') {
          return category.sections.map((section) => {
            return {
              category_id: category.id,
              category_slug: category.slug,
              category,
              page_id: this.activePage.id,
              name: section.name,
              data: {
                setting: section.data.setting,
                data: this.footer.data.data,
                background: this.footer.data.background
              }
            }
          })
        } else if (this.activePage?.sections) {
          this.availableSection = this.activePage.sections.find((section) => section?.category.slug === category.slug)
          if (this.availableSection) {
            return category.sections.map((section) => {
              return {
                category_id: category.id,
                category_slug: category.slug,
                category,
                page_id: this.activePage.id,
                name: section.name,
                data: {
                  setting: section.data.setting,
                  data: this.availableSection.data.data,
                  background: this.availableSection.data.background
                }
              }
            })
          }
        }
      }
      return []
    },
    handleCategoryClick(cat) {
      this.activeCategory = cat
      this.categorySections = this.getCategorySections(cat)
      if (cat.name === 'Header') {
        if (this.prevActivePosition === null) {
          this.prevActivePosition = this.activePosition
        }
        this.activePosition = 'header'
        this.isLimitSection = false
      } else if (cat.name === 'Footer') {
        if (this.prevActivePosition === null) {
          this.prevActivePosition = this.activePosition
        }
        this.activePosition = 'footer'
        this.isLimitSection = false
      } else {
        if (this.prevActivePosition !== null) {
          this.activePosition = this.prevActivePosition
          this.prevActivePosition = null
        }
        this.isLimitSection =
          this.activeSections.filter((s) => s && s.category.slug === this.activeCategory?.slug && s.data.setting.visible).length >= this.activeCategory.limit_per_page
      }
    },
    isSectionActive(section) {
      if (section.name.includes('Header')) {
        return section.name === this.header.name
      }
      if (section.name.includes('Footer')) {
        return section.name === this.footer.name
      }
      if (this.activeSection) {
        return this.activeSection.name === section.name
      } else {
        return this.selectedSection?.name === section.name
      }
    },
    handleSectionClick(section) {
      this.selectedSection = section
      if (this.activeCategory.name === 'Header' || this.activeCategory.name === 'Footer') {
        if (this.activeCategory.name === 'Header') {
          this.header.data.setting = section.data.setting
          this.header.data.background = section.data.background
          this.header.name = section.name
          this.activePosition = 'header'
        } else {
          this.footer.data.setting = section.data.setting
          this.footer.data.background = section.data.background
          for (const key of Object.keys(section.data.data)) {
            if (!this.footer.data.data[key]) {
              this.footer.data.data[key] = section.data.data[key]
            }
          }
          this.footer.name = section.name
          this.activePosition = 'footer'
        }
        eventBus.$emit('scrollToActiveSection')
      } else {
        if (this.availableSection) {
          if (this.visibleSections.length === 0) {
            this.activePosition = 0
          }
          this.activeSections[this.activePosition] = {
            ...section,
            data: {
              data: this.availableSection.data.data,
              setting: {
                ...section.data.setting,
                visible: true
              },
              background: section.data.background
            }
          }
          const availableIndex = this.activeSections.indexOf(this.availableSection)
          if (availableIndex > -1) {
            this.activeSections.splice(availableIndex, 1)
          }
        }
      }

      eventBus.$emit('SectionLayoutChanged')
    },
    // Publish module, so it can be available in the builder.
    activateModule() {
      const self = this
      this.$dialog.confirm().then((res) => {
        if (res) {
          const activateModuleUrl = this.$config.urls.activateModule
          if (!activateModuleUrl) throw 'activateModuleUrl is undefined'
          axios
            .post(activateModuleUrl, {
              module: this.activeCategory.module
            })
            .then((res) => {
              if (res.data.status) {
                self.$store.commit('updateActiveModule', res.data.data.module.slug)
                self.$toast.success(this.activeCategory.name + ' module is active now')
              } else {
                if (res.data.action === 'payment') {
                  self.paymentRequired = true
                  return
                }
                console.error('activateModule: ', res.data)
              }
            })
            .catch((error) => {
              console.error('activateModule: ', error)
            })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.sections_area {
  position: fixed;
  top: 60px;
  height: calc(100vh - 60px);
  background-color: #eff0f1;
  overflow: hidden;
  width: 500px;
  left: 70px;
  z-index: 3;
  transform: translateX(-570px);
  //transition: transform 0.3s linear;

  .bz-close-section-area {
    font-size: 26px;

    &::after {
      width: calc(100vw - 570px);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 570px;
      background-color: red;
      display: none;
    }
  }

  &.active {
    transform: translateX(0);

    .bz-close-section-area {
      &::after {
        display: block;
      }
    }
  }

  .section_heading {
    border-bottom: 1px solid #d7d8d8;
  }

  .section_content {
    .preview_content_ul {
      max-height: calc(100vh - 130px);
    }

    .section_category_area {
      border-right: 1px solid #d7d8d8;

      .side_category_item {
        a {
          color: #000;
          padding: 5px 10px;
          display: block;
          border-radius: 4px;

          &:hover,
          &.active {
            text-decoration: none;
            background-color: #d9dee3;
          }
        }
      }
    }
  }
}

.section_preview_item {
  display: flex;
  border: 2px solid transparent;
  margin-bottom: 10px;
  box-shadow: none;
  transition: 0.3s;
  width: 300px;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  position: relative;
  outline: solid 1px #8080807f;

  &.active {
    outline: solid 2px var(--bz-edit-active);
  }

  .section-preview-base {
    width: 1400px;
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: scale(0.21428571428571428);
    height: min-content;
  }
}
</style>
