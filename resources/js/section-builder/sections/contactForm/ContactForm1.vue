<template>
  <div class="bz-section-container bz-sec--contact-form-1-root" :class="{ [breakPoint]: true }" :data-section="section.id">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>

        <bz-contact-form v-model="data.form" :style="{ '--contact-form-inner-color': 'white' }">
          <div class="tw-grid lg:tw-grid-cols-2 tw-gap-8">
            <div>
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
            </div>
            <div>
              <bz-form-group
                v-if="data.form.formFields.message.enabled"
                v-model="contact.data.message"
                :label="data.form.formFields.message.label"
                type="textarea"
                :stretch="true"
              />
            </div>
          </div>
        </bz-contact-form>

        <div class="tw-flex tw-gap-2">
          <input :id="'bz-checkbox-' + section.id" type="checkbox" class="tw-m-1" />
          <label :for="'bz-checkbox-' + section.id">
            <bz-text v-model="data.form.permissionMessage" />
          </label>
        </div>

        <div v-if="contact.success">
          <div class="title">{{ data.form.successMessage.title }}</div>
          <div class="msg">{{ data.form.successMessage.message }}</div>
        </div>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" @click="handleSubmit" />
        </bz-alignment>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'

import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzContactForm from '../../components/section/BzContactForm.vue'
import BzButton from '../../components/section/BzButton.vue'
import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzFormGroup,
    BzButton,
    BzContactForm,
    BzSubtitle,

    BzAlignment,
    BzContainer,
    BzTitle,
    BzBackground
  },
  extends: BaseContactForm,
  mixins: [sectionMixin]
}
</script>

