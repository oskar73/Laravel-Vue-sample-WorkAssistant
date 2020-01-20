<template>
  <section class="bz-section-container address1" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-w-full tw-h-full tw-flex tw-justify-around tw-flex-col tw-pt-10">
          <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" />

          <bz-business-hours class="mt-3" :location="setting.businessInformation.location" :design="3" />
        </div>
      </bz-container>
    </bz-background>
  </section>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzBusinessHours from '../../components/section/BzBusinessHours.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'

export default {
  name: 'BusinessHours5',
  components: {
    BzTextElement,
    BzContainer,
    BzBusinessHours,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.address1 {
}
</style>
