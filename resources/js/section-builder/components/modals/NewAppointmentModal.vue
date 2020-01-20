<template>
  <modal
    v-model="showModal"
    :classes="['tw-max-w-lg tw-absolute !tw-top-1/2 !tw-left-1/2 -tw-translate-x-1/2 -tw-translate-y-1/2']"
    name="new-appointment-modal"
    @closed="onClose()"
  >
    <div class="tw-flex justify-content-between tw-border-b tw-items-center tw-p-6">
      <h5 class="tw-text-lg tw-text-gray-700">Appointment date and time</h5>
      <div class="cursor-pointer" @click.prevent="onClose()">
        <i class="mdi mdi-close"></i>
      </div>
    </div>
    <form @submit="onSubmit">
      <div class="tw-flex tw-flex-col tw-gap-4 tw-px-6 tw-border-b tw-py-4">
        <div>
          <div>Category</div>
          <bz-select v-model="category" :options="categories" required />
        </div>
        <div>
          <div>Date</div>
          <date-picker v-model="date" :enable-time-picker="false" required />
        </div>
        <div>
          <div>Time</div>
          <date-picker v-model="time" time-picker range disable-time-range-validation required />
        </div>
        <div>
          <div>Reason</div>
          <textarea v-model="reason" class="tw-w-full border border-gray-300 rounded" />
        </div>
        <div>
          <div>Description</div>
          <textarea v-model="description" class="tw-w-full border border-gray-300 rounded" />
        </div>
      </div>
      <div class="w-100 d-flex justify-content-end py-3 tw-px-6 tw-gap-2">
        <button type="button" class="tw-px-3 tw-py-2 tw-border tw-border-gray-300 rounded tw-flex tw-items-center hover:tw-bg-gray-100 tw-font-semibold" @click="onClose">
          Cancel
        </button>
        <button type="submit" :disabled="loading" class="tw-px-3 tw-py-2 tw-bg-blue-500 tw-text-white rounded tw-flex tw-items-center hover:tw-bg-blue-600 tw-font-semibold">
          <spinner v-if="loading" />
          Submit
        </button>
      </div>
    </form>
  </modal>
</template>

<script>
import BzInput from '../page/BzInput.vue'
import BzSelect from '../page/BzSelect.vue'
import templateMixin from '../../mixins/templateMixin'
import { addNewAppointment } from '@/section-builder/apis'
import Spinner from '@/public/Spinner.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

export default {
  components: { Spinner, BzInput, BzSelect },
  mixins: [templateMixin],
  data() {
    return {
      category: '',
      date: '',
      time: '',
      reason: '',
      description: '',
      showModal: false,
      errorMessage: '',
      loading: false,
      categories: []
    }
  },
  mounted() {
    this.$modal.show('new-appointment-modal')
  },
  created() {
    const categories = this.$store.state.modules.appointment?.categories || []
    this.categories = categories.map(({ id, name }) => ({ label: name, value: id }))
  },
  methods: {
    onClose() {
      this.$store.commit('closeModal')
    },
    async onSubmit(e) {
      e.preventDefault()

      if (!this.category) {
        return toast.info('Please select category!', { position: toast.POSITION.TOP_CENTER })
      }

      // TODO: should consider time zone with site admin.
      const data = {
        category_id: this.category,
        date: new Date(this.date).toLocaleDateString(),
        start: `${this.time[0].hours}:${this.time[0].minutes}`,
        end: `${this.time[1].hours}:${this.time[1].minutes}`,
        reason: this.reason,
        description: this.description
      }

      this.loading = true
      try {
        const res = await addNewAppointment(data)
        if (res.data.status) {
          window.location.href = '/account/appointment'
        } else {
          toast.error(Object.values(res.data.data)?.[0] || 'Appointment Creation Failed!', { position: toast.POSITION.TOP_CENTER })
        }
      } catch {
        this.loading = false
        this.onClose()
      }
    }
  }
}
</script>
<style lang="scss">
.vfm__content {
  height: min-content;
  gap: 16px;
  background: white;
}
</style>
