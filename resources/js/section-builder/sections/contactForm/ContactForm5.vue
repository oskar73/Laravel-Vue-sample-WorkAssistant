<template>
  <div class="bz-section-container bz-sec--contact-form-5-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize" class="bz-p-lg-6 bz-p-1">
      <bz-card class="bz-card-root">
        <bz-container>
          <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
          </bz-alignment>

          <bz-contact-form v-model="data.form">
            <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
              <bz-form-group v-if="data.form.formFields.firstName.enabled" v-model="contact.data.firstName" :label="data.form.formFields.firstName.label" />
              <div v-if="data.form.formFields.firstName.enabled && !contact.data.firstName && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.lastName.enabled" v-model="contact.data.lastName" :label="data.form.formFields.lastName.label" />
              <div v-if="data.form.formFields.lastName.enabled && !contact.data.lastName && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.email.enabled" v-model="contact.data.email" :label="data.form.formFields.email.label" />
              <div v-if="data.form.formFields.email.enabled && !contact.data.email && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.subject.enabled" v-model="contact.data.subject" :label="data.form.formFields.subject.label" />
              <div v-if="data.form.formFields.subject.enabled && !contact.data.subject && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.phone.enabled" v-model="contact.data.phone" :label="data.form.formFields.phone.label" />
              <div v-if="data.form.formFields.phone.enabled && !contact.data.phone && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.date.enabled" v-model="contact.data.date" :label="data.form.formFields.date.label" type="date" placeholder="mm/dd/yyy" />
              <div v-if="data.form.formFields.date.enabled && !contact.data.date && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.address.enabled" v-model="contact.data.address" :label="data.form.formFields.address.label" />
              <div v-if="data.form.formFields.address.enabled && !contact.data.address && contact.submitted" class="field-required">This Field is required</div>

              <bz-form-group v-if="data.form.formFields.message.enabled" v-model="contact.data.message" :label="data.form.formFields.message.label" type="textarea" />
              <div v-if="data.form.formFields.message.enabled && !contact.data.message && contact.submitted" class="field-required">This Field is required</div>

              <div class="tw-flex tw-w-100">
                <input :id="'bz-checkbox-' + section.id" type="checkbox" class="tw-m-1" />
                <label :for="'bz-checkbox-' + section.id">
                  <bz-text v-model="data.form.permissionMessage" />
                </label>
              </div>

              <div v-if="contact.success" class="success-text">
                <div class="title">{{ data.form.successMessage.title }}</div>
                <div class="msg">{{ data.form.successMessage.message }}</div>
              </div>

              <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" class="mt-5" @click="handleSubmit" />
            </bz-alignment>
          </bz-contact-form>
        </bz-container>
      </bz-card>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContactForm from '../../components/section/BzContactForm.vue'
import BzButton from '../../components/section/BzButton.vue'

import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzSubtitle,
    BzFormGroup,

    BzButton,
    BzContactForm,
    BzAlignment,
    BzContainer,
    BzCard,
    BzTitle,
    BzBackground
  },
  extends: BaseContactForm,
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec--contact-form-5-root {
  @import 'styles';
  .bz-card-root {
    border-radius: 1em;
    padding: 50px 0 !important;
  }

  .bz-form-item {
    border-radius: 0.2em !important;
    background-color: white !important;
  }
}
</style>
