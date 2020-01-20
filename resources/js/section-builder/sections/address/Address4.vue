<template>
  <div class="bz-section-container bz-sec--address4-root" :class="{ [breakPoint]: true }">
    <div>
      <div class="lg:tw-flex max-lg:tw-flex-col tw-gap-6" :class="{ 'tw-flex-row-reverse max-lg:tw-flex-col-reverse': setting.alignment === 'right' }">
        <div class="tw-w-full lg:tw-w-1/2">
          <bz-google-map
            :type="setting.map.type"
            :zoom="setting.map.zoomLevel"
            :location="setting.businessInformation.location"
            class="lg:tw-h-full"
            style="min-height: 300px !important"
          />
        </div>
        <div class="tw-w-full lg:tw-w-1/2">
          <div class="lg:tw-flex max-lg:tw-flex-col lg:tw-h-full">
            <div class="tw-w-full lg:tw-w-1/2 lg:tw-h-full">
              <bz-background :setting="background">
                <div class="lg:tw-min-h-[300px] tw-flex tw-justify-center tw-items-center tw-flex-col max-lg:tw-px-8 max-lg:tw-pt-8">
                  <bz-title v-if="setting.elements.phoneNumber" v-model="data.elements.phone" :mb="0" />
                  <bz-phone-number v-if="setting.elements.phoneNumber" :location="setting.businessInformation.location" />

                  <bz-divider />

                  <bz-title v-if="setting.elements.email" v-model="data.elements.email" :mb="0" />
                  <bz-email v-if="setting.elements.email" :location="setting.businessInformation.location" />
                </div>
              </bz-background>
            </div>
            <div class="tw-w-full lg:tw-w-1/2 lg:tw-h-full">
              <bz-background class="lg:tw-h-full" :background-color="rightBoxBackgroundColor">
                <div class="lg:tw-min-h-[300px] tw-flex tw-justify-center tw-items-center tw-flex-col max-lg:tw-px-8 max-lg:tw-pb-8">
                  <bz-title v-if="setting.elements.address" v-model="data.elements.address" />
                  <bz-address v-if="setting.elements.address" :location="setting.businessInformation.location" />
                </div>
              </bz-background>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
export default {
  components: { BzGoogleMap, BzAddress, BzEmail, BzDivider, BzPhoneNumber, BzTitle, BzBackground },
  mixins: [sectionMixin],
  computed: {
    rightBoxBackgroundColor() {
      const originalColor = tinycolor(this.backgroundColor)
      return originalColor.darken(5).toString()
    }
  }
}
</script>
