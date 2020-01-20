import sectionMixin from '@/section-builder/mixins/sectionMixin'

export default {
  data() {
    return {
      isLoggedIn: window.config.auth
    }
  },
  mixins: [sectionMixin],
  methods: {
    view() {
      if (!this.isBuilder) {
        window.location.href = '/account/appointment'
      }
    },
    schedule() {
      if (!this.isBuilder) {
        if (!this.isLoggedIn) {
          window.location.href = '/login'
          return
        }
        this.$store.commit('openModal', {
          name: 'newAppointmentModal'
        })
      }
    }
  }
}
