<template>
  <div>
    <new-appointment-modal v-if="checkOpenModal('newAppointmentModal')" />
  </div>
</template>

<script>
import NewAppointmentModal from '@/section-builder/components/modals/NewAppointmentModal.vue'

export default {
  name: 'ModalView',
  components: {
    NewAppointmentModal
  },
  methods: {
    checkOpenModal(modalName) {
      return this.$store.state.modals.openedModals.some((item) => item.name === modalName) || this.$store.state.modals.basic.name === modalName
    }
  }
}
</script>