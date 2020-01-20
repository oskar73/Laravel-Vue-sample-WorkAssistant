<template>
  <div class="bz-section-container bz-sec--contact-form-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-card :background-color="cardBackgroundColor">
          <bz-alignment :alignment="setting.layouts.alignment">
            <bz-title v-if="setting.elements.title" v-model="data.elements.title" :background-color="cardBackgroundColor" />

            <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" :background-color="cardBackgroundColor" />

            <bz-text v-if="setting.elements.description" v-model="data.elements.description" :background-color="cardBackgroundColor" />

            <bz-divider v-if="setting.elements.dividerLine" :line="true" :width="60" :thickness="4" :background-color="cardBackgroundColor" />

            <bz-divider :height="10" />
          </bz-alignment>
          <bz-contact-form v-model="data.form">
            <bz-alignment :alignment="setting.layouts.alignment">
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

              <div class="tw-flex tw-w-full">
                <input :id="'bz-checkbox-' + section.id" type="checkbox" class="tw-m-1" />
                <label :for="'bz-checkbox-' + section.id">
                  <bz-text v-model="data.form.permissionMessage" :background-color="cardBackgroundColor" />
                </label>
              </div>
            </bz-alignment>
          </bz-contact-form>

          <div v-if="contact.success" class="success-text">
            <div class="title">{{ data.form.successMessage.title }}</div>
            <div class="msg">{{ data.form.successMessage.message }}</div>
          </div>

          <bz-button v-model="data.submitButton" :link="false" :class="{ loader: loading }" @click="handleSubmit" />
        </bz-card>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzCard from '../../components/section/BzCard.vue'
import BzFormGroup from '../../components/section/BzFormGroup.vue'
import BzAlignment from '../../components/section/BzAlignment.vue'
import BzContactForm from '../../components/section/BzContactForm.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

import BzDivider from '../../components/section/BzDivider.vue'
import BzButton from '../../components/section/BzButton.vue'
import BaseContactForm from './BaseContactForm.vue'

export default {
  components: {
    BzButton,
    BzDivider,

    BzSubtitle,
    BzContactForm,
    BzAlignment,
    BzFormGroup,
    BzCard,
    BzContainer,
    BzTitle,
    BzBackground
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
.bz-sec--contact-form-2-root {
  @import 'styles';
  .bz-card-root {
    padding: 40px 20% !important;
  }

  &.bz-sm {
    .bz-card-root {
      padding: 20px 10% !important;
    }
  }
}
</style>
