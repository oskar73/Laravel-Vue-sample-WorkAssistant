<template>
  <div class="bz-section-container bz-sec--address3--root" :class="{ [breakPoint]: true }">
    <div>
      <div class="tw-flex max-lg:tw-flex-col">
        <div class="lg:tw-w-1/2" :style="{ backgroundColor: primaryBoxColor }">
          <div class="col-12 left-box"></div>
        </div>
        <bz-background class="tw-w-full" :setting="background" :size="sectionSize">
          <div class="col-12 right-box"></div>
        </bz-background>
      </div>
    </div>
    <div class="section-content tw-flex tw-items-center">
      <div class="tw-w-full">
        <div class="tw-flex max-lg:tw-flex-col tw-gap-6" :class="{ 'tw-flex-row-reverse': setting.alignment === 'right' }">
          <div class="lg:tw-w-2/3 sm:tw-px-10">
            <bz-google-map :type="setting.map.type" :zoom="setting.map.zoomLevel" :height="360" :location="setting.businessInformation.location" />
          </div>
          <div class="lg:tw-w-1/3 tw-flex tw-justify-center tw-flex-col max-lg:tw-px-10 max-sm:tw-px-6">
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
    </div>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzAddress from '../../components/section/BzAddress.vue'
import BzDivider from '../../components/section/BzDivider.vue'
import BzPhoneNumber from '../../components/section/BzPhoneNumber.vue'
import BzEmail from '../../components/section/BzEmail.vue'
import BzGoogleMap from '../../components/section/BzGoogleMap.vue'
export default {
  components: { BzGoogleMap, BzEmail, BzPhoneNumber, BzDivider, BzAddress, BzTitle, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec--address3--root {
  position: relative;

  .left-box,
  .right-box {
    min-height: 320px;
  }

  &.bz-sm {
    .left-box {
      min-height: 200px;
    }
    .right-box {
      min-height: 420px;
    }
  }

  .section-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
}
</style>
