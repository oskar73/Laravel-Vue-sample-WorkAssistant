<template>
  <div class="bz-section-container bz-sec--address5--root" :class="{ [breakPoint]: true }">
    <div class="tw-w-full">
      <bz-google-map :type="setting.map.type" :zoom="setting.map.zoomLevel" :height="400" :location="setting.businessInformation.location" />
    </div>
    <div class="tw-w-full">
      <bz-background :setting="background">
        <bz-container>
          <div class="tw-flex max-lg:tw-flex-col tw-py-10" :class="{ 'tw-flex-row-reverse max-lg:tw-flex-col-reverse': setting.alignment === 'right' }">
            <div class="tw-w-full">
              <bz-title v-if="setting.elements.address" v-model="data.elements.address" />
              <bz-address v-if="setting.elements.address" :location="setting.businessInformation.location" />
            </div>
            <div class="tw-w-full">
              <bz-title v-if="setting.elements.phoneNumber" v-model="data.elements.phone" :mb="0" />
              <bz-phone-number v-if="setting.elements.phoneNumber" :location="setting.businessInformation.location" />
            </div>
            <div class="tw-w-full">
              <bz-title v-if="setting.elements.email" v-model="data.elements.email" :mb="0" />
              <bz-email v-if="setting.elements.email" :location="setting.businessInformation.location" />
            </div>
          </div>
        </bz-container>
      </bz-background>
    </div>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
export default {
  components: { BzGoogleMap, BzPhoneNumber, BzEmail, BzAddress, BzTitle, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec--address5--root {
}
</style>
