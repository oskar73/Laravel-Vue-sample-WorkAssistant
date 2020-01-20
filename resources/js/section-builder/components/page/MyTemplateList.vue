<template>
  <div class="templates_area custom-scroll-h z-index-999" :class="{ active: activeSlider === 'myTemplates' }">
    <div class="py-2 px-3">
      <div class="row align-items-center">
        <div class="col-10">
          <h5 class="mb-0 text-dark">
            <b>My Templates</b>
          </h5>
        </div>
        <div class="col-2 text-right">
          <span class="bz-close-section-area text-dark cursor-pointer fs-20" @click.prevent="closeSlider()">
            <i class="mdi mdi-close"></i>
          </span>
        </div>
      </div>
    </div>
    <div v-if="activeSlider === 'myTemplates'" class="mt-3 px-3 w-100">
      <div class="tw-w-full tw-p-1">
        <div v-if="userTemplates.length">
          <div
            v-for="template in userTemplates"
            :key="template.id"
            class="tw-w-full tw-shadow-md tw-text-[#5e6973] tw-rounded my-4 bg-white"
            :class="[isCurrentTemplate(template.id) ? 'tw-border-2 tw-border-[#86bc42]' : 'tw-border-0 tw-border-t-2 tw-border-[grey]']"
          >
            <div class="tw-p-1.5 tw-mt-auto" :class="{ 'tw-py-4': isCurrentTemplate(template.id) }">
              <div>
                Template Name:
                <span class="tw-font-bold tw-text-lg">{{ template.name }}</span>
              </div>
              <div>
                Created At:
                <span class="tw-font-bold tw-text-lg">{{ formatCreatedAt(template.created_at) }}</span>
              </div>
              <div v-if="!isCurrentTemplate(template.id)" class="tw-flex tw-items-center tw-justify-end tw-gap-2">
                <button
                  class="tw-flex tw-items-center tw-text-sm tw-border tw-border-[#86bc42] tw-text-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white disabled:hover:tw-bg-[#86bc42] disabled:hover:tw-text-white tw-px-2 tw-py-1 tw-rounded"
                  :disabled="isDeleting"
                  @click="onChangeTemplate(template)"
                >
                  Use Template
                </button>
                <button
                  class="tw-flex tw-items-center tw-text-sm tw-border tw-border-[#86bc42] tw-text-[#86bc42] hover:tw-bg-[#86bc42] hover:tw-text-white disabled:hover:tw-bg-[#86bc42] disabled:hover:tw-text-white tw-px-2 tw-py-1 tw-rounded"
                  :disabled="isDeleting"
                  @click="onPreview(template)"
                >
                  Preview
                </button>
                <button
                  class="tw-flex tw-items-center tw-text-sm btn btn-outline-danger btn-sm m-btn m-btn--icon disabled:hover:tw-text-white tw-rounded"
                  :disabled="isDeleting"
                  @click="onDelete(template.id)"
                >
                  <spinner v-if="isDeleting" />
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="tw-text-base tw-text-center tw-py-12">No Templates</div>
      </div>
    </div>
  </div>
</template>

<script>
import builderMixin from '../../mixins/builderMixin'
import Spinner from '@/public/Spinner.vue'
import moment from 'moment'

export default {
  name: 'MyTemplateList',
  components: {
    Spinner
  },
  mixins: [builderMixin],
  data() {
    return {
      templateId: null,
      templateCategory: null,
      templateCategoryId: null,
      isDeleting: false
    }
  },
  computed: {
    userTemplates() {
      return this.$store.state.userTemplates ?? []
    }
  },
  methods: {
    isCurrentTemplate(id) {
      return id === Number(this.$route.query.templateId)
    },
    formatCreatedAt(date) {
      return moment(date).format('YYYY-MM-DD HH:mm:ss')
    },
    onPreview(template) {
      this.$store.commit('openModal', {
        name: 'UserTemplatePreviewModal',
        templateName: template.name,
        templateId: template.id
      })
    },
    async onChangeTemplate(template) {
      const willSaveUserTemplate = await this.$dialog.confirm({
        title: 'Save as a template?',
        description: 'Do you want to save this website as a template?'
      })
      if (willSaveUserTemplate) {
        this.$store.commit('openModal', {
          name: 'newTemplateModal',
          onConfirm: ({ saveSuccess }) => {
            if (saveSuccess) {
              window.location.href = `${window.config.baseUrl}/website/editContent/${template.web_id}?templateId=${template.id}`
            }
          }
        })
      } else {
        window.location.href = `${window.config.baseUrl}/website/editContent/${template.web_id}?templateId=${template.id}`
      }
    },
    async onDelete(templateId) {
      this.isDeleting = true
      await this.$store.dispatch('deleteUserTemplate', templateId)
      this.isDeleting = false
    }
  }
}
</script>

<style lang="scss">
$active: rgb(0, 118, 223);
.templates_area {
  width: 300px;
  position: fixed;
  left: 70px;
  top: 60px;
  background-color: rgb(239, 240, 241);
  z-index: 3;
  //transform: translateX(-370px);
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
