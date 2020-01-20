<template>
  <div class="templates_area z-index-999" :class="{ active: activeSlider === 'templates' }">
    <div class="py-2 px-3">
      <div class="row align-items-center">
        <div class="col-10">
          <h5 class="mb-0 text-dark">
            <b>Templates</b>
          </h5>
        </div>
        <div class="col-2 text-right">
          <span class="bz-close-section-area text-dark cursor-pointer fs-20" @click.prevent="closeSlider()">
            <i class="mdi mdi-close"></i>
          </span>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'templates'" class="mt-3 px-3 w-100">
      <button v-if="isWebsite" class="btn bz-btn-default tw-text-sm w-100" @click="handleClickChangeTheme">Change Template</button>
      <template v-else>
        <bz-select v-model="templateCategory" :options="templateCategories" label-key="name" item-key="id" :auto-select="true" label="Categories">
          <template #selected="{ selected, placeholder }">
            <span>{{ selected?.name || placeholder }}</span>
          </template>
          <template #option="{ option }">
            <span>{{ option.name }}</span>
          </template>
        </bz-select>
        <template v-for="(template, index) in templates" :key="index">
          <div
            v-ripple
            class="template-item item cursor-pointer"
            :class="{ active: isTemplate ? template.id === $store.state.template.id : template.id === $store.state.template.template_id }"
            @click.prevent="chooseTemplate(template)"
          >
            {{ template.name }}
          </div>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'
import BzSelect from '@/public/BzSelect.vue'

export default {
  name: 'TemplateList',
  components: {
    BzSelect
  },
  mixins: [builderMixin],
  data() {
    return {
      templateId: null,
      templateCategory: null,
      templateCategoryId: null
    }
  },
  computed: {
    templateCategories() {
      return this.$store.state.templateCategories ?? []
    },
    templates() {
      // eslint-disable-next-line
      const selectedCategory = this.$store.state.templateCategories.find((cat) => cat.id == this.templateCategoryId)
      if (selectedCategory) {
        return selectedCategory.templates
      }
      return []
    }
  },
  watch: {
    templateCategory() {
      if (this.templateCategory) {
        this.templateCategoryId = this.templateCategory.id
      }
    }
  },
  mounted() {
    this.getTemplate()
  },
  methods: {
    handleClickChangeTheme() {
      this.$store.commit('openModal', {
        name: 'templateSwitchModal'
      })
    },
    getTemplate() {
      if (this.isTemplate) {
        this.templateCategory = this.$store.state.templateCategories.find((cat) => cat.id === this.$store.state.template.category_id)
        this.templateId = this.$store.state.template.id
      } else {
        this.templateId = this.$store.state.template.template_id
        this.templateCategory = this.$store.state.templateCategories.find((cat) => cat.id === this.$store.state.template.category_id)
      }
    },
    chooseTemplate(template) {
      this.getTemplate()
      if (this.templateId !== template.id) {
        if (this.isTemplate) {
          // need to reload page to get correct template data from backend
          location.href = '/admin/template/item/editPages/' + template.id
        } else {
          this.handleClickChangeTheme()
        }
      }
    },
    isActive(template) {
      if (this.isTemplate) {
        return this.$store.state.template.id === template.id
      }
      return this.$store.state.template.template_id === template.id
    }
  }
}
</script>

<style lang="scss">
$active: rgb(0, 118, 223);
.templates_area {
  width: 300px;
  height: calc(100vh - 60px);
  position: fixed;
  left: 70px;
  top: 60px;
  background-color: rgb(239, 240, 241);
  z-index: 3;
  overflow: hidden;
  transform: translateX(-370px);
  //transition: transform 0.3s linear;

  .bz-close-section-area {
    font-size: 26px;

    &::after {
      width: calc(100vw - 370px);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 370px;
      content: '';
      display: none;
    }
  }

  &.active {
    transform: translateX(0px);

    .bz-close-section-area {
      &::after {
        display: block;
      }
    }
  }

  .template-item {
    width: 100%;
    background-color: white;
    border-radius: 4px;
    padding: 10px;
    height: 40px;
    margin-top: 10px;
    box-shadow: 0 0 6px 3px #00000023;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;

    &.active {
      border: solid 2px #2196f3;
    }
  }
}
</style>
