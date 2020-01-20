<template>
  <div class="bz-section-container bz-sec--contact-form-3-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-flex" :class="{ 'flex-row-reverse': setting.alignment === 'right' }">
          <div class="max-lg:tw-hidden tw-w-full"></div>
          <div class="tw-w-full">
            <bz-card :background-color="cardBackgroundColor" class="lg:tw-p-12 tw-p-4">
              <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="cardBackgroundColor" />

                <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="cardBackgroundColor" />

                <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="cardBackgroundColor" />
              </bz-alignment>

              <bz-contact-form v-model="data.form">
                <bz-alignment :alignment="setting.layouts.alignment" :stretch="false">
                  <bz-form-group
                    v-if="data.form.formFields.firstName.enabled"
                    v-model="contact.data.firstName"
                    :label="data.form.formFields.firstName.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.firstName.enabled && !contact.data.firstName && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.lastName.enabled"
                    v-model="contact.data.lastName"
                    :label="data.form.formFields.lastName.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.lastName.enabled && !contact.data.lastName && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.email.enabled"
                    v-model="contact.data.email"
                    :label="data.form.formFields.email.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.email.enabled && !contact.data.email && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.subject.enabled"
                    v-model="contact.data.subject"
                    :label="data.form.formFields.subject.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.subject.enabled && !contact.data.subject && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.phone.enabled"
                    v-model="contact.data.phone"
                    :label="data.form.formFields.phone.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.phone.enabled && !contact.data.phone && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.date.enabled"
                    v-model="contact.data.date"
                    :label="data.form.formFields.date.label"
                    :background-color="cardBackgroundColor"
                    type="date"
                    placeholder="mm/dd/yyy"
                  />
                  <div v-if="data.form.formFields.date.enabled && !contact.data.date && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.address.enabled"
                    v-model="contact.data.address"
                    :label="data.form.formFields.address.label"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.address.enabled && !contact.data.address && contact.submitted" class="field-required">This Field is required</div>

                  <bz-form-group
                    v-if="data.form.formFields.message.enabled"
                    v-model="contact.data.message"
                    :label="data.form.formFields.message.label"
                    type="textarea"
                    :background-color="cardBackgroundColor"
                  />
                  <div v-if="data.form.formFields.message.enabled && !contact.data.message && contact.submitted" class="field-required">This Field is required</div>

                  <div class="d-flex w-100">
                    <input :id="'bz-checkbox-' + section.id" type="checkbox" class="m-1" />
                    <label :for="'bz-checkbox-' + section.id">
                      <bz-text v-model="data.form.permissionMessage" :background-color="cardBackgroundColor" />
                    </label>
                  </div>

                  <div v-if="contact.success" class="success-text">
                    <div class="title">{{ data.form.successMessage.title }}</div>
                    <div class="msg">{{ data.form.successMessage.message }}</div>
                  </div>

                  <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" @click="handleSubmit" />
                </bz-alignment>
              </bz-contact-form>
            </bz-card>
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzTitle from '../../components/section/BzTitle.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzContactForm from '../../components/section/BzContactForm.vue'
import BzButton from '../../components/section/BzButton.vue'
import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzButton,
    BzContactForm,

    BzSubtitle,
    BzAlignment,
    BzFormGroup,
    BzCard,
    BzContainer,
    BzBackground,
    BzTitle
  },
  extends: BaseContactForm,
  mixins: [sectionMixin],
  computed: {
    cardBackgroundColor() {
      return '#ffffff'
    }
  }
}
</script>
<style lang="scss" scoped>
.bz-sec--contact-form-3-root {
  @import 'styles';
}
</style>
