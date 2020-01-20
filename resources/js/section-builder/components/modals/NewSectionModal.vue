<template>
  <modal v-model="showModal" :classes="['new-section-modal']" name="new-section-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
      <h5>Add Section</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="tw-px-3">
      <bz-select v-model="category" placeholder="Section Category" label="Category" :options="categories" :search="true">
        <template #selected="{ selected, placeholder }">
          <span>{{ selected?.name || placeholder }}</span>
        </template>
        <template #option="{ option }">
          <span>{{ option.name }}</span>
        </template>
      </bz-select>

      <div v-if="!!errorMessage" class="tw-border tw-w-full tw-bg-rose-100 tw-border-rose-600 tw-rounded-md tw-px-4 tw-py-2 tw-my-2">
        {{ errorMessage }}
      </div>
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end py-2">
      <button class="btn bz-btn-default mr-4 tw-flex tw-items-center" @click="onConfirm">
        <spinner v-if="loading" />
        Add Section
      </button>
    </div>
  </modal>
</template>

<script>
import BzSelect from '@/public/BzSelect.vue'
import builderMixin from '@/section-builder/mixins/builderMixin'
import templateMixin from '@/section-builder/mixins/templateMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'
import { addNewSectionToTemplate } from '@/section-builder/apis'
import Spinner from '@/public/Spinner.vue'

export default {
  components: { Spinner, BzSelect },
  mixins: [builderMixin, templateMixin, rerenderMixin],
  data() {
    return {
      category: null,
      showModal: false,
      errorMessage: '',
      loading: false,
      categories: []
    }
  },
  mounted() {
    this.categories = this.$store.state.allCategories.filter(cat => cat.slug !== 'header' && cat.slug !== 'footer').sort((a, b) => a.name - b.name)
    this.$modal.show('new-section-modal')
  },
  methods: {
    onClose() {
      this.$store.commit('closeModal')
    },
    async onConfirm() {
      this.errorMessage = ''
      if (this.category) {
        this.loading = true
        try {
          const res = await addNewSectionToTemplate({category: this.category.id})
          const newSection = {
            ...res.data.section,
            category: this.category,
            category_id: this.category.id,
          }
          for (let i = 0; i < this.$store.state.template.pages.length; i++) {
            this.$store.state.template.pages[i].sections.push({...newSection, page_id: this.$store.state.template.pages[i].sections[0].page_id})
          }
          this.onClose()
        } catch {
          this.errorMessage = 'Adding section failed'
        }
      } else {
        this.errorMessage = 'Please select a section category'
      }
    }
  }
}
</script>
<style lang="scss">
.new-section-modal {
  height: 200px !important;
  margin: 20px auto;
  top: 200px !important;
  max-width: 400px;
}

.vfm__content {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: white !important;
}
</style>
