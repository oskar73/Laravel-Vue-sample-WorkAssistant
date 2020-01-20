<template>
  <div class="bz-section-container address1" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <div class="tw-w-full tw-px-4">
        <div class="tw-flex max-lg:tw-flex-col tw-gap-6" :class="{ 'tw-flex-row-reverse max-lg:tw-flex-col-reverse': setting.alignment === 'right' }">
          <div class="tw-w-full">
            <bz-google-map :type="setting.map.type" :zoom="setting.map.zoomLevel" :location="setting.businessInformation.location" />
          </div>
          <div class="tw-w-full">
            <div class="w-100 h-100 d-flex justify-content-around flex-column p-lg-4">
              <bz-text-element v-model="data.elements" :setting="setting.elements" />

              <bz-business-hours v-if="setting.elements.businessHours" :location="setting.businessInformation.location" />

              <bz-divider v-if="setting.elements.dividerLine" :line="true" />

              <div class="row">
                <div v-if="setting.elements.address" class="col-lg-6">
                  <bz-title v-model="data.elements.address" />

                  <bz-address :location="setting.businessInformation.location" />
                </div>

                <div class="col-lg-6">
                  <bz-title v-if="setting.elements.phoneNumber" v-model="data.elements.phone" :mb="0" />

                  <bz-phone-number v-if="setting.elements.phoneNumber" :location="setting.businessInformation.location" />

                  <bz-divider />

                  <bz-title v-if="setting.elements.email" v-model="data.elements.email" :mb="0" />
                  <bz-email v-if="setting.elements.email" :location="setting.businessInformation.location" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzBusinessHours from '../../components/section/BzBusinessHours.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'

export default {
  components: {
    BzTextElement,
    BzGoogleMap,
    BzEmail,
    BzPhoneNumber,
    BzDivider,
    BzAddress,
    BzBusinessHours,
    BzBackground,
    BzTitle
  },
  mixins: [sectionMixin]
}
</script>
