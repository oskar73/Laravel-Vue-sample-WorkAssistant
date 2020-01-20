<template>
  <modal v-model="showModal" :classes="['new-page-modal']" name="new-page-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="tw-flex p-3 justify-content-between">
      <h5>Add page</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <div class="tw-flex-col px-3 tw-flex justify-content-center align-items-center">
      <bz-input v-model="pageName" label="Page Name" />

      <div v-if="!!errorMessage" class="tw-border tw-w-full tw-bg-rose-100 tw-border-rose-600 tw-rounded-md tw-px-4 tw-py-2 tw-my-2">
        {{ errorMessage }}
      </div>
    </div>
    <hr />
    <div class="w-100 d-flex justify-content-end py-2">
      <button class="btn bz-btn-default mr-4 tw-flex tw-items-center" @click="onConfirm">
        <spinner v-if="loading" />
        Add Page
      </button>
    </div>
  </modal>
</template>

<script>
import { useRouter } from 'vue-router'
import BzInput from '../page/BzInput.vue'
import templateMixin from '../../mixins/templateMixin'
import rerenderMixin from '@/section-builder/mixins/rerenderMixin'
import { addNewPage } from '@/section-builder/apis'
import PageContent from '@/section-builder/components/page/PageContent.vue'
import Spinner from '@/public/Spinner.vue'

export default {
  components: { Spinner, BzInput },
  mixins: [templateMixin, rerenderMixin],
  setup() {
    const router = useRouter()

    return {
      router
    }
  },
  data() {
    return {
      pageName: '',
      showModal: false,
      errorMessage: '',
      loading: false
    }
  },
  mounted() {
    this.$modal.show('new-page-modal')
  },
  methods: {
    onClose() {
      this.$store.commit('closeModal')
    },
    async onConfirm() {
      this.errorMessage = ''
      if (this.pageName) {
        this.loading = true
        const res = await addNewPage(this.pageName, this.$config.urls.addPageUrl)
        if (res.data.status) {
          const newPage = res.data.data
          this.allPages = [...this.allPages, newPage]
          this.router.addRoute({
            path: newPage.url,
            name: newPage.name,
            component: PageContent
          })
          this.$store.commit('rerenderSettingPanel', true)
          this.onClose()

          await this.router.replace(newPage.url)
        } else {
          this.errorMessage = res.data.data[0]
        }
        this.loading = false
      } else {
        this.errorMessage = 'Please enter a name of page you are creating'
      }
    }
  }
}
</script>
<style lang="scss">
.new-page-modal {
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
