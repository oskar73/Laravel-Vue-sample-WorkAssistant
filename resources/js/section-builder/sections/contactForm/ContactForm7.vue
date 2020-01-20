<template>
  <div class="bz-section-container bz-sec--contact-form-7-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="lg:tw-grid tw-grid-cols-3">
          <div class="tw-col-span-2 tw-flex tw-flex-col">
            <bz-contact-form v-model="data.form">
              <bz-alignment class="lg:tw-pr-6" :alignment="setting.layouts.alignment" :stretch="false">
                <bz-form-group v-if="data.form.formFields.firstName.enabled" v-model="contact.data.firstName" :label="data.form.formFields.firstName.label" :inline="true" />
                <div v-if="data.form.formFields.firstName.enabled && !contact.data.firstName && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group v-if="data.form.formFields.lastName.enabled" v-model="contact.data.lastName" :label="data.form.formFields.lastName.label" :inline="true" />
                <div v-if="data.form.formFields.lastName.enabled && !contact.data.lastName && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group v-if="data.form.formFields.email.enabled" v-model="contact.data.email" :label="data.form.formFields.email.label" :inline="true" />
                <div v-if="data.form.formFields.email.enabled && !contact.data.email && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group v-if="data.form.formFields.subject.enabled" v-model="contact.data.subject" :label="data.form.formFields.subject.label" :inline="true" />
                <div v-if="data.form.formFields.subject.enabled && !contact.data.subject && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group v-if="data.form.formFields.phone.enabled" v-model="contact.data.phone" :label="data.form.formFields.phone.label" :inline="true" />
                <div v-if="data.form.formFields.phone.enabled && !contact.data.phone && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group
                  v-if="data.form.formFields.date.enabled"
                  v-model="contact.data.date"
                  :label="data.form.formFields.date.label"
                  type="date"
                  placeholder="mm/dd/yyy"
                  :inline="true"
                />
                <div v-if="data.form.formFields.date.enabled && !contact.data.date && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group v-if="data.form.formFields.address.enabled" v-model="contact.data.address" :label="data.form.formFields.address.label" :inline="true" />
                <div v-if="data.form.formFields.address.enabled && !contact.data.address && contact.submitted" class="field-required">This Field is required</div>

                <bz-form-group
                  v-if="data.form.formFields.message.enabled"
                  v-model="contact.data.message"
                  :label="data.form.formFields.message.label"
                  type="textarea"
                  :inline="true"
                />
                <div v-if="data.form.formFields.message.enabled && !contact.data.message && contact.submitted" class="field-required">This Field is required</div>

                <div class="tw-flex lg:tw-w-[70%] tw-self-end">
                  <input :id="'bz-checkbox-' + section.id" type="checkbox" class="m-1" />
                  <label :for="'bz-checkbox-' + section.id">
                    <bz-text v-model="data.form.permissionMessage" />
                  </label>
                </div>
              </bz-alignment>
            </bz-contact-form>

            <bz-divider :height="20" />

            <div v-if="contact.success" class="success-text">
              <div class="title">{{ data.form.successMessage.title }}</div>
              <div class="msg">{{ data.form.successMessage.message }}</div>
            </div>

            <div class="tw-flex lg:tw-w-[70%] tw-self-end">
              <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" @click="handleSubmit" />
              </bz-alignment>
            </div>
          </div>
          <div class="tw-p-6">
            <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
              <bz-title v-if="setting.elements.title" v-model="data.elements.title" />

              <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />

              <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
            </bz-alignment>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContactForm from '../../components/section/BzContactForm.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BzButton from '../../components/section/BzButton.vue'

import BzDivider from '../../components/section/BzDivider.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzSubtitle,
    BzDivider,

    BzButton,
    BzFormGroup,
    BzAlignment,
    BzContainer,
    BzContactForm,
    BzTitle,
    BzBackground
  },
  extends: BaseContactForm,
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--contact-form-7-root {
  @import 'styles';
}
</style>
