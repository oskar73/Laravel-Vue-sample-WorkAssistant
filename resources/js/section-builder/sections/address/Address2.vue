<template>
  <div class="bz-section-container address2" :class="{ [breakPoint]: true }">
    <bz-background :setting="background">
      <div class="tw-w-full tw-px-4">
        <div class="tw-flex max-lg:tw-flex-col tw-gap-6" :class="{ 'tw-flex-row-reverse max-lg:tw-flex-col-reverse': setting.alignment === 'right' }">
          <div class="lg:tw-w-2/3">
            <bz-google-map :type="setting.map.type" :zoom="setting.map.zoomLevel" :height="400" :location="setting.businessInformation.location" />
          </div>
          <div class="lg:tw-w-1/3 tw-flex tw-flex-col tw-justify-center">
            <bz-title v-if="setting.elements.address" v-model="data.elements.address" />
            <bz-address v-if="setting.elements.address" :location="setting.businessInformation.location" />

            <bz-divider />

            <bz-title v-if="setting.elements.phoneNumber" v-model="data.elements.phone" :mb="0" />
            <bz-phone-number v-if="setting.elements.phoneNumber" :location="setting.businessInformation.location" />

            <bz-divider />

            <bz-title v-if="setting.elements.email" v-model="data.elements.email" :mb="0" />
            <bz-email v-if="setting.elements.email" :location="setting.businessInformation.location" />
          </div>
        </div>
      </div>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzDivider from '../../components/section/BzDivider.vue'
import BzBackground from '../../components/section/BzBackground.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
export default {
  components: { BzGoogleMap, BzAddress, BzPhoneNumber, BzTitle, BzEmail, BzBackground, BzDivider },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.address2 {
}
</style>
