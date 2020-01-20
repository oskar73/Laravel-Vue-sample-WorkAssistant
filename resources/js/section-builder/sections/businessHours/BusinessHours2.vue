<template>
  <div class="bz-section-container bz-sec-business-hours-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <div class="tw-grid lg:tw-grid-cols-2">
          <div>
            <bz-text-element v-model="data.elements" :setting="setting.elements" :alignment="setting.layouts.alignment" />
          </div>
          <div>
            <bz-business-hours :location="setting.businessInformation.location" />
          </div>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import BzBackground from '../../components/section/BzBackground.vue'
import BzBusinessHours from '../../components/section/BzBusinessHours.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzTextElement from '../../components/section/BzTextElement.vue'
import sectionMixin from '../../mixins/sectionMixin'

export default {
  name: 'BusinessHours2',
  components: {
    BzTextElement,
    BzContainer,
    BzBusinessHours,
    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
