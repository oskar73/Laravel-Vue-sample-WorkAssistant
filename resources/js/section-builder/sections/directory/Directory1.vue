<script>
import { defineComponent } from 'vue'
import BzContainer from '@/section-builder/components/section/BzContainer.vue'
import BzBackground from '@/section-builder/components/section/BzBackground.vue'
import BzAlignment from '@/section-builder/components/section/BzAlignment.vue'
import BzAspectView from '@/section-builder/components/section/BzAspectView.vue'
import EmptyListing from './EmptyListing.vue'

import directoryMixin from './directoryMixin'

export default defineComponent({
  name: 'Directory1',
  components: {
    BzAlignment,
    BzAspectView,
    BzBackground,
    BzContainer,
    EmptyListing
  },
  mixins: [directoryMixin]
})
</script>

<template>
  <div class="bz-section-container bz-sec--usp-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-alignment :alignment="setting.layouts.alignment">
          <bz-title v-if="setting.elements.title" v-model="data.elements.title" />
          <bz-subtitle v-if="setting.elements.subtitle" v-model="data.elements.subtitle" />
          <bz-text v-if="setting.elements.description" v-model="data.elements.description" />
        </bz-alignment>
        <div :class="`tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-${setting.column || 4} tw-gap-4 tw-mt-4`">
          <template v-for="listing in filteredItems" :key="listing.id">
            <div @click="detailView(listing)" class="tw-flex tw-flex-col tw-gap-2 tw-cursor-pointer tw-w-full">
              <img class="tw-w-full" :src="getImgUrl(listing)" :alt="listing.title" />
              <div class="tw-p-3">
                <div class="tw-font-bold tw-text-3xl tw-mb-2">{{ listing.title }}</div>
                <p class="tw-text-lg tw-font-light" v-html="stringify(listing.description)" />
                <p class="tw-text-blue-500 tw-mt-2">Posted on {{ new Date(listing.created_at).toLocaleString() }}</p>
              </div>
            </div>
          </template>
          <template v-if="!listings.length && isBuilder">
            <empty-listing v-for="i in createArrayFrom1ToN(setting.listing?.listingCount || 4)" :key="i" />
          </template>
        </div>
        <template v-if="!listings.length && !isBuilder">
          <div class="tw-text-gray-500 tw-text-xl">There are no listings yet...</div>
        </template>
        <div class="tw-flex tw-justify-center">
          <a @click="goToPage()" class="cursor-pointer tw-bg-blue-500 tw-text-white tw-rounded tw-shadow tw-px-4 tw-py-1" v-if="listings.length && !isBuilder">View</a>
        </div>
      </bz-container>
    </bz-background>
  </div>
</template>

<style scoped lang="scss">
.btn-show-more {
  background-color: #0069d9;
  border: none;
  outline: none;
  color: white;
  border-radius: 4px;
  padding: 5px 10px;
  margin-top: 10px;
  position: absolute;
  left: calc(50% - 30px);
  z-index: 100;
  cursor: pointer;
  &:hover {
    background-color: #014fa5;
  }
}
</style>
