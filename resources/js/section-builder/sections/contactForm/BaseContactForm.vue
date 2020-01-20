<script>
import axios from 'axios'

export default {
  data() {
    return {
      loading: false
    }
  },
  methods: {
    handleSubmit() {
      const data = {}
      this.contact.submitted = true
      let status = true
      Object.keys(this.data.form.formFields).forEach((key) => {
        if (this.data.form.formFields[key].enabled) {
          data[key] = this.contact.data[key]
          if (!data[key]) status = false
        }
      })
      status = status && data.email && this.validateEmail(data.email)
      if (status) {
        const contact = {
          email: this.data.form.formAddress,
          data
        }
        axios.post('/form/contact', contact).then((res) => {
          if (res) {
            this.contact.success = true
          }
          this.loading = false
        })
      }
    }
  }
}
</script>
