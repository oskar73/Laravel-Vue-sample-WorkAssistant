<template>
  <modal :show="open" @close="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="p-3">
      <h5>Are you sure?</h5>
    </div>
    <p class="p-4" style="font-size: 14px">
      Are you sure you want to delete these images from My Images? This will also remove the images from your website in case they are being used.
    </p>
    <hr />
    <div class="w-100 d-flex justify-content-end">
      <button class="btn btn-default" @click="onClose()">No</button>
      <button class="btn btn-danger mr-4 d-flex align-items-center" @click="handleDelete">
        <span class="pl-2">{{ loading ? 'Deleting...' : 'Yes' }}</span>
      </button>
    </div>
  </modal>
</template>

<script>
import Modal from '@/public/Modal.vue'
export default {
  components: { Modal },
  props: {
    open: {
      type: Boolean,
      required: true
    },
    onClose: {
      type: Function,
      required: true
    },
    onConfirm: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      loading: false
    }
  },
  methods: {
    handleDelete() {
      this.loading = true
      this.onConfirm()
    }
  }
}
</script>

<style lang="scss">
.delete-my-images {
  max-width: 450px;
  height: 255px !important;
}
</style>
