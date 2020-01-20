<template>
  <modal :show="open" class="f_modal" @close="$emit('close')">
    <div class="tw-relative tw-bg-white tw-p-6 tw-rounded">
      <span class="tw-absolute tw-right-3 tw-top-3" @click="$emit('close')">
        <i class="mdi mdi-close tw-text-xl"></i>
      </span>
      <div class="tw-text-center tw-pt-6">
        <p>Would you like to save changes to your logo?</p>
        <div class="tw-flex tw-justify-center tw-items-center">
          <button :disabled="!canSave" class="button button-preview tw-mr-3" @click="exit">Cancel</button>
          <button :disabled="!canSave" class="button button-preview" @click="saveLogoWindow">Save</button>
        </div>
      </div>
    </div>
  </modal>
</template>
<script>
import eventBus from '@/public/eventBus'
import Modal from '@/public/Modal.vue'

export default {
  name: 'SaveConfirmModal',
  components: { Modal },
  props: {
    open: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      canSave: true
    }
  },
  methods: {
    exit() {
      this.$emit('close')
    },
    saveLogoWindow() {
      this.$emit('save')
    }
  }
}
</script>
