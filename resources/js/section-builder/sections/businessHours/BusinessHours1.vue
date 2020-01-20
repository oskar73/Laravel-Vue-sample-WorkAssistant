<template>
  <div class="bz-section-container bz-sec-business-hours-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <div>
        <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-4" :class="{ 'lg:flex-row-reverse tw-flex-col-reverse': setting.alignment === 'right' }">
          <div class="tw-w-full max-lg:tw-h-[360px]">
            <bz-google-map v-if="setting.hasOwnProperty('map')" :type="setting.map.type" :zoom="setting.map.zoomLevel" :height="'100%'" :location="setting.map.location" />
          </div>
          <div class="tw-w-full">
            <div class="tw-w-full tw-h-full tw-flex tw-justify-around tw-flex-col lg:tw-p-4 max-lg:tw-px-4">
              <bz-text-element v-model="data.elements" :setting="setting.elements" />

              <bz-business-hours v-if="setting.elements.businessHours" :location="setting.businessInformation.location" />

              <bz-divider v-if="setting.elements.dividerLine" :line="true" />

              <div class="tw-flex tw-flex-col lg:tw-flex-row">
                <div class="tw-w-full">
                  <bz-title v-if="setting.elements.address" v-model="data.elements.address" />

                  <bz-address v-if="setting.elements.address" :location="setting.businessInformation.location" />
                </div>

                <div class="tw-w-full">
                  <bz-title v-if="setting.elements.phoneNumber" v-model="data.elements.phone" :mb="0" />

                  <bz-phone-number v-if="setting.elements.phone" :location="setting.businessInformation.location" />

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
  name: 'BusinessHours1',
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
