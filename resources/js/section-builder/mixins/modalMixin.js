export default {
  props: {
    open: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      data: {}
    }
  },
  mounted() {
    if (this.$store) {
      this.data = this.$store.state.modals.basic.data ?? this.data ?? {}
    }
  },
  methods: {
    onClose() {
      this.$emit('close')
      this.$store?.commit('closeModal')
    },
    onConfirm(value) {
      this.$emit('confirm', value ?? this.data)
      if (this.$store.state.modals.basic.onChange) {
        this.$store.state.modals.basic.onChange(value ?? this.data)
      }
    }
  }
}
