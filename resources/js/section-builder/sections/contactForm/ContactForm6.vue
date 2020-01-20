<template>
  <div class="bz-section-container bz-sec--contact-form-6-root" :class="{ [breakPoint]: true }">
    <bz-background :background-color="theme.secondaryColor" :setting="background">
      <div class="tw-flex tw-flex-col lg:tw-flex-row tw-px-4 tw-gap-8" :class="{ 'kg:tw-flex-row-reverse tw-flex-col-reverse': setting.alignment === 'right' }">
        <div class="lg:tw-w-1/3 tw-w-full">
          <bz-card :background-color="whiteCardBackgroundColor">
            <bz-section-size :size="sectionSize">
              <bz-address v-if="setting.elements.address" :location="setting.businessInformation.location" :background-color="whiteCardBackgroundColor" />

              <bz-divider />

              <bz-phone-number
                v-if="setting.elements.phone"
                v-model="data.elements.phoneNumber"
                :location="setting.businessInformation.location"
                :background-color="whiteCardBackgroundColor"
              />

              <bz-divider />

              <bz-email
                v-if="setting.elements.email"
                v-model="data.elements.email"
                :location="setting.businessInformation.location"
                :background-color="whiteCardBackgroundColor"
              />
            </bz-section-size>
          </bz-card>
        </div>
        <div class="lg:tw-w-2/3 tw-w-full">
          <bz-card :background-color="primaryCardBackgroundColor">
            <bz-section-size :size="sectionSize">
              <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="primaryCardBackgroundColor" />

                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="primaryCardBackgroundColor" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="primaryCardBackgroundColor" />
              </bz-alignment>

              <bz-contact-form v-model="data.form">
                <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                  <bz-form-group
                    v-if="data.form.formFields.firstName.enabled"
                    v-model="contact.data.firstName"
                    :label="data.form.formFields.firstName.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.firstName.enabled && !contact.data.firstName && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.lastName.enabled"
                    v-model="contact.data.lastName"
                    :label="data.form.formFields.lastName.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.lastName.enabled && !contact.data.lastName && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.email.enabled"
                    v-model="contact.data.email"
                    :label="data.form.formFields.email.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.email.enabled && !contact.data.email && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.subject.enabled"
                    v-model="contact.data.subject"
                    :label="data.form.formFields.subject.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.subject.enabled && !contact.data.subject && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.phone.enabled"
                    v-model="contact.data.phone"
                    :label="data.form.formFields.phone.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.phone.enabled && !contact.data.phone && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.date.enabled"
                    v-model="contact.data.date"
                    :label="data.form.formFields.date.label"
                    :background-color="primaryCardBackgroundColor"
                    type="date"
                    placeholder="mm/dd/yyy"
                  />
                  <div v-if="data.form.formFields.date.enabled && !contact.data.date && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.address.enabled"
                    v-model="contact.data.address"
                    :label="data.form.formFields.address.label"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.address.enabled && !contact.data.address && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.message.enabled"
                    v-model="contact.data.message"
                    :label="data.form.formFields.message.label"
                    type="textarea"
                    :background-color="primaryCardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.message.enabled && !contact.data.message && contact.submitted" class="field-required">This Field is required</div>

                  <div class="tw-flex tw-w-full">
                    <input :id="'bz-checkbox-' + section.id" type="checkbox" class="tw-m-1" />
                    <label :for="'bz-checkbox-' + section.id">
                      <bz-text v-model="data.form.permissionMessage" :background-color="primaryCardBackgroundColor" />
                    </label>
                  </div>

                  <div v-if="contact.success" class="success-text">
                    <div class="title">{{ data.form.successMessage.title }}</div>
                    <div class="msg">{{ data.form.successMessage.message }}</div>
                  </div>
                  <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" @click="handleSubmit" />
                </bz-alignment>
              </bz-contact-form>
            </bz-section-size>
          </bz-card>
        </div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzSectionSize from '../../components/section/BzSectionSize.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContactForm from '../../components/section/BzContactForm.vue'
import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BzButton from '../../components/section/BzButton.vue'

import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzSubtitle,
    BzButton,
    BzFormGroup,
    BzContactForm,
    BzAlignment,
    BzSectionSize,
    BzDivider,
    BzEmail,
    BzPhoneNumber,
    BzAddress,
    BzCard,
    BzTitle,
    BzBackground
  },
  extends: BaseContactForm,
  mixins: [sectionMixin],
  computed: {
    whiteCardBackgroundColor() {
      return '#ffffff'
    },
    primaryCardBackgroundColor() {
      return this.primaryBoxColor
    }
  }
}
</script>
<style lang="scss">
.bz-sec--contact-form-6-root {
  @import 'styles';
  .bz-form-item {
    border: none !important;
    border-radius: 0.2em !important;
    background-color: white !important;
  }
}
</style>
