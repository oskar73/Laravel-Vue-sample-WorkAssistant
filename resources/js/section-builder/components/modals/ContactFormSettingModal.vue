<template>
  <modal v-model="showModal" width="90%" :classes="['bz-modal--contact-form-setting-root']" name="contact-form-setting-modal" @closed="onClose()">
    <div style="background-color: rgb(246, 246, 246)" class="modal-header p-3">
      <h5>Contact form options</h5>
      <div class="d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-center ml-3" @click="onClose">
          <i class="mdi mdi-close"></i>
        </div>
      </div>
    </div>
    <div style="height: calc(100% - 115px); overflow-y: auto" v-if="form">
      <tabs :options="{ disableScrollBehavior: true }">
        <tab id="tab-form-fields" name="Form Fields">
          <div class="col-12">
            <bz-warning class="my-3"> Enable or disable specific form field types, as well as provide a custom label for them. </bz-warning>
          </div>
          <div class="col-12">
            <div class="row">
              <div class="col-9">
                <bz-input v-model="form.formFields.firstName.label" label="First Name" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.firstName.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.lastName.label" label="Last Name" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.lastName.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.subject.label" label="Subject" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.subject.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.message.label" label="Message" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.message.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.email.label" label="Email" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.email.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.phone.label" label="Phone" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.phone.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.date.label" label="Date" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.date.enabled" />
              </div>

              <div class="col-9">
                <bz-input v-model="form.formFields.address.label" label="Address" />
              </div>
              <div class="col-3 d-flex align-items-center justify-content-end">
                <bz-switch v-model="form.formFields.address.enabled" />
              </div>
            </div>
          </div>
        </tab>
        <tab id="tab-form-address" name="Form Address">
          <div class="col-12">
            <bz-warning class="my-3">
              Emails get sent to the email address attached to your account by default. You can change the email address for this contact form in the field below
            </bz-warning>
          </div>
          <div class="col-12">
            <bz-input v-model="form.formAddress" :label="'E-mail'" />
          </div>
        </tab>
        <tab id="tab-success-message" name="Success message">
          <div class="col-12">
            <bz-warning class="my-3"> This is the message you present to users when they have successfully submitted the form. </bz-warning>
          </div>
          <div class="col-12">
            <bz-input v-model="form.successMessage.title" :label="'Title'" />
          </div>
          <div class="col-12">
            <bz-input v-model="form.successMessage.message" :label="'Message'" :multiple="true" :rows="4" />
          </div>
        </tab>
        <tab id="tab-permission-message" name="Permission message">
          <div class="col-12">
            <bz-warning class="my-3"> Add a custom message to inform users they need to give their permission to be contacted. </bz-warning>
          </div>
          <div class="col-12">
            <bz-input v-model="form.permissionMessage" :label="'Title'" :multiple="true" :rows="3" />
          </div>
        </tab>
      </tabs>
    </div>
    <!-- <hr style="margin-top: auto" />
    <div class="w-100 d-flex justify-content-end pb-2">
      <button class="btn bz-btn-default-outline mr-3" @click="onClose()">
        <b>Cancel</b>
      </button>
      <button class="btn bz-btn-default mr-4 d-flex align-items-center" @click="onConfirm()">
        <b>Save</b>
      </button>
    </div> -->
  </modal>
</template>

<script>
import BzWarning from '../section/BzWarning.vue'
import BzInput from '../page/BzInput.vue'
import BzSwitch from '../page/BzSwitch.vue'
import templateMixin from '../../mixins/templateMixin'

export default {
  name: 'ContactFormSettingModal',
  components: { BzSwitch, BzInput, BzWarning },
  mixins: [templateMixin],
  data() {
    return {
      form: null,
      showModal: false
    }
  },
  created() {
    this.form = this.$store.state.modals.contactFormSetting.form
  },
  mounted() {
    this.$modal.show('contact-form-setting-modal')
  },
  methods: {
    onClose() {
      this.$store.commit('closeContactFormSetting')
    },
    onConfirm() {
      this.$store.state.modals.contactFormSetting.onChange(this.form)
      this.onClose()
    }
  }
}
</script>
<style lang="scss">
.bz-modal--contact-form-setting-root {
  max-width: 800px;
  height: 84vh !important;
  top: 8vh !important;
  padding-bottom: 15px;
  margin: auto;
  left: 0 !important;

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .md-tabs {
    .md-button-content {
      text-transform: capitalize;
    }
    .md-tabs-navigation.md-elevation-0 {
      background-color: rgb(246, 246, 246) !important;
      padding: 0 20px;
    }
  }
  .md-field .md-input,
  .md-field .md-textarea {
    height: 32px;
    padding: 0;
    display: block;
    flex: 1;
    border: none;
    background: none;
    transition: 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-property: font-size, padding-top, color;
    font-family: inherit;
    font-size: 16px;
    line-height: 32px;
  }
}
</style>
